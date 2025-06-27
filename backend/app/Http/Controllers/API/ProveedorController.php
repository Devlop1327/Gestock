<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProveedorRequest;
use App\Models\Proveedor;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProveedorController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $proveedores = Proveedor::all();
            return $this->successResponse($proveedores, 'Proveedores obtenidos exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener los proveedores: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProveedorRequest $request): JsonResponse
    {
        try {
            $proveedor = Proveedor::create($request->validated());
            return $this->successResponse($proveedor, 'Proveedor creado exitosamente', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al crear el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor): JsonResponse
    {
        try {
            return $this->successResponse($proveedor, 'Proveedor obtenido exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProveedorRequest $request, Proveedor $proveedor): JsonResponse
    {
        try {
            $proveedor->update($request->validated());
            return $this->successResponse($proveedor, 'Proveedor actualizado exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor): JsonResponse
    {
        try {
            $proveedor->delete();
            return $this->successResponse(null, 'Proveedor eliminado exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al eliminar el proveedor: ' . $e->getMessage());
        }
    }
}
