<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Caja y pagos - Estacionamiento</title>
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
<body data-modulo="pagos">
<div class="contenedor">
    <h1>Caja y pagos</h1>
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
        <h2>Registrar pago</h2>

        @forelse ($pendientes as $registro)
            <form action="{{ route('pagos.store') }}" method="POST">
                @csrf

                <input type="hidden" name="estacionamiento_id"
                       value="{{ $registro->id }}">

                <p>
                    Ticket: {{ $registro->numero_ticket }} |
                    Placa: {{ $registro->vehiculo->placa ?? '—' }} |
                    Espacio: {{ $registro->espacio->codigo ?? '—' }} |
                    Salida: {{ $registro->fecha_salida?->format('d/m/Y H:i') }}
                </p>

                <p>
                   <strong>Total a cobrar:</strong>
                     Bs {{ number_format((float) $registro->total, 2) }}
                </p>

                <label>Método:</label>
                <select name="metodo_pago" required>
                    <option value="">Selecciona</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="QR">QR</option>
                </select>

                <button type="submit">Registrar cobro</button>
            </form>
            <hr>
        @empty
            <p>No hay salidas pendientes de cobro.</p>
        @endforelse
    </div>

    <div class="tarjeta">
        <h2>Pagos registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Ticket</th>
                    <th>Placa</th>
                    <th>Método</th>
                    <th>Monto (Bs)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pagos as $pago)
                    <tr>
                        <td>{{ $pago->fecha_pago?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td>{{ $pago->estacionamiento->numero_ticket ?? '—' }}</td>
                        <td>{{ $pago->estacionamiento->vehiculo->placa ?? '—' }}</td>
                        <td>{{ $pago->metodo_pago }}</td>
                        <td>{{ $pago->monto }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Todavía no hay pagos registrados.</td>
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