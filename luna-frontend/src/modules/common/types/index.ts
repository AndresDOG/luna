export interface KpiEstados 
{  
   carga: number; 
   proceso: number; 
   repuesto: number; 
   soporte: number; 
   cambio: number
}

export interface UserForm 
{
    email: string;
    password: string;
}
  
export interface AuthStoreState 
{
    user:  null | any;
    empresa: null | any;
    if_authenticated: boolean;
    permisos:  any;
}

export interface Header
{  
    title: string; 
    align?: 'start' | 'center' | 'end'; 
    key?: string; 
    sortable?: boolean; 
    value?: string;
}

export interface MenuItem 
{
    com_id: number;
    com_padre_id: number | null;
    com_path: string;
    com_nombre: string;
    com_icono: string;
    children?: MenuItem[];
}