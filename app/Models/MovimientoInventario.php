<?php

namespace App\Models;

use App\Enums\TipoMovimiento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';

    public const UPDATED_AT = null; // el kardex nunca se edita, solo se inserta

    protected $fillable = [
        'ubicacion_id', 'variedad_id', 'tipo', 'toneladas', 'bultos',
        'referencia_tipo', 'referencia_id', 'usuario_id', 'observaciones',
    ];

    protected $casts = [
        'tipo' => TipoMovimiento::class,
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

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
