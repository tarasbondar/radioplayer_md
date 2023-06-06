<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivacyValues extends Seeder
{

    public function run(): void
    {
        DB::table('custom_values')->insert([
            'key' => 'privacy_en',
            'value' => 'User Agreement'
        ]);

        DB::table('custom_values')->insert([
            'key' => 'privacy_ro',
            'value' => 'Acordul Utilizatorului'
        ]);
    }
}
