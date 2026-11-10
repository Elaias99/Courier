<?php

namespace App\Jobs;

use App\Services\PagoCourierService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Procesa el archivo de Geolize en segundo plano.
 *
 * La pantalla no espera: el archivo se deja en la cola y este Job lo procesa
 * mientras la página va preguntando cómo va. El avance y el resultado se
 * guardan en la caché bajo la clave "importacion:{id}".
 */
class ProcesarGeolizeJob implements ShouldQueue
{
    use Queueable;

    /** Si falla, no reintentar: se muestra el error y se vuelve a subir. */
    public int $tries = 1;

    /** Tiempo máximo de trabajo, en segundos. */
    public int $timeout = 900;

    public function __construct(
        public string $id,      // Identificador de esta importación.
        public string $archivo, // Ruta del archivo dentro de storage/app/private.
    ) {}

    public function handle(PagoCourierService $service): void
    {
        self::guardarEstado($this->id, ['estado' => 'leyendo', 'leidas' => 0]);

        $resultado = $service->procesar(
            Storage::path($this->archivo),
            fn (int $leidas) => self::guardarEstado($this->id, ['leidas' => $leidas]),
        );

        self::guardarEstado($this->id, [
            'estado' => 'terminado',
            'resumen' => $service->resumir($resultado),
        ]);

        Storage::delete($this->archivo); // Ya no se necesita el archivo.
    }

    /** Si algo sale mal, se avisa a la pantalla en vez de quedarse cargando. */
    public function failed(?Throwable $error): void
    {
        self::guardarEstado($this->id, [
            'estado' => 'error',
            'error' => $error?->getMessage() ?? 'Error desconocido',
        ]);

        Storage::delete($this->archivo);
    }

    /** Lee el estado actual de una importación (null si no existe). */
    public static function estado(string $id): ?array
    {
        return Cache::get("importacion:{$id}");
    }

    /** Actualiza el estado de una importación, conservando lo que ya tenía. */
    public static function guardarEstado(string $id, array $datos): void
    {
        $actual = self::estado($id) ?? [];

        Cache::put("importacion:{$id}", array_merge($actual, $datos), now()->addDay());
    }
}
