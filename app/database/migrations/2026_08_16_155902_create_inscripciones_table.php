<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id('id_inscripciones');
            $table->foreignId('id_estudiantes')->constrained('estudiantes', 'id_estudiantes');
            $table->foreignId('id_cursos')->constrained('cursos', 'id_cursos');
            $table->string('gestion');
            $table->timestamps();

            $table->unique(['id_estudiantes', 'id_cursos', 'gestion'], 'inscripcion_unica');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
