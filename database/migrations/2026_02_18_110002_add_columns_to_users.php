<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->text('phone_number')->nullable()->default(null)->after('email');
			$table->timestamp('last_login_at')->nullable()->default(null);
			$table->boolean('is_active')->nullable(false)->default(true);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('phone_number');
			$table->dropColumn('is_active');
			$table->dropColumn('last_login_at');
		});
	}
};
