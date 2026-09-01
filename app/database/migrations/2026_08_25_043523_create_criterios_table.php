<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('criterios', function (Blueprint $table) {
            $table->id('id_criterios');
            $table->foreignId('id_evaluaciones')->constrained('evaluaciones', 'id_evaluaciones')->cascadeOnDelete();
            $table->string('nombre');
            $table->unsignedTinyInteger('orden')->default(1);
            $table->decimal('puntaje_maximo', 5, 2);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('criterios');
    }
};
