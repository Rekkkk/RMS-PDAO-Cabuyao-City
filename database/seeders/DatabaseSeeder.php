<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Generator;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(BarangaySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(UserStatusSeeder::class);
        $this->call(PwdSeeder::class);
        $this->call(SignatorySeeder::class);
    
    }
}
