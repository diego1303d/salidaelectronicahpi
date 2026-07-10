<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('ubicacion_id');
            $table->unsignedSmallInteger('variedad_id');
            $table->enum('tipo', [
                'entrada',             // ingreso directo a bodega
                'salida_venta',        // descuento por venta
                'salida_traspaso',     // descuento en bodega origen
                'entrada_traspaso',    // abono en bodega destino (al entregar)
                'reverso_cancelacion', // regreso por cancelacion
            ]);
            // Cantidades CON SIGNO: positivo entra, negativo sale.
            $table->decimal('toneladas', 12, 3);
            $table->integer('bultos');
            $table->enum('referencia_tipo', ['entrada', 'salida'])->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->string('observaciones')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['ubicacion_id', 'variedad_id'], 'idx_mov_ubicacion_variedad');
            $table->index(['referencia_tipo', 'referencia_id'], 'idx_mov_referencia');
            $table->index('created_at', 'idx_mov_fecha');
            $table->foreign('ubicacion_id')->references('id')->on('ubicaciones');
            $table->foreign('variedad_id')->references('id')->on('variedades');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
