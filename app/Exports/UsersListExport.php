<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Acceso;
use App\User;

class UsersListExport implements FromView
{
    use Exportable;

    protected $role_id;
    protected $libro_id;
    protected $estado;

    public function __construct($role_id, $libro_id, $estado)
    {
        $this->role_id = $role_id;
        $this->libro_id = $libro_id;
        $this->estado = $estado;
    }

    public function view(): View
    {
        if($this->estado == 'null') {
            $users = $this->bylibro();
        } else {
            $users = $this->byestado();
        }

        return view('download.users-list', [
            'users' => $users
        ]);
    }

    public function bylibro(){
        $accesos = Acceso::select('user_id')
                ->where('libro_id', $this->libro_id)->get();
        
        $ids = [];
        $accesos->map(function($acceso) use(&$ids){
            $ids[] = $acceso->user_id;
        });

        return User::where('role_id', $this->role_id)
                ->whereIn('id', $ids)
                ->with('acceso.libro')
                ->orderBy('created_at', 'desc')->get();
    }

    public function byestado(){
        return User::where('role_id', $this->role_id)
                ->where('estado', $this->estado)
                ->with('acceso.libro')
                ->orderBy('created_at', 'desc')->get();
    }
}
