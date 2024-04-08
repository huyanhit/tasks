<?php

namespace Database\Seeders;
;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(TaskSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(TaskUserSeeder::class);
        $this->call(ProjectSeeder::class);
    }
}
