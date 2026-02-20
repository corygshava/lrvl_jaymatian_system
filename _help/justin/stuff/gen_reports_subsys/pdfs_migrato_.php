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
            $table->string('file_type', 50);
            
            // Bytecode hashes
            $table->string('hash_bytecode_header', 64)->index(); // SHA256 = 64 chars
            $table->string('hash_bytecode_full', 64)->index();
            
            // Content hashes
            $table->string('hash_text_content', 64)->nullable()->index();
            $table->string('hash_visual_content', 64)->nullable()->index();
            
            // Metadata
            $table->enum('pdf_type', ['text', 'image', 'mixed', 'unknown'])->nullable();
            $table->integer('page_count')->default(0);
            
            $table->timestamps();
            
            // Composite indexes for faster queries
            $table->index(['created_at', 'file_type']);
            $table->index(['created_at', 'hash_bytecode_full']);
            $table->index(['created_at', 'hash_text_content']);
            $table->index(['created_at', 'hash_visual_content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_reports');
    }
};