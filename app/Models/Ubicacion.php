<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';

    protected $fillable = ['clave', 'nombre', 'direccion', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class, 'ubicacion_id');
    }

    public function entradas(): HasMany
    {
        return $this->hasMany(Entrada::class, 'ubicacion_id');
    }

    public function salidasOrigen(): HasMany
    {
        return $this->hasMany(Salida::class, 'ubicacion_origen_id');
    }

    public function salidasDestino(): HasMany
    {
        return $this->hasMany(Salida::class, 'ubicacion_destino_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'ubicacion_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}
