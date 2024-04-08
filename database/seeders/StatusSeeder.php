<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
            'title' => 'not start',
            'index' => 1
        ]);
        DB::table('statuses')->insert([
            'title' => 'in process',
            'index' => 2
        ]);
        DB::table('statuses')->insert([
            'title' => 'pending',
            'index' => 3
        ]);
        DB::table('statuses')->insert([
            'title' => 'done',
            'index' => 4
        ]);
    }
}
