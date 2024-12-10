<?php

namespace Database\Seeders;

use App\Models\Sede;
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
        $sedes = Sede::all();
        $vendedores = User::where('role', 'vendedor')->get();

        if ($sedes->isEmpty() || $vendedores->isEmpty()) {
            $this->command->error('No se encontraron sedes o vendedores para insertar en la tabla sede_vendedor.');

            return;
        }

        // Insertar datos de prueba en la tabla sede_vendedor
        foreach ($sedes as $sede) {
            foreach ($vendedores as $vendedor) {
                SedeVendedor::create([
                    'sede_id' => $sede->id,
                    'vendedor_id' => $vendedor->id,
                ]);
            }
        }

        $this->command->info('Seeder de SedeVendedor ejecutado correctamente.');
    }
}
