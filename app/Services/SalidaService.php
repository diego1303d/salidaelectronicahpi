<?php

namespace App\Services;

use App\Enums\SalidaEstatus;
use App\Enums\SalidaTipo;
use App\Enums\TipoMovimiento;
use App\Models\FolioConsecutivo;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\Salida;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Toda la logica de negocio de ventas y traspasos vive aqui.
 * Cada operacion corre en una transaccion: si algo falla a la mitad
 * (p. ej. no hay existencia), NADA se guarda y el inventario queda intacto.
 */
class SalidaService
{
    /**
     * Crea una VENTA o TRASPASO en estatus 'pendiente' y descuenta
     * el inventario de la bodega ORIGEN.
     *
     * $datos = [
     *   'tipo'                 => 'venta' | 'traspaso',
     *   'ubicacion_origen_id'  => int,
     *   'ubicacion_destino_id' => int|null,   // obligatorio si traspaso
     *   'cliente_nombre'       => string|null, // obligatorio si venta
     *   'cliente_telefono'     => string|null,
     *   'fecha'                => 'Y-m-d',
     *   'forma_pago'           => 'contado'|'credito'|'50_50'|null, // solo venta
     *   'observaciones'        => string|null,
     * ]
     *
     * $detalles = [
     *   ['variedad_id' => 1, 'toneladas' => '10.500', 'bultos' => 210, 'precio_tonelada' => '4800.00'],
     *   ...
     * ]
     */
    public function crear(array $datos, array $detalles, User $usuario): Salida
    {
        if (empty($detalles)) {
            throw new RuntimeException('La salida debe tener al menos una partida.');
        }

        return DB::transaction(function () use ($datos, $detalles, $usuario) {
            $folio = FolioConsecutivo::siguiente('S');

            $salida = Salida::create([
                ...$datos,
                'folio' => $folio,
                'codigo_barras' => $folio, // Code128 del folio
                'estatus' => SalidaEstatus::Pendiente,
                'usuario_id' => $usuario->id,
            ]);

            foreach ($detalles as $d) {
                $salida->detalles()->create($d);

                $this->descontarInventario(
                    (int) $salida->ubicacion_origen_id,
                    (int) $d['variedad_id'],
                    $d['toneladas'],
                    (int) $d['bultos'],
                );

                MovimientoInventario::create([
                    'ubicacion_id' => $salida->ubicacion_origen_id,
                    'variedad_id' => $d['variedad_id'],
                    'tipo' => $salida->tipo === SalidaTipo::Venta
                        ? TipoMovimiento::SalidaVenta
                        : TipoMovimiento::SalidaTraspaso,
                    'toneladas' => '-' . $d['toneladas'], // negativo sin pasar por float
                    'bultos' => -$d['bultos'],
                    'referencia_tipo' => 'salida',
                    'referencia_id' => $salida->id,
                    'usuario_id' => $usuario->id,
                ]);
            }

            // Totales calculados por MySQL sobre DECIMAL: exactos siempre.
            // El importe lo calcula la columna generada; en traspasos suma 0.
            $salida->update([
                'total_toneladas' => $salida->detalles()->sum('toneladas'),
                'total_bultos'    => $salida->detalles()->sum('bultos'),
                'total_importe'   => $salida->detalles()->sum('importe'),
            ]);

            $salida->historial()->create([
                'estatus_anterior' => null,
                'estatus_nuevo' => SalidaEstatus::Pendiente,
                'usuario_id' => $usuario->id,
                'observaciones' => 'Documento creado',
            ]);

            return $salida->fresh(['detalles', 'origen', 'destino']);
        });
    }

    /**
     * Entrega por escaneo de codigo de barras (o folio tecleado).
     * Cambia a 'entregada' con fecha/hora y, si es TRASPASO,
     * suma el trigo al inventario de la bodega DESTINO.
     */
    public function entregar(string $codigoBarras, User $usuario): Salida
    {
        return DB::transaction(function () use ($codigoBarras, $usuario) {
            $salida = Salida::where('codigo_barras', $codigoBarras)
                ->lockForUpdate()
                ->first();

            if (!$salida) {
                throw new RuntimeException("No existe una salida con el código {$codigoBarras}.");
            }

            if (!$salida->estaPendiente()) {
                throw new RuntimeException(
                    "La salida {$salida->folio} ya está \"{$salida->estatus->etiqueta()}\", no se puede entregar."
                );
            }

            $salida->update([
                'estatus' => SalidaEstatus::Entregada,
                'fecha_entrega' => now(),
                'usuario_entrega_id' => $usuario->id,
            ]);

            // El traspaso apenas "llega" a la bodega destino al entregarse:
            if ($salida->esTraspaso()) {
                foreach ($salida->detalles as $detalle) {
                    $this->abonarInventario(
                        (int) $salida->ubicacion_destino_id,
                        (int) $detalle->variedad_id,
                        $detalle->toneladas,
                        (int) $detalle->bultos,
                    );

                    MovimientoInventario::create([
                        'ubicacion_id' => $salida->ubicacion_destino_id,
                        'variedad_id' => $detalle->variedad_id,
                        'tipo' => TipoMovimiento::EntradaTraspaso,
                        'toneladas' => $detalle->toneladas,
                        'bultos' => $detalle->bultos,
                        'referencia_tipo' => 'salida',
                        'referencia_id' => $salida->id,
                        'usuario_id' => $usuario->id,
                    ]);
                }
            }

            $salida->historial()->create([
                'estatus_anterior' => SalidaEstatus::Pendiente,
                'estatus_nuevo' => SalidaEstatus::Entregada,
                'usuario_id' => $usuario->id,
                'observaciones' => 'Entregada por escaneo',
            ]);

            return $salida->fresh(['detalles']);
        });
    }

