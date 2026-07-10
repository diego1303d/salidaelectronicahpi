<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse; // Asegúrate de importar esto arriba
use App\Http\Requests\variedad\StoreVariedadRequest;
use App\Services\VariedadService;
use App\Models\Variedad;

use Illuminate\Http\JsonResponse;


use App\Enums\VariedadCategoria;
use Illuminate\Validation\Rule;

class VariedadesController extends Controller
{
public function index()
{
    $variedades = Variedad::orderBy('nombre')->get();

    return view('Variedad.index', compact('variedades'));
}

public function toggle(Variedad $variedad)
{
    // ! voltea el valor: 1 → 0 y 0 → 1
    $variedad->update(['activo' => ! $variedad->activo]);

    return redirect()
        ->route('Variedad.index')
        ->with('success', "Variedad \"{$variedad->nombre}\" " .
            ($variedad->activo ? 'activada' : 'desactivada') . ' correctamente.');
}

public function create()
{
 return view('Variedad.create', [
        'categorias' => VariedadCategoria::cases(), // los 3 casos del enum al combo
    ]);

}


public function store(StoreVariedadRequest $request,VariedadService $variedadService):RedirectResponse
{
   
  
$variedadData = $request->validated();


$variedad = $variedadService->register($variedadData);
    // Redirigimos al index con un mensaje "flash": vive en sesión
    // UNA sola petición y luego se borra solo.
  return redirect()
        ->route('Variedad.index')
        ->with('success', "Variedad \"{$variedad['nombre']}\" registrada correctamente.");
}




public function edit(Variedad $variedad)
{
    return view('Variedad.edit', [
        'variedad'   => $variedad,
        'categorias' => VariedadCategoria::cases(),
    ]);
}

public function update(Request $request, Variedad $variedad)
{
    $datos = $request->validate([
        // ignore($variedad->id): que no se queje de duplicado
        // contra SÍ MISMA si no le cambiaste el nombre
        'nombre'        => ['required', 'string', 'max:100',
                            Rule::unique('variedades', 'nombre')->ignore($variedad->id)],
        'categoria'     => ['required', Rule::enum(VariedadCategoria::class)],
        'peso_bulto_kg' => ['required', 'numeric', 'min:0.01', 'max:200'],
        'activo'        => ['required', 'boolean'],
    ], [
        'nombre.required'        => 'El nombre de la variedad es obligatorio.',
        'nombre.unique'          => 'Ya existe otra variedad con ese nombre.',
        'categoria.required'     => 'La categoría es obligatoria.',
        'peso_bulto_kg.required' => 'El peso por bulto es obligatorio.',
        'peso_bulto_kg.numeric'  => 'El peso debe ser un número.',
        'peso_bulto_kg.min'      => 'El peso debe ser mayor a cero.',
    ]);

    $variedad->update($datos);

    return redirect()
        ->route('Variedad.index')
        ->with('success', "Variedad \"{$variedad->nombre}\" actualizada correctamente.");
}



}
