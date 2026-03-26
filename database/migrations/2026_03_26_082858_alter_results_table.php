<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->integer('worksheet_id')->after('sample_id')->nullable();
            $table->integer('last_updated_by')->after('updated_at')->nullable();
            $table->boolean('is_good')->after('updated_at')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('worksheet_id');
            $table->dropColumn('last_updated_by');
            $table->dropColumn('is_good');
        });
    }
};
