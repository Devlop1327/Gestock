@component('mail::message')
# Alerta: Stock Bajo

El producto **{{ $producto->nombre }}** (Código: {{ $producto->codigo }}) ha alcanzado un nivel de stock crítico.

## Detalles del Producto
- **Stock Actual:** {{ $stockActual }}
- **Stock Mínimo:** {{ $stockMinimo }}
- **Categoría:** {{ $producto->categoria->nombre }}
- **Proveedor:** {{ $producto->proveedor->nombre }}

@component('mail::button', ['url' => url('/productos/' . $producto->id)])
Ver Detalles del Producto
@endcomponent

Por favor, tome las medidas necesarias para reponer el stock.

Gracias,<br>
{{ config('app.name') }}
@endcomponent 