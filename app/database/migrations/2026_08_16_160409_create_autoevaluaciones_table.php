<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('autoevaluaciones', function (Blueprint $table) {
            $table->id('id_autoevaluaciones');
            $table->foreignId('id_asignaciones')->constrained('asignaciones', 'id_asignaciones');
            $table->foreignId('id_estudiantes')->constrained('estudiantes', 'id_estudiantes');
            $table->foreignId('id_trimestres')->constrained('trimestres', 'id_trimestres');
            $table->decimal('nota', 5, 2)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('autoevaluaciones');
    }
};
