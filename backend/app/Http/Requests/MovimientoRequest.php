<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovimientoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo' => 'required|in:entrada,salida',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'fecha' => 'required|date',
            'motivo' => 'required|string|max:500',
            'observaciones' => 'nullable|string|max:1000',
            'usuario_id' => 'required|exists:users,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tipo.required' => 'El tipo de movimiento es obligatorio',
            'tipo.in' => 'El tipo de movimiento debe ser entrada o salida',
            'producto_id.required' => 'El producto es obligatorio',
            'producto_id.exists' => 'El producto seleccionado no existe',
            'cantidad.required' => 'La cantidad es obligatoria',
            'cantidad.integer' => 'La cantidad debe ser un número entero',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.date' => 'La fecha debe ser una fecha válida',
            'motivo.required' => 'El motivo es obligatorio',
            'motivo.max' => 'El motivo no puede exceder los 500 caracteres',
            'observaciones.max' => 'Las observaciones no pueden exceder los 1000 caracteres',
            'usuario_id.required' => 'El usuario es obligatorio',
            'usuario_id.exists' => 'El usuario seleccionado no existe'
        ];
    }
} 