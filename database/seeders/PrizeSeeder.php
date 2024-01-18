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
            'description' => 'Some description here..',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Top Line',
            'description' => 'Some description here..',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Middle Line',
            'description' => 'Some description here..',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Bottom Line',
            'description' => 'Some description here..',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Star',
            'description' => 'Some description here..',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Center, Laddu',
            'description' => 'Some description here..',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Early Five',
            'description' => 'Some description here..',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Lucky Seven',
            'description' => 'Some description here..',
        ]);

        DB::table('prizes')->insert([
            'name' => 'Corners',
            'description' => 'Some description here..',
        ]);
    }
}

