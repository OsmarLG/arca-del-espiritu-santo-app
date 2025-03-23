<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('fecha_nacimiento')->nullable()->after('email'); // Campo para la fecha de nacimiento
            $table->date('fecha_conversion')->nullable()->after('fecha_nacimiento'); // Campo para la fecha de conversion
            $table->string('numero_telefono')->nullable()->after('fecha_conversion'); // Campo para el número de teléfono
            $table->text('direccion')->nullable()->after('numero_telefono'); // Campo para la dirección
            $table->foreignId('genero_id')->nullable()->after('direccion')->constrained('generos')->nullOnDelete(); // Campo para el género
            $table->foreignId('estado_civil_id')->nullable()->after('genero_id')->constrained('estados_civiles')->nullOnDelete(); // Campo para el estado civil
            $table->string('profesion')->nullable()->after('estado_civil_id'); // Campo para la profesión
            $table->foreignId('invitador_id')->nullable()->after('profesion')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fecha_nacimiento');
            $table->dropColumn('numero_telefono');
            $table->dropColumn('direccion');
            $table->dropColumn('genero_id');
            $table->dropColumn('estado_civil_id');
            $table->dropColumn('profesion');
            $table->dropColumn('invitador_id');
        });
    }
};
