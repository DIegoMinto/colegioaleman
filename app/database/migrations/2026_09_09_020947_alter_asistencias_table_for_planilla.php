<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropForeign(['id_cursos']);
            $table->dropColumn(['id_cursos', 'fecha']);

            $table->foreignId('id_dias_asistencia')
                ->after('id_asistencias')
                ->constrained('dias_asistencia', 'id_dias_asistencia')
                ->cascadeOnDelete();

            $table->unique(['id_dias_asistencia', 'id_estudiantes']);
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropUnique(['id_dias_asistencia', 'id_estudiantes']);
            $table->dropForeign(['id_dias_asistencia']);
            $table->dropColumn('id_dias_asistencia');

            $table->foreignId('id_cursos')->nullable()->constrained('cursos', 'id_cursos');
            $table->date('fecha')->nullable();
        });
    }
};
