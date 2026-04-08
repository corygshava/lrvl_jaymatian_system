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
		Schema::create('monthly_cash_reports', function (Blueprint $table) {
			$table->id();
			$table->integer('added_by');

			$table->decimal('carry_over',11,2);
			$table->decimal('total_income',11,2);
			$table->decimal('cash_from_debtors',11,2);
			$table->decimal('total_daily_cash',11,2);
			$table->decimal('total_cash',11,2);
			$table->decimal('expenditure',11,2);
			$table->decimal('total_balance_forward',11,2);

			$table->json('sample_data')->default('{}');
			$table->json('cash_data')->default('{}');
			$table->json('spend_data')->default('{}');

			$table->integer('users_count')->default(0);
			$table->integer('clients_count')->default(0);
			$table->integer('tasks_count')->default(0);
			$table->integer('samples_count')->default(0);
			$table->integer('total_payments_task_count')->default(0);
			$table->integer('total_payments_invoice_count')->default(0);
			$table->integer('reports_count')->default(0);
			$table->integer('invoices_count')->default(0);
			$table->integer('expenses_count')->default(0);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists('monthly_cash_reports');
	}
};
