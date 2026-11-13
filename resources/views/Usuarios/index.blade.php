<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuarios - Estacionamiento</title>
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
<body data-modulo="usuarios">
<div class="contenedor">
    <h1>Gestión de usuarios</h1>
    <p>Sesión iniciada como {{ auth()->user()->name }} ({{ auth()->user()->role }}).</p>
    <a href="{{ url('/panel') }}">Volver al panel</a>

    <form action="{{ route('logout') }}" method="POST" style="display:inline">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>

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
        <h2>Crear usuario</h2>
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <input type="text" name="name" placeholder="Nombre completo"
                   value="{{ old('name') }}" required>

            <input type="email" name="email" placeholder="Correo"
                   value="{{ old('email') }}" required>

            <input type="password" name="password"
                   placeholder="Contraseña (mínimo 8 caracteres)" required>

            <input type="password" name="password_confirmation"
                   placeholder="Repetir contraseña" required>

            <select name="role" required>
                <option value="">Selecciona un rol</option>
                <option value="administrador">Administrador</option>
                <option value="operador">Operador/Cajero</option>
                <option value="supervisor">Supervisor</option>
            </select>

            <button type="submit">Crear usuario</button>
        </form>
    </div>

    <div class="tarjeta">
        <h2>Usuarios registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ ucfirst($usuario->role) }}</td>
                        <td>{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</td>
                        <td>
                            <details>
                                <summary>Editar</summary>
                                <form action="{{ route('usuarios.update', $usuario) }}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="text" name="name"
                                           value="{{ $usuario->name }}" required>

                                    <input type="email" name="email"
                                           value="{{ $usuario->email }}" required>

                                    <select name="role" required>
                                        <option value="administrador"
                                            {{ $usuario->role === 'administrador' ? 'selected' : '' }}>
                                            Administrador
                                        </option>
                                        <option value="operador"
                                            {{ $usuario->role === 'operador' ? 'selected' : '' }}>
                                            Operador/Cajero
                                        </option>
                                        <option value="supervisor"
                                            {{ $usuario->role === 'supervisor' ? 'selected' : '' }}>
                                            Supervisor
                                        </option>
                                    </select>

                                    <input type="password" name="password"
                                           placeholder="Nueva contraseña (opcional)">

                                    <input type="password" name="password_confirmation"
                                           placeholder="Repetir nueva contraseña">

                                    <button type="submit">Guardar cambios</button>
                                </form>
                            </details>

                            @if ($usuario->id !== auth()->id())
                                <form action="{{ route('usuarios.estado', $usuario) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit">
                                        {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            @else
                                Tu cuenta actual
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay usuarios registrados.</td>
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