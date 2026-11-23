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
        Schema::table('products', function (Blueprint $table) {
            $table->string('type', 10)->default('buah')->after('category')->index();
            $table->string('propagation_method', 50)->nullable()->after('origin');
            $table->string('plant_height', 50)->nullable()->after('propagation_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn(['type', 'propagation_method', 'plant_height']);
        });
    }
};
