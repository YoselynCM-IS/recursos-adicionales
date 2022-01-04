<?php

namespace App\Http\Controllers;

use App\Exports\LibrosListExport;
use Illuminate\Http\Request;
use App\Recurso;
use App\Libro;
use Excel;

class LibroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // MOSTRAR LIBROS POR COINCIDENCIA DLE TITULO
    public function show(Request $request){
        $libros = Libro::where('libro', 'like', '%'.$request->libro.'%')
                        ->orderBy('libro', 'asc')->get();
        return response()->json($libros);
    }

    // MOSTRAR LIBROS POR COINCIDENCIA DLE TITULO (PAGINADO)
    public function show_paginate(Request $request){
        $libros = Libro::where('libro', 'like', '%'.$request->libro.'%')
                        ->orderBy('libro', 'asc')->paginate(25);
        return response()->json($libros);
    }

    // GUARDAR LIBRO
    public function store(Request $request){
        $this->validate($request, [
            'code' => ['required', 'string', 'min:4', 'max:4', 'unique:libros']
        ]);

        \DB::beginTransaction();
        try {
            Libro::create([
                'tipo_id'   => $request->tipo_id,
                'code'      => $request->code,
                'libro'     => $request->libro
            ]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json(true);
    }

    // OBTENER TODOS LOS LIBROS
    public function index(){
        $libros = Libro::orderBy('libro', 'asc')->paginate(25);
        return response()->json($libros);
    }

    // ACTUALIZAR LIBRO
    public function update(Request $request){
        $libro = Libro::find($request->id);
        \DB::beginTransaction();
        try {
            $libro->update([
                'tipo_id'   => $request->tipo_id,
                'code'      => $request->code,
                'libro'     => $request->libro
            ]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json($libro);
    }

    // DESACTIVAR/Activar LIBRO
    public function des_activar(Request $request){
        $libro = Libro::find($request->id);

        if($request->estado == 1) $estado = 'activo';
        else $estado = 'inactivo';

        \DB::beginTransaction();
        try {
            $libro->update([ 'estado'  => $estado ]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json($libro);
    }

    // GUARDAR RECURSO
    public function store_recurso(Request $request){
        $this->validate_recurso($request);
        \DB::beginTransaction();
        try {
            $recurso = Recurso::create($this->set_recurso($request));
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json($recurso);
    }

    // OBTENER LOS RECURSOS
    public function get_recursos(){
        $recursos = Recurso::orderBy('recurso', 'asc')->with('role')->paginate(25);
        return response()->json($recursos);
    }

    // ASIGNAR LOS VALORES PARA INSERTAR O ACTUALIZAR
    public function set_recurso($request){
        return [
            'role_id' => $request->role_id,
            'tipo_id' => $request->tipo_id,
            'recurso' => $request->recurso
        ];
    }

    // ACTUALIZAR RECURSO
    public function update_recurso(Request $request){
        $recurso = Recurso::find($request->id);
        
        if($recurso->recurso !== $request->recurso){
            $this->validate_recurso($request);
        }
        
        \DB::beginTransaction();
        try {
            $recurso->update($this->set_recurso($request));
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json($recurso);
    }

    // VALIDAR RECURSO
    public function validate_recurso($request){
        $this->validate($request, [
            'recurso' => ['required', 'string', 'min:4', 'max:100', 'unique:recursos']
        ]);
    }

    // OBTENER RECURSOS POR LIBRO
    public function available_recursos(Request $request){
        $libro = Libro::whereId($request->libro_id)->with('recursos')->first();
        
        $ids = [];
        $libro->recursos->map(function($recurso) use(&$ids){
            $ids[] = $recurso->id;
        });

        $recursos = Recurso::where('tipo_id', $libro->tipo_id)
                            ->whereNotIn('id', $ids)
                            ->orderBy('recurso', 'asc')->get();
        return response()->json($recursos);
    }

    // GUARDAR RECURSOS EN EL LIBRO
    public function save_recursos(Request $request){
        $libro = Libro::find($request->id);
        $ids_recursos = collect($request->selected);
        
        \DB::beginTransaction();
        try {
            $ids_recursos->map(function($ir) use(&$libro){
                $libro->recursos()->attach($ir);
            });
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json(true);
    }

    // OBTENER LOS RECURSOS POR LIBRO
    public function recursos_bylibro(Request $request){
        $libro = Libro::whereId($request->libro_id)->with('recursos')->first();
        return response()->json($libro);
    }

    // ASIGNAR LINK DEL LIBRO
    public function set_link(Request $request){
        $libro = Libro::find($request->libro_id);

        \DB::beginTransaction();
        try {
            $libro->recursos()->updateExistingPivot($request->recurso_id, [
                'link' => $request->link,
            ]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json(true);
    }

    // ELIMINAR RECURSO DEL LIBRO
    public function delete_recursolibro(Request $request){
        $libro = Libro::find($request->libro_id);
        \DB::beginTransaction();
        try {
            $libro->recursos()->detach($request->recurso_id);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json(true);
    }

    // OBTENER LOS TIPOS
    public function get_tipos(){
        $tipos = \DB::table('tipos')->orderBy('tipo', 'asc')->get();
        return response()->json($tipos);
    }

    // GUARDAR ENLACES DE RECURSOS
    public function save_enlaces(Request $request){
        $libro_recurso = $this->get_librorecurso($request);
        \DB::beginTransaction();
        try {
            \DB::table('lrenlaces')->insert([
                'lr_id'     => $libro_recurso->id,
                'nombre'    => $request->nombre,
                'link'      => $request->link,
                'tipo'      => $request->tipo,
            ]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json(true);
    }

    // OBTENER LIBRO RECURSO
    public function get_librorecurso($request){
        return \DB::table('libro_recurso')
                            ->select('id')->where([
                                'libro_id' => $request->libro_id,
                                'recurso_id' => $request->recurso_id
                            ])->first();
    }

    // OBTENER ENLACES
    public function get_enlaces(Request $request){
        $libro_recurso = $this->get_librorecurso($request);
        $lrenlaces = \DB::table('lrenlaces')
                    ->where('lr_id', $libro_recurso->id)
                    ->orderBy('tipo', 'desc')->get();
        return response()->json($lrenlaces);
    }

    // ACTUALIZAR ENLACES
    public function update_enlaces(Request $request){
        \DB::beginTransaction();
        try {
            \DB::table('lrenlaces')->where('id', $request->id)
                ->update([
                    'nombre'    => $request->nombre,
                    'link'      => $request->link,
                    'tipo'      => $request->tipo,
                ]);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json(true);
    }

    // ELIMINAR ENLACES
    public function delete_enlaces(Request $request){
        \DB::beginTransaction();
        try {
            \DB::table('lrenlaces')->where('id', $request->enlace_id)
                ->delete();
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage());
        }
        return response()->json(true);
    }

    // OBTENER LIBROS POR TIPO
    public function by_tipo(Request $request){
        $libros = Libro::where('tipo_id', $request->tipo_id)
                        ->orderBy('libro', 'asc')->paginate(25);
        return response()->json($libros);
    }

    // DESCARGAR LISTA DE LIBROS
    public function download_bysearch($libro, $tipo_id){
        return Excel::download(new LibrosListExport($libro, $tipo_id), 'lista-libros.xlsx');
    }

}
