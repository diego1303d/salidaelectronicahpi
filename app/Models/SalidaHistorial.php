<?php

namespace App\Models;

use App\Enums\SalidaEstatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalidaHistorial extends Model
{
    protected $table = 'salida_historial';

    public const UPDATED_AT = null; // el historial nunca se edita

    protected $fillable = [
        'salida_id', 'estatus_anterior', 'estatus_nuevo', 'usuario_id', 'observaciones',
    ];

    protected $casts = [
        'estatus_anterior' => SalidaEstatus::class,
        'estatus_nuevo' => SalidaEstatus::class,
    ];

    public function salida(): BelongsTo
    {
        return $this->belongsTo(Salida::class, 'salida_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
