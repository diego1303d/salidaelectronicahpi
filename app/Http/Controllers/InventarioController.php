<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Ubicacion;

class InventarioController extends Controller
{
    public function index()
    {
        $ubicaciones = Ubicacion::where('activo', true)->orderBy('nombre')->get();

        $inventarios = Inventario::with('variedad')->get();

        // Filas de la matriz: solo variedades que tienen (o tuvieron) inventario
        $variedades = $inventarios
            ->pluck('variedad')
            ->unique('id')
            ->sortBy('nombre');

        // Lookup para encontrar cada celda en O(1): "ubicacion-variedad" => renglón
        $matriz = $inventarios->keyBy(
            fn ($inv) => $inv->ubicacion_id . '-' . $inv->variedad_id
        );

        return view('inventario.index', compact('ubicaciones', 'variedades', 'inventarios', 'matriz'));
    }
}