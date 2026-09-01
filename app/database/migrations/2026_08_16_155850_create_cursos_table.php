<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id('id_cursos');
            $table->string('nombre');
            $table->string('nivel');
            $table->string('paralelo');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
