<?php

namespace App\Models;

use App\Enums\FormaPago;
use App\Enums\SalidaEstatus;
use App\Enums\SalidaTipo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salida extends Model
{
    protected $table = 'salidas';

    protected $fillable = [
        'folio', 'codigo_barras', 'tipo',
        'ubicacion_origen_id', 'ubicacion_destino_id',
        'cliente_nombre', 'cliente_telefono',
        'fecha', 'forma_pago',
        'total_toneladas', 'total_bultos', 'total_importe',
        'estatus', 'fecha_entrega', 'usuario_entrega_id',
        'fecha_cancelacion', 'usuario_cancela_id', 'motivo_cancelacion',
        'observaciones', 'usuario_id',
    ];

    protected $casts = [
        'tipo' => SalidaTipo::class,
        'estatus' => SalidaEstatus::class,
        'forma_pago' => FormaPago::class,
        'fecha' => 'date',
        'fecha_entrega' => 'datetime',
        'fecha_cancelacion' => 'datetime',
        'total_toneladas' => 'decimal:3',
        'total_bultos' => 'integer',
        'total_importe' => 'decimal:2',
    ];

    // ------------------------- Relaciones -------------------------

    public function origen(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_origen_id');
    }

    public function destino(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_destino_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(SalidaDetalle::class, 'salida_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(SalidaHistorial::class, 'salida_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function usuarioEntrega(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_entrega_id');
    }

    public function usuarioCancela(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_cancela_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'referencia_id')
                    ->where('referencia_tipo', 'salida');
    }

    // -------------------------- Scopes ----------------------------

    public function scopePendientes($query)
    {
        return $query->where('estatus', SalidaEstatus::Pendiente);
    }

    public function scopeVentas($query)
    {
        return $query->where('tipo', SalidaTipo::Venta);
    }

    public function scopeTraspasos($query)
    {
        return $query->where('tipo', SalidaTipo::Traspaso);
    }

    // -------------------------- Helpers ---------------------------

    public function esVenta(): bool
    {
        return $this->tipo === SalidaTipo::Venta;
    }

    public function esTraspaso(): bool
    {
        return $this->tipo === SalidaTipo::Traspaso;
    }

    public function estaPendiente(): bool
    {
        return $this->estatus === SalidaEstatus::Pendiente;
    }
}
