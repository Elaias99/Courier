<?php

namespace App\Http\Controllers;

use App\Jobs\ProcesarGeolizeJob;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Coordina la pantalla de pago Courier.
 * No tiene reglas del negocio: recibe el archivo, lo deja en la cola
 * para procesarlo en segundo plano y muestra el avance y el resultado.
 */
class CourierController extends Controller
{
    /** Muestra el formulario para subir el archivo de Geolize. */
    public function index()
    {
        return view('courier.index', ['id' => null, 'importacion' => null]);
    }

    /** Recibe el archivo, lo guarda y deja el trabajo en la cola. */
    public function subir(Request $request)
    {
        $request->validate([
            'archivo' => ['required', 'file', 'mimes:xlsx', 'max:40960'], // máximo 40 MB
        ]);

        $id = (string) Str::uuid();
        $nombreOriginal = $request->file('archivo')->getClientOriginalName();

        // Se guarda en storage/app/private/geolize/ hasta que el Job lo procese.
        $archivo = $request->file('archivo')->storeAs('geolize', "{$id}.xlsx");

        ProcesarGeolizeJob::guardarEstado($id, [
            'estado' => 'en_cola',
            'nombre' => $nombreOriginal,
            'leidas' => 0,
        ]);

        ProcesarGeolizeJob::dispatch($id, $archivo);

        return redirect()->route('courier.ver', $id);
    }

    /** Muestra la pantalla de una importación (con su avance o su resultado). */
    public function ver(string $id)
    {
        $importacion = ProcesarGeolizeJob::estado($id);

        abort_if($importacion === null, 404, 'Esta importación no existe o ya expiró.');

        return view('courier.index', ['id' => $id, 'importacion' => $importacion]);
    }

    /** La pantalla pregunta aquí, cada pocos segundos, cómo va el proceso. */
    public function estado(string $id)
    {
        $importacion = ProcesarGeolizeJob::estado($id);

        abort_if($importacion === null, 404);

        return response()->json($importacion);
    }
}
