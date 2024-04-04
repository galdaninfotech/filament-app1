<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('winners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('game_prize_id');
            $table->foreignId('game_id');
            $table->foreignId('user_id')->nullable();  //nullable because we populate this table as winners declared
            $table->foreignUuid('ticket_id')->nullable();
            $table->foreignId('claim_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('winners');
    }
};

