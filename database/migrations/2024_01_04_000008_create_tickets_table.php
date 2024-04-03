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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('game_id');
            $table->foreignId('user_id');
            $table->json('object');
            $table->string('status'); // Active, Disqualified, Old
            $table->text('comment');
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
        Schema::dropIfExists('tickets');
    }
};
