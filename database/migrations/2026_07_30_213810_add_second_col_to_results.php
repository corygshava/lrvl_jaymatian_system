<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public $col1 = 'au_ppm_2';
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('results', function (Blueprint $table) {
            $table->decimal($this->col1, 10, 4)->after('au_ppm')->default('-77.00');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn($this->col1);
        });
    }
};
