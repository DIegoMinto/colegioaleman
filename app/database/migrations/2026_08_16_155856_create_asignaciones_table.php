<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id('id_asignaciones');
            $table->foreignId('id_docentes')->constrained('docentes', 'id_docentes');
            $table->foreignId('id_cursos')->constrained('cursos', 'id_cursos');
            $table->foreignId('id_materias')->constrained('materias', 'id_materias');
            $table->string('gestion');
            $table->timestamps();

            $table->unique(['id_docentes', 'id_cursos', 'id_materias', 'gestion'], 'asignacion_unica');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
