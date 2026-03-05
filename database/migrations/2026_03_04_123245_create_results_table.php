<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->integer('sample_id');
            $table->integer('client_id');
            $table->integer('added_by');

            $table->string('sample_type');
            $table->decimal('au_ppm', 10, 4)->nullable();
            $table->decimal('cu_ppm', 10, 4)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
