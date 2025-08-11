<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeccionRequest;
use App\Http\Requests\UpdateSeccionRequest;
use App\Models\Seccione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Caracteristica;
use Exception;

class SeccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $secciones = Seccione::with('caracteristica')->get();
        return view('secciones.index', [
            'secciones' => $secciones,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('secciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSeccionRequest $request)
    {
        try {
            DB::beginTransaction();
            $caracteristica = Caracteristica::create([
                'nombre' => $request->input('nombre'),
            ]);
            $caracteristica->seccion()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('secciones.index')->with('error', 'Error al crear la sección: ' . $e->getMessage());
        }

        return redirect()->route('secciones.index')->with('success', 'Sección registrada');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seccione $seccion)
    {
        $seccion->load('caracteristica');
        return view('secciones.edit', compact('seccion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSeccionRequest $request, Seccione $seccion)
    {
        try {
            Caracteristica::where('id', $seccion->caracteristica->id)
                ->update($request->validated());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('secciones.index')->with('error', 'Error al editar la sección: ' . $e->getMessage());
        }

        return redirect()->route('secciones.index')->with('success', 'Sección editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = "";
        $seccion = Seccione::find($id);
        if ($seccion->caracteristica->estado == 1) {
            Caracteristica::where('id', $seccion->caracteristica->id)
                ->update([
                    'estado' => 0
                ]);
            $message = 'Sección eliminada';
        } else {
            Caracteristica::where('id', $seccion->caracteristica->id)
                ->update([
                    'estado' => 1
                ]);
            $message = 'Sección restaurada';
        }
        return redirect()->route('secciones.index')->with('success', $message);
    }
}
