<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id('id_calificaciones');
            $table->foreignId('id_criterios')->constrained('criterios', 'id_criterios');
            $table->foreignId('id_estudiantes')->constrained('estudiantes', 'id_estudiantes');
            $table->decimal('nota', 5, 2);
            $table->timestamps();

            $table->unique(['id_criterios', 'id_estudiantes'], 'calificacion_unica');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
