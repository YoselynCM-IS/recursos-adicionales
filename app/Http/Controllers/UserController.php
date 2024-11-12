<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Acceso;
use App\Libro;
use App\User;
use App\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // OBTENER FECHA FINAL
    public function get_final($inicio, $months){
        $fecha = date("Y-m-d",strtotime($inicio."+ ".$months." month"));
        return date("Y-m-d",strtotime($fecha."- 1 days")); 
    }

    // ACTUALIZAR USUARIO
    public function update_user($user, $request){
        \DB::beginTransaction();
        try {
            $user->update([
                'name'      => strtoupper($request->name),
                'email'     => $request->email,
                'estado'    => 'activo'
            ]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    // OBTENER TODOS LOS ROLES
    public function get_roles(){
        $roles = Role::get();
        return response()->json($roles);
    }

    // CREAR USUARIO
    public function create_user($request, $codigo){
        \DB::beginTransaction();
        try {
            $user = User::create([
                'role_id' => $request->role_id, 
                'codigo' => $codigo, 
                'password' => bcrypt('CODPRUEBA')
            ]);

            Acceso::create([
                'user_id' => $user->id, 
                'libro_id' => $request->libro_id, 
                'months' => (int)$request->months
            ]);

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    // OBTENER NUMERO DE LIBROS GENERADOS
    public function get_count_libros($role_id, $libro_id){
        return \DB::table('accesos')
            ->join('users', 'accesos.user_id', '=', 'users.id')
            ->where('role_id', $role_id)
            ->where('libro_id', $libro_id)->count();
    }

    // GUARDAR USUARIOS MASIVAMENTE
    public function store_mass(Request $request){
        $role_id = $request->role_id;
        $libro_id = $request->libro_id;
        $libros_count = $this->get_count_libros($role_id, $libro_id);
        $libro = Libro::find($libro_id);
        
        $numeros = collect();
        for ($i=0; $i < (int)$request->quantity; $i++) { 
            $numeros->push($i + 1);
        }

        $numeros->map(function($n) use(&$libros_count, $role_id, $libro, $request){
            $libros_count++;
            $codigo = $this->set_codigo($role_id, $libros_count, $libro->code);
            $this->create_user($request, $codigo);
        });

        return response()->json(true);
    }

    // OBTENER EL CODIGO
    public function set_codigo($role_id, $count, $code){
        $ale_l = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 4);
        $numero = str_pad($count, 5, "0", STR_PAD_LEFT);
        return $role_id.$code.$ale_l.$numero;
    }

    // ACTUALIZAR INFORMACION DEL USUARIO
    public function update(Request $request){
        $user = User::whereId($request->id)->with('acceso')->first();
        $acceso = $user->acceso;
        $months = $request->months;
        $inicio = $request->inicio;

        $this->update_acceso($acceso, $months, null, null);
        if($user->name !== null){
            $this->update_user($user, $request);
            $final = $this->get_final($inicio, $months);
            $this->update_acceso($acceso, $months, $inicio, $final);
        } 
        
        return response()->json($user);
    }

    // ACTUALIZAR ACCESO
    public function update_acceso($acceso, $months, $inicio, $final){
        \DB::beginTransaction();
        try {
            $acceso->update([
                'months' => $months,
                'inicio' => $inicio,
                'final'  => $final
            ]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    // DESHABILITAR/HABILITAR USUARIO
    public function des_habilitar(Request $request){
        \DB::beginTransaction();
        try {
            $user = User::find($request->id);
            $user->update([ 'estado' =>  $user->estado == 'activo' ? 'deshabilitado':'activo']);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json($user);
    }

    // PAGINA PRINCIPAL DE LOS USUARIOS
    public function index(){
        $user = auth()->user();
        $libro_id = $user->acceso->libro_id;
        $libro = Libro::find($libro_id);

        $hoy = Carbon::now()->format('Y-m-d');
        $final = new Carbon($user->acceso->final);

        if($hoy > $final->format('Y-m-d') || $user->estado == 'vencido'){
            $situacion = 'Tu código ha vencido, por lo cual ya no podrás consultar los recursos adicionales. Venció el: '.$user->acceso->final;
            return view('users.expired-date', compact('user', 'situacion'));
        } 
        if($hoy <= $final->format('Y-m-d') && $user->estado == 'deshabilitado') {
            $situacion = 'El código ha sido desactivado, por lo cual ya no podrás consultar los recursos adicionales.';
            return view('users.expired-date', compact('user', 'situacion'));
        }
        if($libro->estado == 'inactivo'){
            $situacion = 'El libro no se encuentra disponible por el momento.';
            return view('users.expired-date', compact('user', 'situacion'));
        }

        // PRIMERA VEZ
        $form = collect([
            'name' => null,
            'email' => null
        ]);

        // YA REGISTRADO
        $role_id = $user->role_id;

        // $libro_recurso = \DB::table('libro_recurso')
        //                 ->where('libro_id', $libro_id)
        //                 ->get();
        // $ids = [];
        // $libro_recurso->map(function($lr) use(&$ids){
        //     $ids[] = $lr->recurso_id;
        // });


        // dd($libro->recursos);
        if($role_id == 2){
            $recursos = $libro->recursos;
        } else {
            $recursos = $libro->recursos->where('role_id', $role_id);
        }

        $libro = collect([
            'id' => $libro_id,
            'libro' => $user->acceso->libro->libro,
            'recursos' => $recursos
        ]);
        return view('users.index', compact('form', 'libro'));
    }

    // OBTENER USUARIOS POR LIBRO Y ROL
    public function by_code(Request $request){
        $users = User::where('code_id', $request->code_id)
                ->with('acceso.libro')
                ->orderBy('created_at', 'desc')->paginate(50);

        return response()->json($users);
    }
}
