<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salida_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('salida_id');
            $table->unsignedSmallInteger('variedad_id');
            $table->decimal('toneladas', 12, 3);
            $table->unsignedInteger('bultos');
            $table->decimal('precio_tonelada', 12, 2)->nullable(); // NULL en traspasos
            // Columna generada: la base calcula el importe sola,
            // nadie puede capturar un total equivocado.
            $table->decimal('importe', 14, 2)
                  ->storedAs('ROUND(toneladas * IFNULL(precio_tonelada, 0), 2)');

            $table->index('salida_id', 'idx_saldet_salida');
            $table->unique(['salida_id', 'variedad_id'], 'uq_saldet_salida_variedad');
            $table->foreign('salida_id')->references('id')->on('salidas')->cascadeOnDelete();
            $table->foreign('variedad_id')->references('id')->on('variedades');
        });

        DB::statement('ALTER TABLE salida_detalles ADD CONSTRAINT chk_saldet_toneladas CHECK (toneladas > 0)');
        DB::statement('ALTER TABLE salida_detalles ADD CONSTRAINT chk_saldet_bultos CHECK (bultos > 0)');
        DB::statement('ALTER TABLE salida_detalles ADD CONSTRAINT chk_saldet_precio CHECK (precio_tonelada IS NULL OR precio_tonelada >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('salida_detalles');
    }
};
