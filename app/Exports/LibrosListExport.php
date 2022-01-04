<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Libro;

class LibrosListExport implements FromView
{
    use Exportable;

    protected $libro;
    protected $tipo_id;

    public function __construct($libro, $tipo_id)
    {
        $this->libro = $libro;
        $this->tipo_id = $tipo_id;
    }

    public function view(): View
    {
        if($this->tipo_id == 'null') {
            $libros = $this->bylibro();
        } else {
            $libros = $this->bytipo();
        }

        return view('download.libros-list', [
            'libros' => $libros
        ]);
    }

    public function bylibro(){
        return Libro::where('libro', 'like', '%'.$this->libro.'%')
                        ->orderBy('libro', 'asc')->get();
    }

    public function bytipo(){
        return Libro::where('tipo_id', $this->tipo_id)
                        ->orderBy('libro', 'asc')->get();
    }
}
