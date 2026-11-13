<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Abonados y mensualidades</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f6f9; }
        .contenedor { max-width: 1100px; margin: auto; }
        .tarjeta { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; overflow-x: auto; }
        input, select, button { padding: 9px; margin: 5px; }
        button { cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 9px; text-align: left; }
        .mensaje { color: green; }
        .error { color: #b00020; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/modulos.css') }}">
</head>
<body data-modulo="membresias">
<div class="contenedor">
    <h1>Abonados y mensualidades</h1>
    <a href="{{ route('panel') }}">Volver al panel</a>

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
        <h2>Registrar abonado</h2>

        @if ($clientes->isEmpty())
            <p>Primero registra un cliente activo en el módulo Clientes.</p>
        @else
            <form action="{{ route('membresias.store') }}" method="POST">
                @csrf

                <label>Cliente:</label>
                <select name="cliente_id" required>
                    <option value="">Selecciona un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}"
                            {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="convenio"
                    placeholder="Convenio (opcional)"
                    value="{{ old('convenio') }}">

                <label>Inicio:</label>
                <input type="date" name="fecha_inicio"
                    value="{{ old('fecha_inicio') }}" required>

                <label>Fin (opcional):</label>
                <input type="date" name="fecha_fin"
                    value="{{ old('fecha_fin') }}">

                <label>Monto mensual (Bs):</label>
                <input type="number" name="monto_mensual"
                    min="0.01" step="0.01"
                    value="{{ old('monto_mensual') }}" required>

                <button type="submit">Guardar abonado</button>
            </form>
        @endif
    </div>

    <div class="tarjeta">
        <h2>Abonados registrados</h2>

        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Convenio</th>
                    <th>Vigencia</th>
                    <th>Mensualidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
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
                        <td>{{ $membresia->activo ? 'Activo' : 'Inactivo' }}</td>
                        <td>
                            <details>
                                <summary>Editar</summary>
                                <form action="{{ route('membresias.update', $membresia) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <select name="cliente_id" required>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}"
                                                {{ $membresia->cliente_id == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nombre }} {{ $cliente->apellido }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input type="text" name="convenio"
                                        value="{{ $membresia->convenio }}"
                                        placeholder="Convenio (opcional)">

                                    <label>Inicio:</label>
                                    <input type="date" name="fecha_inicio"
                                        value="{{ $membresia->fecha_inicio?->format('Y-m-d') }}"
                                        required>

                                    <label>Fin:</label>
                                    <input type="date" name="fecha_fin"
                                        value="{{ $membresia->fecha_fin?->format('Y-m-d') }}">

                                    <input type="number" name="monto_mensual"
                                        min="0.01" step="0.01"
                                        value="{{ $membresia->monto_mensual }}" required>

                                    <button type="submit">Guardar cambios</button>
                                </form>
                            </details>

                            <form action="{{ route('membresias.estado', $membresia) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit">
                                    {{ $membresia->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Todavía no hay abonados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/alertas-modulos.js') }}"></script>s
</body>
</html>