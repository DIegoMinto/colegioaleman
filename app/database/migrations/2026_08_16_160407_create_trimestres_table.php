<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trimestres', function (Blueprint $table) {
            $table->id('id_trimestres');
            $table->string('nombres');
            $table->unsignedTinyInteger('orden');
            $table->string('gestion');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('trimestres');
    }
};
