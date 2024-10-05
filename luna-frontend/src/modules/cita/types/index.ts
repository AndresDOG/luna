export interface rsIdentidad
{
  idTipoIdentidad?: number;
  tipoIdentidadCodigo?: string;
  tipoIdentidadNombre?: string;
}

export interface rsDepartamento
{
  idDepartamento?: number;
  nombreDepartamento?: string;
  idDepartamentoPais?: number;
  codigoDepartamento?: string;
}

export interface rsLocalidad
{
  idLocalidad?: number;
  idPaisLocalidad?: number;
  idDepartamentoLocalidad?: number;
  codigoLocalidad?: string;
  nombreLocalidad?: string;
}

export interface rsTipoCita
{
  idTipoCita?: number;
  nombreTipoCita?: string;
}

export interface rsTratamiento
{
  idTratamiento?: number;
  nombreTramiento?: string;
}