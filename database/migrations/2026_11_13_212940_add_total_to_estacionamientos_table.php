<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('estacionamientos', 'total')) {
            Schema::table('estacionamientos', function (Blueprint $table) {
                $table->decimal('total', 10, 2)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('estacionamientos', 'total')) {
            Schema::table('estacionamientos', function (Blueprint $table) {
                $table->dropColumn('total');
            });
        }
    }
};