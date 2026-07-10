<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variedades', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('nombre', 100)->unique();
            // Peso de un bulto en kg (p. ej. 50.00). Sirve para validar
            // en la app que toneladas y bultos capturados sean congruentes.
            $table->decimal('peso_bulto_kg', 6, 2)->nullable();
            $table->boolean('activo')->default(true);
            $table->enum('categoria', ['CERTIFICADO', 'REGISTRADO', 'DECLARADO'])->default('CERTIFICADO');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variedades');
    }
};
