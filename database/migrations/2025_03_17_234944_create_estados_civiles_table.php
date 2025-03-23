<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estados_civiles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        // Insertar datos iniciales
        DB::table('estados_civiles')->insert([
            ['nombre' => 'Soltero'],
            ['nombre' => 'Casado'],
            ['nombre' => 'Divorciado'],
            ['nombre' => 'Viudo'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_civiles');
    }
};
