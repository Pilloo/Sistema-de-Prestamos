<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Marca;
use App\Models\Caracteristica;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use Exception;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = Marca::with('caracteristica')->get();
        return view('marcas.index', [
            'marcas' => $marcas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarcaRequest $request)
    {
        try {
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->marca()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('marcas.index')->with('error', 'Error al crear la marca: ' . $e->getMessage());
        }

        return redirect()->route('marcas.index')->with('success', 'Marca registrada');
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
    public function edit(Marca $marca)
    {
        $marca->load('caracteristica');
        return view('marcas.edit', compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarcaRequest $request, Marca $marca)
    {
        try {
            Caracteristica::where('id', $marca->caracteristica->id)
                ->update($request->validated());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('marcas.index')->with('error', 'Error al editar la marca: ' . $e->getMessage());
        }

        return redirect()->route('marcas.index')->with('success', 'Marca editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = "";
        $marca = Marca::find($id);
        if ($marca->caracteristica->estado == 1) {
            Caracteristica::where('id', $marca->caracteristica->id)
                ->update([
                    'estado' => 0
                ]);
            $message = 'Marca eliminada';
        } else {
            Caracteristica::where('id', $marca->caracteristica->id)
                ->update([
                    'estado' => 1
                ]);
            $message = 'Marca restaurada';
        }
        return redirect()->route('marcas.index')->with('success', $message);
    }
}
