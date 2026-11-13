<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reportes - Estacionamiento</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f4f6f9;
            color: #263247;
        }

        .contenedor {
            max-width: 1100px;
            margin: auto;
        }

        .tarjeta {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(32, 50, 80, .07);
            overflow-x: auto;
        }

        input, button, .boton {
            padding: 9px 12px;
            margin: 5px;
        }

        button, .boton {
            cursor: pointer;
            border: 0;
            border-radius: 5px;
            background: #3977e8;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .boton-pdf { background: #c0394b; }
        .boton-excel { background: #168453; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border-bottom: 1px solid #e4e9f1;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f1f5fb;
            color: #526078;
        }

        .error { color: #b00020; }
        .acciones { margin-top: 12px; }

        @media print {
            .no-imprimir { display: none !important; }
            body { margin: 10px; background: white; }
            .tarjeta { box-shadow: none; break-inside: avoid; }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/modulos.css') }}">
</head>
<body data-modulo="reportes">
<div class="contenedor">
    <h1>Reportes del estacionamiento</h1>
    <p class="no-imprimir"><a href="{{ route('panel') }}">Volver al panel</a></p>

    <div class="tarjeta no-imprimir">
        <h2>Filtrar por fechas</h2>

        <form id="filtroReporte" action="{{ route('reportes.index') }}" method="GET">
            <label for="desde">Desde:</label>
            <input id="desde" type="date" name="desde"
                   value="{{ $filtros['desde'] ?? '' }}">

            <label for="hasta">Hasta:</label>
            <input id="hasta" type="date" name="hasta"
                   value="{{ $filtros['hasta'] ?? '' }}">

            <button type="submit">Aplicar filtro</button>
            <a class="boton" href="{{ route('reportes.index') }}">Limpiar</a>
        </form>

        <p id="mensajeError" class="error" role="alert"></p>

        <div class="acciones">
            <a id="descargarExcel"
               class="boton boton-excel"
               href="{{ route('reportes.excel', request()->only('desde', 'hasta')) }}">
                Descargar Excel (CSV)
            </a>

            <button class="boton-pdf" type="button" onclick="window.print()">
                Guardar como PDF
            </button>
        </div>
    </div>

    <div class="tarjeta">
        <h2>Resumen</h2>
        <p><strong>Ingresos:</strong> Bs
            <span id="totalIngresos">{{ number_format((float) $totalIngresos, 2) }}</span>
        </p>
        <p><strong>Pagos registrados:</strong>
            <span id="cantidadPagos">{{ $cantidadPagos }}</span>
        </p>
        <p><strong>Espacios ocupados:</strong>
            <span id="espaciosOcupados">{{ $espaciosOcupados }}</span>
            de
            <span id="espaciosActivos">{{ $espaciosActivos }}</span> activos
        </p>
    </div>

    <div class="tarjeta">
        <h2>Ingresos por día</h2>
        <table>
            <thead><tr><th>Fecha</th><th>Total (Bs)</th></tr></thead>
            <tbody id="tablaIngresosDia">
                @forelse ($ingresosPorDia as $fila)
                    <tr>
                        <td>{{ $fila->periodo }}</td>
                        <td>{{ number_format((float) $fila->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2">No hay pagos para ese período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="tarjeta">
        <h2>Ingresos por mes</h2>
        <table>
            <thead><tr><th>Mes</th><th>Total (Bs)</th></tr></thead>
            <tbody id="tablaIngresosMes">
                @forelse ($ingresosPorMes as $fila)
                    <tr>
                        <td>{{ $fila->periodo }}</td>
                        <td>{{ number_format((float) $fila->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2">No hay pagos para ese período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="tarjeta">
        <h2>Vehículos más frecuentes</h2>
        <table>
            <thead><tr><th>Placa</th><th>Vehículo</th><th>Visitas</th></tr></thead>
            <tbody id="tablaVehiculos">
                @forelse ($vehiculosFrecuentes as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->placa }}</td>
                        <td>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</td>
                        <td>{{ $vehiculo->cantidad }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">No hay entradas para ese período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="{{ asset('plantilla/vendor/jquery-3.2.1.min.js') }}"></script>
<script>
$(function () {
    const urlReporte = @json(route('reportes.index'));
    const urlExcel = @json(route('reportes.excel'));

    function escapar(texto) {
        return $('<div>').text(texto ?? '').html();
    }

    function llenarTabla(selector, filas, columnas, formato) {
        const cuerpo = $(selector);
        cuerpo.empty();

        if (!filas || filas.length === 0) {
            cuerpo.append(
                $('<tr>').append(
                    $('<td>')
                        .attr('colspan', columnas)
                        .text('No hay datos para ese período.')
                )
            );
            return;
        }

        filas.forEach(function (fila) {
            const tr = $('<tr>');
            formato(fila).forEach(function (valor) {
                tr.append($('<td>').html(escapar(valor)));
            });
            cuerpo.append(tr);
        });
    }

    $('#filtroReporte').on('submit', function (evento) {
        evento.preventDefault();

        const desde = $('#desde').val();
        const hasta = $('#hasta').val();
        $('#mensajeError').text('');

        $.ajax({
            url: urlReporte,
            method: 'GET',
            data: { desde: desde, hasta: hasta },
            dataType: 'json',
            success: function (datos) {
                $('#totalIngresos').text(Number(datos.totalIngresos || 0).toFixed(2));
                $('#cantidadPagos').text(datos.cantidadPagos || 0);
                $('#espaciosOcupados').text(datos.espaciosOcupados || 0);
                $('#espaciosActivos').text(datos.espaciosActivos || 0);

                llenarTabla(
                    '#tablaIngresosDia',
                    datos.ingresosPorDia,
                    2,
                    fila => [fila.periodo, Number(fila.total || 0).toFixed(2)]
                );

                llenarTabla(
                    '#tablaIngresosMes',
                    datos.ingresosPorMes,
                    2,
                    fila => [fila.periodo, Number(fila.total || 0).toFixed(2)]
                );

                llenarTabla(
                    '#tablaVehiculos',
                    datos.vehiculosFrecuentes,
                    3,
                    fila => [
                        fila.placa,
                        (fila.marca || '') + ' ' + (fila.modelo || ''),
                        fila.cantidad
                    ]
                );

                const parametros = new URLSearchParams();
                if (desde) parametros.set('desde', desde);
                if (hasta) parametros.set('hasta', hasta);

                $('#descargarExcel').attr(
                    'href',
                    urlExcel + (parametros.toString() ? '?' + parametros.toString() : '')
                );
            },
            error: function (xhr) {
                let mensaje = 'No se pudo actualizar el reporte. Revisa las fechas e inténtalo otra vez.';

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    mensaje = Object.values(xhr.responseJSON.errors).flat().join(' ');
                }

                $('#mensajeError').text(mensaje);
            }
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/alertas-modulos.js') }}"></script>
</body>
</html>