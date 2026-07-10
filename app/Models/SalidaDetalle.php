<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalidaDetalle extends Model
{
    protected $table = 'salida_detalles';

    public $timestamps = false;

    // OJO: 'importe' NO va en fillable, es columna generada por MySQL
    // (toneladas * precio_tonelada). Solo se lee, nunca se escribe.
    protected $fillable = ['salida_id', 'variedad_id', 'toneladas', 'bultos', 'precio_tonelada'];

    protected $casts = [
        'toneladas' => 'decimal:3',
        'bultos' => 'integer',
        'precio_tonelada' => 'decimal:2',
        'importe' => 'decimal:2',
    ];

    public function salida(): BelongsTo
    {
        return $this->belongsTo(Salida::class, 'salida_id');
    }

    public function variedad(): BelongsTo
    {
        return $this->belongsTo(Variedad::class, 'variedad_id');
    }
}
