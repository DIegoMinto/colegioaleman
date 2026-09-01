<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuarios');
            $table->string('email')->unique();
            $table->string('user')->unique();
            $table->string('password');
            $table->foreignId('id_roles')->constrained('roles', 'id_roles');
            $table->foreignId('id_personas')->unique()->constrained('personas', 'id_personas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
