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
        Schema::table('claims', function (Blueprint $table) {
            $table
                ->foreign('ticket_id')
                ->references('id')
                ->on('tickets')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            // $table
            //     ->foreign('winner_id')
            //     ->references('id')
            //     ->on('winners')
            //     ->onUpdate('CASCADE')
            //     ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropForeign(['winner_id']);
        });
    }
};
