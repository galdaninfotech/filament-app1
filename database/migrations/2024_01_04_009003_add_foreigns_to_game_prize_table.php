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
        Schema::table('game_prize', function (Blueprint $table) {
            $table
                ->foreign('prize_id')
                ->references('id')
                ->on('prizes')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_prize', function (Blueprint $table) {
            $table->dropForeign(['prize_id']);
            $table->dropForeign(['game_id']);
        });
    }
};
