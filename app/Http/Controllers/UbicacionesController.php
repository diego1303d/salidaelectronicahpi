<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;

class UbicacionesController extends Controller
{
  public function index(){
    $ubicaciones = Ubicacion::all();

    return view('ubicaciones.index', compact('ubicaciones'));
  }

  public function create(){
    return view('ubicaciones.create');
  }



  public function toggle(Ubicacion $ubicacion)
{
    // ! voltea el valor: 1 → 0 y 0 → 1
    $ubicacion->update(['activo' => ! $ubicacion->activo]);

    return redirect()
        ->route('ubicaciones.index')
        ->with('success', "Variedad \"{$ubicacion->nombre}\" " .
            ($ubicacion->activo ? 'activada' : 'desactivada') . ' correctamente.');
}

    public function store(Request $request){
        $request->validate([
        'nombre' => 'required',
        'clave' => 'required',
        ]);



        Ubicacion::create($request->all());

        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación creada exitosamente.');
    }



    public function edit(Ubicacion $ubicacion){
            return view('ubicaciones.edit', ['ubicacion'   => $ubicacion ]);

    }

public function update(Request $request, Ubicacion $ubicacion)
{
    $datos = $request->validate([
        // ignore($variedad->id): que no se queje de duplicado
        // contra SÍ MISMA si no le cambiaste el nombre
        'clave'        => ['required', 'string', 'max:100',
                            Rule::unique('ubicaciones', 'clave')->ignore($ubicacion->id)],
        'nombre'     => ['required', 'string', 'max:100'],
            'direccion'     => ['nullable', 'string', 'max:100'],
        'activo'        => ['required', 'boolean'],
    ], [
        'clave.required'        => 'El nombre de la clave es obligatorio.',
        'clave.unique'          => 'Ya existe otra  ubicacion con esa clave ',

    ]);

    $ubicacion->update($datos);

    return redirect()
        ->route('ubicaciones.index')
        ->with('success', "Ubicacion \"{$ubicacion->nombre}\" actualizada correctamente.");
}










}
