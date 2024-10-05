import type {  rsTipoCita, rsIdentidad,  rsDepartamento,  rsLocalidad,rsTratamiento } from '@/modules/cita/types/index'

export interface rsItem 
{
    tipocita: rsTipoCita[]; 
    identidad: rsIdentidad[];
    departamento: rsDepartamento[];
    localidad: rsLocalidad[];
    tratamiento: rsTratamiento[];
}

export interface rsPaciente
{
  tipo_identidad: string;
  tipo_contacto: string;
  cedula: string;
  nombre: string;
  direccion: string;
  telefono: string;
  departamento: string;
  localidad: string; 
  mail: string
  tratamiento: string;
}

export interface rsMedico
{
  tipo_identidad: string;
  tipo_contacto: string;
  cedula: string;
  nombre: string;
  direccion: string;
  telefono: string;
  departamento: string;
  localidad: string; 
  mail: string
}

export interface rsKey
{
    idtipo: any; 
    idPaciente: string;
    idMedico: string;
    observacion: string;
    idTratamieto: string;
    sintoma: string;
    detalle: string;
    fecha: any;
}

export interface rsInfo
{
    noCita:string;
    fechaCreacion:string;
    fechaEstado:string;
    tipo:string;
    medico:string;
    id_estado:string | number;
    estado:string | undefined;
    paciente:string;
    idPaciente:string;
    cedula:string;
    direccion:string;
    mail:string;
    telefono:string;
    localidad:string;
    observacion:string;
    cedulaMedico:string;
    detalle:string;
    sintoma:string;
}

export interface rsListPaciente
{
  pac_id: number;
  pac_nombre: string;
}