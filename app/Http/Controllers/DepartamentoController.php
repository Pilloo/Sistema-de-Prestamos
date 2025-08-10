<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartamentoRequest;
use App\Http\Requests\UpdateDepartamentoRequest;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Caracteristica;
use Exception;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentos = Departamento::with('caracteristica')->get();
        return view('departamentos.index', [
            'departamentos' => $departamentos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departamentos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartamentoRequest $request)
    {
        try {
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->departamento()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('departamentos.index')->with('error', 'Error al crear el departamento: ' . $e->getMessage());
        }

        return redirect()->route('departamentos.index')->with('success', 'Departamento registrado');
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
    public function edit(Departamento $departamento)
    {
        $departamento->load('caracteristica');
        return view('departamentos.edit', compact('departamento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartamentoRequest $request, Departamento $departamento)
    {
        try {
            Caracteristica::where('id', $departamento->caracteristica->id)
                ->update($request->validated());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('departamentos.index')->with('error', 'Error al editar el departamento: ' . $e->getMessage());
        }

        return redirect()->route('departamentos.index')->with('success', 'Departamento editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = "";
        $departamento = Departamento::find($id);
        if ($departamento->caracteristica->estado == 1) {
            Caracteristica::where('id', $departamento->caracteristica->id)
                ->update([
                    'estado' => 0
                ]);
            $message = 'Departamento eliminado';
        } else {
            Caracteristica::where('id', $departamento->caracteristica->id)
                ->update([
                    'estado' => 1
                ]);
            $message = 'Departamento restaurado';
        }
        return redirect()->route('departamentos.index')->with('success', $message);
    }
}
