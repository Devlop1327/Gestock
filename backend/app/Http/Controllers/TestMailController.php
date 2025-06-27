<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function send()
    {
        Mail::raw('Este es un correo de prueba desde Mailtrap y Laravel.', function ($message) {
            $message->to('test@example.com')
                    ->subject('Correo de prueba Mailtrap');
        });
        return 'Correo enviado (revisa tu inbox de Mailtrap)';
    }

    public function sendHelloWorld()
    {
        Mail::raw('Hola mundo', function ($message) {
            $message->to('test@example.com')
                    ->subject('Hola Mundo desde Mailtrap');
        });
        return 'Correo "Hola mundo" enviado (revisa tu inbox de Mailtrap)';
    }
}
