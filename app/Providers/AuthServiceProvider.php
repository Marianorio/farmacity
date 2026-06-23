<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Mapea tus modelos a políticas aquí
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Definir permisos
        Gate::define('home', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Farmaceutico') || $user->hasRole('Auxiliar') || $user->hasRole('Cajero');
        });

        Gate::define('perfil', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Farmaceutico') || $user->hasRole('Auxiliar') || $user->hasRole('Cajero');
        });

        Gate::define('vista_admin', function ($user) {
            return $user->hasRole('Admin');
        });

        Gate::define('productos', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Farmaceutico');
        });

        Gate::define('obras_sociales', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Farmaceutico');
        });

        Gate::define('proveedores', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Farmaceutico');
        });

        Gate::define('ventas', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Farmaceutico') || $user->hasRole('Auxiliar') || $user->hasRole('Cajero');
        });

        Gate::define('info', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Farmaceutico') || $user->hasRole('Auxiliar') || $user->hasRole('Cajero');
        });

        Gate::define('reportes', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Farmaceutico');
        });

        Gate::define('recetas', function ($user) {
            return $user->hasRole('Admin') || $user->hasRole('Farmaceutico') || $user->hasRole('Auxiliar');
        });
    }
} 