<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos (nombres que coinciden con los "can:..." usados en routes)
        $permissions = [
            'home',
            'perfil',
            'info',
            'recetas',
            'vista_admin',
            'productos',
            'proveedores',
            'ventas',
            'reportes',
            'categorias',
            'obras_sociales',
            'administrar usuarios'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles y asignar permisos de forma idempotente
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions($permissions);

        $farmaceutico = Role::firstOrCreate(['name' => 'Farmaceutico']);
        $farmaceutico->syncPermissions([
            'productos', 'ventas', 'obras_sociales', 'proveedores', 'reportes'
        ]);

        $auxiliar = Role::firstOrCreate(['name' => 'Auxiliar']);
        $auxiliar->syncPermissions([
            'productos', 'ventas', 'obras_sociales', 'proveedores'
        ]);

        $cajero = Role::firstOrCreate(['name' => 'Cajero']);
        $cajero->syncPermissions([
            'productos', 'ventas'
        ]);
    }
}