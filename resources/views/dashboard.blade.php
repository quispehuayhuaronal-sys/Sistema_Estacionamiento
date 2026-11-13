<!DOCTYPE html>
<html lang="es">
<head>
    <base href="{{ asset('plantilla') }}/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel - Estacionamiento</title>

    <link href="css/font-face.css" rel="stylesheet">
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="vendor/fontawesome-7.3.1/css/all.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

    <style>
        body {
            background: #f3f6fb;
            color: #263247;
        }

        .menu-estacionamiento a {
            display: block;
            padding: 12px 18px;
            color: #34425a;
            text-decoration: none;
        }

        .menu-estacionamiento a:hover {
            background: #edf3ff;
            color: #2458c6;
        }

        .cabecera-panel {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 22px 26px;
            margin-bottom: 24px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(32, 50, 80, .07);
        }

        .cabecera-panel h1 {
            margin: 0 0 6px;
            color: #24324a;
            font-size: 27px;
            font-weight: 700;
        }

        .cabecera-panel p {
            margin: 0;
            color: #718096;
        }

        .cerrar-sesion button {
            border: 0;
            border-radius: 8px;
            background: #fff0f0;
            color: #c0394b;
            padding: 10px 15px;
            cursor: pointer;
            font-weight: 600;
        }

        .cerrar-sesion button:hover {
            background: #ffe0e0;
        }

        .titulo-seccion {
            margin: 24px 0 14px;
            color: #263247;
            font-weight: 700;
        }

        .resumen-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .resumen-card {
            padding: 21px;
            background: #fff;
            border-left: 5px solid #3977e8;
            border-radius: 13px;
            box-shadow: 0 5px 20px rgba(32, 50, 80, .07);
        }

        .resumen-card.ingresos { border-left-color: #17a673; }
        .resumen-card.entradas { border-left-color: #3977e8; }
        .resumen-card.ocupacion { border-left-color: #f0a329; }

        .resumen-card h3 {
            margin: 0 0 12px;
            color: #66758b;
            font-size: 14px;
            font-weight: 600;
        }

        .resumen-card strong {
            color: #25344d;
            font-size: 27px;
        }

        .resumen-card p {
            margin: 8px 0 0;
            color: #8793a5;
            font-size: 13px;
        }

        .registros-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            padding-bottom: 35px;
        }

        .registro-card {
            --acento: #3977e8;
            overflow: hidden;
            background: #fff;
            border-top: 4px solid var(--acento);
            border-radius: 13px;
            box-shadow: 0 5px 20px rgba(32, 50, 80, .07);
        }

        .registro-card.clientes { --acento: #3977e8; }
        .registro-card.vehiculos { --acento: #8b5cf6; }
        .registro-card.espacios { --acento: #17a673; }
        .registro-card.estacionamientos { --acento: #f0a329; }
        .registro-card.pagos { --acento: #12a6b5; }
        .registro-card.membresias { --acento: #e05c8a; }

        .registro-card__titulo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 17px 20px 12px;
        }

        .registro-card__titulo h3 {
            margin: 0;
            color: #263247;
            font-size: 17px;
            font-weight: 700;
        }

        .contador {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 20px;
            background: #f0f3f9;
            color: var(--acento);
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .tabla-contenedor {
            overflow-x: auto;
            padding: 0 18px 18px;
        }

        table.tabla-registros {
            width: 100%;
            min-width: 620px;
            margin: 0;
        }

        table.tabla-registros thead th {
            background: #f5f7fb;
            color: #65738a;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
            white-space: nowrap;
        }

        table.tabla-registros th,
        table.tabla-registros td {
            padding: 11px 12px;
            vertical-align: middle;
        }

        table.tabla-registros td {
            color: #3f4d63;
            font-size: 13px;
        }

        .estado {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .estado.activo,
        .estado.libre,
        .estado.finalizado {
            background: #e7f7ef;
            color: #148454;
        }

        .estado.inactivo,
        .estado.pendiente {
            background: #fff3df;
            color: #a86700;
        }

        .estado.ocupado,
        .estado.estacionado {
            background: #e9efff;
            color: #315fca;
        }

        .sin-registros {
            color: #8793a5 !important;
            text-align: center;
        }

        .dt-container {
            color: #526078;
            font-size: 13px;
        }

        .dt-container .dt-search input,
        .dt-container .dt-length select {
            padding: 6px 9px;
            border: 1px solid #d9e0eb;
            border-radius: 6px;
            background: #fff;
        }

        @media (max-width: 700px) {
            .cabecera-panel {
                padding: 18px;
            }

            .cabecera-panel h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body class="app">
<div class="page-wrapper">

    <aside class="menu-sidebar" id="main-sidebar">
        <div class="logo">
            <a class="logo-link" href="{{ route('panel') }}">
                <span class="logo-mark">E</span>
                <span class="logo-text">Estacionamiento</span>
            </a>
        </div>

        <div class="menu-sidebar__content">
            <nav class="navbar-sidebar">
                <ul class="list-unstyled navbar__list menu-estacionamiento">
                    <li><a href="{{ route('panel') }}">Panel principal</a></li>
                    <li><a href="{{ route('clientes.index') }}">Clientes</a></li>

                    @if (in_array(auth()->user()->role, ['administrador', 'operador']))
                        <li>
                            <a href="{{ route('membresias.index') }}">
                                Abonados y mensualidades
                            </a>
                        </li>
                    @endif

                    <li><a href="{{ route('vehiculos.index') }}">Vehículos</a></li>
                    <li><a href="{{ route('espacios.index') }}">Espacios</a></li>
                    <li>
                        <a href="{{ route('estacionamientos.index') }}">
                            Entradas y salidas
                        </a>
                    </li>

                    @if (in_array(auth()->user()->role, ['administrador', 'operador']))
                        <li><a href="{{ route('pagos.index') }}">Caja y pagos</a></li>
                    @endif

                    @if (in_array(auth()->user()->role, ['administrador', 'supervisor']))
                        <li><a href="{{ route('reportes.index') }}">Reportes</a></li>
                    @endif

                    @if (auth()->user()->role === 'administrador')
                        <li>
                            <a href="{{ route('usuarios.index') }}">
                                Usuarios y accesos
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    <div class="page-container">
        <main class="main-content" id="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">

                    <header class="cabecera-panel">
                        <div>
                            <h1>Panel del estacionamiento</h1>
                            <p>
                                Bienvenido, {{ auth()->user()->name }}.
                                Rol: {{ ucfirst(auth()->user()->role) }}
                            </p>
                        </div>

                        <form class="cerrar-sesion"
                              action="{{ route('logout') }}"
                              method="POST">
                            @csrf
                            <button type="submit">
                                <i class="fa-solid fa-power-off"></i>
                                Cerrar sesión
                            </button>
                        </form>
                    </header>

                    <h2 class="titulo-seccion">Resumen de hoy</h2>

                    <div class="resumen-grid">
                        <section class="resumen-card ingresos">
                            <h3>Ingresos de hoy</h3>
                            <strong>Bs {{ number_format((float) $ingresosHoy, 2) }}</strong>
                            <p>Total de pagos registrados hoy</p>
                        </section>

                        <section class="resumen-card entradas">
                            <h3>Entradas de hoy</h3>
                            <strong>{{ $entradasHoy }}</strong>
                            <p>Registros de entrada de hoy</p>
                        </section>

                        <section class="resumen-card ocupacion">
                            <h3>Espacios ocupados</h3>
                            <strong>{{ $espaciosOcupados }} / {{ $espaciosActivos }}</strong>
                            <p>{{ $porcentajeOcupacion }}% de ocupación</p>
                        </section>
                    </div>

                    <h2 class="titulo-seccion">Todos los registros</h2>

                    <div class="registros-grid">

                        <section class="registro-card clientes">
                            <div class="registro-card__titulo">
                                <h3>Clientes</h3>
                                <span class="contador">{{ $clientes->count() }} registros</span>
                            </div>
                            <div class="tabla-contenedor">
                                <table class="table tabla-registros">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Documento</th>
                                            <th>Teléfono</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($clientes as $cliente)
                                            <tr>
                                                <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                                                <td>{{ $cliente->documento ?: '—' }}</td>
                                                <td>{{ $cliente->telefono ?: '—' }}</td>
                                                <td>
                                                    <span class="estado {{ $cliente->activo ? 'activo' : 'inactivo' }}">
                                                        {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="sin-registros" colspan="4">
                                                    No hay clientes registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section class="registro-card vehiculos">
                            <div class="registro-card__titulo">
                                <h3>Vehículos</h3>
                                <span class="contador">{{ $vehiculos->count() }} registros</span>
                            </div>
                            <div class="tabla-contenedor">
                                <table class="table tabla-registros">
                                    <thead>
                                        <tr>
                                            <th>Placa</th>
                                            <th>Vehículo</th>
                                            <th>Cliente</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($vehiculos as $vehiculo)
                                            <tr>
                                                <td>{{ $vehiculo->placa }}</td>
                                                <td>{{ $vehiculo->marca }} {{ $vehiculo->modelo }} — {{ $vehiculo->tipo }}</td>
                                                <td>
                                                    {{ $vehiculo->cliente
                                                        ? $vehiculo->cliente->nombre.' '.$vehiculo->cliente->apellido
                                                        : 'Sin cliente' }}
                                                </td>
                                                <td>
                                                    <span class="estado {{ $vehiculo->activo ? 'activo' : 'inactivo' }}">
                                                        {{ $vehiculo->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="sin-registros" colspan="4">
                                                    No hay vehículos registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section class="registro-card espacios">
                            <div class="registro-card__titulo">
                                <h3>Espacios</h3>
                                <span class="contador">{{ $espacios->count() }} registros</span>
                            </div>
                            <div class="tabla-contenedor">
                                <table class="table tabla-registros">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Piso</th>
                                            <th>Sector</th>
                                            <th>Ocupación</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($espacios as $espacio)
                                            <tr>
                                                <td>{{ $espacio->codigo }}</td>
                                                <td>{{ $espacio->piso }}</td>
                                                <td>{{ $espacio->sector }}</td>
                                                <td>
                                                    <span class="estado {{ $espacio->ocupado ? 'ocupado' : 'libre' }}">
                                                        {{ $espacio->ocupado ? 'Ocupado' : 'Libre' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="estado {{ $espacio->activo ? 'activo' : 'inactivo' }}">
                                                        {{ $espacio->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="sin-registros" colspan="5">
                                                    No hay espacios registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section class="registro-card estacionamientos">
                            <div class="registro-card__titulo">
                                <h3>Entradas y salidas</h3>
                                <span class="contador">{{ $estacionamientos->count() }} registros</span>
                            </div>
                            <div class="tabla-contenedor">
                                <table class="table tabla-registros">
                                    <thead>
                                        <tr>
                                            <th>Ticket</th>
                                            <th>Placa</th>
                                            <th>Espacio</th>
                                            <th>Entrada</th>
                                            <th>Salida</th>
                                            <th>Estado</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($estacionamientos as $registro)
                                            <tr>
                                                <td>{{ $registro->numero_ticket }}</td>
                                                <td>{{ $registro->vehiculo->placa ?? '—' }}</td>
                                                <td>{{ $registro->espacio->codigo ?? '—' }}</td>
                                                <td>{{ $registro->fecha_entrada?->format('d/m/Y H:i') ?? '—' }}</td>
                                                <td>{{ $registro->fecha_salida?->format('d/m/Y H:i') ?? 'Pendiente' }}</td>
                                                <td>
                                                    <span class="estado {{ $registro->fecha_salida ? 'finalizado' : 'estacionado' }}">
                                                        {{ $registro->fecha_salida ? 'Finalizado' : 'Estacionado' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $registro->total !== null
                                                        ? 'Bs '.number_format((float) $registro->total, 2)
                                                        : 'Pendiente' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="sin-registros" colspan="7">
                                                    No hay entradas registradas.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section class="registro-card pagos">
                            <div class="registro-card__titulo">
                                <h3>Pagos</h3>
                                <span class="contador">{{ $pagos->count() }} registros</span>
                            </div>
                            <div class="tabla-contenedor">
                                <table class="table tabla-registros">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Ticket</th>
                                            <th>Placa</th>
                                            <th>Método</th>
                                            <th>Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pagos as $pago)
                                            <tr>
                                                <td>{{ $pago->fecha_pago?->format('d/m/Y H:i') ?? '—' }}</td>
                                                <td>{{ $pago->estacionamiento->numero_ticket ?? '—' }}</td>
                                                <td>{{ $pago->estacionamiento->vehiculo->placa ?? '—' }}</td>
                                                <td>{{ $pago->metodo_pago }}</td>
                                                <td>Bs {{ number_format((float) $pago->monto, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="sin-registros" colspan="5">
                                                    No hay pagos registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section class="registro-card membresias">
                            <div class="registro-card__titulo">
                                <h3>Abonados y mensualidades</h3>
                                <span class="contador">{{ $membresias->count() }} registros</span>
                            </div>
                            <div class="tabla-contenedor">
                                <table class="table tabla-registros">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Convenio</th>
                                            <th>Vigencia</th>
                                            <th>Mensualidad</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($membresias as $membresia)
                                            <tr>
                                                <td>
                                                    {{ $membresia->cliente->nombre ?? '—' }}
                                                    {{ $membresia->cliente->apellido ?? '' }}
                                                </td>
                                                <td>{{ $membresia->convenio ?: '—' }}</td>
                                                <td>
                                                    {{ $membresia->fecha_inicio?->format('d/m/Y') ?? '—' }}
                                                    —
                                                    {{ $membresia->fecha_fin?->format('d/m/Y') ?? 'Sin fecha fin' }}
                                                </td>
                                                <td>Bs {{ number_format((float) $membresia->monto_mensual, 2) }}</td>
                                                <td>
                                                    <span class="estado {{ $membresia->activo ? 'activo' : 'inactivo' }}">
                                                        {{ $membresia->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="sin-registros" colspan="5">
                                                    No hay abonados registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="vendor/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
<script src="js/main.js"></script>

<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.tabla-registros').forEach(function (tabla) {
            new DataTable(tabla, {
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [],
                language: {
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'No hay registros para mostrar',
                    zeroRecords: 'No se encontraron coincidencias',
                    emptyTable: 'No hay registros',
                    paginate: {
                        first: 'Primera',
                        last: 'Última',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                }
            });
        });
    });
</script>
</body>
</html>