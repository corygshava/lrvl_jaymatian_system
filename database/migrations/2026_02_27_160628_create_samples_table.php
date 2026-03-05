<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();

			$table->integer('added_by');
			$table->integer('task_id')->constrained()->onDelete('cascade');
			$table->integer('client_id')->constrained()->onDelete('cascade');

            $table->string('sample_no')->unique();
            $table->string('sample_tag');
            $table->string('sample_type');
            $table->integer('sample_count');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
