<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'Super User',
            'username' => 'superuser',
            'password' => Hash::make('password'),
            'role' => 'super',
        ]);

        User::create([
            'name' => 'Admin User',
            'username' => 'adminuser',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Asesor User',
            'username' => 'asesoruser',
            'password' => Hash::make('password'),
            'role' => 'asesor',
        ]);

        User::create([
            'name' => 'Coordinador User',
            'username' => 'coordinadoruser',
            'password' => Hash::make('password'),
            'role' => 'coordinador',
        ]);
    }
}
