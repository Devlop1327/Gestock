<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfiguracionController extends Controller
{
    public function index()
    {
        return view('configuracion.index', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'theme' => ['nullable', 'string', 'in:light,dark'],
            'notifications_enabled' => ['required', 'boolean'],
        ]);

        // Convertir el valor del checkbox a booleano
        $notificationsEnabled = filter_var($request->notifications_enabled, FILTER_VALIDATE_BOOLEAN);

        // Guardar las preferencias del usuario
        $user->update([
            'theme' => $request->theme,
            'notifications_enabled' => $notificationsEnabled,
        ]);

        return redirect()->route('configuracion.index')->with('success', 'Configuración actualizada correctamente.');
    }
} 