<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (! Schema::hasColumn('estacionamientos', 'numero_ticket')) {
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->string('numero_ticket')->nullable()->unique();
        });
    }
}

public function down(): void
{
    if (Schema::hasColumn('estacionamientos', 'numero_ticket')) {
        Schema::table('estacionamientos', function (Blueprint $table) {
            $table->dropUnique(['numero_ticket']);
            $table->dropColumn('numero_ticket');
        });
    }
}
};
