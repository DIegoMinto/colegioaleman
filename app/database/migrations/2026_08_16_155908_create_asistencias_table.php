<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id('id_asistencias');
            $table->foreignId('id_estudiantes')->constrained('estudiantes', 'id_estudiantes');
            $table->foreignId('id_cursos')->constrained('cursos', 'id_cursos');
            $table->date('fecha');
            $table->string('estado');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
