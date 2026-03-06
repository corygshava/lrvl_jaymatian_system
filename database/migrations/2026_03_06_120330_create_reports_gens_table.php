<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports_gen', function (Blueprint $table) {
            $table->id();

			$table->integer('task_id');
			$table->integer('sample_id');
			$table->integer('created_by');
			$table->integer('client_id');

            $table->string('company_contact');
            $table->string('sample_type');
            $table->integer('sample_count');
            $table->date('date_received');
            $table->date('date_compiled');
            $table->string('task_no');
            $table->string('technical_staff_name');
            $table->json('analytical_report');
            $table->text('deviation_data')->nullable();
            $table->string('business_logo_img')->nullable();
            $table->string('pdf_link')->nullable();
            $table->string('image_link')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports_gen');
    }
};
