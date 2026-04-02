<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('added_by');
			$table->integer('task_id')->nullable();
			$table->integer('client_id')->constrained()->onDelete('cascade');

			$table->string('method')->default('cash');
            $table->decimal('amount', 10, 2);
            $table->integer('status')->default(1);
			$table->json('extra_details')->nullable();
			$table->dateTime('transaction_time');

            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('payments');
    }
};
