<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Caracteristica;
use App\Models\EstadoEquipo;
use App\Models\LoteEquipo;
use App\Http\Requests\StoreLoteEquipoRequest;
use App\Http\Requests\UpdateLoteEquipoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Js;
use Illuminate\Support\Facades\File;
use Exception;

class LoteEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lotes = LoteEquipo::with(['marca', 'categorias', 'equipos.estado_equipo'])->latest()->get();

        return view('lotes.index', compact('lotes'));
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

        // Get all existing lots with their relations for duplication
        $todos_lotes = \App\Models\LoteEquipo::with(['marca.caracteristica', 'categorias.caracteristica'])->get();

        // Agrupar por modelo, marca y categorías (como en inventario)
        $agrupados = [];
        foreach ($todos_lotes as $lote) {
            $cat_ids = $lote->categorias->pluck('id')->sort()->implode(',');
            $key = $lote->modelo . '|' . $lote->marca_id . '|' . $cat_ids;
            if (!isset($agrupados[$key])) {
                $agrupados[$key] = $lote;
            }
        }
        $lotes_existentes = collect(array_values($agrupados));

        return view('lotes.create', compact('marcas', 'categorias', 'estado_equipos', 'lotes_existentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoteEquipoRequest $request)
    {
        try{
            DB::beginTransaction();
            $lote_equipo = new LoteEquipo();
            $name = null;
            if ($request->hasFile('img_path')){
                $name = $lote_equipo->handleUploadImage($request->file('img_path'));
            } else if ($request->filled('lote_existente_id')) {
                // Copiar la imagen del lote seleccionado
                $lote_existente = LoteEquipo::find($request->lote_existente_id);
                if ($lote_existente && $lote_existente->img_path) {
                    $origen = public_path('img/equipos/' . $lote_existente->img_path);
                    $nuevo_nombre = time() . '_copia_' . $lote_existente->img_path;
                    $destino = public_path('img/equipos/' . $nuevo_nombre);
                    if (file_exists($origen)) {
                        copy($origen, $destino);
                        $name = $nuevo_nombre;
                    }
                }
            }

            $lote_equipo->fill([
                'modelo' => $request->modelo,
                'contenido_etiqueta' => $request->contenido_etiqueta,
                'detalle' => $request->detalle,
                'cantidad_total' => $request->cantidad_total,
                'cantidad_disponible' => $request->cantidad_total,
                'marca_id' => $request->marca_id,
                'img_path' => $name
            ])->save();

            $categorias = $request->get('categorias');
            $lote_equipo->categorias()->attach($categorias);

            DB::commit();
            return redirect()->route('lotes.seriales.create', $lote_equipo->id)
                             ->with('success', 'Lote creado. Ahora registre los números de serie.');
        }catch(Exception $e){
            DB::rollBack();
            return back()->with('error', 'Error al crear el lote de equipos: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */

    public function show(LoteEquipo $loteEquipo)
    {
        $equipos = $loteEquipo->equipos()->get();
        return view('lotes.show', compact('loteEquipo', 'equipos'));
    }

    //estas bichillas hacen la magia
    public function showSerialForm(LoteEquipo $loteEquipo)
    {
        $estado_equipos = EstadoEquipo::all();
        $faltantes = $loteEquipo->cantidad_total - $loteEquipo->equipos()->count();
        return view('lotes.seriales', compact('loteEquipo', 'faltantes', 'estado_equipos'));
    }

    //y esta hasta te deja los pelos de punto bichillo
    //una locura
    //P.D. estas cosas son las que hacen que la vida valga la pena
    //P.D. despues pongo las reglas del request bien
    public function saveSerials(Request $request, LoteEquipo $loteEquipo)
    {
        try {
            DB::beginTransaction();

            foreach ($request->seriales as $serial) {
                Equipo::create([
                    'numero_serie' => $serial['numero'],
                    'lote_equipo_id' => $loteEquipo->id,
                    'estado_equipo_id' => $serial['estado_equipo_id'],
                ]);
            }

            DB::commit();
            return redirect()->route('lotes.index')->with('success', 'Seriales agregados correctamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar los números de serie: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoteEquipo $loteEquipo)
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('lotes.edit', compact('loteEquipo', 'marcas', 'categorias'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoteEquipoRequest $request, LoteEquipo $loteEquipo)
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('img_path')) {
                $name = $loteEquipo->handleUploadImage($request->file('img_path'));
            } else {
                $name = $loteEquipo->img_path; // mantiene la imagen actual
            }

            $loteEquipo->update([
                'modelo' => $request->modelo,
                'contenido_etiqueta' => $request->contenido_etiqueta,
                'detalle' => $request->detalle,
                'marca_id' => $request->marca_id,
                'img_path' => $name,
            ]);

            $categorias = $request->get('categorias', []);
            $loteEquipo->categorias()->sync($categorias);

            DB::commit();
            return redirect()->route('lotes.index')->with('success', 'Lote actualizado correctamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el lote: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoteEquipo $loteEquipo)
    {
        //algun dia tendremos destroy
    }

}
