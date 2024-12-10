<?php

namespace Database\Seeders;

use App\Models\Fijo;
use Illuminate\Database\Seeder;

class FijoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fijo::factory()->count(10)->create();
    }
}
