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
    if (! Schema::hasColumn('estacionamientos', 'espacio_id')) {
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->foreignId('espacio_id')
                ->nullable()
                ->constrained('espacios');
        });
    }
}

public function down(): void
{
    if (Schema::hasColumn('estacionamientos', 'espacio_id')) {
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->dropForeign(['espacio_id']);
            $table->dropColumn('espacio_id');
        });
    }
}
};
