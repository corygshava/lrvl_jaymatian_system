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
			$table->json('readings')->default('[]')->after('sample_count');
            $table->boolean('has_readings')->default(false)->after('readings');
            $table->boolean('is_published')->default(false)->after('has_readings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::table('worksheets', function (Blueprint $table) {
            $table->dropColumn('has_readings');
            $table->dropColumn('readings');
            $table->dropColumn('is_published');
        });
    }
};
