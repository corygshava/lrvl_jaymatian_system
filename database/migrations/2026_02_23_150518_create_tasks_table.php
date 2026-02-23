<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_no')->unique();
			$table->integer('added_by');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('client_name')->nullable();
            $table->integer('status')->default(0);
            $table->integer('payment_status')->default(0);
            $table->boolean('is_paid')->default(false);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tasks');
    }
};
