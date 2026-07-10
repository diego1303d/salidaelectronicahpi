<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salidas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('folio', 20)->unique();          // ej. S-2026-000001
            $table->string('codigo_barras', 30)->unique();  // Code128 (normalmente = folio)
            $table->enum('tipo', ['venta', 'traspaso']);
            $table->unsignedTinyInteger('ubicacion_origen_id');
            $table->unsignedTinyInteger('ubicacion_destino_id')->nullable(); // obligatorio si traspaso
            // Datos del cliente capturados directo en el documento (sin catalogo,
            // porque los clientes cambian muy seguido):
            $table->string('cliente_nombre', 150)->nullable();  // obligatorio si venta
            $table->string('cliente_telefono', 20)->nullable();
            $table->date('fecha');
            $table->enum('forma_pago', ['contado', 'credito', '50_50'])->nullable(); // solo ventas
            $table->decimal('total_toneladas', 12, 3)->default(0);
            $table->unsignedInteger('total_bultos')->default(0);
            $table->decimal('total_importe', 14, 2)->default(0);
            $table->enum('estatus', ['pendiente', 'entregada', 'cancelada'])->default('pendiente');
            $table->dateTime('fecha_entrega')->nullable();
            $table->foreignId('usuario_entrega_id')->nullable()->constrained('users');
            $table->dateTime('fecha_cancelacion')->nullable();
            $table->foreignId('usuario_cancela_id')->nullable()->constrained('users');
            $table->string('motivo_cancelacion')->nullable();
            $table->string('observaciones')->nullable();
            $table->foreignId('usuario_id')->constrained('users'); // quien creo el documento
            $table->timestamps();

            $table->index('estatus', 'idx_salidas_estatus');
            $table->index('fecha', 'idx_salidas_fecha');
            $table->index('tipo', 'idx_salidas_tipo');
            $table->index('cliente_nombre', 'idx_salidas_cliente');
            $table->foreign('ubicacion_origen_id')->references('id')->on('ubicaciones');
            $table->foreign('ubicacion_destino_id')->references('id')->on('ubicaciones');
        });

        // Reglas de integridad segun el tipo de documento:
        DB::statement("ALTER TABLE salidas ADD CONSTRAINT chk_sal_traspaso_destino CHECK (tipo <> 'traspaso' OR ubicacion_destino_id IS NOT NULL)");
        DB::statement("ALTER TABLE salidas ADD CONSTRAINT chk_sal_venta_cliente CHECK (tipo <> 'venta' OR cliente_nombre IS NOT NULL)");
        DB::statement("ALTER TABLE salidas ADD CONSTRAINT chk_sal_venta_pago CHECK (tipo <> 'venta' OR forma_pago IS NOT NULL)");
        DB::statement('ALTER TABLE salidas ADD CONSTRAINT chk_sal_origen_destino CHECK (ubicacion_destino_id IS NULL OR ubicacion_destino_id <> ubicacion_origen_id)');
    }

    public function down(): void
    {
        Schema::dropIfExists('salidas');
    }
};
