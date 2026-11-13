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
    if (! Schema::hasColumn('espacios', 'ocupado')) {
        Schema::table('espacios', function (Blueprint $table) {
            $table->boolean('ocupado')->default(false);
        });
    }
}

public function down(): void
{
    if (Schema::hasColumn('espacios', 'ocupado')) {
        Schema::table('espacios', function (Blueprint $table) {
            $table->dropColumn('ocupado');
        });
    }
}
};
