<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordChangedNotification;

class AdminUserController extends Controller
{
    // Cambiar contraseña de usuario por admin
    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        // Enviar notificación por correo
        Mail::to($user->email)->send(new PasswordChangedNotification($user));

        return response()->json(['message' => 'Contraseña actualizada y notificación enviada.']);
    }
}
