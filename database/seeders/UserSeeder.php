<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Lê Anh Huy',
            'avatar'=> fake()->imageUrl(),
            'email' => 'huyanhit@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('761148'),
            'remember_token' => Str::random(10),
        ]);

        User::factory(5)->create();
    }
}
