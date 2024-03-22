<?php

namespace Database\Seeders;

use App\Models\Prize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prizes')->insert([
            'name' => 'Full House',
            'description' => 'Full House',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Top Line',
            'description' => 'Top Line',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Middle Line',
            'description' => 'Middle Line',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Bottom Line',
            'description' => 'Bottom Line',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Lucky Seven',
            'description' => 'Lucky Seven',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Ticket Corners',
            'description' => 'line1 = first & last, line3 = first & last',
        ]);
    }
}

