export interface Proveedor {
  id?: number;
  nombre: string;
  nit: string;
  direccion: string;
  telefono: string;
  email: string;
  contacto?: string;
  activo: boolean;
  created_at?: string;
  updated_at?: string;
}

export interface ProveedorResponse {
  data: Proveedor[];
  message: string;
  status: string;
}