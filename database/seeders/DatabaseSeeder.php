<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ClearRolesAndPermissionsSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CategoriaSeeder::class,
            ProveedorSeeder::class,
            ObraSocialSeeder::class,
            ProductoSeeder::class,
            VentaSeeder::class,
        ]);
    }
}
