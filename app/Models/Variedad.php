<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variedad extends Model
{
    protected $table = 'variedades';

    protected $fillable = ['nombre', 'categoria','peso_bulto_kg', 'activo'];

    protected $casts = [
        'peso_bulto_kg' => 'decimal:2',
        'activo' => 'boolean',
    ];

    

    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class, 'variedad_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Bultos esperados para X toneladas segun el peso del bulto.
     * Devuelve null si la variedad no tiene peso_bulto_kg configurado.
     */
  public function bultosEsperados(float $toneladas): int
{
    return (int) round($toneladas * 1000 / $this->peso_bulto_kg);
}
}
