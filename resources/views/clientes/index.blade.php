<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clientes - Estacionamiento</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f4f6f9;
        }

        .contenedor {
            max-width: 1000px;
            margin: auto;
        }

        .tarjeta {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        input {
            padding: 8px;
            margin: 4px;
        }

        button {
            padding: 8px 12px;
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

        .mensaje {
            color: green;
        }

        .error {
            color: red;
        }
    </style> 
    <link rel="stylesheet" href="{{ asset('css/modulos.css') }}">
</head>

<body data-modulo="clientes">
<div class="contenedor">

    <h1>Gestión de clientes</h1>

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
        <h2>Registrar cliente</h2>

        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <input type="text" name="nombre"
                placeholder="Nombre" value="{{ old('nombre') }}" required>

            <input type="text" name="apellido"
                placeholder="Apellido" value="{{ old('apellido') }}" required>

            <input type="text" name="documento"
                placeholder="Documento" value="{{ old('documento') }}">

            <input type="text" name="telefono"
                placeholder="Teléfono" value="{{ old('telefono') }}">

            <input type="email" name="correo"
                placeholder="Correo" value="{{ old('correo') }}">

            <input type="text" name="direccion"
                placeholder="Dirección" value="{{ old('direccion') }}">

            <button type="submit">Guardar cliente</button>
        </form>
    </div>

    <div class="tarjeta">
        <h2>Lista de clientes</h2>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                        <td>{{ $cliente->documento }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>{{ $cliente->activo ? 'Activo' : 'Inactivo' }}</td>
                        <td>
                            <details>
                                <summary>Editar</summary>

                                <form
                                    action="{{ route('clientes.update', $cliente) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="text" name="nombre"
                                        value="{{ $cliente->nombre }}"
                                        placeholder="Nombre" required>

                                    <input type="text" name="apellido"
                                        value="{{ $cliente->apellido }}"
                                        placeholder="Apellido" required>

                                    <input type="text" name="documento"
                                        value="{{ $cliente->documento }}"
                                        placeholder="Documento">

                                    <input type="text" name="telefono"
                                        value="{{ $cliente->telefono }}"
                                        placeholder="Teléfono">

                                    <input type="email" name="correo"
                                        value="{{ $cliente->correo }}"
                                        placeholder="Correo">

                                    <input type="text" name="direccion"
                                        value="{{ $cliente->direccion }}"
                                        placeholder="Dirección">

                                    <button type="submit">Guardar cambios</button>
                                </form>
                            </details>

                            <form
                                action="{{ route('clientes.estado', $cliente) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit">
                                    {{ $cliente->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Todavía no hay clientes registrados.</td>
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