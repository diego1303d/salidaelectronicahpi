<?php

namespace App\Http\Requests;

use App\Rules\BultosCongruentes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEntradaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ubicacion_id'           => ['required', Rule::exists('ubicaciones', 'id')->where('activo', 1)],
            'fecha'                  => ['required', 'date', 'before_or_equal:today'],
            'origen'                 => ['nullable', 'string', 'max:150'],
            'observaciones'          => ['nullable', 'string', 'max:255'],
            'detalles'               => ['required', 'array', 'min:1'],
            'detalles.*.variedad_id' => ['required', Rule::exists('variedades', 'id')->where('activo', 1), 'distinct'],
            'detalles.*.toneladas'   => ['required', 'numeric', 'gt:0'],
            'detalles.*.bultos'      => ['required', 'integer', 'gt:0', new BultosCongruentes],
        ];
    }

    public function attributes(): array
    {
        return [
            'ubicacion_id'           => 'bodega',
            'fecha'                  => 'fecha',
            'origen'                 => 'origen',
            'detalles.*.variedad_id' => 'variedad',
            'detalles.*.toneladas'   => 'toneladas',
            'detalles.*.bultos'      => 'bultos',
        ];
    }

    public function messages(): array
    {
        return [
            'detalles.required'               => 'Agrega al menos una variedad a la entrada.',
            'detalles.min'                    => 'Agrega al menos una variedad a la entrada.',
            'detalles.*.variedad_id.distinct' => 'Hay una variedad repetida; junta las cantidades en un solo renglón.',
            'fecha.before_or_equal'           => 'La fecha no puede ser futura.',
        ];
    }
}