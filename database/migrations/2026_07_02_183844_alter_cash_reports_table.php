<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public $nme = 'total_payments_debt_count';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daily_cash_reports', function (Blueprint $table) {
            $table->integer($this->nme)->default(0)->after('total_payments_task_count');
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->integer($this->nme)->default(0)->after('total_payments_task_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_cash_reports', function (Blueprint $table) {
            $table->dropColumn($this->nme);
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->dropColumn($this->nme);
        });
    }
};
