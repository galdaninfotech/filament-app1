<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Tsewang Norboo',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Sonam Dorjey',
            'email' => 'sonam@sonam.com',
            'password' => bcrypt('sonam123'),
        ]);
    }
}
