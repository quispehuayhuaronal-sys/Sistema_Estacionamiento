<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vehículos - Estacionamiento</title>

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

        input, select, button {
            padding: 8px;
            margin: 4px;
        }

        button {
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .mensaje { color: green; }
        .error { color: red; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/modulos.css') }}">
</head>

<body data-modulo="vehiculos">
<div class="contenedor">

    <h1>Gestión de vehículos</h1>

    <a href="{{ url('/panel') }}">Volver al panel</a>

    @if (session('mensaje'))
        <p class="mensaje">{{ session('mensaje') }}</p>
    @endif

    @if ($errors->any())
        <div class="error">
            <p>Revisa los siguientes datos:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="tarjeta">
        <h2>Registrar vehículo</h2>

        <form action="{{ route('vehiculos.store') }}" method="POST">
            @csrf

            <select name="cliente_id">
                <option value="">Sin cliente asociado</option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}"
                        {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nombre }} {{ $cliente->apellido }}
                        {{ $cliente->activo ? '' : '(inactivo)' }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="placa" placeholder="Placa"
                value="{{ old('placa') }}" required>

            <input type="text" name="marca" placeholder="Marca"
                value="{{ old('marca') }}" required>

            <input type="text" name="modelo" placeholder="Modelo"
                value="{{ old('modelo') }}" required>

            <input type="text" name="color" placeholder="Color"
                value="{{ old('color') }}" required>

            <select name="tipo" required>
                <option value="">Selecciona el tipo</option>
                @foreach (['Auto', 'Moto', 'Camioneta'] as $tipo)
                    <option value="{{ $tipo }}"
                        {{ old('tipo') == $tipo ? 'selected' : '' }}>
                        {{ $tipo }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Guardar vehículo</button>
        </form>
    </div>

    <div class="tarjeta">
        <h2>Lista de vehículos</h2>

        <table>
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Vehículo</th>
                    <th>Tipo</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($vehiculos as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->placa }}</td>
                        <td>
                            {{ $vehiculo->marca }}
                            {{ $vehiculo->modelo }}
                            — {{ $vehiculo->color }}
                        </td>
                        <td>{{ $vehiculo->tipo }}</td>
                        <td>
                            @if ($vehiculo->cliente)
                                {{ $vehiculo->cliente->nombre }}
                                {{ $vehiculo->cliente->apellido }}
                            @else
                                Sin cliente
                            @endif
                        </td>
                        <td>
                            {{ $vehiculo->activo ? 'Activo' : 'Inactivo' }}
                        </td>
                        <td>
                            <details>
                                <summary>Editar</summary>

                                <form action="{{ route('vehiculos.update', $vehiculo) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <select name="cliente_id">
                                        <option value="">Sin cliente asociado</option>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}"
                                                {{ $vehiculo->cliente_id == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nombre }}
                                                {{ $cliente->apellido }}
                                                {{ $cliente->activo ? '' : '(inactivo)' }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="text" name="placa"
                                        value="{{ $vehiculo->placa }}"
                                        placeholder="Placa" required>

                                    <input type="text" name="marca"
                                        value="{{ $vehiculo->marca }}"
                                        placeholder="Marca" required>

                                    <input type="text" name="modelo"
                                        value="{{ $vehiculo->modelo }}"
                                        placeholder="Modelo" required>

                                    <input type="text" name="color"
                                        value="{{ $vehiculo->color }}"
                                        placeholder="Color" required>

                                    <select name="tipo" required>
                                        @foreach (['Auto', 'Moto', 'Camioneta'] as $tipo)
                                            <option value="{{ $tipo }}"
                                                {{ $vehiculo->tipo == $tipo ? 'selected' : '' }}>
                                                {{ $tipo }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="submit">Guardar cambios</button>
                                </form>
                            </details>

                            <form action="{{ route('vehiculos.estado', $vehiculo) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit">
                                    {{ $vehiculo->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Todavía no hay vehículos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/alertas-modulos.js') }}"></script>
</body>
</html>