<?php

namespace App\Services;

use App\Imports\GeolizeImport;
use App\Models\Coberturas;
use Illuminate\Support\Str;

/**
 * Reglas del pago a Courier.
 *
 * Etapa 1: leer el archivo de Geolize y dejar pasar solo los bultos
 *          cuyo estado se paga.
 * Etapa 2: ponerle a cada bulto que pasó su tipo de pago
 *          (Lanas, Peumo, Retornos o Variables).
 * Etapa 3: (por ahora solo Variables) buscar su comuna en la cobertura
 *          para saber qué operador la atiende y a qué proveedor se le paga.
 * Las siguientes etapas (repartidor de 4N, precio, suma) se irán agregando aquí, una a la vez.
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

        $resultado['pasan'] = $this->asignarOperador($resultado['pasan'], 'Variables');

        return $resultado;
    }

    /**
     * Piso 3: a los bultos de un tipo les agrega quién atiende su comuna.
     *
     * Busca la comuna en la tabla coberturas comparando sin mayúsculas, tildes ni símbolos.
     * Si la comuna no está, el bulto queda con operador null ("sin cobertura"):
     * no se le inventa un proveedor ni se descarta en silencio.
     */
    public function asignarOperador(array $bultos, string $tipo): array
    {
        // Comuna normalizada => cobertura (con su proveedor), cargadas una sola vez.
        $coberturas = Coberturas::with('proveedor')->get()
            ->keyBy(fn (Coberturas $cobertura) => $this->normalizarComuna($cobertura->comuna));

        foreach ($bultos as $i => $bulto) {
            if ($bulto['tipo'] !== $tipo) {
                continue;
            }

            $cobertura = $coberturas->get($this->normalizarComuna($bulto['comuna']));

            $bultos[$i]['operador'] = $cobertura?->operador;
            $bultos[$i]['proveedor'] = $cobertura?->proveedor?->razon_social;
        }

        return $bultos;
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
            'variablesPorComuna' => $this->contarPorComuna($pasan, 'Variables'),
            'variablesPorOperador' => $this->contarPorOperador($pasan, 'Variables'),
        ];
    }

    /**
     * Cuenta cuántos bultos de un tipo tiene cada operador.
     * Los que no encontraron su comuna se juntan en "sin cobertura", con el detalle de sus comunas.
     *
     * Devuelve: ['operadores' => [['operador', 'proveedor', 'bultos', 'comunas'], ...],
     *            'sinCobertura' => [['comuna', 'bultos'], ...]]
     */
    public function contarPorOperador(array $bultos, string $tipo): array
    {
        $operadores = [];
        $sinCobertura = [];

        foreach ($bultos as $bulto) {
            if ($bulto['tipo'] !== $tipo) {
                continue;
            }

            if ($bulto['operador'] === null) {
                $comuna = $bulto['comuna'] !== '' ? $bulto['comuna'] : '(sin comuna)';
                $sinCobertura[$comuna] = ($sinCobertura[$comuna] ?? 0) + 1;

                continue;
            }

            $operador = $bulto['operador'];
            $operadores[$operador] ??= ['operador' => $operador, 'proveedor' => $bulto['proveedor'], 'bultos' => 0, 'comunas' => []];
            $operadores[$operador]['bultos']++;
            $operadores[$operador]['comunas'][$this->normalizarComuna($bulto['comuna'])] = true;
        }

        $operadores = array_values(array_map(function ($fila) {
            $fila['comunas'] = count($fila['comunas']);

            return $fila;
        }, $operadores));
        usort($operadores, fn ($a, $b) => $b['bultos'] <=> $a['bultos']);

        arsort($sinCobertura);
        $sinCobertura = array_map(
            fn ($comuna, $cantidad) => ['comuna' => (string) $comuna, 'bultos' => $cantidad],
            array_keys($sinCobertura),
            $sinCobertura,
        );

        return ['operadores' => $operadores, 'sinCobertura' => $sinCobertura];
    }

    /**
     * Cuenta cuántos bultos de un tipo hay en cada comuna de destino.
     *
     * Las formas de escribir que solo difieren en mayúsculas, tildes, espacios o
     * símbolos se juntan en una sola comuna ("PUERTO NATALES" y "Puerto Natales").
     * Los errores de tipeo ("Cerillos") NO se juntan: siguen apareciendo aparte.
     *
     * Devuelve una lista ordenada de mayor a menor:
     * [['comuna' => 'Puerto Natales', 'bultos' => 44, 'variantes' => [['nombre' => 'PUERTO NATALES', 'bultos' => 28], ...]], ...]
     */
    public function contarPorComuna(array $bultos, string $tipo): array
    {
        $grupos = []; // clave normalizada => [forma escrita => cantidad]

        foreach ($bultos as $bulto) {
            if ($bulto['tipo'] !== $tipo) {
                continue;
            }

            $escrita = $bulto['comuna'] !== '' ? $bulto['comuna'] : '(sin comuna)';
            $clave = $this->normalizarComuna($escrita);

            $grupos[$clave][$escrita] = ($grupos[$clave][$escrita] ?? 0) + 1;
        }

        $lista = [];
        foreach ($grupos as $formas) {
            arsort($formas);

            $variantes = [];
            foreach ($formas as $nombre => $cantidad) {
                $variantes[] = ['nombre' => (string) $nombre, 'bultos' => $cantidad];
            }

            $lista[] = [
                'comuna' => $variantes[0]['nombre'], // Se muestra la forma más usada.
                'bultos' => array_sum($formas),
                'variantes' => $variantes,
            ];
        }

        usort($lista, fn ($a, $b) => $b['bultos'] <=> $a['bultos']);

        return $lista;
    }

    /**
     * Deja el nombre de una comuna en una forma única para poder compararlo:
     * sin tildes, en minúsculas y sin espacios ni símbolos.
     *
     * "Concón", "CON CON" y "_Con Con" quedan todas como "concon".
     */
    public function normalizarComuna(string $comuna): string
    {
        $sinTildes = Str::ascii($comuna);

        return preg_replace('/[^a-z0-9]/', '', strtolower($sinTildes));
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
