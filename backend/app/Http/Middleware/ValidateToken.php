<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No autorizado: Token inválido o expirado'
            ], 401);
        }

        $token = $request->user()->currentAccessToken();
        if ($token && $token->expires_at && $token->expires_at->diffInMinutes(now()) < 5) {
            // Refrescar el token
            $newToken = $request->user()->createToken('api-token', ['*'], now()->addHours(2));
            
            return response()->json([
                'status' => 'warning',
                'message' => 'Token a punto de expirar',
                'new_token' => $newToken->plainTextToken
            ], 200);
        }

        return $next($request);
    }
} 