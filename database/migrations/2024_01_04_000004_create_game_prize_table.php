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
        Schema::create('game_prize', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('prize_id');
            $table->foreignId('game_id');
            $table->string('name');
            $table->integer('prize_amount');
            $table->integer('quantity');
            $table->boolean('active');
            $table->text('comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_prize');
    }
};
