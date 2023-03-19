<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PwdBarangay;

class PwdBarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 10 ; $i++) {
            PwdBarangay::create([
                'pwd_id' => $i+1,
                'barangay_id' => $i+1
            ]);
        }
    }
}
