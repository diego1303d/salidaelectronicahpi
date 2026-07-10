<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolioConsecutivo extends Model
{
    protected $table = 'folio_consecutivos';

    public $timestamps = false;

    protected $fillable = ['tipo', 'anio', 'consecutivo'];

    /**
     * Genera el siguiente folio para un tipo ('S' salidas, 'E' entradas).
     * Devuelve algo como: S-2026-000001
     *
     * IMPORTANTE: llamar SIEMPRE dentro de DB::transaction().
     * El lockForUpdate bloquea la fila del consecutivo para que dos
     * usuarios guardando al mismo tiempo NO generen el mismo folio.
     */
    public static function siguiente(string $tipo): string
    {
        $anio = now()->year;

        static::firstOrCreate(
            ['tipo' => $tipo, 'anio' => $anio],
            ['consecutivo' => 0]
        );

        $registro = static::where('tipo', $tipo)
            ->where('anio', $anio)
            ->lockForUpdate()
            ->first();

        $registro->consecutivo++;
        $registro->save();

        return sprintf('%s-%d-%06d', $tipo, $anio, $registro->consecutivo);
    }
}
