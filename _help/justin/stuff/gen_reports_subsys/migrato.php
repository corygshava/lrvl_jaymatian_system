<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_reports', function (Blueprint $table) {
            $table->id();
            $table->string('img_path');
            $table->string('img_hash')->index(); // Indexed for faster lookups
            $table->timestamps();
            
            // Composite index for date + hash queries
            $table->index(['created_at', 'img_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_reports');
    }
};