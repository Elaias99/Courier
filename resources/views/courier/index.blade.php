<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pago Courier</title>
    <style>
        :root { --borde: #d9dde3; --suave: #f5f7fa; --texto: #1f2933; --gris: #616e7c; --azul: #2f6fdf; --verde: #1f8a4c; --rojo: #c0392b; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: system-ui, -apple-system, "Segoe UI", sans-serif; color: var(--texto); background: #fff; }
        main { max-width: 760px; margin: 0 auto; padding: 32px 16px; }
        h1 { font-size: 1.5rem; margin: 0 0 4px; }
        h2 { font-size: 1.1rem; margin: 0 0 12px; }
        .sub { color: var(--gris); margin: 0 0 24px; }
        .caja { border: 1px solid var(--borde); border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .error { color: var(--rojo); }
        button, .boton { background: var(--azul); color: #fff; border: 0; border-radius: 6px; padding: 10px 18px; font-size: 1rem; cursor: pointer; text-decoration: none; display: inline-block; }
        button:disabled { opacity: .5; cursor: default; }
        .barra { height: 8px; background: var(--suave); border-radius: 4px; overflow: hidden; margin: 12px 0 6px; }
        .barra div { height: 100%; width: 30%; background: var(--azul); animation: mover 1.2s ease-in-out infinite; }
        @keyframes mover { 0% { margin-left: -30%; } 100% { margin-left: 100%; } }
        .cifras { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px; }
        .cifra { background: var(--suave); border-radius: 6px; padding: 12px; }
        .cifra span { display: block; color: var(--gris); font-size: .85rem; }
        .cifra strong { font-size: 1.4rem; font-variant-numeric: tabular-nums; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 6px 4px; border-bottom: 1px solid var(--borde); }
        td.n, th.n { text-align: right; font-variant-numeric: tabular-nums; }
        .columnas { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .ok { color: var(--verde); }
        .oculto { display: none; }
        @media (max-width: 560px) { .cifras, .columnas { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<main>
    <h1>Pago Courier</h1>
    <p class="sub">Etapa 1: filtro por estado · Etapa 2: clasificación por tipo de pago</p>

    @if ($id === null)
        {{-- Formulario para subir el archivo --}}
        <form class="caja" method="POST" action="{{ route('courier.subir') }}" enctype="multipart/form-data">
            @csrf
            <h2>Subir archivo de Geolize</h2>
            <p><input type="file" name="archivo" accept=".xlsx" required></p>
            @error('archivo') <p class="error">{{ $message }}</p> @enderror
            <button type="submit">Procesar</button>
        </form>
    @else
        <p>Archivo: <strong>{{ $importacion['nombre'] ?? '' }}</strong> · <a href="{{ route('courier.index') }}">Subir otro</a></p>

        {{-- Mientras se procesa --}}
        <section class="caja" id="cargando">
            <h2 id="cargando-titulo">Procesando…</h2>
            <div class="barra"><div></div></div>
            <p class="sub" id="cargando-detalle">En cola, esperando turno.</p>
        </section>

        {{-- Si falla --}}
        <section class="caja oculto" id="fallo">
            <h2 class="error">No se pudo procesar el archivo</h2>
            <p id="fallo-mensaje"></p>
        </section>

        {{-- Etapa 1 --}}
        <section class="caja oculto" id="etapa1">
            <h2>Etapa 1 · Filtro por estado</h2>
            <div class="cifras">
                <div class="cifra"><span>Bultos en el archivo</span><strong id="total"></strong></div>
                <div class="cifra"><span>Pasan (se pagan)</span><strong id="pasan" class="ok"></strong></div>
                <div class="cifra"><span>Descartados</span><strong id="descartados"></strong></div>
            </div>
            <div class="columnas">
                <table><thead><tr><th>Pasan</th><th class="n">Bultos</th></tr></thead><tbody id="pasan-estados"></tbody></table>
                <table><thead><tr><th>Descartados</th><th class="n">Bultos</th></tr></thead><tbody id="descartados-estados"></tbody></table>
            </div>
            <p><button type="button" id="boton-clasificar">Clasificar</button></p>
        </section>

        {{-- Etapa 2 --}}
        <section class="caja oculto" id="etapa2">
            <h2>Etapa 2 · Tipo de pago</h2>
            <table>
                <thead><tr><th>Tipo</th><th class="n">Bultos</th></tr></thead>
                <tbody id="tipos"></tbody>
                <tfoot><tr><th>Total</th><th class="n" id="tipos-total"></th></tr></tfoot>
            </table>
            <p id="tipos-control"></p>
            <p><button type="button" id="boton-comunas">Ver Variables por comuna</button></p>
        </section>

        {{-- Variables por comuna --}}
        <section class="caja oculto" id="etapa3">
            <h2>Variables por comuna de destino</h2>
            <p class="sub">Se juntan las formas que solo difieren en mayúsculas, tildes, espacios o símbolos. Los errores de tipeo siguen apareciendo aparte.</p>
            <div class="cifras">
                <div class="cifra"><span>Comunas distintas</span><strong id="comunas-cantidad"></strong></div>
                <div class="cifra"><span>Bultos de Variables</span><strong id="comunas-bultos"></strong></div>
            </div>
            <table>
                <thead><tr><th>Comuna de destino</th><th class="n">Bultos</th><th>Cómo viene escrita en Geolize</th></tr></thead>
                <tbody id="comunas"></tbody>
            </table>
            <p><button type="button" id="boton-operadores">Ver a quién se le paga</button></p>
        </section>

        {{-- Variables por operador --}}
        <section class="caja oculto" id="etapa4">
            <h2>Etapa 3 · A quién se le paga (Variables)</h2>
            <p class="sub">Cada comuna se buscó en la cobertura. En 4N RM y 4N Temuco todavía falta separar por repartidor.</p>
            <div class="cifras">
                <div class="cifra"><span>Con operador</span><strong id="op-con" class="ok"></strong></div>
                <div class="cifra"><span>Sin cobertura</span><strong id="op-sin"></strong></div>
                <div class="cifra"><span>Total Variables</span><strong id="op-total"></strong></div>
            </div>
            <table>
                <thead><tr><th>Operador</th><th>Proveedor (razón social)</th><th class="n">Comunas</th><th class="n">Bultos</th></tr></thead>
                <tbody id="operadores"></tbody>
            </table>
            <div id="sin-cobertura-caja" class="oculto">
                <h2 style="margin-top:20px">Sin cobertura</h2>
                <p class="sub">Comunas que no están en la tabla de coberturas. No se les asigna proveedor.</p>
                <table>
                    <thead><tr><th>Comuna de destino</th><th class="n">Bultos</th></tr></thead>
                    <tbody id="sin-cobertura"></tbody>
                </table>
            </div>
        </section>

        <script>
            const urlEstado = @json(route('courier.estado', $id));
            let importacion = @json($importacion);

            const numero = (n) => Number(n).toLocaleString('es-CL');
            const $ = (id) => document.getElementById(id);

            // Llena una tabla con pares nombre => cantidad (con textContent, para no interpretar HTML del archivo).
            function llenarTabla(tbody, datos, orden = null) {
                tbody.replaceChildren();
                const nombres = orden ?? Object.keys(datos).sort((a, b) => datos[b] - datos[a]);
                for (const nombre of nombres) {
                    if (!datos[nombre]) continue; // Si viene en cero (ej. Peumo), no se muestra.
                    const fila = tbody.insertRow();
                    fila.insertCell().textContent = nombre;
                    const celda = fila.insertCell();
                    celda.className = 'n';
                    celda.textContent = numero(datos[nombre]);
                }
            }

            function mostrar(imp) {
                if (imp.estado === 'error') {
                    $('cargando').classList.add('oculto');
                    $('fallo').classList.remove('oculto');
                    $('fallo-mensaje').textContent = imp.error;
                    return true;
                }

                if (imp.estado !== 'terminado') {
                    $('cargando-detalle').textContent = imp.estado === 'leyendo'
                        ? `Leyendo el archivo: ${numero(imp.leidas ?? 0)} filas leídas…`
                        : 'En cola, esperando turno.';
                    return false;
                }

                const r = imp.resumen;
                $('cargando').classList.add('oculto');
                $('etapa1').classList.remove('oculto');
                $('total').textContent = numero(r.total);
                $('pasan').textContent = numero(r.pasan);
                $('descartados').textContent = numero(r.descartados);
                llenarTabla($('pasan-estados'), r.pasanPorEstado);
                llenarTabla($('descartados-estados'), r.descartadosPorEstado);

                llenarTabla($('tipos'), r.porTipo, ['Lanas', 'Peumo', 'Retornos', 'Variables']);
                const sumaTipos = Object.values(r.porTipo).reduce((a, b) => a + b, 0);
                $('tipos-total').textContent = numero(sumaTipos);
                $('tipos-control').textContent = sumaTipos === r.pasan
                    ? '✔ La suma de los tipos coincide con los bultos que pasaron.'
                    : '✘ La suma de los tipos NO coincide con los bultos que pasaron.';
                $('tipos-control').className = sumaTipos === r.pasan ? 'ok' : 'error';

                const comunas = r.variablesPorComuna ?? [];
                $('comunas-cantidad').textContent = numero(comunas.length);
                $('comunas-bultos').textContent = numero(comunas.reduce((a, c) => a + c.bultos, 0));
                $('comunas').replaceChildren();
                for (const c of comunas) {
                    const fila = $('comunas').insertRow();
                    fila.insertCell().textContent = c.comuna;
                    const celda = fila.insertCell();
                    celda.className = 'n';
                    celda.textContent = numero(c.bultos);
                    // Solo si se juntaron varias formas de escribirla, se muestran con su cantidad.
                    fila.insertCell().textContent = (c.variantes ?? []).length > 1
                        ? c.variantes.map((v) => `${v.nombre} (${numero(v.bultos)})`).join(' · ')
                        : '';
                }

                const porOperador = r.variablesPorOperador ?? { operadores: [], sinCobertura: [] };
                const conOperador = porOperador.operadores.reduce((a, o) => a + o.bultos, 0);
                const sinOperador = porOperador.sinCobertura.reduce((a, c) => a + c.bultos, 0);
                $('op-con').textContent = numero(conOperador);
                $('op-sin').textContent = numero(sinOperador);
                $('op-total').textContent = numero(conOperador + sinOperador);
                $('operadores').replaceChildren();
                for (const o of porOperador.operadores) {
                    const fila = $('operadores').insertRow();
                    fila.insertCell().textContent = o.operador;
                    fila.insertCell().textContent = o.proveedor ?? '';
                    for (const valor of [o.comunas, o.bultos]) {
                        const celda = fila.insertCell();
                        celda.className = 'n';
                        celda.textContent = numero(valor);
                    }
                }
                $('sin-cobertura').replaceChildren();
                for (const c of porOperador.sinCobertura) {
                    const fila = $('sin-cobertura').insertRow();
                    fila.insertCell().textContent = c.comuna;
                    const celda = fila.insertCell();
                    celda.className = 'n';
                    celda.textContent = numero(c.bultos);
                }
                $('sin-cobertura-caja').classList.toggle('oculto', porOperador.sinCobertura.length === 0);
                return true;
            }

            $('boton-clasificar').addEventListener('click', () => {
                $('etapa2').classList.remove('oculto');
                $('boton-clasificar').disabled = true;
            });

            $('boton-comunas').addEventListener('click', () => {
                $('etapa3').classList.remove('oculto');
                $('boton-comunas').disabled = true;
            });

            $('boton-operadores').addEventListener('click', () => {
                $('etapa4').classList.remove('oculto');
                $('boton-operadores').disabled = true;
            });

            // Pregunta cada 2 segundos cómo va, hasta que termine o falle.
            async function consultar() {
                if (mostrar(importacion)) return;
                await new Promise((listo) => setTimeout(listo, 2000));
                try {
                    const respuesta = await fetch(urlEstado, { headers: { Accept: 'application/json' } });
                    if (respuesta.ok) importacion = await respuesta.json();
                } catch (e) { /* Sin conexión un momento: se vuelve a intentar. */ }
                consultar();
            }
            consultar();
        </script>
    @endif
</main>
</body>
</html>
