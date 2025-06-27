<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function uploadProductImage(Request $request, Producto $producto)
    {
        $validator = Validator::make($request->all(), [
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::delete('public/productos/' . $producto->imagen);
            }

            $file = $request->file('imagen');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            
            $path = $file->storeAs('public/productos', $filename);
            
            $producto->update(['imagen' => $filename]);

            return response()->json([
                'message' => 'Imagen subida correctamente',
                'path' => Storage::url($path)
            ]);
        }

        return response()->json(['error' => 'No se proporcionó ninguna imagen'], 400);
    }

    public function deleteProductImage(Producto $producto)
    {
        if ($producto->imagen) {
            Storage::delete('public/productos/' . $producto->imagen);
            $producto->update(['imagen' => null]);
            
            return response()->json(['message' => 'Imagen eliminada correctamente']);
        }

        return response()->json(['error' => 'El producto no tiene imagen'], 404);
    }

    public function getProductImage(Producto $producto)
    {
        if (!$producto->imagen) {
            return response()->json(['error' => 'El producto no tiene imagen'], 404);
        }

        $path = 'public/productos/' . $producto->imagen;
        
        if (!Storage::exists($path)) {
            return response()->json(['error' => 'La imagen no existe'], 404);
        }

        return response()->json([
            'url' => Storage::url($path)
        ]);
    }
} 