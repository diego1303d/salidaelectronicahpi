<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('folio', 20)->unique();          // ej. E-2026-000001
            $table->unsignedTinyInteger('ubicacion_id');
            $table->date('fecha');
            $table->string('origen', 150)->nullable();      // proveedor / campo / cosecha
            $table->string('observaciones')->nullable();
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamps();

            $table->index('fecha', 'idx_entradas_fecha');
            $table->foreign('ubicacion_id')->references('id')->on('ubicaciones');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};
