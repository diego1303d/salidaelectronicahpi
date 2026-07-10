<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folio_consecutivos', function (Blueprint $table) {
            $table->increments('id');
            $table->char('tipo', 1);        // 'S' salidas, 'E' entradas
            $table->smallInteger('anio');
            $table->unsignedInteger('consecutivo')->default(0);

            $table->unique(['tipo', 'anio'], 'uq_folio_tipo_anio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folio_consecutivos');
    }
};
