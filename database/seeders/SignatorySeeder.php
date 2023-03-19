<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Signatory;


class SignatorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Signatory::create([
            'img_id' => '1',
            'img_file' => 'id-signature.png',
            'signatory_type' => 'ID'
        ]);
        Signatory::create([
            'img_id' => '2',
            'img_file' => 'Cancellation.png',
            'signatory_type' => 'Cancellation'
        ]);
    }
}
