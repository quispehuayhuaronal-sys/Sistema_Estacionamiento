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
    if (! Schema::hasColumn('estacionamientos', 'vehiculo_id')) {
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->foreignId('vehiculo_id')
                ->nullable()
                ->constrained('vehiculos');
        });
    }
}

public function down(): void
{
    if (Schema::hasColumn('estacionamientos', 'vehiculo_id')) {
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->dropForeign(['vehiculo_id']);
            $table->dropColumn('vehiculo_id');
        });
    }
}
};
