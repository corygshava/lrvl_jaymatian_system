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
            $table->string('rectype')->after('added_by')->default('daily');
            $table->json('debtors')->after('spend_data')->default('[]');
            $table->decimal('total_owed',11,2)->after('carry_over')->default(0);
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->string('rectype')->after('added_by')->default('daily');
            $table->json('debtors')->after('spend_data')->default('[]');
            $table->decimal('total_owed',11,2)->after('carry_over')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void {
        Schema::table('daily_cash_reports', function (Blueprint $table) {
            $table->dropColumn('rectype');
            $table->dropColumn('debtors');
            $table->dropColumn('total_owed');
        });
        Schema::table('monthly_cash_reports', function (Blueprint $table) {
            $table->dropColumn('rectype');
            $table->dropColumn('debtors');
            $table->dropColumn('total_owed');
        });
    }
};
