<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Retorna una respuesta exitosa
     */
    protected function successResponse($data, $message = null, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Retorna una respuesta de error
     */
    protected function errorResponse($message, $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    /**
     * Retorna una respuesta de recurso no encontrado
     */
    protected function notFoundResponse($message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Retorna una respuesta de no autorizado
     */
    protected function unauthorizedResponse($message = 'No autorizado'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Retorna una respuesta de validación fallida
     */
    protected function validationErrorResponse($errors): JsonResponse
    {
        return $this->errorResponse('Error de validación', 422, $errors);
    }
} 