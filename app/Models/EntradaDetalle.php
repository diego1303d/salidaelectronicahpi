<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntradaDetalle extends Model
{
    protected $table = 'entrada_detalles';

    public $timestamps = false;

    protected $fillable = ['entrada_id', 'variedad_id', 'toneladas', 'bultos'];

    protected $casts = [
        'toneladas' => 'decimal:3',
        'bultos' => 'integer',
    ];

    public function entrada(): BelongsTo
    {
        return $this->belongsTo(Entrada::class, 'entrada_id');
    }

    public function variedad(): BelongsTo
    {
        return $this->belongsTo(Variedad::class, 'variedad_id');
    }
}
