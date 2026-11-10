<?php

namespace App\Imports;

use OpenSpout\Reader\XLSX\Manager\SharedStringsCaching\CachingStrategyFactoryInterface;
use OpenSpout\Reader\XLSX\Manager\SharedStringsCaching\CachingStrategyInterface;
use OpenSpout\Reader\XLSX\Manager\SharedStringsCaching\FileBasedStrategy;
use OpenSpout\Reader\XLSX\Manager\SharedStringsCaching\InMemoryStrategy;
use OpenSpout\Reader\XLSX\Reader;
use RuntimeException;

/**
 * Lee el Excel exportado desde Geolize.
 *
 * Solo LEE: convierte cada fila en un bulto con los datos que necesitamos.
 * No decide si se paga ni nada del negocio; eso lo hace el Service.
 *
 * Usa OpenSpout: recorre el archivo UNA sola vez, fila por fila,
 * sin cargarlo completo en memoria. Por eso es rápido aunque tenga 80 mil filas.
 */
class GeolizeImport
{
    /** Columnas del Excel que necesitamos => nombre que les damos en el bulto. */
    public const COLUMNAS = [
        'Seguimiento paquete' => 'seguimiento',
        'Estado de entrega' => 'estado',
        'Comerciante' => 'comerciante',
        'Servicio' => 'servicio',
        'Nombre del destinatario' => 'destinatario',
        'Comuna de destino' => 'comuna',
    ];

    /** Cada cuántas filas se avisa el avance. */
    public const AVISAR_CADA = 5000;

    /**
     * Lee el archivo y devuelve la lista de bultos.
     *
     * @param  string  $ruta  Ruta del archivo .xlsx en el disco.
     * @param  callable|null  $alAvanzar  Se llama cada AVISAR_CADA filas con la cantidad leída.
     */
    public function leer(string $ruta, ?callable $alAvanzar = null): array
    {
        $reader = new Reader(null, $this->textosEnMemoria());
        $reader->open($ruta);

        $bultos = [];
        $posiciones = null; // En qué columna está cada dato (se averigua con la fila de títulos).

        try {
            foreach ($reader->getSheetIterator() as $hoja) {
                foreach ($hoja->getRowIterator() as $fila) {
                    $celdas = $fila->toArray();

                    // La primera fila son los títulos.
                    if ($posiciones === null) {
                        $posiciones = $this->ubicarColumnas($celdas);

                        continue;
                    }

                    $bulto = [];
                    foreach ($posiciones as $nombre => $posicion) {
                        $bulto[$nombre] = trim((string) ($celdas[$posicion] ?? ''));
                    }

                    // Saltar filas vacías (sin ninguno de los datos que usamos).
                    if (implode('', $bulto) === '') {
                        continue;
                    }

                    $bultos[] = $bulto;

                    if ($alAvanzar && count($bultos) % self::AVISAR_CADA === 0) {
                        $alAvanzar(count($bultos));
                    }
                }

                break; // Solo la primera hoja.
            }
        } finally {
            $reader->close();
        }

        return $bultos;
    }

    /**
     * Le indica a OpenSpout que guarde los textos del Excel en memoria.
     *
     * Un .xlsx guarda cada texto distinto una sola vez en una lista aparte, y las celdas
     * solo apuntan a esa lista. Geolize tiene cientos de miles de textos distintos
     * (seguimientos, direcciones, nombres). Con eso, OpenSpout calcula de forma muy
     * conservadora que no caben en memoria y los reparte en miles de archivos temporales,
     * lo que hace la lectura casi detenida. En realidad caben de sobra en los 512 MB de PHP.
     */
    private function textosEnMemoria(): CachingStrategyFactoryInterface
    {
        return new class implements CachingStrategyFactoryInterface
        {
            public function createBestCachingStrategy(?int $sharedStringsUniqueCount, string $tempFolder): CachingStrategyInterface
            {
                // Si el archivo no informa cuántos textos tiene, se usa el modo seguro (archivos temporales).
                return $sharedStringsUniqueCount === null
                    ? new FileBasedStrategy($tempFolder, 10000)
                    : new InMemoryStrategy($sharedStringsUniqueCount);
            }
        };
    }

    /** Busca en la fila de títulos la posición de cada columna que necesitamos. */
    private function ubicarColumnas(array $titulos): array
    {
        $titulos = array_map(fn ($titulo) => trim((string) $titulo), $titulos);

        $posiciones = [];
        foreach (self::COLUMNAS as $titulo => $nombre) {
            $posicion = array_search($titulo, $titulos, true);

            if ($posicion === false) {
                throw new RuntimeException("El archivo no tiene la columna \"{$titulo}\". ¿Es una exportación de Geolize?");
            }

            $posiciones[$nombre] = $posicion;
        }

        return $posiciones;
    }
}
