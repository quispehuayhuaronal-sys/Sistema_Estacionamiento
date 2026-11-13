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
    if (! Schema::hasColumn('estacionamientos', 'fecha_salida')) {
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->dateTime('fecha_salida')->nullable();
        });
    }
}

public function down(): void
{
    if (Schema::hasColumn('estacionamientos', 'fecha_salida')) {
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->dropColumn('fecha_salida');
        });
    }
}
};
