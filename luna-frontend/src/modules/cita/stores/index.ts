import { defineStore } from 'pinia';
import { AuthStore } from '@/modules/common/stores/auth';
import type { TDocumentDefinitions, Content, PageSize } from 'pdfmake/interfaces';
import type { CitaInfo } from '@/modules/cita/types/index';

export const CitaStore = defineStore('citaStore', {
  getters: {
    rsEmpresa: () => {
      const authStore = AuthStore();
      const { empresa } = authStore;

      return {
        nombre: empresa.emp_nombre,
        nit: empresa.emp_identidad,
        direccion: empresa.emp_direccion,
        telefono: empresa.emp_telefono,
        mail: empresa.emp_email,
        logo: empresa.emp_logo,
      };
    },
  },
  actions: {
    // -------- Reporte para impresión de Citas --------
    async configReportPrint(info: any): Promise<TDocumentDefinitions> {
        const rsEmpresa = this.rsEmpresa;
      
        // Validar que `rsEmpresa` y `info` tengan todos los datos necesarios.
        const empresaLogo = rsEmpresa?.logo ?? '';
        const empresaNombre = rsEmpresa?.nombre ?? '';
        const empresaNit = rsEmpresa?.nit ?? '';
        const empresaDireccion = rsEmpresa?.direccion ?? '';
        const empresaTelefono = rsEmpresa?.telefono ?? '';
        const empresaEmail = rsEmpresa?.mail ?? '';
      
        // Validar los datos de `info`
        const citaNo = info?.noCita ?? '';
        const fechaCreacion = info?.fechaCreacion ?? '';
        const tipoServicio = info?.tipo ?? '';
        const paciente = info?.paciente ?? '';
        const cedula = info?.cedula ?? '';
        const direccion = info?.direccion ?? '';
        const localidad = info?.localidad ?? '';
        const telefono = info?.telefono ?? '';
        const detalle = info?.detalle ?? '';
        const sintoma = info?.sintoma ?? '';
        const observacion = info?.observacion ?? '';
        const medico = info?.medico ?? '';
      
        // Construir el documento PDF con validaciones adicionales
        const formatReportPrint: TDocumentDefinitions = {
          pageSize: 'A4',
          pageMargins: [40, 20, 40, 40],
          content: [
            // --- PRIMERA ORDEN ---
            {
              margin: [0, 0, 0, 0],
              fontSize: 9,
              table: {
                widths: [80, 220, 0, 178],
                body: [
                  [
                    { image: empresaLogo, width: 80, height: 50 },
                    {
                      colSpan: 2,
                      text: [
                        { text: empresaNombre + '\n', fontSize: 12, bold: true },
                        {
                          text: empresaNit + '\n' + empresaDireccion + '\n' + empresaTelefono + '\n' + empresaEmail,
                          fontSize: 10,
                        },
                      ],
                      alignment: 'center',
                      bold: true,
                    },
                    '',
                    {
                      table: {
                        widths: [80, 84],
                        body: [
                          [{ text: 'No. Cita:', alignment: 'right', bold: true }, citaNo || ''],
                          [{ text: 'Fecha:', alignment: 'right', bold: true }, fechaCreacion || ''],
                          [{ text: 'Tipo Servicio:', alignment: 'right', bold: true }, tipoServicio || ''],
                        ],
                      },
                    },
                  ],
                ].filter(row => row.every(cell => cell !== undefined)),
              },
              layout: { defaultBorder: false },
            },
            // --- INFORMACIÓN DEL PACIENTE ---
            {
              margin: [0, 0, 0, 0],
              fontSize: 9,
              table: {
                widths: [42, 200, 50, '*'],
                body: [
                  [
                    { text: 'Paciente:', bold: true }, paciente || '',
                    { text: 'CC/Nit:', bold: true }, cedula || ''
                  ],
                  [
                    { text: 'Dirección:', bold: true }, direccion || '',
                    { text: 'Localidad:', bold: true }, localidad || ''
                  ],
                  [
                    { text: 'Teléfono:', bold: true }, telefono || ''
                  ],
                ].filter(row => row.every(cell => cell !== undefined)),
              },
              layout: { defaultBorder: false },
            },
            // --- DETALLE DE CITA ---
            {
              fontSize: 9,
              table: {
                widths: [42, '*'],
                body: [
                  [{ text: 'Detalle:', alignment: 'left', bold: true }, detalle || ''],
                  [{ text: 'Síntoma:', alignment: 'left', bold: true }, sintoma || ''],
                  [{ text: 'Observación:', alignment: 'left', bold: true }, observacion || '']
                ].filter(row => row.every(cell => cell !== undefined)),
              },
              layout: { defaultBorder: false },
            },
            // --- FIRMA PACIENTE Y MÉDICO ---
            {
              margin: [0, 0, 0, 4],
              fontSize: 9,
              table: {
                widths: ['25%', 58, 70, 40, '*'],
                body: [
                  [
                    { text: 'Firma Paciente:', bold: true },
                    '',
                    '',
                    { text: 'Médico:', alignment: 'right', bold: true },
                    medico || ''
                  ],
                ].filter(row => row.every(cell => cell !== undefined)),
              },
              layout: { defaultBorder: false },
            },
          ],
        };
      
        return formatReportPrint;
      }
      
      
  },
});
