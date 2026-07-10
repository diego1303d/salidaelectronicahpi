<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entrada extends Model
{
    protected $table = 'entradas';

    protected $fillable = [
        'folio', 'ubicacion_id', 'fecha', 'origen', 'observaciones', 'usuario_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(EntradaDetalle::class, 'entrada_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
