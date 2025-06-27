export interface Categoria {
  id?: number;
  nombre: string;
  descripcion?: string;
  activo?: boolean;
  created_at?: string;
  updated_at?: string;
}

export interface CategoriaResponse {
  data: Categoria[];
  message: string;
  status: string;
}