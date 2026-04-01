<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->integer('created_by');

            $table->decimal('total', 10, 2);
            $table->decimal('total_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2);
            $table->integer('tasks_count');
            $table->json('task_ids');
            $table->json('invoice_data')->nullable();
            $table->integer('status');
            $table->integer('last_updated_by');

            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('invoices');
    }
};
