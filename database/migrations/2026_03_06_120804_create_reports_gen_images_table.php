<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports_gen_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('sample_id')->constrained('samples')->onDelete('cascade');
            $table->foreignId('reports_gen_id')->constrained('reports_gen')->onDelete('cascade');
            $table->string('created_by');
            $table->integer('client_id');

            $table->string('image_path');
            $table->string('image_hash');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports_gen_images');
    }
};
