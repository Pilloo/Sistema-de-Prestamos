<?php

namespace App\Http\Controllers;

use App\Models\SolicitudPrestamo;
use App\Models\LoteEquipo;
use App\Models\Equipo;
use App\Http\Requests\StoreSolicitudPrestamoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SolicitudPrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $solicitudes = SolicitudPrestamo::with(['estadoSolicitud', 'lotes.marca'])
            ->where('id_solicitante', Auth::id())
            ->orderBy('fecha_solicitud', 'desc')
            ->get();

        return view('solicitud-prestamo.index', compact('solicitudes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $lotes = LoteEquipo::with(['marca', 'categoria'])
            ->where('cantidad', '>', 0)
            ->where('disponible', true)
            ->get();

        return view('solicitud-prestamo.create', compact('lotes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSolicitudPrestamoRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Create the loan request
            $solicitud = SolicitudPrestamo::create([
                'fecha_solicitud' => $request->fecha_solicitud,
                'fecha_limite_solicitada' => $request->fecha_limite_solicitada,
                'detalle' => $request->detalle,
                'id_solicitante' => Auth::id(),
                'id_tecnico_aprobador' => null,
                'id_estado_solicitud' => 1 // "En espera"
            ]);

            // Process each lot with quantity and return date
            foreach ($request->lotes as $loteData) {
                $loteId = $loteData['id_lote'];
                $cantidadSolicitada = $loteData['cantidad'];
                $fechaDevolucion = $loteData['fecha_devolucion'];

                // Get the lot
                $lote = LoteEquipo::findOrFail($loteId);

                // Check if requested quantity is available
                if ($lote->cantidad < $cantidadSolicitada) {
                    throw new \Exception("Cantidad solicitada no disponible para el lote: " . $lote->modelo);
                }

                // Attach lot with pivot data
                $solicitud->lotes()->attach($loteId, [
                    'cantidad' => $cantidadSolicitada,
                    'fecha_devolucion' => $fechaDevolucion
                ]);

                // Update lot quantity
                $lote->decrement('cantidad', $cantidadSolicitada);

                // If lot is now empty, mark as unavailable
                if ($lote->cantidad === 0) {
                    $lote->update(['disponible' => false]);
                }
            }

            DB::commit();

            Session::flash('success', 'Solicitud de préstamo creada exitosamente');
            return redirect()->route('solicitud-prestamo.show', $solicitud->id);

        } catch (\Exception $e) {
            DB::rollBack();

            Session::flash('error', 'Error al crear la solicitud de préstamo: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $solicitud = SolicitudPrestamo::with([
            'estadoSolicitud',
            'lotes.marca',
            'lotes.categoria'
        ])->findOrFail($id);

        // Ensure user can only see their own requests
        if ($solicitud->id_solicitante !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta solicitud');
        }

        return view('solicitud-prestamo.show', compact('solicitud'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $solicitud = SolicitudPrestamo::with(['lotes'])->findOrFail($id);

        // Ensure user can only delete their own pending requests
        if ($solicitud->id_solicitante !== Auth::id() || $solicitud->id_estado_solicitud !== 1) {
            abort(403, 'No puedes eliminar esta solicitud');
        }

        DB::beginTransaction();

        try {
            // Restore quantities to lots
            foreach ($solicitud->lotes as $lote) {
                $cantidadPrestada = $lote->pivot->cantidad;
                $lote->increment('cantidad', $cantidadPrestada);

                // Mark lot as available again
                if ($lote->cantidad > 0) {
                    $lote->update(['disponible' => true]);
                }
            }

            // Delete the request (this will also delete pivot records due to cascade)
            $solicitud->delete();

            DB::commit();

            Session::flash('success', 'Solicitud de préstamo eliminada exitosamente');
            return redirect()->route('solicitud-prestamo.index');

        } catch (\Exception $e) {
            DB::rollBack();

            Session::flash('error', 'Error al eliminar la solicitud de préstamo: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}F
