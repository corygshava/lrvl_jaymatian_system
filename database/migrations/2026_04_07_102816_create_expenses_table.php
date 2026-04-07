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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

			$table->string('purpose');
			$table->integer('added_by');
			$table->decimal('amount',12,2);
			$table->string('desc')->nullable()->default('--');
			$table->string('expenditure_no')->nullable()->default('--');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('expenses');
    }
};
