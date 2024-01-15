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
        Schema::table('game_number', function (Blueprint $table) {
            $table
                ->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('number_id')
                ->references('id')
                ->on('numbers')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_number', function (Blueprint $table) {
            $table->dropForeign(['game_id']);
            $table->dropForeign(['number_id']);
        });
    }
};
