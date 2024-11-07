<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Acceso;
use App\Code;
use App\User;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }


    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        
        $code = Code::where('codigo', $request->password)->first();
        if($code){
            $user = User::where('email', $request->email)->where('code_id', $code->id)->first();
            if(!$user){
                if($code->limite > 0){
                    \DB::beginTransaction();
                    try {
                        $user = User::create([
                            'role_id' => $code->role_id, 
                            'code_id' => $code->id, 
                            'name' => strtoupper($request->name),
                            'email' => $request->email,
                            'password' => Hash::make($code->codigo),
                            'estado' => 'activo'
                        ]);

                        $months = $code->months;
                        $hoy = Carbon::now()->format('Y-m-d');
                        $fecha = date("Y-m-d",strtotime($hoy."+ ".$months." month"));
                        $final = date("Y-m-d",strtotime($fecha."- 1 days")); 
                        Acceso::create([
                            'user_id' => $user->id, 
                            'libro_id' => $code->libro_id, 
                            'months' => $months,
                            'inicio' => $hoy,
                            'final'  => $final
                        ]);

                        $code->update(['limite' => $code->limite - 1]);
                        
                        event(new Registered($user));

                        \DB::commit();
                    } catch (Exception $e) {
                        \DB::rollBack();
                        return response()->json($e->getMessage());
                    }
                    return redirect()->route('login')->with('status', 'Estamos a punto de terminar. Por favor, revisa tu correo electrónico y haz clic en el enlace que te hemos enviado para poder acceder.');
                }
                return redirect()->route('register')->with('status', 'El código ingresado no es válido. Por favor, dirígete a la sección de Soporte.');
            }
            return redirect()->route('login')->with('status', 'Tus datos ya están registrados. Puedes Iniciar sesión.');
        }
        return redirect()->route('register')->with('status', 'El código ingresado no es válido. Por favor, revisa y vuelve a intentarlo.');
    }
}
