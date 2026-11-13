<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Membresia;
use Illuminate\Http\Request;

class MembresiaController extends Controller
{
    public function index()
    {
        $membresias = Membresia::with('cliente')
            ->orderByDesc('id')
            ->get();

        $clientes = Cliente::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('membresias.index', compact('membresias', 'clientes'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'convenio' => ['nullable', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'monto_mensual' => ['required', 'numeric', 'min:0.01'],
        ]);

        Membresia::create($datos);

        return redirect()->route('membresias.index')
            ->with('mensaje', 'Abonado registrado correctamente.');
    }

    public function update(Request $request, Membresia $membresia)
    {
        $datos = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'convenio' => ['nullable', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'monto_mensual' => ['required', 'numeric', 'min:0.01'],
        ]);

        $membresia->update($datos);

        return redirect()->route('membresias.index')
            ->with('mensaje', 'Membresía actualizada correctamente.');
    }

    public function cambiarEstado(Membresia $membresia)
    {
        $membresia->activo = ! $membresia->activo;
        $membresia->save();

        return redirect()->route('membresias.index')
            ->with('mensaje', 'Estado de la membresía actualizado.');
    }
}