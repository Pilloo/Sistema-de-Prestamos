<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Caracteristica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;
use App\Http\Requests\StoreEquipoRequest;
use App\Http\Requests\UpdateEquipoRequest;
use App\Models\EstadoEquipo;
use App\Models\LoteEquipo;
use Illuminate\Support\Facades\File;
use Exception;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipos = Equipo::with(["estado_equipo", "lote"])->latest()->get();

        return view('equipos.index', compact('equipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $equipos = Equipo::select('id', 'modelo', 'numero_serie', 'contenido_etiqueta', 'detalle', 'cantidad_total', 'marca_id', 'estado_equipo_id', 'img_path')
            ->where('estado_equipo_id', '!=', 7)
            ->get();

        $equiposCategorias = $equipos->map(function ($equipo) {
            return [
                'id' => $equipo->id,
                'modelo' => $equipo->modelo,
                'numero_serie' => $equipo->numero_serie,
                'contenido_etiqueta' => $equipo->contenido_etiqueta,
                'detalle' => $equipo->detalle,
                'cantidad_total' => $equipo->cantidad_total,
                'marca_id' => $equipo->marca_id,
                'estado_equipo_id' => $equipo->estado_equipo_id,
                'img_path' => $equipo->img_path,
                'categorias' => $equipo->categorias->pluck('id')->toArray(),
            ];
        });
        
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $estado_equipos = EstadoEquipo::all();

        return view('equipos.create', compact('marcas', 'categorias', 'estado_equipos', 'equipos', 'equiposCategorias' ));
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

            $equipoExistente = Equipo::where('modelo', $request->modelo)
                ->where('numero_serie', $request->numero_serie)
                ->first();
            
            if($equipoExistente){
                $equipoExistente->cantidad_total += $request->cantidad_total;
                $equipoExistente->cantidad_disponible += $request->cantidad_total;
                $equipoExistente->save();
            }else{
                $equipo->fill([
                    'modelo' => $request->modelo,
                    'numero_serie' => $request->numero_serie,
                    'contenido_etiqueta' => $request->contenido_etiqueta,
                    'detalle' => $request->detalle,
                    'cantidad_total' => $request->cantidad_total,
                    'cantidad_disponible' => $request->cantidad_total,
                    'marca_id' => $request->marca_id,
                    'estado_equipo_id' => $request->estado_equipo_id,
                    'img_path' => $name
                ]);

                $equipo->save();
                $categorias = $request->get('categorias');
                $equipo->categorias()->attach($categorias);
            }
            
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

            $rutaImagenes = public_path('img/equipos');

            if ($request->hasFile('img_path')) {
                if ($equipo->img_path && File::exists($rutaImagenes . '/' . $equipo->img_path)) {
                    File::delete($rutaImagenes . '/' . $equipo->img_path);
                }

                $file = $request->file('img_path');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($rutaImagenes, $filename);
            } else {
                $filename = $equipo->img_path;
            }

            // calculo para poder actualizar la cantidad disponible
            $cantidadTotalNueva = $request->cantidad_total;
            $prestadosActualmente = $equipo->cantidad_total - $equipo->cantidad_disponible;
            // se valida que la nueva cantidad total no sea menor que los que ya están prestados
            if ($cantidadTotalNueva < $prestadosActualmente) {
                throw new Exception('No se puede reducir la cantidad total por debajo de la cantidad prestada (' . $prestadosActualmente . ')');
            }

            $diferencia = $cantidadTotalNueva - $equipo->cantidad_total;
            $cantidadDisponibleNueva = $equipo->cantidad_disponible + $diferencia;

            $equipo->update([
                'modelo' => $request->modelo,
                'numero_serie' => $request->numero_serie,
                'contenido_etiqueta' => $request->contenido_etiqueta,
                'detalle' => $request->detalle,
                'cantidad_total' => $cantidadTotalNueva,
                'cantidad_disponible' => $cantidadDisponibleNueva,
                'marca_id' => $request->marca_id,
                'estado_equipo_id' => $request->estado_equipo_id,
                'img_path' => $filename,
            ]);

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
            $equipo->estado_equipo_id = 7;
            $message = 'Equipo eliminado';
        } else {
            $equipo->estado_equipo_id = 1;
            $message = 'Equipo restaurado';
        }

        $equipo->save();

        return redirect()->route('equipos.index')->with('success', $message);
    }


}
