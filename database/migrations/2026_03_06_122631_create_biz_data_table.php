<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biz_data', function (Blueprint $table) {
            $table->id();

            $table->string('item_name');
            $table->json('item_data');
			$table->integer('created_by');
			$table->integer('modified_by');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biz_data');
    }
};
