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
        Schema::table('daily_cash_reports', function (Blueprint $table) {
            $table->json('debt_pay_data')->after('added_by')->default('[]');
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->json('debt_pay_data')->after('added_by')->default('[]');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_cash_reports', function (Blueprint $table) {
            $table->dropColumn('debt_pay_data');
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->dropColumn('debt_pay_data');
        });
    }
};
