<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Admin',
            'telephone' => '123456789',
            'email' => 'admin@example.com',
            'role_id' => 4,
            'password' => Hash::make('1234'),
        ]);

        User::create([
            'nom' => 'Attache',
            'prenom' => 'Attache',
            'telephone' => '987654321',
            'email' => 'attache@example.com',
            'role_id' => 3,
            'password' => Hash::make('1234'),
        ]);

        User::create([
            'nom' => 'Professeur',
            'prenom' => 'Professeur',
            'telephone' => '555666777',
            'email' => 'professeur@example.com',
            'role_id' => 2,
            'password' => Hash::make('1234'),
        ]);
    }
}