    /**
     * Cancela una salida PENDIENTE y regresa el trigo a la bodega origen.
     * Una salida 'entregada' NO se cancela: se corrige con una entrada
     * de reverso para no romper la trazabilidad.
     */
    public function cancelar(int $salidaId, string $motivo, User $usuario): Salida
    {
        return DB::transaction(function () use ($salidaId, $motivo, $usuario) {
            $salida = Salida::whereKey($salidaId)->lockForUpdate()->first();

            if (!$salida) {
                throw new RuntimeException('La salida no existe.');
            }

            if (!$salida->estaPendiente()) {
                throw new RuntimeException(
                    "Solo se pueden cancelar salidas pendientes. La salida {$salida->folio} está \"{$salida->estatus->etiqueta()}\"."
                );
            }

            $salida->update([
                'estatus' => SalidaEstatus::Cancelada,
                'fecha_cancelacion' => now(),
                'usuario_cancela_id' => $usuario->id,
                'motivo_cancelacion' => $motivo,
            ]);

            // Regresar el trigo al inventario de la bodega origen:
            foreach ($salida->detalles as $detalle) {
                $this->abonarInventario(
                    (int) $salida->ubicacion_origen_id,
                    (int) $detalle->variedad_id,
                    $detalle->toneladas,
                    (int) $detalle->bultos,
                );

                MovimientoInventario::create([
                    'ubicacion_id' => $salida->ubicacion_origen_id,
                    'variedad_id' => $detalle->variedad_id,
                    'tipo' => TipoMovimiento::ReversoCancelacion,
                    'toneladas' => $detalle->toneladas,
                    'bultos' => $detalle->bultos,
                    'referencia_tipo' => 'salida',
                    'referencia_id' => $salida->id,
                    'usuario_id' => $usuario->id,
                    'observaciones' => $motivo,
                ]);
            }

            $salida->historial()->create([
                'estatus_anterior' => SalidaEstatus::Pendiente,
                'estatus_nuevo' => SalidaEstatus::Cancelada,
                'usuario_id' => $usuario->id,
                'observaciones' => $motivo,
            ]);

            return $salida->fresh(['detalles']);
        });
    }

    // ------------------------------------------------------------------
    // Helpers privados de inventario
    // ------------------------------------------------------------------

    /**
     * Descuenta del inventario validando existencia en el mismo UPDATE
     * (check-and-act atomico). Si no alcanza o no existe el renglon,
     * no afecta filas y lanzamos excepcion => rollback de TODO.
     */
    private function descontarInventario(int $ubicacionId, int $variedadId, int|float|string $toneladas, int $bultos): void
    {
        $afectadas = Inventario::where('ubicacion_id', $ubicacionId)
            ->where('variedad_id', $variedadId)
            ->where('toneladas', '>=', $toneladas)
            ->where('bultos', '>=', $bultos)
             ->toBase()
            ->decrementEach([
                'toneladas' => $toneladas,
                'bultos'    => $bultos,
            ]);

        if ($afectadas === 0) {
            throw new RuntimeException(
                'Existencia insuficiente en la bodega origen para la variedad seleccionada.'
            );
        }
    }

    /**
     * Suma al inventario; crea el renglon en cero si la bodega
     * aun no tiene esa variedad.
     */
    private function abonarInventario(int $ubicacionId, int $variedadId, int|float|string $toneladas, int $bultos): void
    {
        Inventario::firstOrCreate(
            ['ubicacion_id' => $ubicacionId, 'variedad_id' => $variedadId],
            ['toneladas' => 0, 'bultos' => 0]
        );

        Inventario::where('ubicacion_id', $ubicacionId)
            ->where('variedad_id', $variedadId)
            ->incrementEach([
                'toneladas' => $toneladas,
                'bultos'    => $bultos,
            ]);
    }
}