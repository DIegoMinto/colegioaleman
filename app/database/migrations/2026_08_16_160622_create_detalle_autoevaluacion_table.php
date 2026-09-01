<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detalle_autoevaluacion', function (Blueprint $table) {
            $table->id('id_detalle_autoevaluacion');
            $table->foreignId('id_autoevaluaciones')->unique()->constrained('autoevaluaciones', 'id_autoevaluaciones');
            $table->decimal('ser', 5, 2);
            $table->decimal('saber', 5, 2);
            $table->decimal('hacer', 5, 2);
            $table->decimal('decidir', 5, 2);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('detalle_autoevaluacion');
    }
};
