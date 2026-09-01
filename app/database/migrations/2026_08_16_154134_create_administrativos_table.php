<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('administrativos', function (Blueprint $table) {
            $table->id('id_administrativos');
            $table->foreignId('id_personas')->unique()->constrained('personas', 'id_personas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrativos');
    }
};
