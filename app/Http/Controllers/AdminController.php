<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // OBTENER VISTA DE USUARIOS
    public function usuarios(){
        return view('admin.usuarios');
    }

    // OBTENER VISTA DE LIBROS
    public function libros(){
        return view('admin.libros');
    }
}
