<?php

namespace App\Http\Controllers;

use App\Models\SolicitudPrestamo;
use App\Models\LoteEquipo;
use App\Models\EstadoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SolicitudPrestamoController extends Controller
{
    /**
     * Show equipment selection page (Blade view)
     */
    public function create()
    {
        $equipos = LoteEquipo::with(['marca', 'categorias'])
            ->where('cantidad_disponible', '>', 0)
            ->get();

        return view('solicitud.create', compact('equipos'));
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
            'lote_id' => 'required|exists:lote_equipo,id',
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
                // Create the loan request
                $solicitud = SolicitudPrestamo::create([
                    'id_solicitante' => auth()->id(),
                    'fecha_solicitud' => now(),
                    'fecha_limite_solicitada' => $cart[0]['fecha_limite'], // Use first item's due date
                    'detalle' => $request->detalle ?? 'Solicitud de préstamo de equipos',
                    'id_estado_solicitud' => 1, // pendiente
                    'id_tecnico_aprobador' => null
                ]);

                // Process each equipment lot
                foreach ($cart as $item) {
                    $lote = LoteEquipo::findOrFail($item['lote_id']);

                    $equipos = $lote->equipos()->whereDoesntHave('solicitudes')->limit($item['cantidad']);

                    // Attach the lot to the request
                    $solicitud->equipos()->attach($equipos);

                    // Decrease the available quantity
                    $lote->decrement('cantidad_disponible', $item['cantidad']);
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
}
