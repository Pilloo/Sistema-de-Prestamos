<?php

namespace App\Http\Controllers;

use App\Models\SolicitudPrestamo;
use App\Models\LoteEquipo;
use App\Models\EstadoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SolicitudPrestamoController extends Controller
{
    /**
     * Listar solo las solicitudes del usuario actual
     */
    public function misSolicitudes()
    {
        $solicitudes = SolicitudPrestamo::with(['solicitante', 'estadoSolicitud'])
            ->where('id_solicitante', Auth::user() ? Auth::user()->id : null)
            ->orderBy('id', 'desc')
            ->get();
        return view('solicitud.index', compact('solicitudes'));
    }

    /**
     * Listar todas las solicitudes
     */
    public function index()
    {
        $solicitudes = SolicitudPrestamo::with(['solicitante', 'estadoSolicitud'])->orderBy('id', 'desc')->get();
        return view('solicitud.index', compact('solicitudes'));
    }

    /**
     * Mostrar el detalle de una solicitud
     */
    public function show($id)
    {
        $solicitud = SolicitudPrestamo::with([
            'solicitante',
            'estadoSolicitud',
            'equipos.lote.marca',
            'equipos.estado_equipo',
            'equipos.lote.categorias'
        ])->findOrFail($id);
        return view('solicitud.show', compact('solicitud'));
    }
    /**
     * Show equipment selection page (Blade view)
     */
    public function create()
    {
        $lotes = LoteEquipo::with(['marca', 'categorias', 'equipos.estado_equipo'])
            ->where('cantidad_disponible', '>', 0)
            ->get();

        // Agrupar lotes por modelo, marca y categorías (similar a inventario)
        $agrupados = [];
        foreach ($lotes as $lote) {
            foreach ($lote->categorias as $categoria) {
                $key = $lote->modelo . '|' . ($lote->marca->caracteristica ? $lote->marca->caracteristica->nombre : 'Sin marca') . '|' . ($categoria->caracteristica->nombre ?? 'Sin categoría');
                if (!isset($agrupados[$key])) {
                    $agrupados[$key] = [
                        'modelo' => $lote->modelo,
                        'marca' => $lote->marca->caracteristica ? $lote->marca->caracteristica->nombre : 'Sin marca',
                        'categoria' => $categoria->caracteristica->nombre ?? 'Sin categoría',
                        'cantidad_total' => 0,
                        'cantidad_disponible' => 0,
                        'lotes' => [],
                    ];
                }
                $agrupados[$key]['cantidad_total'] += $lote->cantidad_total;
                $agrupados[$key]['cantidad_disponible'] += $lote->cantidad_disponible;
                $agrupados[$key]['lotes'][] = $lote;
            }
        }

        $equiposAgrupados = collect($agrupados)->values();

        return view('solicitud.create', ['equiposAgrupados' => $equiposAgrupados]);
    }

    /**
     * Show shopping cart page
     */
    public function cart()
    {
        // Get cart from session
        $cart = session()->get('equipment_cart', []);

        return view('solicitud.cart', compact('cart'));
    }

    /**
     * Add item to cart (session)
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lote_id' => 'required|exists:lote_equipos,id',
            'cantidad' => 'required|integer|min:1',
            'equipo_id' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $lote = LoteEquipo::findOrFail($request->lote_id);

        // Check availability
        if ($lote->cantidad_disponible < $request->cantidad) {
            return redirect()->back()
                ->with('error', 'No hay suficiente cantidad disponible en el lote seleccionado.');
        }

        // Get current cart
        $cart = session()->get('equipment_cart', []);

        // Obtener los equipos seleccionados por serial
        $equipoIds = $request->equipo_id;
        $seriales = [];
        foreach ($equipoIds as $eid) {
            $equipo = \App\Models\Equipo::find($eid);
            if ($equipo) {
                $seriales[] = $equipo->numero_serie;
            }
        }

        // Filtrar los equipos que ya están en el carrito (por id)
        $equiposEnCarrito = collect($cart)->flatMap(function ($item) {
            return is_array($item['equipo_id']) ? $item['equipo_id'] : [$item['equipo_id']];
        })->toArray();

        $nuevosEquipos = [];
        foreach ($equipoIds as $index => $eid) {
            if (!in_array($eid, $equiposEnCarrito)) {
                $nuevosEquipos[] = [
                    'lote_id' => $request->lote_id,
                    'modelo' => $lote->modelo,
                    'marca' => $lote->marca->caracteristica ? $lote->marca->caracteristica->nombre : ($lote->marca->nombre ?? 'Sin marca'),
                    'categoria' => $lote->categorias->first()->nombre ?? 'Sin categoría',
                    'cantidad' => 1,
                    'max_disponible' => $lote->cantidad_disponible,
                    'equipo_id' => [$eid],
                    'numero_serie' => $seriales[$index]
                ];
            }
        }

        // Si no hay nuevos equipos, mostrar error
        if (empty($nuevosEquipos)) {
            return redirect()->back()
                ->with('error', 'Los equipos seleccionados ya están en el carrito.');
        }

        // Agregar los nuevos equipos al carrito
        $cart = array_merge($cart, $nuevosEquipos);
        session()->put('equipment_cart', $cart);

        return redirect()->route('solicitud.cart')
            ->with('success', 'Equipo(s) agregado(s) al carrito correctamente.');
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($index)
    {
        $cart = session()->get('equipment_cart', []);

        if (isset($cart[$index])) {
            unset($cart[$index]);
            session()->put('equipment_cart', array_values($cart)); // Reindex array
        }

        return redirect()->route('solicitud.cart')
            ->with('success', 'Equipo removido del carrito.');
    }

    /**
     * Update cart item
     */
    public function updateCart(Request $request, $index)
    {
        $cart = session()->get('equipment_cart', []);

        if (!isset($cart[$index])) {
            return redirect()->route('solicitud.cart')
                ->with('error', 'Ítem no encontrado en el carrito.');
        }

        $validator = Validator::make($request->all(), [
            'cantidad' => 'required|integer|min:1|max:' . $cart[$index]['max_disponible'],
            'fecha_limite' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return redirect()->route('solicitud.cart')
                ->withErrors($validator)
                ->withInput();
        }

        $cart[$index]['cantidad'] = $request->cantidad;
        $cart[$index]['fecha_limite'] = $request->fecha_limite;
        if ($request->has('equipo_id')) {
            // Permitir múltiples equipos seleccionados
            $equipoIds = is_array($request->equipo_id) ? $request->equipo_id : [$request->equipo_id];
            $cart[$index]['equipo_id'] = $equipoIds;
            // Actualizar los seriales para mostrar en el carrito
            $seriales = [];
            foreach ($equipoIds as $eid) {
                $equipo = \App\Models\Equipo::find($eid);
                if ($equipo) {
                    $seriales[] = $equipo->numero_serie;
                }
            }
            $cart[$index]['numero_serie'] = implode(', ', $seriales);
        }
        session()->put('equipment_cart', $cart);
        return redirect()->route('solicitud.cart')
            ->with('success', 'Carrito actualizado correctamente.');
    }

    /**
     * Submit the loan request
     */
    public function store(Request $request)
    {
        $cart = session()->get('equipment_cart', []);

        if (empty($cart)) {
            return redirect()->route('solicitud.cart')
                ->with('error', 'El carrito está vacío.');
        }

        // Validate all items in cart
        foreach ($cart as $index => $item) {
            $lote = LoteEquipo::find($item['lote_id']);

            if (!$lote || $lote->cantidad_disponible < $item['cantidad']) {
                return redirect()->route('solicitud.cart')
                    ->with('error', "El lote {$item['modelo']} ya no tiene suficiente cantidad disponible.");
            }
        }

        try {
            DB::transaction(function () use ($cart, $request) {
                // Si el admin seleccionó un usuario, asociar la solicitud a ese usuario
                $idSolicitante = $request->has('user_id') ? $request->input('user_id') : (Auth::user() ? Auth::user()->id : null);
                // Buscar el id del estado 'Aprobado'
                $estadoAprobado = \App\Models\EstadoSolicitud::where('nombre', 'Aprobado')->first();
                $idEstadoAprobado = $estadoAprobado ? $estadoAprobado->id : 2; // fallback a 2 si existe

                $solicitud = SolicitudPrestamo::create([
                    'id_solicitante' => $idSolicitante,
                    'fecha_solicitud' => now(),
                    'fecha_limite_solicitada' => $request->input('fecha_limite'), // Use first item's due date
                    'detalle' => $request->detalle ?? 'Solicitud de préstamo de equipos',
                    'id_estado_solicitud' => $idEstadoAprobado,
                    'id_tecnico_aprobador' => Auth::user() ? Auth::user()->id : null
                ]);

                // Procesar cada equipo individual seleccionado por serial
                foreach ($cart as $item) {
                    $lote = LoteEquipo::findOrFail($item['lote_id']);
                    $equipoIds = is_array($item['equipo_id'] ?? null) ? $item['equipo_id'] : (isset($item['equipo_id']) ? [$item['equipo_id']] : []);
                    foreach ($equipoIds as $eid) {
                        $equipo = \App\Models\Equipo::find($eid);
                        if ($equipo) {
                            $solicitud->equipos()->attach($equipo->id);
                            $lote->decrement('cantidad_disponible', 1);
                            $estadoEnPrestamo = \App\Models\EstadoEquipo::where('nombre', 'En préstamo')->first();
                            if ($estadoEnPrestamo) {
                                $equipo->estado_equipo_id = $estadoEnPrestamo->id;
                                $equipo->save();
                            }
                        }
                    }
                }
            });

            // Clear the cart
            session()->forget('equipment_cart');

            return redirect()->route('solicitud.create')
                ->with('success', 'Solicitud de préstamo enviada correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('solicitud.cart')
                ->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }


    /**
     * Clear the entire cart
     */
    public function clearCart()
    {
        session()->forget('equipment_cart');

        return redirect()->route('solicitud.create')
            ->with('success', 'Carrito vaciado correctamente.');
    }

    /**
     * Realiza la devolución del préstamo.
     */
    public function devolver($id)
    {
        // Solo admin puede devolver
        if (!\Illuminate\Support\Facades\Gate::allows('gestionar solicitudes')) {
            return redirect()->back()->with('error', 'No tienes permisos para realizar la devolución.');
        }

        $solicitud = SolicitudPrestamo::with(['equipos.lote'])->findOrFail($id);

        // Solo se puede devolver si está en estado "Prestado / Entregado" (id_estado_solicitud = 2)
        if ($solicitud->id_estado_solicitud != 2) {
            return redirect()->back()->with('error', 'La solicitud no está en estado de préstamo activo.');
        }

        $equiposIds = $solicitud->equipos->pluck('id')->toArray();
        $rules = [
            'comentario_devolucion' => 'nullable|string|max:255',
        ];
        foreach ($equiposIds as $id) {
            $rules["estado_equipo_id.$id"] = 'required|integer|exists:estado_equipos,id';
        }
        request()->validate($rules);

        $estadoEquipoIds = request('estado_equipo_id'); // array: equipo_id => estado_id
        $comentarioDevolucion = request('comentario_devolucion');

        DB::transaction(function () use ($solicitud, $estadoEquipoIds, $comentarioDevolucion) {
            // Buscar el id del estado "Devuelto" dinámicamente
            $estadoDevuelto = \App\Models\EstadoSolicitud::where('nombre', 'Devuelto')->first();
            $idEstadoDevuelto = $estadoDevuelto ? $estadoDevuelto->id : null;

            $solicitud->update([
                'id_estado_solicitud' => $idEstadoDevuelto,
                'fecha_devolucion' => now(),
                'estado_prestamo' => 'Devuelto',
                'comentario_devolucion' => $comentarioDevolucion,
            ]);

            // Aumentar cantidad disponible solo si el estado de devolución es 'Disponible'
            foreach ($solicitud->equipos as $equipo) {
                $lote = $equipo->lote;
                if (isset($estadoEquipoIds[$equipo->id])) {
                    $equipo->estado_equipo_id = $estadoEquipoIds[$equipo->id];
                    $equipo->save();
                    $estadoEquipo = \App\Models\EstadoEquipo::find($estadoEquipoIds[$equipo->id]);
                        if ($lote && $estadoEquipo && strtolower($estadoEquipo->nombre) === 'disponible') {
                            $lote->cantidad_disponible = min($lote->cantidad_disponible + 1, $lote->cantidad_total);
                            $lote->save();
                        }
                }
            }
        });

        return redirect()->back()->with('success', 'Préstamo devuelto correctamente.');
    }

    public function addBySerial(Request $request)
    {
        $serial = $request->input('serial');
        if (!$serial) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No se proporcionó número de serial.'], 400);
            } else {
                return redirect()->route('solicitud.cart')->with('error', 'No se proporcionó número de serial.');
            }
        }

        // Buscar el equipo por serial
        $equipo = \App\Models\Equipo::where('numero_serie', $serial)->first();
        if (!$equipo) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No se encontró equipo con ese número de serial.'], 404);
            } else {
                return redirect()->route('solicitud.cart')->with('error', 'No se encontró equipo con ese número de serial.');
            }
        }

        // Verificar si el equipo está disponible (no tiene préstamo activo)
        $prestado = $equipo->prestamos()->whereHas('estadoSolicitud', function($q) {
            $q->where('nombre', '!=', 'Devuelto');
        })->exists();
        if ($prestado) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'El equipo ya está prestado.'], 409);
            } else {
                return redirect()->route('solicitud.cart')->with('error', 'El equipo ya está prestado.');
            }
        }

        // Verificar que el lote tenga disponibilidad
        $lote = $equipo->lote;
        if (!$lote || $lote->cantidad_disponible < 1) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No hay disponibilidad en el lote del equipo.'], 409);
            } else {
                return redirect()->route('solicitud.cart')->with('error', 'No hay disponibilidad en el lote del equipo.');
            }
        }

        // Obtener el carrito actual
        $cart = session()->get('equipment_cart', []);

        // Verificar si ya está en el carrito
        $existingIndex = collect($cart)->search(function ($item) use ($lote, $equipo) {
            return $item['lote_id'] == $lote->id && ($item['numero_serie'] ?? null) == $equipo->numero_serie;
        });

        if ($existingIndex !== false) {
            // Ya está en el carrito
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El equipo ya está en el carrito.',
                    'cart_count' => count($cart)
                ], 409);
            } else {
                return redirect()->route('solicitud.cart')->with('error', 'El equipo ya está en el carrito.');
            }
        }

        // Agregar al carrito como cantidad 1 y guardar equipo_id
        $cart[] = [
            'lote_id' => $lote->id,
            'modelo' => $lote->modelo,
            'marca' => $lote->marca->caracteristica ? $lote->marca->caracteristica->nombre : ($lote->marca->nombre ?? 'Sin marca'),
            'categoria' => $lote->categorias->first()->nombre ?? 'Sin categoría',
            'cantidad' => 1,
            'max_disponible' => $lote->cantidad_disponible,
            'numero_serie' => $equipo->numero_serie,
            'equipo_id' => $equipo->id
        ];
        session()->put('equipment_cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Equipo agregado al carrito correctamente.',
                'cart_count' => count($cart)
            ]);
        } else {
            return redirect()->route('solicitud.cart')->with('success', 'Equipo agregado al carrito correctamente.');
        }
    }


}
