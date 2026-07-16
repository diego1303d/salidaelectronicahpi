<?php

namespace App\Http\Requests;

use App\Enums\FormaPago;
use App\Enums\SalidaTipo;
use App\Models\Variedad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\BultosCongruentes;
class StoreSalidaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo' => ['required', Rule::enum(SalidaTipo::class)],

            'ubicacion_origen_id' => ['required', Rule::exists('ubicaciones', 'id')->where('activo', 1)],

            // Solo traspasos:
            'ubicacion_destino_id' => [
                'exclude_unless:tipo,traspaso',
                'required',
                'different:ubicacion_origen_id',
                Rule::exists('ubicaciones', 'id')->where('activo', 1),
            ],

            // Solo ventas:
            'cliente_nombre'   => ['exclude_unless:tipo,venta', 'required', 'string', 'max:150'],
            'cliente_telefono' => ['exclude_unless:tipo,venta', 'nullable', 'string', 'max:20'],
            'forma_pago'       => ['exclude_unless:tipo,venta', 'required', Rule::enum(FormaPago::class)],

            'fecha'         => ['required', 'date', 'before_or_equal:today'],
            'observaciones' => ['nullable', 'string', 'max:255'],

            'detalles'                   => ['required', 'array', 'min:1'],
            'detalles.*.variedad_id'     => ['required', Rule::exists('variedades', 'id')->where('activo', 1), 'distinct'],
            'detalles.*.toneladas'       => ['required', 'numeric', 'gt:0'],
            'detalles.*.bultos'          => ['required', 'integer', 'gt:0'],
            'detalles.*.precio_tonelada' => ['exclude_unless:tipo,venta', 'required', 'numeric', 'gt:0'],
              'detalles.*.bultos'      => ['required', 'integer', 'gt:0', new BultosCongruentes],
        ];
    }

    public function attributes(): array
    {
        return [
            'ubicacion_origen_id'        => 'bodega origen',
            'ubicacion_destino_id'       => 'bodega destino',
            'cliente_nombre'             => 'cliente',
            'forma_pago'                 => 'forma de pago',
            'detalles.*.variedad_id'     => 'variedad',
            'detalles.*.toneladas'       => 'toneladas',
            'detalles.*.bultos'          => 'bultos',
            'detalles.*.precio_tonelada' => 'precio por tonelada',

        ];
    }

    public function messages(): array
    {
        return [
            'detalles.required'                    => 'Agrega al menos una partida a la salida.',
            'detalles.min'                         => 'Agrega al menos una partida a la salida.',
            'detalles.*.variedad_id.distinct'      => 'Hay una variedad repetida; junta las cantidades en un solo renglón.',
            'ubicacion_destino_id.different'       => 'La bodega destino debe ser diferente a la de origen.',
            'fecha.before_or_equal'                => 'La fecha no puede ser futura.',
        ];
    }

    /**
     * Validación de congruencia bultos vs toneladas.
     * Corre DESPUÉS de que pasaron las reglas de arriba.
     */

}
