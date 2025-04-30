<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse // ligne ajoutée 29/04
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est un admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // L'utilisateur peut accéder à la route
        }

        // Sinon, rediriger vers la page précédente ou une autre page
        return redirect()->route('access.denied')->with('error', 'Accès réservé aux administrateurs.');   
     }
}
