# Documentación de la API

## Autenticación

### Registro de Usuario
```http
POST /api/register
```

**Body:**
```json
{
    "name": "string",
    "email": "string",
    "password": "string",
    "password_confirmation": "string"
}
```

### Inicio de Sesión
```http
POST /api/login
```

**Body:**
```json
{
    "email": "string",
    "password": "string"
}
```

### Cierre de Sesión
```http
POST /api/logout
```

**Headers:**
```
Authorization: Bearer {token}
```

## Productos

### Listar Productos
```http
GET /api/productos
```

**Query Parameters:**
- `search`: Búsqueda por nombre o código
- `categoria_id`: Filtrar por categoría
- `proveedor_id`: Filtrar por proveedor
- `stock_bajo`: Filtrar productos con stock bajo (1)

### Crear Producto
```http
POST /api/productos
```

**Body:**
```json
{
    "codigo": "string",
    "nombre": "string",
    "descripcion": "string",
    "precio": "number",
    "stock": "integer",
    "stock_minimo": "integer",
    "categoria_id": "integer",
    "proveedor_id": "integer"
}
```

### Actualizar Producto
```http
PUT /api/productos/{id}
```

**Body:**
```json
{
    "nombre": "string",
    "descripcion": "string",
    "precio": "number",
    "stock": "integer",
    "stock_minimo": "integer",
    "categoria_id": "integer",
    "proveedor_id": "integer"
}
```

### Eliminar Producto
```http
DELETE /api/productos/{id}
```

## Categorías

### Listar Categorías
```http
GET /api/categorias
```

### Crear Categoría
```http
POST /api/categorias
```

**Body:**
```json
{
    "nombre": "string",
    "descripcion": "string"
}
```

## Proveedores

### Listar Proveedores
```http
GET /api/proveedores
```

### Crear Proveedor
```http
POST /api/proveedores
```

**Body:**
```json
{
    "nombre": "string",
    "email": "string",
    "telefono": "string",
    "direccion": "string"
}
```

## Movimientos

### Listar Movimientos
```http
GET /api/movimientos
```

### Crear Movimiento
```http
POST /api/movimientos
```

**Body:**
```json
{
    "producto_id": "integer",
    "tipo": "string", // "entrada" o "salida"
    "cantidad": "integer",
    "motivo": "string"
}
```

## Respuestas de Error

### 401 Unauthorized
```json
{
    "message": "No autenticado"
}
```

### 403 Forbidden
```json
{
    "message": "No autorizado"
}
```

### 422 Unprocessable Entity
```json
{
    "message": "Los datos proporcionados no son válidos",
    "errors": {
        "campo": ["mensaje de error"]
    }
}
```

### 404 Not Found
```json
{
    "message": "Recurso no encontrado"
}
```

## Autenticación

Todas las rutas protegidas requieren un token de autenticación en el header:

```
Authorization: Bearer {token}
```

El token se obtiene al iniciar sesión y debe incluirse en todas las peticiones a rutas protegidas. 