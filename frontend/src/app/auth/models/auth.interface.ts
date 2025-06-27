export interface LoginRequest {
  email: string;
  password: string;
}

export interface LoginResponse {
  success: boolean;
  message: string;
  data: {
    user: User;
    role: Role;
    permissions: Permission[];
    token: string;
    token_type: string;
  };
}

export interface User {
  id: number;
  name: string;
  email: string;
  role_id: number;
  activo: boolean;
  created_at?: string;
  updated_at?: string;
  role?: Role;
}

export interface Role {
  id: number;
  nombre: string;
  descripcion: string;
  permisos: Permission[];
}

export interface Permission {
  id: number;
  nombre: string;
  descripcion: string;
}

export interface RegisterRequest {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  role_id: number;
} 