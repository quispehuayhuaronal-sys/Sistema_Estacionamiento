<?php

namespace App\Http\Controllers;

use App\Models\Estacionamiento;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PagoController extends Controller
{
    public function index()
    {
        $pendientes = Estacionamiento::with(['vehiculo', 'espacio'])
            ->whereNotNull('fecha_salida')
            ->whereDoesntHave('pago')
            ->orderByDesc('fecha_salida')
            ->get();

        $pagos = Pago::with(['estacionamiento.vehiculo', 'estacionamiento.espacio'])
            ->orderByDesc('fecha_pago')
            ->get();

        return view('pagos.index', compact('pendientes', 'pagos'));
    }

    public function store(Request $request)
{
    $datos = $request->validate([
        'estacionamiento_id' => ['required', 'exists:estacionamientos,id'],
        'metodo_pago' => ['required', 'in:Efectivo,QR'],
    ]);

    DB::transaction(function () use ($datos) {
        $estacionamiento = Estacionamiento::whereKey($datos['estacionamiento_id'])
            ->lockForUpdate()
            ->firstOrFail();

        if ($estacionamiento->fecha_salida === null) {
            throw ValidationException::withMessages([
                'estacionamiento_id' => 'Primero registra la salida del vehículo.',
            ]);
        }

        if ($estacionamiento->total === null || $estacionamiento->total <= 0) {
            throw ValidationException::withMessages([
                'estacionamiento_id' => 'Este estacionamiento no tiene un total válido.',
            ]);
        }

        if (Pago::where('estacionamiento_id', $estacionamiento->id)->exists()) {
            throw ValidationException::withMessages([
                'estacionamiento_id' => 'Este estacionamiento ya tiene un pago registrado.',
            ]);
        }

        Pago::create([
            'estacionamiento_id' => $estacionamiento->id,
            'monto' => $estacionamiento->total,
            'metodo_pago' => $datos['metodo_pago'],
            'fecha_pago' => now(),
        ]);
    });

    return redirect()->route('pagos.index')
        ->with('mensaje', 'Pago registrado correctamente.');
}
}