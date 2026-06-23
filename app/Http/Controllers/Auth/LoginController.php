<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Determine redirect path after login based on user role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user) {
            if (method_exists($user, 'hasRole')) {
                if ($user->hasRole('Farmaceutico')) {
                    return route('productos.index');
                }
                if ($user->hasRole('Cajero')) {
                    return route('ventas.index');
                }
            }
        }

        return '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
