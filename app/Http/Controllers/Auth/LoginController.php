<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //** FUNCIONES SOBREESCRITAS */
    public function redirectPath(){
        if(auth()->user()->role->role == 'admin'){
            return 'admin/usuarios';
        } else {
            return 'users/index';
        }
    } 

    // CAMPO USER, VA A SER CODIGO
    public function username() {
        return 'codigo';
    }

    // PARA VALIDAR LA CONTRASEÑA
    protected function validateLogin(Request $request) {
        // dd($request);
        $request->validate([
            $this->username() => 'required|string', 
            'password' => 'required',
        ]);
    }

    //Sobreescribir metodo logout para cerrar sesion, y nos ubique en la vista login
    public function logout(){
        auth()->logout(); //Para poder cerrar sesión de la aplicación
        session()->flush(); //Limpiar todas las sesiones
        return redirect('/login');
    }
}
