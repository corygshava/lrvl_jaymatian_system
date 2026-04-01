<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_no')->nullable()->after('created_by');
            $table->string('pdf_link')->nullable()->after('invoice_data');
            $table->string('image_link')->nullable()->after('pdf_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_no');
            $table->dropColumn('pdf_link');
            $table->dropColumn('image_link');
        });
    }
};
