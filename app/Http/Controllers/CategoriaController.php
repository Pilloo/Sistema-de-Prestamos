<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;
use App\Models\Caracteristica;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use Exception;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::with('caracteristica')->get();
        return view('categorias.index', [
            'categorias' => $categorias,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        try {
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->categoria()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('categorias.index')->with('error', 'Error al crear la categoría: ' . $e->getMessage());
        }

        return redirect()->route('categorias.index')->with('success', 'Categoría registrada');
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
    public function edit(Categoria $categoria)
    {
        $categoria->load('caracteristica');
        return view('categorias.edit' , compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        try{
            Caracteristica::where('id', $categoria->caracteristica->id)
            ->update($request->validated());
        }catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('categorias.index')->with('error', 'Error al editar la categoría: ' . $e->getMessage());
        }
        
        return redirect()->route('categorias.index')->with('success', 'Categoria editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = "";
        $categoria = Categoria::find($id);
        if ($categoria->caracteristica->estado==1){
            Caracteristica::where('id', $categoria->caracteristica->id)
            ->update([
                'estado' => 0
            ]);
            $message = 'Categoria eliminada';
        } else{
            Caracteristica::where('id', $categoria->caracteristica->id)
            ->update([
                'estado' => 1
            ]);
            $message = 'Categoria restaurada';
        }
        return redirect()->route('categorias.index')->with('success', $message);
    }
}
