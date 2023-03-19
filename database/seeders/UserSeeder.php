<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'user_id' => '3',
            'is_new_account' => 0,
            'email' => 'jericcabusas17@gmail.com',
            'password' => bcrypt('1'),
            'user_role' => '0',
            'barangay_id' => 1,
            'last_name' => 'Cabusas',
            'first_name' => 'Jeric Michael',
            'middle_name' => 'Goloran',
            'suffix' => '',
            'birthday' => '2000-11-17',
            'sex' => 'Male',
            'civil_status' => 'Single',
            'address' => 'Cabuyao City',
            'phone_number' => '09095145293',
            'temp_password' => '',
        ]);
        User::create([
            'user_id' => '4',
            'is_new_account' => 0,
            'email' => 'claraclara17@pwd.com',
            'password' => bcrypt('1'),
            'user_role' => '1',
            'barangay_id' => 3,
            'last_name' => 'Clara',
            'first_name' => 'Clara',
            'middle_name' => 'Benjamin',
            'suffix' => '',
            'birthday' => '2000-11-17',
            'sex' => 'Male',
            'civil_status' => 'Single',
            'address' => 'Cabuyao City',
            'phone_number' => '09095145293',
            'temp_password' => '',
        ]);
        User::create([
            'user_id' => '2',
            'is_new_account' => 0,
            'email' => 'jeric@yahoo.com',
            'password' => bcrypt('1'),
            'user_role' => '2',
            'barangay_id' => null,
            'last_name' => 'Cabusas',
            'first_name' => 'Jeric Michael',
            'middle_name' => 'Goloran',
            'suffix' => '',
            'birthday' => '2000-11-17',
            'sex' => 'Male',
            'civil_status' => 'Single',
            'address' => 'Cabuyao City',
            'phone_number' => '09095145293',
            'temp_password' => '',
        ]);
        User::create([
            'user_id' => '1',
            'is_new_account' => 0,
            'email' => 'accountmanager@pwd.com',
            'password' => bcrypt('admin'),
            'user_role' => '3',
            'barangay_id' => null,
            'last_name' => 'SysAdmin',
            'first_name' => 'SysAdmin',
            'middle_name' => 'SysAdmin',
            'suffix' => '',
            'birthday' => null,
            'sex' => null,
            'civil_status' => null,
            'address' => null,
            'phone_number' => null,
            'temp_password' => null,
        ]);
    }
}
