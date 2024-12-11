<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // En tu UserSeeder.php
        User::create([
            'name' => 'Super User',
            'username' => 'superuser2',  // Cambia este valor por uno único
            'password' => bcrypt('superpassword'),
            'role' => 'super',
        ]);

        User::create([
            'name' => 'Test User',
            'username' => 'testuser',  // Otro valor único
            'password' => bcrypt('testpassword'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Normal User',
            'username' => 'normaluser',  // Otro valor único
            'password' => bcrypt('normalpassword'),
            'role' => 'asesor',
        ]);

    }
}
