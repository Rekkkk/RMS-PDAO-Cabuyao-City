<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barangay;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Barangay::create(['barangay_name' => 'Staff']);
        Barangay::create(['barangay_name' => 'Baclaran']);
        Barangay::create(['barangay_name' => 'Banay-Banay']);
        Barangay::create(['barangay_name' => 'Banlic']);
        Barangay::create(['barangay_name' => 'Bigaa']);
        Barangay::create(['barangay_name' => 'Butong']);
        Barangay::create(['barangay_name' => 'Casile']);
        Barangay::create(['barangay_name' => 'Diezmo']);
        Barangay::create(['barangay_name' => 'Gulod']);
        Barangay::create(['barangay_name' => 'Mamatid']);
        Barangay::create(['barangay_name' => 'Marinig']);
        Barangay::create(['barangay_name' => 'Niugan']);
        Barangay::create(['barangay_name' => 'Pitland']);
        Barangay::create(['barangay_name' => 'Pulo']);
        Barangay::create(['barangay_name' => 'Sala']);
        Barangay::create(['barangay_name' => 'San Isidro']);
        Barangay::create(['barangay_name' => 'Barangay I Poblacion']);
        Barangay::create(['barangay_name' => 'Barangay II Poblacion']);
        Barangay::create(['barangay_name' => 'Barangay III Poblacion']);
    }
}
