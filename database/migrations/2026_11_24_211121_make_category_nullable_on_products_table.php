<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category', 20)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations. Products without a category fall back to the previous default.
     */
    public function down(): void
    {
        DB::table('products')->whereNull('category')->update(['category' => 'durian']);

        Schema::table('products', function (Blueprint $table) {
            $table->string('category', 20)->nullable(false)->default('durian')->change();
        });
    }
};
