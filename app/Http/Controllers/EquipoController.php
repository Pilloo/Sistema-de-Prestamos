<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Caracteristica;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreEquipoRequest;
use App\Http\Requests\UpdateEquipoRequest;
use App\Models\EstadoEquipo;
use Illuminate\Support\Facades\File;
use Exception;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipos = Equipo::with(["categorias.caracteristica", "marca.caracteristica", "estado_equipo"])->latest()->get();

        return view('equipos.index', compact('equipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $estado_equipos = EstadoEquipo::all();

        return view('equipos.create', compact('marcas', 'categorias', 'estado_equipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipoRequest $request)
    {
        try{
            DB::beginTransaction();
            $equipo = new Equipo();
            if ($request->hasFile('img_path')){
                $name = $equipo->handleUploadImage($request->file('img_path'));
            }else{
                $name = null;
            }
            $equipo->fill([
                'modelo' => $request->modelo,
                'numero_serie' => $request->numero_serie,
                'contenido_etiqueta' => $request->contenido_etiqueta,
                'detalle' => $request->detalle,
                'marca_id' => $request->marca_id,
                'estado_equipo_id' => $request->estado_equipo_id,
                'img_path' => $name
            ]);

            $equipo->save();
            $categorias = $request->get('categorias');
            $equipo->categorias()->attach($categorias);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            dd($e->getMessage());
        }
        return redirect()->route('equipos.index')->with('success', 'Equipo registrado');
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
    public function edit(Equipo $equipo)
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $estado_equipos = EstadoEquipo::all();

        return view('equipos.edit', compact('equipo', 'marcas', 'categorias', 'estado_equipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipoRequest $request, Equipo $equipo)
    {
        try {
            DB::beginTransaction();

            $rutaImagenes = public_path('img/equipos'); // Ajusta el path si es diferente

            if ($request->hasFile('img_path')) {
                // Borra la imagen anterior si existe
                if ($equipo->img_path && File::exists($rutaImagenes . '/' . $equipo->img_path)) {
                    File::delete($rutaImagenes . '/' . $equipo->img_path);
                }

                // Guarda la nueva imagen
                $file = $request->file('img_path');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($rutaImagenes, $filename);
            } else {
                $filename = $equipo->img_path;
            }

            // Actualiza datos
            $equipo->update([
                'modelo' => $request->modelo,
                'numero_serie' => $request->numero_serie,
                'contenido_etiqueta' => $request->contenido_etiqueta,
                'detalle' => $request->detalle,
                'marca_id' => $request->marca_id,
                'estado_equipo_id' => $request->estado_equipo_id,
                'img_path' => $filename,
            ]);

            // Actualiza categorías relacionadas
            $categorias = $request->get('categorias');
            $equipo->categorias()->sync($categorias);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error: ' . $e->getMessage());
        }

        return redirect()->route('equipos.index')->with('success', 'Equipo editado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
        $equipo = Equipo::findOrFail($id);
        $message = '';

        if ($equipo->estado_equipo_id != 7) {
            // Cambiar a eliminado
            $equipo->estado_equipo_id = 7;
            $message = 'Equipo eliminado';
        } else {
            // Restaurar (asumimos estado activo = 1)
            $equipo->estado_equipo_id = 1;
            $message = 'Equipo restaurado';
        }

        $equipo->save();

        return redirect()->route('equipos.index')->with('success', $message);
    }


}
