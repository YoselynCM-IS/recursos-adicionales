<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libro;
use App\Code;

class CodeController extends Controller
{
    // CREAR CODIGO
    public function store(Request $request){
        \DB::beginTransaction();
        try {
            $role_id = $request->role_id;
            $libro = Libro::find($request->libro_id);
            $codigo = $this->set_codigo($role_id, $libro->code);
            
            Code::create([
                'role_id' => $role_id, 
                'libro_id' => $libro->id, 
                'codigo' => $codigo, 
                'months' => (int)$request->months, 
                'limite' => (int)$request->limite
            ]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }

        return response()->json(true);
    }

    // OBTENER EL CODIGO
    public function set_codigo($role_id, $code){
        $ale_l = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4);
        return $role_id.$code.$ale_l;
    }

    // OBTENER CODIGOS POR LIBRO
    public function by_libro(Request $request){
        $codes = Code::where('libro_id', $request->libro_id)
                ->with('libro', 'role')->orderBy('id', 'desc')->paginate(50);
        return response()->json($codes);
    }

    // OBTENER CODIGOS POR COINCIDENCIA
    public function show(Request $request){
        $codes = Code::where('codigo', 'LIKE', '%'.$request->code.'%')
                ->with('libro', 'role')->orderBy('id', 'desc')->paginate(50);
        return response()->json($codes);
    }
}
