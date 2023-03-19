<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barangay;
use App\Models\Account;
use App\Models\BarangayHandle;

class BarangayHandleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BarangayHandle::create([
            'account_id' => 1,
            'barangay_id' => 1
        ]);
    }
}
