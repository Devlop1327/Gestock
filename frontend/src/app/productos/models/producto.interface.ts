export interface Producto {
  id?: number;
  codigo: string;
  nombre: string;
  descripcion: string;
  precio: number;
  stock: number;
  stock_minimo: number;
  categoria_id: number;
  proveedor_id: number;
  created_at?: string;
  updated_at?: string;
  activo?: boolean;
}

export interface ProductoResponse {
  data: Producto[];
  message: string;
  status: string;
}