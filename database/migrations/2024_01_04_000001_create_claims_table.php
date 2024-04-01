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
        Schema::create('claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignUuid('ticket_id');
            $table->foreignId('game_prize_id');
            $table->string('status'); // Open, Winner, Boggy
            $table->text('remarks');
            $table->boolean('is_winner')->default(0);
            $table->boolean('is_boogy')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
