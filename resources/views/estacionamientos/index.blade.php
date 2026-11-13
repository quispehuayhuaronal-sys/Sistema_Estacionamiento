<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entradas y salidas - Estacionamiento</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f4f6f9;
        }

        .contenedor {
            max-width: 1100px;
            margin: auto;
        }

        .tarjeta {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            overflow-x: auto;
        }

        select, button {
            padding: 9px;
            margin: 5px;
        }

        button { cursor: pointer; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 9px;
            text-align: left;
        }

        .mensaje { color: green; }
        .error { color: #b00020; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/modulos.css') }}">
</head>

<body data-modulo="estacionamientos">
<div class="contenedor">
    <h1>Entradas y salidas</h1>
    <a href="{{ url('/panel') }}">Volver al panel</a>

    @if (session('mensaje'))
        <p class="mensaje">{{ session('mensaje') }}</p>
    @endif

    @if ($errors->any())
        <div class="error">
            <p>Revisa lo siguiente:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="tarjeta">
        <h2>Registrar entrada</h2>

        @if ($vehiculos->isEmpty())
            <p>Primero registra un vehículo activo en el módulo Vehículos.</p>
        @elseif ($espacios->isEmpty())
            <p>No hay espacios libres y activos. Registra o habilita un espacio libre.</p>
        @else
            <form action="{{ route('estacionamientos.store') }}" method="POST">
                @csrf

                <label for="vehiculo_id">Vehículo:</label>
                <select name="vehiculo_id" id="vehiculo_id" required>
                    <option value="">Selecciona la placa</option>
                    @foreach ($vehiculos as $vehiculo)
                        <option value="{{ $vehiculo->id }}"
                            {{ old('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                            {{ $vehiculo->placa }} — {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                        </option>
                    @endforeach
                </select>

                <label for="espacio_id">Espacio libre:</label>
                <select name="espacio_id" id="espacio_id" required>
                    <option value="">Selecciona un espacio</option>
                    @foreach ($espacios as $espacio)
                        <option value="{{ $espacio->id }}"
                            {{ old('espacio_id') == $espacio->id ? 'selected' : '' }}>
                            {{ $espacio->codigo }} — Piso {{ $espacio->piso }}, sector {{ $espacio->sector }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">Registrar entrada</button>
            </form>
        @endif
    </div>

    <div class="tarjeta">
        <h2>Registro de estacionamientos</h2>

        <table>
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Placa</th>
                    <th>Espacio</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
           <tbody>
    @forelse ($estacionamientos as $registro)
        <tr>
            <td>{{ $registro->numero_ticket }}</td>
            <td>{{ $registro->vehiculo->placa ?? '—' }}</td>
            <td>{{ $registro->espacio->codigo ?? '—' }}</td>
            <td>{{ $registro->fecha_entrada?->format('d/m/Y H:i') ?? '—' }}</td>
            <td>{{ $registro->fecha_salida?->format('d/m/Y H:i') ?? '—' }}</td>
            <td>
                {{ $registro->fecha_salida ? 'Finalizado' : 'Estacionado' }}
            </td>
            <td>
                @if ($registro->fecha_salida === null)
                    <form
                        action="{{ route('estacionamientos.salida', $registro) }}"
                        method="POST">
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            onclick="return confirm('¿Registrar la salida de este vehículo?')">
                            Registrar salida
                        </button>
                    </form>
                @else
                    Salida registrada
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7">Todavía no hay entradas registradas.</td>
        </tr>
    @endforelse
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/alertas-modulos.js') }}"></script>
</body>
</html>