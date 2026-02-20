<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
			$table->id();
			$table->integer('added_by');
            $table->string('client_name');
            $table->string('client_contact');
            $table->string('client_gender');

            // $table->enum('payment_plan', ['monthly', 'per_visit']);
            $table->string('payment_plan')->default('per_visit');
            $table->decimal('credit_balance', 10, 2)->default(0);
            $table->string('kra_pin')->nullable();
            $table->string('id_number')->nullable();
            $table->json('extra_details')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
