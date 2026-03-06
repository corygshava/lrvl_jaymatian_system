<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id');
            $table->string('user_name');
            $table->string('path');
            $table->string('ip_address');
            $table->string('intent');
            $table->string('method');
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->string('permission_required')->nullable();
            $table->json('extra_details')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
