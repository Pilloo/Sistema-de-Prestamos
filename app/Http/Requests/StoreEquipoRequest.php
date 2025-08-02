<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'cantidad_total' => 'required|integer|min:1',
        ];

        if (!$this->filled('equipo_id')) {
            $rules = array_merge($rules, [
                'modelo' => 'required|string|max:255',
                'numero_serie' => 'nullable|string',
                'contenido_etiqueta' => 'nullable|string',
                'detalle' => 'nullable|string',
                'marca_id' => 'required|exists:marcas,id',
                'estado_equipo_id' => 'required|exists:estado_equipos,id',
                'categorias' => 'required|array',
            ]);
        }

        return $rules;
    }
}
