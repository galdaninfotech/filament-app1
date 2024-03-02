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
        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->dateTimeTz('start');
            $table->dateTimeTz('end');
            $table->integer('ticket_price')->default(0);
            $table->boolean('active')->default(false); //Active playing game
            $table->string('status')->default('Starting Shortly'); //Starting Shortly, Started, On, Paused, End
            $table->text('comment');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
