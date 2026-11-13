<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
{
    $filtros = $request->validate([
        'desde' => ['nullable', 'date'],
        'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
    ]);

    $pagosFiltrados = Pago::query()
        ->when(!empty($filtros['desde']), function ($consulta) use ($filtros) {
            $consulta->whereDate('fecha_pago', '>=', $filtros['desde']);
        })
        ->when(!empty($filtros['hasta']), function ($consulta) use ($filtros) {
            $consulta->whereDate('fecha_pago', '<=', $filtros['hasta']);
        });

    $totalIngresos = (clone $pagosFiltrados)->sum('monto');
    $cantidadPagos = (clone $pagosFiltrados)->count();

    $ingresosPorDia = (clone $pagosFiltrados)
        ->selectRaw('DATE(fecha_pago) as periodo, SUM(monto) as total')
        ->groupByRaw('DATE(fecha_pago)')
        ->orderBy('periodo', 'desc')
        ->get();

    $ingresosPorMes = (clone $pagosFiltrados)
        ->selectRaw("DATE_FORMAT(fecha_pago, '%Y-%m') as periodo, SUM(monto) as total")
        ->groupByRaw("DATE_FORMAT(fecha_pago, '%Y-%m')")
        ->orderBy('periodo', 'desc')
        ->get();

    $vehiculosFrecuentes = \Illuminate\Support\Facades\DB::table('estacionamientos')
        ->join('vehiculos', 'estacionamientos.vehiculo_id', '=', 'vehiculos.id')
        ->when(!empty($filtros['desde']), function ($consulta) use ($filtros) {
            $consulta->whereDate('estacionamientos.fecha_entrada', '>=', $filtros['desde']);
        })
        ->when(!empty($filtros['hasta']), function ($consulta) use ($filtros) {
            $consulta->whereDate('estacionamientos.fecha_entrada', '<=', $filtros['hasta']);
        })
        ->select(
            'vehiculos.placa',
            'vehiculos.marca',
            'vehiculos.modelo',
            \Illuminate\Support\Facades\DB::raw('COUNT(estacionamientos.id) as cantidad')
        )
        ->groupBy(
            'vehiculos.id',
            'vehiculos.placa',
            'vehiculos.marca',
            'vehiculos.modelo'
        )
        ->orderByDesc('cantidad')
        ->limit(10)
        ->get();

    $espaciosActivos = \App\Models\Espacio::where('activo', true)->count();
    $espaciosOcupados = \App\Models\Espacio::where('activo', true)
        ->where('ocupado', true)
        ->count();

    $datos = compact(
        'filtros',
        'totalIngresos',
        'cantidadPagos',
        'ingresosPorDia',
        'ingresosPorMes',
        'vehiculosFrecuentes',
        'espaciosActivos',
        'espaciosOcupados'
    );

    if ($request->ajax()) {
        return response()->json($datos);
    }

    return view('reportes.index', $datos);
}
    public function exportarExcel(Request $request)
{
    $filtros = $request->validate([
        'desde' => ['nullable', 'date'],
        'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
    ]);

    $pagos = Pago::with(['estacionamiento.vehiculo', 'estacionamiento.espacio'])
        ->when(!empty($filtros['desde']), function ($consulta) use ($filtros) {
            $consulta->whereDate('fecha_pago', '>=', $filtros['desde']);
        })
        ->when(!empty($filtros['hasta']), function ($consulta) use ($filtros) {
            $consulta->whereDate('fecha_pago', '<=', $filtros['hasta']);
        })
        ->orderBy('fecha_pago')
        ->get();

    return response()->streamDownload(function () use ($pagos) {
        $archivo = fopen('php://output', 'w');

        // Permite que Excel interprete correctamente tildes y ñ.
        fwrite($archivo, "\xEF\xBB\xBF");

        fputcsv($archivo, ['Fecha', 'Ticket', 'Placa', 'Espacio', 'Método de pago', 'Monto (Bs)'], ';');

        foreach ($pagos as $pago) {
            fputcsv($archivo, [
                $pago->fecha_pago?->format('d/m/Y H:i'),
                $pago->estacionamiento?->numero_ticket ?? '',
                $pago->estacionamiento?->vehiculo?->placa ?? '',
                $pago->estacionamiento?->espacio?->codigo ?? '',
                $pago->metodo_pago,
                number_format((float) $pago->monto, 2, ',', ''),
            ], ';');
        }

        fclose($archivo);
    }, 'reporte_pagos.csv', [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}
}