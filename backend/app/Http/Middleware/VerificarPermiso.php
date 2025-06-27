<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarPermiso
{
    public function handle(Request $request, Closure $next, $permiso): Response
    {
        if (!$request->user() || !$request->user()->tienePermiso($permiso)) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        return $next($request);
    }
} 