<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{

    // public function showLoginForm()
    // {
    //     // Deshabilitado para solo API RESTful
    //     // return view('auth.login');
    // }


    // public function login(Request $request)
    // {
    //     // Deshabilitado para solo API RESTful
    // }


    // public function logout(Request $request)
    // {
    //     // Deshabilitado para solo API RESTful
    // }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas. Por favor, intente nuevamente.'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ]);
    }
}
