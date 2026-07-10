<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrada_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('entrada_id');
            $table->unsignedSmallInteger('variedad_id');
            $table->decimal('toneladas', 12, 3);
            $table->unsignedInteger('bultos');

            $table->index('entrada_id', 'idx_entdet_entrada');
            $table->foreign('entrada_id')->references('id')->on('entradas')->cascadeOnDelete();
            $table->foreign('variedad_id')->references('id')->on('variedades');
        });

        DB::statement('ALTER TABLE entrada_detalles ADD CONSTRAINT chk_entdet_toneladas CHECK (toneladas > 0)');
        DB::statement('ALTER TABLE entrada_detalles ADD CONSTRAINT chk_entdet_bultos CHECK (bultos > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('entrada_detalles');
    }
};
