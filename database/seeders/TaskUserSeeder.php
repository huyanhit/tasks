<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('task_user')->insert([
            'task_id' => 1,
            'user_id' => 1,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 2,
            'user_id' => 1,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 2,
            'user_id' => 2,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 2,
            'user_id' => 3,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 3,
            'user_id' => 2,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 4,
            'user_id' => 1,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 4,
            'user_id' => 5,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 4,
            'user_id' => 3,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 5,
            'user_id' => 3,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 6,
            'user_id' => 2,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 7,
            'user_id' => 1,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 8,
            'user_id' => 1,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 9,
            'user_id' => 1,
            'role_id' => 1
        ]);
        DB::table('task_user')->insert([
            'task_id' => 10,
            'user_id' => 1,
            'role_id' => 1
        ]);
    }
}
