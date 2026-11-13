<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehiculoController extends Controller
{
    public function index()
    {
        $vehiculos = Vehiculo::with('cliente')
            ->orderBy('id', 'desc')
            ->get();

        $clientes = Cliente::orderBy('nombre')->get();

        return view('vehiculos.index', compact('vehiculos', 'clientes'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'placa' => 'required|string|max:255|unique:vehiculos,placa',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'tipo' => ['required', Rule::in(['Auto', 'Moto', 'Camioneta'])],
        ]);

        Vehiculo::create($datos);

        return redirect()->route('vehiculos.index')
            ->with('mensaje', 'Vehículo registrado correctamente.');
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $datos = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'placa' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehiculos', 'placa')->ignore($vehiculo->id),
            ],
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'tipo' => ['required', Rule::in(['Auto', 'Moto', 'Camioneta'])],
        ]);

        $vehiculo->update($datos);

        return redirect()->route('vehiculos.index')
            ->with('mensaje', 'Vehículo actualizado correctamente.');
    }

    public function cambiarEstado(Vehiculo $vehiculo)
    {
        $vehiculo->activo = ! $vehiculo->activo;
        $vehiculo->save();

        return redirect()->route('vehiculos.index')
            ->with('mensaje', 'Estado del vehículo actualizado.');
    }
}