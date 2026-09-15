<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dias_asistencia', function (Blueprint $table) {
            $table->id('id_dias_asistencia');
            $table->foreignId('id_asignaciones')->constrained('asignaciones', 'id_asignaciones')->cascadeOnDelete();
            $table->foreignId('id_trimestres')->constrained('trimestres', 'id_trimestres')->cascadeOnDelete();
            $table->date('fecha')->nullable();
            $table->unsignedTinyInteger('orden');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_asistencia');
    }
};
