<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear usuarios de forma idempotente
        $admin = User::firstOrCreate(
            ['email' => 'admin@farmacia.com'],
            ['name' => 'Administrador', 'password' => Hash::make('admin123')]
        );
        if (! $admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        $vendedor = User::firstOrCreate(
            ['email' => 'vendedor@farmacia.com'],
            ['name' => 'Vendedor', 'password' => Hash::make('vendedor123')]
        );
        if (! $vendedor->hasRole('Farmaceutico')) {
            $vendedor->assignRole('Farmaceutico');
        }

        $farmaceutico = User::firstOrCreate(
            ['email' => 'farmaceutico@farmacia.com'],
            ['name' => 'Farmacéutico', 'password' => Hash::make('farmaceutico123')]
        );
        if (! $farmaceutico->hasRole('Farmaceutico')) {
            $farmaceutico->assignRole('Farmaceutico');
        }

        $auxiliar = User::firstOrCreate(
            ['email' => 'auxiliar@farmacia.com'],
            ['name' => 'Auxiliar', 'password' => Hash::make('auxiliar123')]
        );
        if (! $auxiliar->hasRole('Auxiliar')) {
            $auxiliar->assignRole('Auxiliar');
        }

        $cajero = User::firstOrCreate(
            ['email' => 'cajero@farmacia.com'],
            ['name' => 'Cajero', 'password' => Hash::make('cajero123')]
        );
        if (! $cajero->hasRole('Cajero')) {
            $cajero->assignRole('Cajero');
        }
    }
} 