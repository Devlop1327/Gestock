<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\User;

class ProductosAdicionalesSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            return;
        }

        $nuevosProductos = [
            [
                'codigo' => 'ELEC011', 'nombre' => 'Smartwatch Pro', 'descripcion' => 'Reloj inteligente con monitor cardíaco',
                'precio' => 149.99, 'stock' => 25, 'stock_minimo' => 8, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC012', 'nombre' => 'Cámara DSLR', 'descripcion' => 'Cámara profesional 24MP',
                'precio' => 699.99, 'stock' => 15, 'stock_minimo' => 5, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC013', 'nombre' => 'Altavoz Bluetooth', 'descripcion' => 'Altavoz portátil resistente al agua',
                'precio' => 79.99, 'stock' => 30, 'stock_minimo' => 10, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC014', 'nombre' => 'Batería Externa', 'descripcion' => '20000mAh con carga rápida',
                'precio' => 45.99, 'stock' => 40, 'stock_minimo' => 15, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC015', 'nombre' => 'Mouse Gaming', 'descripcion' => 'Mouse RGB 16000 DPI',
                'precio' => 59.99, 'stock' => 35, 'stock_minimo' => 12, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC016', 'nombre' => 'Disco Duro SSD', 'descripcion' => 'SSD 1TB NVMe',
                'precio' => 129.99, 'stock' => 20, 'stock_minimo' => 8, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC017', 'nombre' => 'Webcam 4K', 'descripcion' => 'Webcam profesional con micrófono',
                'precio' => 89.99, 'stock' => 25, 'stock_minimo' => 10, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC018', 'nombre' => 'Teclado Inalámbrico', 'descripcion' => 'Teclado compacto con touchpad',
                'precio' => 49.99, 'stock' => 30, 'stock_minimo' => 12, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC019', 'nombre' => 'Adaptador USB-C', 'descripcion' => 'Hub multipuerto USB-C',
                'precio' => 39.99, 'stock' => 45, 'stock_minimo' => 15, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC020', 'nombre' => 'Proyector LED', 'descripcion' => 'Proyector HD portátil',
                'precio' => 299.99, 'stock' => 15, 'stock_minimo' => 5, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC021', 'nombre' => 'Micrófono USB', 'descripcion' => 'Micrófono condensador para streaming',
                'precio' => 69.99, 'stock' => 20, 'stock_minimo' => 8, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC022', 'nombre' => 'Tarjeta de Memoria', 'descripcion' => 'SD 256GB Clase 10',
                'precio' => 34.99, 'stock' => 50, 'stock_minimo' => 20, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC023', 'nombre' => 'Soporte Laptop', 'descripcion' => 'Soporte ergonómico ajustable',
                'precio' => 29.99, 'stock' => 40, 'stock_minimo' => 15, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC024', 'nombre' => 'Cargador Inalámbrico', 'descripcion' => 'Cargador Qi 15W',
                'precio' => 24.99, 'stock' => 35, 'stock_minimo' => 12, 'categoria_id' => 1
            ],
            [
                'codigo' => 'ELEC025', 'nombre' => 'Ventilador USB', 'descripcion' => 'Ventilador silencioso para laptop',
                'precio' => 19.99, 'stock' => 45, 'stock_minimo' => 18, 'categoria_id' => 1
            ],

            // Alimentos (12 productos adicionales)
            [
                'codigo' => 'ALI004', 'nombre' => 'Quinua Orgánica', 'descripcion' => 'Quinua blanca orgánica 1kg',
                'precio' => 8.99, 'stock' => 60, 'stock_minimo' => 20, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI005', 'nombre' => 'Miel Natural', 'descripcion' => 'Miel de abeja pura 500g',
                'precio' => 12.99, 'stock' => 40, 'stock_minimo' => 15, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI006', 'nombre' => 'Café Gourmet', 'descripcion' => 'Café tostado en grano 250g',
                'precio' => 14.99, 'stock' => 50, 'stock_minimo' => 18, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI007', 'nombre' => 'Aceite de Coco', 'descripcion' => 'Aceite de coco extra virgen 500ml',
                'precio' => 16.99, 'stock' => 35, 'stock_minimo' => 12, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI008', 'nombre' => 'Granola Premium', 'descripcion' => 'Granola con frutos secos 400g',
                'precio' => 9.99, 'stock' => 45, 'stock_minimo' => 15, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI009', 'nombre' => 'Té Verde', 'descripcion' => 'Té verde orgánico 50 bolsitas',
                'precio' => 7.99, 'stock' => 70, 'stock_minimo' => 25, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI010', 'nombre' => 'Almendras', 'descripcion' => 'Almendras naturales 500g',
                'precio' => 13.99, 'stock' => 40, 'stock_minimo' => 15, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI011', 'nombre' => 'Avena Integral', 'descripcion' => 'Avena en hojuelas 1kg',
                'precio' => 4.99, 'stock' => 80, 'stock_minimo' => 30, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI012', 'nombre' => 'Chocolate Oscuro', 'descripcion' => 'Chocolate 70% cacao 100g',
                'precio' => 6.99, 'stock' => 55, 'stock_minimo' => 20, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI013', 'nombre' => 'Mermelada Artesanal', 'descripcion' => 'Mermelada de fresa 250g',
                'precio' => 5.99, 'stock' => 45, 'stock_minimo' => 15, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI014', 'nombre' => 'Nueces', 'descripcion' => 'Nueces peladas 300g',
                'precio' => 11.99, 'stock' => 35, 'stock_minimo' => 12, 'categoria_id' => 2
            ],
            [
                'codigo' => 'ALI015', 'nombre' => 'Aceitunas', 'descripcion' => 'Aceitunas negras 500g',
                'precio' => 8.99, 'stock' => 40, 'stock_minimo' => 15, 'categoria_id' => 2
            ],

            // Limpieza (12 productos adicionales)
            [
                'codigo' => 'LIM003', 'nombre' => 'Desinfectante Multi', 'descripcion' => 'Desinfectante multiusos 1L',
                'precio' => 7.99, 'stock' => 65, 'stock_minimo' => 20, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM004', 'nombre' => 'Jabón Líquido', 'descripcion' => 'Jabón líquido antibacterial 500ml',
                'precio' => 5.99, 'stock' => 75, 'stock_minimo' => 25, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM005', 'nombre' => 'Esponjas', 'descripcion' => 'Pack 3 esponjas multiuso',
                'precio' => 3.99, 'stock' => 100, 'stock_minimo' => 30, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM006', 'nombre' => 'Limpiador Baño', 'descripcion' => 'Limpiador especializado baño 750ml',
                'precio' => 6.99, 'stock' => 55, 'stock_minimo' => 18, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM007', 'nombre' => 'Paños Microfibra', 'descripcion' => 'Set 5 paños microfibra',
                'precio' => 8.99, 'stock' => 45, 'stock_minimo' => 15, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM008', 'nombre' => 'Ambientador', 'descripcion' => 'Ambientador automático',
                'precio' => 12.99, 'stock' => 40, 'stock_minimo' => 12, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM009', 'nombre' => 'Guantes Limpieza', 'descripcion' => 'Guantes de látex talla M',
                'precio' => 4.99, 'stock' => 80, 'stock_minimo' => 25, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM010', 'nombre' => 'Escoba', 'descripcion' => 'Escoba multiángulo',
                'precio' => 9.99, 'stock' => 35, 'stock_minimo' => 10, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM011', 'nombre' => 'Recogedor', 'descripcion' => 'Recogedor con mango largo',
                'precio' => 7.99, 'stock' => 40, 'stock_minimo' => 12, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM012', 'nombre' => 'Limpia Muebles', 'descripcion' => 'Spray limpiador de muebles 400ml',
                'precio' => 6.99, 'stock' => 50, 'stock_minimo' => 15, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM013', 'nombre' => 'Desengrasante', 'descripcion' => 'Desengrasante cocina 500ml',
                'precio' => 8.99, 'stock' => 45, 'stock_minimo' => 15, 'categoria_id' => 3
            ],
            [
                'codigo' => 'LIM014', 'nombre' => 'Limpia Alfombras', 'descripcion' => 'Limpiador de alfombras 750ml',
                'precio' => 11.99, 'stock' => 30, 'stock_minimo' => 10, 'categoria_id' => 3
            ],

            // Papelería (11 productos adicionales)
            [
                'codigo' => 'PAP003', 'nombre' => 'Cuaderno A5', 'descripcion' => 'Cuaderno espiral 100 hojas',
                'precio' => 4.99, 'stock' => 80, 'stock_minimo' => 25, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP004', 'nombre' => 'Marcadores', 'descripcion' => 'Set 12 marcadores permanentes',
                'precio' => 7.99, 'stock' => 60, 'stock_minimo' => 20, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP005', 'nombre' => 'Clips', 'descripcion' => 'Caja 100 clips metálicos',
                'precio' => 2.99, 'stock' => 100, 'stock_minimo' => 30, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP006', 'nombre' => 'Carpeta Archivador', 'descripcion' => 'Archivador A4 lomo ancho',
                'precio' => 5.99, 'stock' => 50, 'stock_minimo' => 15, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP007', 'nombre' => 'Post-it', 'descripcion' => 'Pack 5 blocks notas adhesivas',
                'precio' => 6.99, 'stock' => 70, 'stock_minimo' => 20, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP008', 'nombre' => 'Grapadora', 'descripcion' => 'Grapadora metálica',
                'precio' => 8.99, 'stock' => 40, 'stock_minimo' => 12, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP009', 'nombre' => 'Tijeras', 'descripcion' => 'Tijeras oficina 8 pulgadas',
                'precio' => 4.99, 'stock' => 45, 'stock_minimo' => 15, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP010', 'nombre' => 'Lápices', 'descripcion' => 'Set 12 lápices grafito',
                'precio' => 3.99, 'stock' => 85, 'stock_minimo' => 25, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP011', 'nombre' => 'Correctores', 'descripcion' => 'Pack 3 correctores líquidos',
                'precio' => 5.99, 'stock' => 60, 'stock_minimo' => 18, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP012', 'nombre' => 'Folder Manila', 'descripcion' => 'Pack 25 folders A4',
                'precio' => 7.99, 'stock' => 55, 'stock_minimo' => 20, 'categoria_id' => 4
            ],
            [
                'codigo' => 'PAP013', 'nombre' => 'Regla', 'descripcion' => 'Regla metálica 30cm',
                'precio' => 3.99, 'stock' => 65, 'stock_minimo' => 20, 'categoria_id' => 4
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