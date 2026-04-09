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
            $table->string('cash_report_no')->after('added_by')->default('CRPT_D_0000');
            $table->decimal('total_profit',11,2)->after('cash_report_no')->default(0);
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->string('cash_report_no')->after('added_by')->default('CRPT_M_0000');
            $table->decimal('total_profit',11,2)->after('cash_report_no')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_cash_reports', function (Blueprint $table) {
            $table->dropColumn('total_profit');
            $table->dropColumn('cash_report_no');
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->dropColumn('total_profit');
            $table->dropColumn('cash_report_no');
        });
    }
};
