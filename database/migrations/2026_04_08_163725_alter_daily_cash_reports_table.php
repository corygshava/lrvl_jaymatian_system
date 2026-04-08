<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('daily_cash_reports', function (Blueprint $table) {
            $table->string('rectype')->default('daily');
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->string('rectype')->default('daily');
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void {
        Schema::table('daily_cash_reports', function (Blueprint $table) {
            $table->dropColumn('rectype');
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->dropColumn('rectype');
        });
    }
};
