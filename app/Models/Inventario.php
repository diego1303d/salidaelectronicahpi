<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    protected $table = 'inventarios';

    public const UPDATED_AT = 'updated_at';
    public const CREATED_AT = null; // la tabla solo tiene updated_at

    protected $fillable = ['ubicacion_id', 'variedad_id', 'toneladas', 'bultos'];

    protected $casts = [
        'toneladas' => 'decimal:3',
        'bultos' => 'integer',
    ];

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    public function variedad(): BelongsTo
    {
        return $this->belongsTo(Variedad::class, 'variedad_id');
    }
}
