<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\User;

class ProductosFinalesSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            return;
        }

        $nuevosProductos = [
            [
                'codigo' => 'SW-002',
                'nombre' => 'Licencia Office 365',
                'descripcion' => 'Suscripción anual Microsoft Office 365',
                'precio' => 99.99,
                'stock' => 20,
                'stock_minimo' => 8,
                'categoria_id' => Categoria::where('nombre', 'Software')->first()->id
            ],
            [
                'codigo' => 'SW-003',
                'nombre' => 'Antivirus Premium',
                'descripcion' => 'Licencia antivirus 3 dispositivos',
                'precio' => 79.99,
                'stock' => 25,
                'stock_minimo' => 10,
                'categoria_id' => Categoria::where('nombre', 'Software')->first()->id
            ],
            [
                'codigo' => 'SW-004',
                'nombre' => 'Adobe Creative Cloud',
                'descripcion' => 'Suite completa Adobe CC anual',
                'precio' => 599.99,
                'stock' => 10,
                'stock_minimo' => 5,
                'categoria_id' => Categoria::where('nombre', 'Software')->first()->id
            ],
            [
                'codigo' => 'SW-005',
                'nombre' => 'AutoCAD 2024',
                'descripcion' => 'Licencia AutoCAD última versión',
                'precio' => 1499.99,
                'stock' => 8,
                'stock_minimo' => 3,
                'categoria_id' => Categoria::where('nombre', 'Software')->first()->id
            ],
            [
                'codigo' => 'SW-006',
                'nombre' => 'Windows 11 Pro',
                'descripcion' => 'Licencia Windows 11 Professional',
                'precio' => 199.99,
                'stock' => 15,
                'stock_minimo' => 6,
                'categoria_id' => Categoria::where('nombre', 'Software')->first()->id
            ],

            // Periféricos (5 productos adicionales)
            [
                'codigo' => 'PER-002',
                'nombre' => 'Monitor 27" 4K',
                'descripcion' => 'Monitor UHD IPS 27 pulgadas',
                'precio' => 399.99,
                'stock' => 12,
                'stock_minimo' => 4,
                'categoria_id' => Categoria::where('nombre', 'Periféricos')->first()->id
            ],
            [
                'codigo' => 'PER-003',
                'nombre' => 'Teclado Mecánico RGB',
                'descripcion' => 'Teclado gaming switches Cherry MX',
                'precio' => 129.99,
                'stock' => 18,
                'stock_minimo' => 6,
                'categoria_id' => Categoria::where('nombre', 'Periféricos')->first()->id
            ],
            [
                'codigo' => 'PER-004',
                'nombre' => 'Auriculares Gaming',
                'descripcion' => 'Auriculares 7.1 con micrófono',
                'precio' => 89.99,
                'stock' => 22,
                'stock_minimo' => 8,
                'categoria_id' => Categoria::where('nombre', 'Periféricos')->first()->id
            ],
            [
                'codigo' => 'PER-005',
                'nombre' => 'Webcam 1080p',
                'descripcion' => 'Cámara web Full HD con autofocus',
                'precio' => 69.99,
                'stock' => 25,
                'stock_minimo' => 10,
                'categoria_id' => Categoria::where('nombre', 'Periféricos')->first()->id
            ],
            [
                'codigo' => 'PER-006',
                'nombre' => 'Dock Station USB-C',
                'descripcion' => 'Estación de acoplamiento universal',
                'precio' => 159.99,
                'stock' => 15,
                'stock_minimo' => 5,
                'categoria_id' => Categoria::where('nombre', 'Periféricos')->first()->id
            ],

            // Mobiliario (5 productos adicionales)
            [
                'codigo' => 'MOB-002',
                'nombre' => 'Silla Ergonómica',
                'descripcion' => 'Silla de oficina premium ajustable',
                'precio' => 299.99,
                'stock' => 10,
                'stock_minimo' => 4,
                'categoria_id' => Categoria::where('nombre', 'Mobiliario')->first()->id
            ],
            [
                'codigo' => 'MOB-003',
                'nombre' => 'Archivador Metálico',
                'descripcion' => 'Archivador 4 cajones con llave',
                'precio' => 199.99,
                'stock' => 8,
                'stock_minimo' => 3,
                'categoria_id' => Categoria::where('nombre', 'Mobiliario')->first()->id
            ],
            [
                'codigo' => 'MOB-004',
                'nombre' => 'Mesa de Conferencias',
                'descripcion' => 'Mesa para 8 personas con conexiones',
                'precio' => 599.99,
                'stock' => 5,
                'stock_minimo' => 2,
                'categoria_id' => Categoria::where('nombre', 'Mobiliario')->first()->id
            ],
            [
                'codigo' => 'MOB-005',
                'nombre' => 'Estantería Modular',
                'descripcion' => 'Estantería ajustable 5 niveles',
                'precio' => 149.99,
                'stock' => 12,
                'stock_minimo' => 4,
                'categoria_id' => Categoria::where('nombre', 'Mobiliario')->first()->id
            ],
            [
                'codigo' => 'MOB-006',
                'nombre' => 'Biombo Divisor',
                'descripcion' => 'Separador de espacios móvil',
                'precio' => 179.99,
                'stock' => 8,
                'stock_minimo' => 3,
                'categoria_id' => Categoria::where('nombre', 'Mobiliario')->first()->id
            ]
        ];

        $proveedores = Proveedor::all();

        foreach ($nuevosProductos as $productoData) {
            // Asignar un proveedor aleatorio
            $productoData['proveedor_id'] = $proveedores->random()->id;
            $productoData['activo'] = true;
            $productoData['user_id'] = $admin ? $admin->id : null;
            Producto::firstOrCreate(
                ['codigo' => $productoData['codigo']],
                $productoData
            );
        }
    }
} 