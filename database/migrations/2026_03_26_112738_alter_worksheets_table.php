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
        Schema::table('worksheets', function (Blueprint $table) {
            $table->string('sample_type')->nullable()->default('--')->after('sample_data');
            $table->integer('sample_count')->nullable()->after('sample_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::table('worksheets', function (Blueprint $table) {
            $table->dropColumn('sample_type');
            $table->dropColumn('sample_count');
        });
    }
};
