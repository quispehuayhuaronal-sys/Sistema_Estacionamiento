<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EspacioController extends Controller
{
    public function index()
    {
        $espacios = Espacio::orderBy('piso')
            ->orderBy('sector')
            ->orderBy('codigo')
            ->get();

        return view('espacios.index', compact('espacios'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'codigo' => 'required|string|max:255|unique:espacios,codigo',
            'piso' => 'required|string|max:100',
            'sector' => 'required|string|max:100',
        ]);

        Espacio::create($datos);

        return redirect()->route('espacios.index')
            ->with('mensaje', 'Espacio registrado correctamente.');
    }

    public function update(Request $request, Espacio $espacio)
    {
        $datos = $request->validate([
            'codigo' => [
                'required',
                'string',
                'max:255',
                Rule::unique('espacios', 'codigo')->ignore($espacio->id),
            ],
            'piso' => 'required|string|max:100',
            'sector' => 'required|string|max:100',
        ]);

        $espacio->update($datos);

        return redirect()->route('espacios.index')
            ->with('mensaje', 'Espacio actualizado correctamente.');
    }

    public function cambiarEstado(Espacio $espacio)
    {
        $espacio->activo = ! $espacio->activo;
        $espacio->save();

        return redirect()->route('espacios.index')
            ->with('mensaje', 'Estado del espacio actualizado.');
    }
}