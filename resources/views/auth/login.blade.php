<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - Estacionamiento</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; }
        .caja { max-width: 380px; margin: 80px auto; padding: 25px; background: white; border-radius: 8px; }
        input, button { box-sizing: border-box; width: 100%; padding: 10px; margin: 8px 0; }
        button { cursor: pointer; background: #1677d2; color: white; border: 0; }
        .error { color: #b00020; }
    </style>
</head>
<body>
    <div class="caja">
        <h1>Iniciar sesión</h1>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <label for="email">Correo</label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}" required autofocus>

            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>