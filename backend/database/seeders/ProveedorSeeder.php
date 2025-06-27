<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'TechSolutions S.A.',
                'nit' => '20123456789',
                'direccion' => 'Av. Principal 123, Lima',
                'telefono' => '01-333-4444',
                'email' => 'contacto@techsolutions.com',
                'contacto' => 'Juan Pérez',
                'activo' => true
            ],
            [
                'nombre' => 'Office Supplies EIRL',
                'nit' => '20987654321',
                'direccion' => 'Jr. Secundario 456, Lima',
                'telefono' => '01-555-6666',
                'email' => 'ventas@officesupplies.com',
                'contacto' => 'María García',
                'activo' => true
            ],
            [
                'nombre' => 'Furniture World SAC',
                'nit' => '20678912345',
                'direccion' => 'Av. Industrial 789, Lima',
                'telefono' => '01-777-8888',
                'email' => 'info@furnitureworld.com',
                'contacto' => 'Carlos López',
                'activo' => true
            ],
            [
                'nombre' => 'Software Solutions Perú',
                'nit' => '20345678912',
                'direccion' => 'Av. Tecnológica 321, Lima',
                'telefono' => '01-999-0000',
                'email' => 'soporte@softwaresolutions.com',
                'contacto' => 'Ana Torres',
                'activo' => true
            ],
            [
                'nombre' => 'Peripherals Plus SAC',
                'nit' => '20789123456',
                'direccion' => 'Jr. Comercial 654, Lima',
                'telefono' => '01-222-3333',
                'email' => 'ventas@peripheralsplus.com',
                'contacto' => 'Roberto Sánchez',
                'activo' => true
            ]
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::firstOrCreate(
                ['nit' => $proveedor['nit']],
                $proveedor
            );
        }
    }
}
