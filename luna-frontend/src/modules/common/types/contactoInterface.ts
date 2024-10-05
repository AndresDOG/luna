import type { rsIdentidad,rsDepartamento,rsLocalidad } from '@/modules/cita/types/index';

export interface rsItems
{
    identidad: rsIdentidad[],
    departamento: rsDepartamento[],
    localidad: rsLocalidad[]
}

export interface rsEspecialidad
{
    id: number;
    nombre: string;
}
