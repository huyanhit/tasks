<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            'title' => 'aloha',
            'content' => fake()->text(100),
            'date_start'=>fake()->dateTime(),
            'date_end'=>fake()->dateTime(),
        ]);

        DB::table('projects')->insert([
            'title' => 'aloha 2',
            'content' => fake()->text(100),
            'date_start'=>fake()->dateTime(),
            'date_end'=>fake()->dateTime(),
        ]);

        DB::table('projects')->insert([
            'title' => 'aloha 3',
            'content' => fake()->text(100),
            'date_start'=>fake()->dateTime(),
            'date_end'=>fake()->dateTime(),
        ]);

        DB::table('projects')->insert([
            'title' => 'aloha 4',
            'content' => fake()->text(100),
            'date_start'=>fake()->dateTime(),
            'date_end'=>fake()->dateTime(),
        ]);
    }
}
