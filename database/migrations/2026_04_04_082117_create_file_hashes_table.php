<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_hashes', function (Blueprint $table) {
            $table->id();
            $table->enum('file_type', ['invoice', 'report']);
            $table->longText('myhash');
            $table->integer('invoice_id')->nullable();
            $table->integer('report_id')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_format');				// 'image' or 'pdf'
            $table->integer('page_count')->default(1);	// Number of pages (1 for images, multiple for PDFs)
            $table->timestamps();

			// /*
            // Indexes for fast lookups
            $table->index('myhash');
            $table->index(['file_type', 'invoice_id']);
            $table->index(['file_type', 'report_id']);
			// */
        });
    }

    public function down(): void{
        Schema::dropIfExists('file_hashes');
    }
};
