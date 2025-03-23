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
        Schema::create('users_consolidacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consolidado_id')->constrained('consolidados')->onDelete('cascade'); // Relación con consolidado
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Persona consolidada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_consolidacion');
    }
};

