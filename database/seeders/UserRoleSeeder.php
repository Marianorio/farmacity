<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Asignar rol a un usuario específico (si existe)
        $user = User::find(1); // Cambia el ID según sea necesario
        if ($user && ! $user->hasRole('Admin')) {
            $user->assignRole('Admin'); // Asigna el rol de Admin
        }
    }
} 