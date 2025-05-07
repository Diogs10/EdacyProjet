<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemestreSeeder extends Seeder
{
    public function run()
    {
        DB::table('semestres')->insert([
            [
                'libelle' => 'Semestre 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'libelle' => 'Semestre 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
