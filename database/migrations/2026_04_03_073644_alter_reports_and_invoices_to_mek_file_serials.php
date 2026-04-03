<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	static $tableu_1 = 'reports_gen';
	static $tableu_2 = 'invoices';

    /**
     * Run the migrations.
     */
    public function up(): void{
        Schema::table(self::$tableu_1, function (Blueprint $table) {
            $table->string('file_slug')->default(null)->nullable()->after('image_link');
        });
        Schema::table(self::$tableu_2, function (Blueprint $table) {
            $table->string('file_slug')->default(null)->nullable()->after('image_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::table(self::$tableu_1, function (Blueprint $table) {
            $table->dropColumn('file_slug');
        });
        Schema::table(self::$tableu_2, function (Blueprint $table) {
            $table->dropColumn('file_slug');
        });
    }
};
