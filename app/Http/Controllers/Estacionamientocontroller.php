<?php

namespace App\Http\Controllers;

use App\Models\Estacionamiento;
use App\Models\Espacio;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EstacionamientoController extends Controller
{
    public function index()
    {
        $estacionamientos = Estacionamiento::with(['vehiculo', 'espacio'])
            ->orderByDesc('fecha_entrada')
            ->get();

        $vehiculos = Vehiculo::where('activo', true)
            ->orderBy('placa')
            ->get();

        $espacios = Espacio::where('activo', true)
            ->where('ocupado', false)
            ->orderBy('piso')
            ->orderBy('codigo')
            ->get();

        return view('estacionamientos.index', compact(
            'estacionamientos',
            'vehiculos',
            'espacios'
        ));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'espacio_id' => ['required', 'exists:espacios,id'],
        ]);

        DB::transaction(function () use ($datos) {
            $espacio = Espacio::whereKey($datos['espacio_id'])
                ->lockForUpdate()
                ->firstOrFail();
                

                $activo = (int) $espacio->getRawOriginal('activo');
                $ocupado = (int) $espacio->getRawOriginal('ocupado');
                if ($activo !== 1 || $ocupado !== 0) {
                throw ValidationException::withMessages([
                    'espacio_id' => 'Ese espacio ya no está disponible. Actualiza la página y elige otro.',
                ]);
            }

            $yaEstacionado = Estacionamiento::where('vehiculo_id', $datos['vehiculo_id'])
                ->whereNull('fecha_salida')
                ->exists();

            if ($yaEstacionado) {
                throw ValidationException::withMessages([
                    'vehiculo_id' => 'Ese vehículo ya tiene una entrada sin registrar su salida.',
                ]);
            }

            Estacionamiento::create([
                'vehiculo_id' => $datos['vehiculo_id'],
                'espacio_id' => $espacio->id,
                'numero_ticket' => 'EST-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'fecha_entrada' => now(),
            ]);

            $espacio->update(['ocupado' => true]);
        });

        return redirect()->route('estacionamientos.index')
            ->with('mensaje', 'Entrada registrada. El espacio quedó marcado como ocupado.');
    }
    public function salida(Estacionamiento $estacionamiento)
{
    $resultado = DB::transaction(function () use ($estacionamiento) {
        $registro = Estacionamiento::whereKey($estacionamiento->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($registro->fecha_salida !== null) {
            return false;
        }

        $horaSalida = now();
        $segundos = $registro->fecha_entrada->diffInSeconds($horaSalida);
        $horasIniciadas = max(1, (int) ceil($segundos / 3600));
        $total = $horasIniciadas * 10;

        $registro->fecha_salida = $horaSalida;
        $registro->total = $total;
        $registro->save();

        Espacio::whereKey($registro->espacio_id)
            ->update(['ocupado' => false]);

        return $total;
    });

    if ($resultado === false) {
        return redirect()->route('estacionamientos.index')
            ->with('mensaje', 'Ese vehículo ya tenía registrada su salida.');
    }

    return redirect()->route('estacionamientos.index')
        ->with('mensaje', 'Salida registrada. Total a cobrar: Bs ' . number_format($resultado, 2));
}
}