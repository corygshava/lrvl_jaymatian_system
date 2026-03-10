<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::table('samples', function (Blueprint $table) {
            $table->boolean('has_report')->default(false)->after('sample_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::table('samples', function (Blueprint $table) {
            $table->dropColumn('has_report');
        });
    }
};
