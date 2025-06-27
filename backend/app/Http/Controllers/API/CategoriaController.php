<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $categorias = Categoria::all();
            return $this->successResponse($categorias, 'Categorías obtenidas exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener las categorías: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaRequest $request): JsonResponse
    {
        try {
            $categoria = Categoria::create($request->validated());
            return $this->successResponse($categoria, 'Categoría creada exitosamente', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al crear la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria): JsonResponse
    {
        try {
            return $this->successResponse($categoria, 'Categoría obtenida exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaRequest $request, Categoria $categoria): JsonResponse
    {
        try {
            $categoria->update($request->validated());
            return $this->successResponse($categoria, 'Categoría actualizada exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria): JsonResponse
    {
        try {
            $categoria->delete();
            return $this->successResponse(null, 'Categoría eliminada exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
}
