<?php

namespace App\Http\Requests\Variedad;
// ¡AÑADE ESTAS DOS LÍNEAS AQUÍ ARRIBA!
use Illuminate\Validation\Rule;
use App\Enums\VariedadCategoria;


use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVariedadRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'nombre'        => ['required', 'string', 'max:100', 'unique:variedades,nombre'],
            'categoria'     => ['required', Rule::enum(VariedadCategoria::class)],
            'peso_bulto_kg' => ['required', 'numeric', 'min:0.01', 'max:200'],
            'activa'        => ['required', 'boolean'],
        ];
    }

    /**
     * 2. LOS MENSAJES PERSONALIZADOS VAN EN ESTE SEGUNDO MÉTODO
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique'   => 'Ya existe una variedad con ese nombre.',
            'categoria.required' => 'La categoría es obligatoria.',
            // ... puedes agregar los demás mensajes aquí
        ];
    }
}
