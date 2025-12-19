<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login'); // Redirige si no está autenticado
        }

        // Suponiendo que tu modelo User tiene un campo 'role'
        // Ajusta esta lógica según cómo gestiones los roles (ej. campo 'role' simple o relación con tabla de roles)
        // if (!in_array(Auth::user()->role->role, $roles)) {
        //     abort(403, 'Acceso no autorizado.'); // Muestra un error 403 si el rol no coincide
        // }
        if(auth()->check() && auth()->user()->role->role != $role){
            abort(401, __("No puedes acceder a este sitio"));
        } 

        return $next($request);
    }
}
