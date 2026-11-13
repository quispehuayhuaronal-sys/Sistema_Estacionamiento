<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Espacio;
use App\Models\Estacionamiento;
use App\Models\Pago;
use App\Models\Membresia;

class DashboardController extends Controller
{
    public function index()
    {
        $entradasHoy = Estacionamiento::whereDate('fecha_entrada', today())->count();

        $espaciosActivos = Espacio::where('activo', true)->count();
        $espaciosOcupados = Espacio::where('activo', true)
            ->where('ocupado', true)
            ->count();

        $ingresosHoy = Pago::whereDate('fecha_pago', today())->sum('monto');

        $porcentajeOcupacion = $espaciosActivos > 0
            ? round(($espaciosOcupados / $espaciosActivos) * 100)
            : 0;

        $clientes = Cliente::orderByDesc('id')->get();
        $vehiculos = Vehiculo::with('cliente')->orderByDesc('id')->get();
        $espacios = Espacio::orderBy('piso')->orderBy('codigo')->get();

        $estacionamientos = Estacionamiento::with(['vehiculo', 'espacio'])
            ->orderByDesc('fecha_entrada')
            ->get();

        $pagos = Pago::with(['estacionamiento.vehiculo'])
            ->orderByDesc('fecha_pago')
            ->get();

        $membresias = Membresia::with('cliente')
            ->orderByDesc('id')
            ->get();

        return view('dashboard', compact(
            'entradasHoy',
            'espaciosActivos',
            'espaciosOcupados',
            'ingresosHoy',
            'porcentajeOcupacion',
            'clientes',
            'vehiculos',
            'espacios',
            'estacionamientos',
            'pagos',
            'membresias'
        ));
    }
}