<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
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
            'nombre' => 'required|string|max:100',
            'nit' => 'required|string|max:20|unique:proveedores,nit,' . $this->proveedor,
            'direccion' => 'required|string|max:200',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'contacto' => 'nullable|string|max:100',
            'activo' => 'boolean'
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
            'nombre.required' => 'El nombre del proveedor es obligatorio',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres',
            'nit.required' => 'El NIT es obligatorio',
            'nit.max' => 'El NIT no puede exceder los 20 caracteres',
            'nit.unique' => 'Ya existe un proveedor con este NIT',
            'direccion.required' => 'La dirección es obligatoria',
            'direccion.max' => 'La dirección no puede exceder los 200 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede exceder los 20 caracteres',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser una dirección válida',
            'email.max' => 'El email no puede exceder los 100 caracteres',
            'contacto.max' => 'El nombre del contacto no puede exceder los 100 caracteres',
            'activo.boolean' => 'El estado activo debe ser verdadero o falso'
        ];
    }
} 