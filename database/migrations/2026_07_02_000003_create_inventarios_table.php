<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('ubicacion_id');
            $table->unsignedSmallInteger('variedad_id');
            $table->decimal('toneladas', 12, 3)->default(0);
            $table->integer('bultos')->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['ubicacion_id', 'variedad_id'], 'uq_inventario_ubicacion_variedad');
            $table->foreign('ubicacion_id')->references('id')->on('ubicaciones');
            $table->foreign('variedad_id')->references('id')->on('variedades');
        });

        // El saldo nunca puede quedar negativo: si una venta intenta sacar
        // mas de lo que hay, MySQL rechaza el UPDATE y la transaccion se revierte.
        DB::statement('ALTER TABLE inventarios ADD CONSTRAINT chk_inv_toneladas CHECK (toneladas >= 0)');
        DB::statement('ALTER TABLE inventarios ADD CONSTRAINT chk_inv_bultos CHECK (bultos >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
