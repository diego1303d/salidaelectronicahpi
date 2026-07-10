<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salida_historial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('salida_id');
            $table->enum('estatus_anterior', ['pendiente', 'entregada', 'cancelada'])->nullable();
            $table->enum('estatus_nuevo', ['pendiente', 'entregada', 'cancelada']);
            $table->foreignId('usuario_id')->constrained('users');
            $table->string('observaciones')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('salida_id', 'idx_hist_salida');
            $table->foreign('salida_id')->references('id')->on('salidas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salida_historial');
    }
};
