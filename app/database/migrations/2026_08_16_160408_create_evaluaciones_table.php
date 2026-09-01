<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id('id_evaluaciones');
            $table->string('nombre');
            $table->string('tipo');
            $table->decimal('porcentaje', 5, 2);
            $table->foreignId('id_asignaciones')->constrained('asignaciones', 'id_asignaciones');
            $table->foreignId('id_trimestres')->constrained('trimestres', 'id_trimestres');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
