<?php

namespace Database\Seeders;

use App\Models\Winner;
use Illuminate\Database\Seeder;

class WinnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Winner::factory()
            ->count(5)
            ->create();
    }
}
