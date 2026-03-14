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
        Schema::table('reports_gen', function (Blueprint $table) {
            $table->string('authorizer')->after('technical_staff_name')->default('--');
            $table->string('attendant')->after('technical_staff_name')->default('--');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports_gen', function (Blueprint $table) {
            $table->dropColumn('authorizer');
            $table->dropColumn('attendant');
        });
    }
};
