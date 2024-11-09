import { defineStore } from 'pinia';
import { AuthStore } from '@/modules/common/stores/auth';
import type { TDocumentDefinitions, Content, PageSize } from 'pdfmake/interfaces';



export const CitaStore = defineStore('CitaStore', 
{
  getters: 
  {
    rsEmpresa: () => 
    {
        const authStore = AuthStore()
        const { empresa } = authStore

        return {
        nombre: empresa.emp_nombre,
        nit: empresa.emp_identidad,
        direccion: empresa.emp_direccion,
        telefono: empresa.emp_telefono,
        mail: empresa.emp_email,
        logo: empresa.emp_logo
        }
    }
  },
  actions: 
  {
    // --------Reporte para impresion de Citas--------
        
    async configReportPrint(info: any): Promise<TDocumentDefinitions> 
    {
      const rsEmpresa = this.rsEmpresa
      
      const formatReportPrint = 
      {
        pageSize: 'A4' as PageSize,
        pageMargins: [ 40, 20, 40, 40 ] as [number, number, number, number],
        content: [
           //--  PRIMERA ORDEN  --//
           {
            margin: [0, 0, 0, 0],
            fontSize: 9,
            table: {
                widths: [80, 220,0, 178],
                body: [
                    [
                        {
                            image: rsEmpresa.logo,
                            width: 80,
                            height: 50,
                        },
                        {
                            colSpan: 2,
                            text: [
                                {text: rsEmpresa.nombre+'\n', fontSize: 12, bold: true},
                                {text: rsEmpresa.nit+'\n'+rsEmpresa.direccion+'\n'+rsEmpresa.telefono+'\n'+rsEmpresa.mail, fontSize: 10, bold: false},
                            ],
                            alignment: 'center',
                            bold: true
                        },
                        '',
                        {
                            //border: [true, true, true, false],
                            table: {
                            widths: [80,84],
                            heights: [10, 10],
                            body: [
                                [{text: 'No. Cita:',alignment: 'right',bold: true}, info.noCita],
                                [{text: 'Fecha:',alignment: 'right',bold: true}, info.fechaCreacion],
                                [{text: 'Tipo Servicio:',alignment: 'right',bold: true}, info.tipo]
                                ]
                            }
                        }
                    ]

                ]
            },
            layout: {
                        defaultBorder: false,
                    }
        },
        {
            margin: [0, 0, 0, 0],
            fontSize: 9,
            table: {
                widths: [42, 200, 50, '*'],
                body: [
                    [
                        {
                            colSpan: 4,
                            border: [true, true, true, false],
                            text: 'INFORMACION PACIENTE',
                            bold: true
                        },
                        '',
                        '',
                        ''
                    ],
                    [
                        {
                            alignment: 'left',
                            border: [true, true, false, false],
                            text: 'Paciente:',
                            bold: true
                        },
                        {
                            border: [false, true, false, false],
                            text: info.paciente,

                        },
                        {
                            alignment: 'left',
                            border: [false, true, false, false],
                            text: 'CC/Nit:',
                            bold: true
                        },
                        {
                            border: [false, true, true, false],
                            text: info.cedula
                        }
                    ],
                    [
                        {
                            alignment: 'left',
                            border: [true, false, false, false],
                            text: 'Dirección:',
                            bold: true
                        },
                        {
                            colSpan: 3,
                            border: [false, false, true, false],
                            text: info.direccion.substring(0,92)
                        }
                    ],
                    [
                        {
                            alignment: 'left',
                            border: [true, false, false, true],
                            text: 'Localidad:',
                            bold: true
                        },
                        {
                            border: [false, false, false, true],
                            text: info.localidad
                        },
                        {
                            alignment: 'left',
                            border: [false, false, false, true],
                            text: 'Telefono:',
                            bold: true
                        },
                        {
                            border: [false, false, true, true],
                            text: info.telefono
                        }
                    ]

                ]
            },
            layout: {
                        defaultBorder: false,
                    }
        },
        {
            //margin: [0, 0, 0, 0],
            fontSize: 9,
            table: {
                widths: [42,'*'],
                heights: [10],
                body: [

                    [
                        {text: 'Detalle:',alignment: 'left',border: [true, true, false, true],  },
                        {
                            border: [false, true, true, true],
                            text: info.detalle.substring(0,92),
                        },
                    ],

                    [
                        {text: 'Síntoma:',alignment: 'left',border: [true, true, false, true],  },
                        {
                            border: [false, true, true, true],
                            text: info.sintoma.substring(0,92),
                        },
                    ],
                    [
                        {text: 'Observacion:',alignment: 'left',border: [true, false, false, true],  },
                        {
                            border: [false, true, true, true],
                            text: info.observacion.substring(0,189),
                        },
                    ],
                ]
            },
            layout: {
                        defaultBorder: false,
                    }
        },
        {
            margin: [0, 0, 0, 4],
            fontSize: 9,
            table: {
                widths: ['25%',58,70,40,'*'],
                heights: [10,10],
                body: [

                    [
                        {text: 'Firma Paciente:',alignment: 'left',border: [false, false, false, false],bold: true},
                        '',
                        '',
                        {text: 'Medico:',alignment: 'right',border: [false, false, false, false],bold: true},
                        {text: info.medico, alignment: 'left',border: [false, false, false, false],bold: true},


                    ],
                ]
            },
            layout: {
                        defaultBorder: false,
                    }
        },
    ] as Content[]
      };

      return formatReportPrint;
    },
  }
});