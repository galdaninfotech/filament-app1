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
            'ticket_price' => 50,
            'active' => false,
            'status' => 'End',
            'comment' => 'Some comments here..',
        ]);

        DB::table('games')->insert([
            'name' => 'Daily Game Two',
            'start' => now(),
            'end' => now(),
            'ticket_price' => 100,
            'active' => true,
            'comment' => 'Some comments here..',
        ]);


        // Game Numbers =====================================================================
        // DB::table('game_number')->insert([
        //     'game_id' => 2,
        //     'number_id' => 24,
        //     'declared_at' => now(),
        // ]);

        // DB::table('game_number')->insert([
        //     'game_id' => 2,
        //     'number_id' => 44,
        //     'declared_at' => now(),
        // ]);

        // DB::table('game_number')->insert([
        //     'game_id' => 2,
        //     'number_id' => 64,
        //     'declared_at' => now(),
        // ]);

        // DB::table('game_number')->insert([
        //     'game_id' => 2,
        //     'number_id' => 84,
        //     'declared_at' => now(),
        // ]);


        // Game Prizes =====================================================================
        DB::table('game_prize')->insert([
            'game_id' => 2,
            'prize_id' => 1,
            'name' => 'Full House',
            'prize_amount' => 40000,
            'quantity' => 1,
            'active' => 1,
            'comment' => 'Some comment here..',
        ]);

        DB::table('game_prize')->insert([
            'game_id' => 2,
            'prize_id' => 2,
            'name' => 'Top Line',
            'prize_amount' => 7000,
            'quantity' => 5,
            'active' => 1,
            'comment' => 'Some comment here..',
        ]);

        DB::table('game_prize')->insert([
            'game_id' => 2,
            'prize_id' => 3,
            'name' => 'Bottom Line',
            'prize_amount' => 7000,
            'quantity' => 1,
            'active' => 1,
            'comment' => 'Some comment here..',
        ]);

    }
}
