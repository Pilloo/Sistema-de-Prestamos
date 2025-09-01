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
        $lotes = LoteEquipo::with(['marca', 'categorias'])
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

        // Check if item already in cart
        $existingIndex = collect($cart)->search(function ($item) use ($request) {
            return $item['lote_id'] == $request->lote_id;
        });

        if ($existingIndex !== false) {
            // Update existing item
            $cart[$existingIndex]['cantidad'] = $request->cantidad;
        } else {
            // Add new item
            $cart[] = [
                'lote_id' => $request->lote_id,
                'modelo' => $lote->modelo,
                'marca' => $lote->marca->nombre,
                'categoria' => $lote->categorias->first()->nombre ?? 'Sin categoría',
                'cantidad' => $request->cantidad,
                'max_disponible' => $lote->cantidad_disponible
            ];
        }

        session()->put('equipment_cart', $cart);

        return redirect()->route('solicitud.cart')
            ->with('success', 'Equipo agregado al carrito correctamente.');
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

                // Process each equipment lot
                foreach ($cart as $item) {
                    $lote = LoteEquipo::findOrFail($item['lote_id']);

                    $equipos = $lote->equipos()->whereDoesntHave('prestamos')->limit($item['cantidad'])->get();
                    $solicitud->equipos()->attach($equipos->pluck('id')->toArray());
                        // Disminuir la cantidad disponible del lote
                        $lote->decrement('cantidad_disponible', $item['cantidad']);
                            // Cambiar estado de los equipos a 'En préstamo'
                            $estadoEnPrestamo = \App\Models\EstadoEquipo::where('nombre', 'En préstamo')->first();
                            if ($estadoEnPrestamo) {
                                foreach ($equipos as $equipo) {
                                    $equipo->estado_equipo_id = $estadoEnPrestamo->id;
                                    $equipo->save();
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

}
