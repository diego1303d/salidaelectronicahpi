<?php

namespace App\Services;

use App\Enums\TipoMovimiento;
use App\Models\Entrada;
use App\Models\FolioConsecutivo;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Ingreso de inventario a cualquiera de las bodegas.
 */
class EntradaService
{
    /**
     * $datos = [
     *   'ubicacion_id'  => int,
     *   'fecha'         => 'Y-m-d',
     *   'origen'        => string|null, // proveedor / campo / cosecha
     *   'observaciones' => string|null,
     * ]
     *
     * $detalles = [
     *   ['variedad_id' => 1, 'toneladas' => 25.000, 'bultos' => 500],
     *   ...
     * ]
     */
    public function crear(array $datos, array $detalles, User $usuario): Entrada
    {
        if (empty($detalles)) {
            throw new RuntimeException('La entrada debe tener al menos una partida.');
        }

        return DB::transaction(function () use ($datos, $detalles, $usuario) {
            $entrada = Entrada::create([
                ...$datos,
                'folio' => FolioConsecutivo::siguiente('E'),
                'usuario_id' => $usuario->id,
            ]);

            foreach ($detalles as $d) {
                $entrada->detalles()->create($d);

                Inventario::firstOrCreate(
                    ['ubicacion_id' => $entrada->ubicacion_id, 'variedad_id' => $d['variedad_id']],
                    ['toneladas' => 0, 'bultos' => 0]
                );

                Inventario::where('ubicacion_id', $entrada->ubicacion_id)
                    ->where('variedad_id', $d['variedad_id'])
                  ->incrementEach([
            'toneladas' => $d['toneladas'],
            'bultos'    => $d['bultos'],
        ]);
                MovimientoInventario::create([
                    'ubicacion_id' => $entrada->ubicacion_id,
                    'variedad_id' => $d['variedad_id'],
                    'tipo' => TipoMovimiento::Entrada,
                    'toneladas' => $d['toneladas'],
                    'bultos' => $d['bultos'],
                    'referencia_tipo' => 'entrada',
                    'referencia_id' => $entrada->id,
                    'usuario_id' => $usuario->id,
                ]);
            }

            return $entrada->fresh(['detalles', 'ubicacion']);
        });
    }
}
