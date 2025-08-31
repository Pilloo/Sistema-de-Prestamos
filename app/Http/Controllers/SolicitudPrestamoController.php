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
            'fecha_limite' => 'required|date|after:today'
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
            $cart[$existingIndex]['fecha_limite'] = $request->fecha_limite;
        } else {
            // Add new item
            $cart[] = [
                'lote_id' => $request->lote_id,
                'modelo' => $lote->modelo,
                'marca' => $lote->marca->nombre,
                'categoria' => $lote->categorias->first()->nombre ?? 'Sin categoría',
                'cantidad' => $request->cantidad,
                'fecha_limite' => $request->fecha_limite,
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
                $solicitud = SolicitudPrestamo::create([
                    'id_solicitante' => $idSolicitante,
                    'fecha_solicitud' => now(),
                    'fecha_limite_solicitada' => $cart[0]['fecha_limite'], // Use first item's due date
                    'detalle' => $request->detalle ?? 'Solicitud de préstamo de equipos',
                    'id_estado_solicitud' => 1, // pendiente
                    'id_tecnico_aprobador' => null
                ]);

                // Process each equipment lot
                foreach ($cart as $item) {
                    $lote = LoteEquipo::findOrFail($item['lote_id']);

                    $equipos = $lote->equipos()->whereDoesntHave('prestamos')->limit($item['cantidad'])->get();
                    $solicitud->equipos()->attach($equipos->pluck('id')->toArray());
                    // NO disminuir cantidad_disponible aquí
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
     * Aceptar solicitud de préstamo
     */
    public function aceptar($id)
    {
        $solicitud = SolicitudPrestamo::with(['equipos.lote'])->findOrFail($id);

        if ($solicitud->id_estado_solicitud != 1) {
            return redirect()->back()->with('error', 'La solicitud ya fue procesada.');
        }

        DB::transaction(function () use ($solicitud) {
            // Cambiar estado a aceptada (por ejemplo, 2) y registrar datos de préstamo
            $solicitud->update([
                'id_estado_solicitud' => 2, // aceptada
                'id_tecnico_aprobador' => (Auth::user() ? Auth::user()->id : null),
                'fecha_entrega' => now(),
                'estado_prestamo' => 'Prestado / Entregado'
            ]);

            // Obtener el id del estado "En préstamo"
            $estadoEnPrestamo = \App\Models\EstadoEquipo::where('nombre', 'En préstamo')->first();

            // Disminuir cantidad disponible de cada lote y cambiar estado de equipo
            foreach ($solicitud->equipos as $equipo) {
                $lote = $equipo->lote;
                if ($lote) {
                    $lote->decrement('cantidad_disponible', 1);
                }
                if ($estadoEnPrestamo) {
                    $equipo->estado_equipo_id = $estadoEnPrestamo->id;
                    $equipo->save();
                }
            }
        });

        return redirect()->back()->with('success', 'Solicitud aceptada correctamente.');
    }

    /**
     * Rechazar solicitud de préstamo
     */
    public function rechazar($id)
    {
        $solicitud = SolicitudPrestamo::findOrFail($id);

        if ($solicitud->id_estado_solicitud != 1) {
            return redirect()->back()->with('error', 'La solicitud ya fue procesada.');
        }

        $solicitud->update([
            'id_estado_solicitud' => 3, // rechazada
            'id_tecnico_aprobador' => (Auth::user() ? Auth::user()->id : null)
        ]);

        return redirect()->back()->with('success', 'Solicitud rechazada correctamente.');
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
        $solicitud = SolicitudPrestamo::with(['equipos.lote'])->findOrFail($id);

        DB::transaction(function () use ($solicitud) {
            // Obtener el id del estado "En préstamo"
            $estadoEnPrestamo = \App\Models\EstadoEquipo::where('nombre', 'Disponible')->first();

            // Disminuir cantidad disponible de cada lote y cambiar estado de equipo
            foreach ($solicitud->equipos as $equipo) {
                $lote = $equipo->lote;
                if ($lote) {
                    $lote->increment('cantidad_disponible', 1);
                }
                if ($estadoEnPrestamo) {
                    $equipo->estado_equipo_id = $estadoEnPrestamo->id;
                    $equipo->save();
                }
            }
        });

        return redirect()->back()->with('success', 'Solicitud aceptada correctamente.');
    }
}
