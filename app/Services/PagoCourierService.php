<?php

namespace App\Services;

use App\Imports\GeolizeImport;

/**
 * Reglas del pago a Courier.
 *
 * Etapa 1: leer el archivo de Geolize y dejar pasar solo los bultos
 *          cuyo estado se paga.
 * Etapa 2: ponerle a cada bulto que pasó su tipo de pago
 *          (Lanas, Peumo, Retornos o Variables).
 * Las siguientes etapas (proveedor, precio, suma) se irán agregando aquí, una a la vez.
 */
class PagoCourierService
{
    /**
     * Estados que SÍ se pagan.
     * Es una lista de los que pasan (no de los que se descartan):
     * cualquier estado que no esté aquí, aunque sea nuevo o raro, queda fuera.
     */
    public const ESTADOS_PAGABLES = ['Entregado', 'En reparto', 'Fallido'];

    /** Regla 1 - Lanas: comerciantes de lanas. */
    public const COMERCIANTES_LANAS = [
        'Revesderecho',
        'Comercial Reginella Ltda',
        '(Orquidea) Hilanderia Maisa',
    ];

    /** Regla 2 - Peumo: este comerciante con este servicio. */
    public const COMERCIANTE_PEUMO = 'Comercial Peumo Ltda';

    public const SERVICIO_PEUMO = 'Servicio Standar (V. Trabajadores)';

    /** Regla 3 - Retornos: el nombre del destinatario empieza con esto. */
    public const PREFIJO_RETORNO = 'Desde';

    /**
     * Lee el archivo, aplica el filtro de estados y clasifica los que pasan.
     *
     * @param  string  $ruta  Ruta del archivo .xlsx en el disco.
     * @param  callable|null  $alAvanzar  Recibe cuántas filas se llevan leídas.
     * @return array{pasan: array, descartados: array}
     */
    public function procesar(string $ruta, ?callable $alAvanzar = null): array
    {
        $bultos = $this->leer($ruta, $alAvanzar);

        $resultado = $this->filtrarPorEstado($bultos);

        $resultado['pasan'] = $this->clasificar($resultado['pasan']);

        return $resultado;
    }

    /** Piso 1: el Import lee el Excel y devuelve todos los bultos. */
    public function leer(string $ruta, ?callable $alAvanzar = null): array
    {
        return (new GeolizeImport)->leer($ruta, $alAvanzar);
    }

    /**
     * Convierte el resultado en números para mostrar en pantalla
     * (no enviamos los 80 mil bultos a la vista).
     */
    public function resumir(array $resultado): array
    {
        $pasan = $resultado['pasan'];
        $descartados = $resultado['descartados'];

        return [
            'total' => count($pasan) + count($descartados),
            'pasan' => count($pasan),
            'descartados' => count($descartados),
            'pasanPorEstado' => array_count_values(array_column($pasan, 'estado')),
            'descartadosPorEstado' => array_count_values(array_column($descartados, 'estado')),
            'porTipo' => array_count_values(array_column($pasan, 'tipo')),
        ];
    }

    /** Filtro de entrada: separa los bultos que pasan de los descartados. */
    public function filtrarPorEstado(array $bultos): array
    {
        $pasan = [];
        $descartados = [];

        foreach ($bultos as $bulto) {
            if (in_array($bulto['estado'], self::ESTADOS_PAGABLES, true)) {
                $pasan[] = $bulto;
            } else {
                $descartados[] = $bulto;
            }
        }

        return [
            'pasan' => $pasan,
            'descartados' => $descartados,
        ];
    }

    /** Piso 2: a cada bulto que pasó le agrega su tipo de pago. */
    public function clasificar(array $bultos): array
    {
        foreach ($bultos as $i => $bulto) {
            $bultos[$i]['tipo'] = $this->tipoDePago($bulto);
        }

        return $bultos;
    }

    /**
     * Decide el tipo de pago de un bulto.
     * Las reglas se revisan en orden y gana la primera que se cumple.
     */
    public function tipoDePago(array $bulto): string
    {
        // 1. Lanas
        if (in_array($bulto['comerciante'], self::COMERCIANTES_LANAS, true)) {
            return 'Lanas';
        }

        // 2. Peumo
        if ($bulto['comerciante'] === self::COMERCIANTE_PEUMO
            && $bulto['servicio'] === self::SERVICIO_PEUMO) {
            return 'Peumo';
        }

        // 3. Retornos
        if (str_starts_with($bulto['destinatario'], self::PREFIJO_RETORNO)) {
            return 'Retornos';
        }

        // 4. Todo lo demás
        return 'Variables';
    }
}
