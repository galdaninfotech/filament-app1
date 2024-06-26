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
        Schema::create('ticket_repositories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('object');
            $table->string('color')->default('aliceblue');
            $table->string('on_color')->default('#5be196');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets_repository');
    }
};
