<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Games =====================================================================
        DB::table('games')->insert([
            'name' => 'Daily Game One',
            'start' => now(),
            'end' => now(),
            'status' => 0,
            'comment' => 'Some comments here..',
        ]);

        DB::table('games')->insert([
            'name' => 'Daily Game Two',
            'start' => now(),
            'end' => now(),
            'status' => 1,
            'comment' => 'Some comments here..',
        ]);


        // Game Numbers =====================================================================
        DB::table('game_number')->insert([
            'game_id' => 2,
            'number_id' => 24,
            'declared_at' => now(),
        ]);


        // Game Prizes =====================================================================
        DB::table('game_prize')->insert([
            'game_id' => 2,
            'prize_id' => 1,
            'prize_amount' => 80000,
            'quantity' => 1,
            'active' => 1,
            'comment' => 'Some comment here..',
        ]);

        DB::table('game_prize')->insert([
            'game_id' => 2,
            'prize_id' => 2,
            'prize_amount' => 7000,
            'quantity' => 5,
            'active' => 1,
            'comment' => 'Some comment here..',
        ]);

        DB::table('game_prize')->insert([
            'game_id' => 2,
            'prize_id' => 3,
            'prize_amount' => 7000,
            'quantity' => 1,
            'active' => 1,
            'comment' => 'Some comment here..',
        ]);

    }
}
