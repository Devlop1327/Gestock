<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function limpiarCache(): JsonResponse
    {
        Cache::flush();
        return response()->json([
            'success' => true,
            'message' => 'Caché limpiada correctamente.'
        ]);
    }
}
