<?php

namespace Database\Seeders;

use App\Models\Sedes;
use App\Models\SedeVendedor;
use App\Models\User;
use Illuminate\Database\Seeder;

class SedeVendedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Verificar si existen las sedes y vendedores antes de insertar datos
        $sedes = Sedes::all();
        $vendedores = User::where('role', 'asesor')->get();

        if ($sedes->isEmpty() || $vendedores->isEmpty()) {
            $this->command->error('No se encontraron sedes o vendedores para insertar en la tabla sede_vendedor.');

            return;
        }

        // Insertar datos de prueba en la tabla sede_vendedor
        foreach ($sedes as $sede) {
            foreach ($vendedores as $vendedor) {
                SedeVendedor::create([
                    'vendedor_id' => $vendedor->id,
                    'sede_id' => $sede->id,
                ]);
            }
        }

        $this->command->info('Seeder de SedeVendedor ejecutado correctamente.');
    }
}
