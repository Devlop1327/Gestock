<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;

class CategoriaProductoSeeder extends Seeder
{
    public function run()
    {
        // Crear proveedores
        $proveedores = [
            [
                'nombre' => 'TechMaster Electronics',
                'nit' => '20111111111',
                'direccion' => 'Av. Tecnología 123',
                'telefono' => '987654321',
                'email' => 'ventas@techmaster.com',
                'contacto' => 'Roberto García'
            ],
            [
                'nombre' => 'Fashion Trends S.A.',
                'nit' => '20222222222',
                'direccion' => 'Calle Moda 456',
                'telefono' => '987123456',
                'email' => 'contacto@fashiontrends.com',
                'contacto' => 'Ana López'
            ],
            [
                'nombre' => 'Home & Living Import',
                'nit' => '20333333333',
                'direccion' => 'Jr. Hogar 789',
                'telefono' => '999888777',
                'email' => 'ventas@homeandliving.com',
                'contacto' => 'Carlos Ruiz'
            ],
            [
                'nombre' => 'Sports Elite Corp',
                'nit' => '20444444444',
                'direccion' => 'Av. Deportes 234',
                'telefono' => '966555444',
                'email' => 'info@sportselite.com',
                'contacto' => 'Diego Torres'
            ],
            [
                'nombre' => 'Global Books & Media',
                'nit' => '20555555555',
                'direccion' => 'Av. Cultura 567',
                'telefono' => '944333222',
                'email' => 'ventas@globalbooks.com',
                'contacto' => 'María Sánchez'
            ]
        ];

        $proveedoresCreados = [];
        foreach ($proveedores as $proveedorData) {
            $proveedoresCreados[] = Proveedor::create($proveedorData);
        }

        // Categorías y productos
        $categorias = [
            [
                'nombre' => 'Electrónicos',
                'descripcion' => 'Dispositivos y accesorios electrónicos',
                'productos' => [
                    ['codigo' => 'ELEC001', 'nombre' => 'Laptop HP', 'descripcion' => 'Laptop HP 15.6" Core i5', 'precio' => 799.99, 'stock' => 10, 'stock_minimo' => 5],
                    ['codigo' => 'ELEC002', 'nombre' => 'Smartphone Samsung', 'descripcion' => 'Samsung Galaxy S21', 'precio' => 699.99, 'stock' => 15, 'stock_minimo' => 5],
                    ['codigo' => 'ELEC003', 'nombre' => 'Tablet Apple', 'descripcion' => 'iPad Pro 11"', 'precio' => 899.99, 'stock' => 8, 'stock_minimo' => 3],
                    ['codigo' => 'ELEC004', 'nombre' => 'Auriculares Sony', 'descripcion' => 'Auriculares inalámbricos', 'precio' => 199.99, 'stock' => 20, 'stock_minimo' => 8],
                    ['codigo' => 'ELEC005', 'nombre' => 'Smart TV LG', 'descripcion' => 'TV LED 55" 4K', 'precio' => 599.99, 'stock' => 5, 'stock_minimo' => 2],
                    ['codigo' => 'ELEC006', 'nombre' => 'Monitor Gaming', 'descripcion' => 'Monitor 27" 144Hz', 'precio' => 299.99, 'stock' => 12, 'stock_minimo' => 4],
                    ['codigo' => 'ELEC007', 'nombre' => 'Teclado Mecánico', 'descripcion' => 'Teclado RGB Gaming', 'precio' => 89.99, 'stock' => 25, 'stock_minimo' => 8],
                    ['codigo' => 'ELEC008', 'nombre' => 'Webcam HD', 'descripcion' => 'Webcam 1080p con micrófono', 'precio' => 49.99, 'stock' => 30, 'stock_minimo' => 10],
                    ['codigo' => 'ELEC009', 'nombre' => 'Router WiFi', 'descripcion' => 'Router Dual Band AC1200', 'precio' => 79.99, 'stock' => 18, 'stock_minimo' => 6],
                    ['codigo' => 'ELEC010', 'nombre' => 'Impresora Láser', 'descripcion' => 'Impresora B/N Duplex', 'precio' => 199.99, 'stock' => 15, 'stock_minimo' => 5]
                ]
            ],
            [
                'nombre' => 'Ropa',
                'descripcion' => 'Vestimenta y accesorios de moda',
                'productos' => [
                    ['codigo' => 'ROPA001', 'nombre' => 'Camisa Casual', 'descripcion' => 'Camisa manga larga', 'precio' => 29.99, 'stock' => 50, 'stock_minimo' => 15],
                    ['codigo' => 'ROPA002', 'nombre' => 'Jeans Clásicos', 'descripcion' => 'Jeans azul oscuro', 'precio' => 39.99, 'stock' => 40, 'stock_minimo' => 10],
                    ['codigo' => 'ROPA003', 'nombre' => 'Vestido Elegante', 'descripcion' => 'Vestido negro cocktail', 'precio' => 79.99, 'stock' => 25, 'stock_minimo' => 8],
                    ['codigo' => 'ROPA004', 'nombre' => 'Zapatos Deportivos', 'descripcion' => 'Zapatillas running', 'precio' => 59.99, 'stock' => 30, 'stock_minimo' => 10],
                    ['codigo' => 'ROPA005', 'nombre' => 'Chaqueta Invierno', 'descripcion' => 'Chaqueta impermeable', 'precio' => 89.99, 'stock' => 20, 'stock_minimo' => 5],
                    ['codigo' => 'ROPA006', 'nombre' => 'Blazer Formal', 'descripcion' => 'Blazer negro elegante', 'precio' => 99.99, 'stock' => 15, 'stock_minimo' => 5],
                    ['codigo' => 'ROPA007', 'nombre' => 'Pantalón Cargo', 'descripcion' => 'Pantalón estilo militar', 'precio' => 49.99, 'stock' => 30, 'stock_minimo' => 10],
                    ['codigo' => 'ROPA008', 'nombre' => 'Sudadera Deportiva', 'descripcion' => 'Sudadera con capucha', 'precio' => 39.99, 'stock' => 40, 'stock_minimo' => 12],
                    ['codigo' => 'ROPA009', 'nombre' => 'Falda Plisada', 'descripcion' => 'Falda midi elegante', 'precio' => 45.99, 'stock' => 20, 'stock_minimo' => 8],
                    ['codigo' => 'ROPA010', 'nombre' => 'Abrigo Invierno', 'descripcion' => 'Abrigo largo de lana', 'precio' => 129.99, 'stock' => 10, 'stock_minimo' => 4]
                ]
            ],
            [
                'nombre' => 'Belleza',
                'descripcion' => 'Productos de belleza y cuidado personal',
                'productos' => [
                    ['codigo' => 'BELL001', 'nombre' => 'Perfume Floral', 'descripcion' => 'Fragancia femenina 100ml', 'precio' => 69.99, 'stock' => 25, 'stock_minimo' => 8],
                    ['codigo' => 'BELL002', 'nombre' => 'Set Maquillaje', 'descripcion' => 'Kit completo de maquillaje', 'precio' => 89.99, 'stock' => 20, 'stock_minimo' => 6],
                    ['codigo' => 'BELL003', 'nombre' => 'Crema Facial', 'descripcion' => 'Crema hidratante anti-edad', 'precio' => 34.99, 'stock' => 30, 'stock_minimo' => 10],
                    ['codigo' => 'BELL004', 'nombre' => 'Máscara Pestañas', 'descripcion' => 'Máscara volumen extremo', 'precio' => 19.99, 'stock' => 40, 'stock_minimo' => 12],
                    ['codigo' => 'BELL005', 'nombre' => 'Aceite Corporal', 'descripcion' => 'Aceite hidratante natural', 'precio' => 24.99, 'stock' => 35, 'stock_minimo' => 10]
                ]
            ],
            [
                'nombre' => 'Mascotas',
                'descripcion' => 'Productos para mascotas',
                'productos' => [
                    ['codigo' => 'MASC001', 'nombre' => 'Alimento Premium', 'descripcion' => 'Alimento perro 15kg', 'precio' => 49.99, 'stock' => 30, 'stock_minimo' => 8],
                    ['codigo' => 'MASC002', 'nombre' => 'Cama Mascota', 'descripcion' => 'Cama grande acolchada', 'precio' => 39.99, 'stock' => 20, 'stock_minimo' => 5],
                    ['codigo' => 'MASC003', 'nombre' => 'Juguete Interactivo', 'descripcion' => 'Juguete dispensador', 'precio' => 14.99, 'stock' => 40, 'stock_minimo' => 12],
                    ['codigo' => 'MASC004', 'nombre' => 'Arena Gato', 'descripcion' => 'Arena aglomerante 10kg', 'precio' => 19.99, 'stock' => 35, 'stock_minimo' => 10],
                    ['codigo' => 'MASC005', 'nombre' => 'Collar Ajustable', 'descripcion' => 'Collar con placa ID', 'precio' => 9.99, 'stock' => 50, 'stock_minimo' => 15]
                ]
            ]
        ];

        // Crear categorías y productos
        foreach ($categorias as $categoriaData) {
            $productos = $categoriaData['productos'];
            unset($categoriaData['productos']);
            // Buscar la categoría existente por nombre
            $categoria = Categoria::where('nombre', $categoriaData['nombre'])->first();
            if (!$categoria) {
                // Si no existe, la omitimos para evitar duplicados
                continue;
            }
            foreach ($productos as $producto) {
                // Asignar un proveedor aleatorio
                $producto['proveedor_id'] = $proveedoresCreados[array_rand($proveedoresCreados)]->id;
                $categoria->productos()->create($producto);
            }
        }
    }
}