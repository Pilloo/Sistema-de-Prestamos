<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Illuminate\Http\Request;

class PrestamoController extends Controller{
    /**
     * Listar solo los préstamos del usuario actual
     */
    public function misPrestamos()
    {
        $prestamos = Prestamo::with(['solicitante', 'aprobador', 'estadoPrestamo', 'solicitud.equipos.lote.marca', 'solicitud.equipos.estado_equipo'])
            ->where('id_solicitante', auth()->id())
            ->orderBy('id', 'desc')
            ->get();
        return view('prestamos.index', compact('prestamos'));
    }
    /**
     * Listar todos los préstamos
     */
    public function index()
    {
        $prestamos = Prestamo::with(['solicitante', 'aprobador', 'estadoPrestamo', 'solicitud.equipos.lote.marca', 'solicitud.equipos.estado_equipo'])->orderBy('id', 'desc')->get();
        return view('prestamos.index', compact('prestamos'));
    }

    /**
     * Mostrar el detalle de un préstamo
     */
    public function show($id)
    {
        $prestamo = Prestamo::with(['solicitante', 'aprobador', 'estadoPrestamo', 'solicitud.equipos.lote.marca', 'solicitud.equipos.estado_equipo'])->findOrFail($id);
        return view('prestamos.show', compact('prestamo'));
    }
}
