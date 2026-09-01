<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id('id_personas');
            $table->string('nombres');
            $table->string('apellido_p');
            $table->string('apellido_m')->nullable();
            $table->string('ci')->unique();
            $table->string('extension_ci')->nullable();
            $table->date('fecha_nacimiento');
            $table->string('domicilio')->nullable();
            $table->string('celular')->nullable();
            $table->string('departamento_residencia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
