<?php

namespace App\Http\Controllers\Auth;

use App\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //** FUNCIONES SOBREESCRITAS */
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $code = Code::where('codigo', $request->password)->first();
        return [
            'email' =>  $request->email,
            'password' =>  $request->password,
            'code_id' => $code->id
        ];
    }

    public function redirectPath(){
        if(auth()->user()->role->role == 'admin'){
            return 'admin/usuarios';
        } else {
            return 'users/index';
        }
    }

    //Sobreescribir metodo logout para cerrar sesion, y nos ubique en la vista login
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
