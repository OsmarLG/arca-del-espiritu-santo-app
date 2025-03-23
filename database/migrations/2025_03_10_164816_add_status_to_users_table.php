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
            $table->boolean('status')->default(true)->after('password');
            $table->boolean('viene_otra_iglesia')->nullable()->default(false)->after('status');
            $table->boolean('bautizado')->nullable()->default(false)->after('viene_otra_iglesia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status'); // Elimina el campo en caso de rollback
        });
    }
};
