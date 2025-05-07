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
    }
}
