<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->id('id_materias');
            $table->string('nombre');
            $table->foreignId('id_areas')->constrained('areas', 'id_areas');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
