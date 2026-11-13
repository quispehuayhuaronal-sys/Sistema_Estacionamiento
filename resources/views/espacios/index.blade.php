<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espacios - Estacionamiento</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f6f9; }
        .contenedor { max-width: 1000px; margin: auto; }
        .tarjeta { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; overflow-x: auto; }
        input, button { padding: 8px; margin: 4px; }
        button { cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .mensaje { color: green; }
        .error { color: red; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/modulos.css') }}">
</head>
<body data-modulo="espacios">
<div class="contenedor">
    <h1>Espacios de estacionamiento</h1>
    <a href="{{ url('/panel') }}">Volver al panel</a>

    @if (session('mensaje'))
        <p class="mensaje">{{ session('mensaje') }}</p>
    @endif

    @if ($errors->any())
        <div class="error">
            <p>Revisa estos datos:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="tarjeta">
        <h2>Registrar espacio</h2>
        <form action="{{ route('espacios.store') }}" method="POST">
            @csrf
            <input name="codigo" placeholder="Código (ej. A-01)"
                   value="{{ old('codigo') }}" required>
            <input name="piso" placeholder="Piso"
                   value="{{ old('piso') }}" required>
            <input name="sector" placeholder="Sector"
                   value="{{ old('sector') }}" required>
            <button type="submit">Guardar espacio</button>
        </form>
    </div>

    <div class="tarjeta">
        <h2>Lista de espacios</h2>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Piso</th>
                    <th>Sector</th>
                    <th>Ocupación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($espacios as $espacio)
                    <tr>
                        <td>{{ $espacio->codigo }}</td>
                        <td>{{ $espacio->piso }}</td>
                        <td>{{ $espacio->sector }}</td>
                        <td>{{ $espacio->ocupado ? 'Ocupado' : 'Libre' }}</td>
                        <td>{{ $espacio->activo ? 'Activo' : 'Inactivo' }}</td>
                        <td>
                            <details>
                                <summary>Editar</summary>
                                <form action="{{ route('espacios.update', $espacio) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input name="codigo" value="{{ $espacio->codigo }}"
                                           placeholder="Código" required>
                                    <input name="piso" value="{{ $espacio->piso }}"
                                           placeholder="Piso" required>
                                    <input name="sector" value="{{ $espacio->sector }}"
                                           placeholder="Sector" required>
                                    <button type="submit">Guardar cambios</button>
                                </form>
                            </details>

                            <form action="{{ route('espacios.estado', $espacio) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit">
                                    {{ $espacio->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Todavía no hay espacios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/alertas-modulos.js') }}"></script>
</body>
</html>