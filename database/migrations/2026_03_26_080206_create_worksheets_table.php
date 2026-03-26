<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('worksheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->Integer('created_by');
            $table->Integer('last_updated_by');
            $table->Integer('sample_id');
            $table->Integer('client_id');

            $table->json('sample_data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('worksheets');
    }
};
