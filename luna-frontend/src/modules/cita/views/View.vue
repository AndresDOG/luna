<template>    
    
    <v-list>
        <v-list-item>
            <template v-slot:prepend>
            <v-avatar>
                <v-icon color="pink" icon="mdi-monitor-dashboard" size="x-large"></v-icon>
            </v-avatar>
            </template>
            <v-list-item-title class="font-weight-bold my-0">Detalle Cita</v-list-item-title>
            <v-list-item-subtitle class="text-caption grey--text">Cita > Detalle</v-list-item-subtitle>        
        </v-list-item>
    </v-list>
    
    <div class="my-3">
        <v-row class="px-lg-5">

            <!-- INFORMACION Cita -->
            <v-col cols="12" md="8" class="pr-lg-2">
                
                <v-card outlined>
                    <v-toolbar color="pink lighten-1" density="compact">
                        <v-app-bar-nav-icon> <v-icon>mdi-cloud-print-outline</v-icon></v-app-bar-nav-icon>
                        <v-toolbar-title><h5 class="font-weight-normal my-0">Información Cita</h5></v-toolbar-title>  

                        <v-spacer></v-spacer>

                        <v-tooltip v-if="info.id_estado === 3"  top>
                            <template v-slot:activator="{ props  }">
                                <v-btn @click="goToEditCita" icon v-bind="props">
                                <v-icon>mdi-file-document-edit-outline</v-icon>
                                </v-btn>
                            </template>
                            <span>Modificar Cita</span>
                        </v-tooltip>
                        <div  class="d-none d-sm-flex">
                            <v-tooltip  top >
                                <template v-slot:activator="{ props  }">
                                    <v-btn @click="printCita" icon v-bind="props">
                                    <v-icon>mdi-printer-wireless</v-icon>
                                    </v-btn>
                                </template>
                                <span>Imprimir Cita</span>
                            </v-tooltip>
                        </div>
                    </v-toolbar>

                    <v-container>
                        <v-row>
                            <v-col cols="12" md="2">
                                <v-text-field                                        
                                    v-model="info.noCita"                                        
                                    label="Cita"
                                    bg-color="grey-lighten-4"
                                    readonly
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>

                            <v-col cols="12" md="2">
                                <v-text-field                                        
                                    v-model="info.fechaCreacion"                                        
                                    label="Fecha Creación"
                                    readonly
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>

                            <v-col cols="12" md="4">
                                <v-text-field                                        
                                    v-model="info.tipo"
                                    label="Tipo Cita"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row class="pb-0 mt-0">

                            <v-col cols="12" md="8">
                                <v-text-field
                                    v-model="info.medico"
                                    label="Medico"
                                    bg-color="grey-lighten-4"
                                    readonly
                                    density="compact"
                                    variant="underlined"
                                    >
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row class="pb-0 mt-0">
                            <v-col cols="12" md="4">
                                <v-text-field                                       
                                    v-model="info.fechaEstado"
                                    label="Fecha Cita"
                                    readonly                                       
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>

                            <v-col cols="12" md="8">
                                <v-text-field                                        
                                    v-model="info.estado"
                                    label="Estado"                                                                              
                                    bg-color="grey-lighten-4"
                                    readonly 
                                    density="compact"
                                    variant="underlined">
                                    <template v-slot:append>
                                        <v-tooltip v-if="info.id_estado !== 4 && info.id_estado !== 5" text="Cambiar Estado" location="top">
                                            <template v-slot:activator="{ props }">
                                                <v-icon
                                                    v-bind="props"
                                                    @click="abrirDialogEstado"
                                                    color="pink-accent-3"
                                                >
                                                    mdi-autorenew
                                                </v-icon>
                                            </template>
                                        </v-tooltip>
                                    </template>       
                                </v-text-field>
                            </v-col>
                        </v-row>                                
                        <v-row class="pb-0 mt-0">
                            <v-col cols="12" md="8">
                                <v-text-field                                       
                                    v-model="info.paciente"
                                    label="Paciente"
                                    bg-color="grey-lighten-4"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>

                            <v-col cols="12" md="4">
                                <v-text-field                                        
                                    v-model="info.cedula"
                                    bg-color="grey-lighten-4"
                                    label="Cedula"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row class="pb-0 mt-0">
                            <v-col cols="12" md="8">
                                <v-text-field                                       
                                    v-model="info.direccion"                                        
                                    label="Dirección"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>

                            <v-col cols="12" md="4">
                                <v-text-field                                       
                                    v-model="info.localidad"                                       
                                    label="Localidad"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row class="pb-0 mt-0">
                            <v-col cols="12" md="8">
                                <v-text-field                                       
                                    v-model="info.telefono"                                        
                                    label="Teléfono"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>

                            <v-col cols="12" md="4">
                                <v-text-field                                        
                                    v-model="info.mail"                                       
                                    label="E-mail"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>
                        </v-row>                               
                        <v-row class="pb-0 mt-0">
                            <v-col cols="12">
                                <v-text-field                                        
                                    v-model="info.detalle"                                        
                                    label="Detalle"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row class="pb-0 mt-0">
                            <v-col cols="12">
                                <v-text-field                                        
                                    v-model="info.observacion"                                        
                                    label="Observacion"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>
                        </v-row>
                        <v-row class="pb-0 mt-0">
                            <v-col cols="12">
                                <v-text-field                                       
                                    v-model="info.sintoma"                                        
                                    label="Sintoma"
                                    readonly                                        
                                    density="compact"
                                    variant="underlined">
                                </v-text-field>
                            </v-col>
                        </v-row>                 
                    </v-container>
                </v-card>                
            </v-col>
            <v-col cols="12" md="4" class="pl-lg-2">
                        <v-card outlined>
                            <v-toolbar color="cyan-darken-1"  density="compact">
                                <v-app-bar-nav-icon><v-icon>mdi-message-bulleted</v-icon></v-app-bar-nav-icon>
                                <v-toolbar-title><h5 class="font-weight-normal my-0">Notas</h5></v-toolbar-title>
                            </v-toolbar>

                            <v-container id="scroll-target" class="overflow-y-auto">

                                <v-row v-scroll:#scroll-target="onScroll" style="height:480px">
                                    <v-container>

                                        <div v-for="item in rsNotas" :key="item.not_id" >
                                            <v-row>
                                                <v-col cols="12">
                                                    <div class="text-sm text--secondary ml-3">                                                      
                                                        <v-icon color="blue" size="x-small">mdi-calendar-month</v-icon><span class="ml-2 text-caption text-medium-emphasis">{{ item.not_fecha }}</span>
                                                    </div>
                                                    <v-card color="grey-darken-2" variant="tonal">
                                                        <v-card-text>{{ item.not_detalle.charAt(0).toUpperCase()+item.not_detalle.slice(1).toLowerCase() }}</v-card-text>
                                                    </v-card>
                                                    <div class="text-sm text--secondary ml-1">
                                                        <v-icon color="pink" size="x-small">mdi-account-check</v-icon> <span class="ml-1 text-caption text-medium-emphasis">{{ item.usu_nombre.charAt(0).toUpperCase()+item.usu_nombre.slice(1).toLowerCase() }}</span>
                                                    </div>
                                                </v-col>
                                            </v-row>

                                        </div>

                                    </v-container>
                                </v-row>

                            </v-container>

                            <v-divider class="mb-3"></v-divider>

                            <v-container class="py-2 px-2">
                                <v-text-field v-if="true/*statusServicio*/"
                                   v-model="textNota"
                                   :append-icon="textNota ? 'mdi-send' : 'mdi mdi-keyboard-close-outline'"
                                    hint="Ingresar nota - (Máximo: 250 caracteres)"
                                    persistent-hint
                                    variant="outlined"
                                    density="compact"
                                    @click:append="newNota">                                    
                                </v-text-field>
                            </v-container>
                        </v-card>
                    </v-col>
        </v-row>

        
                    
</div>    

<!--              -->
<!-- Modals Forms -->
<!--              -->

    <!-- dialog estado -->
    <v-dialog v-model="dialogEstado" max-width="500px">
            <v-card>
                <v-layout>
                <v-app-bar
                    
                    dark
                    density="compact"
                    color="blue">
                    <v-app-bar-nav-icon> <v-icon>mdi-autorenew</v-icon></v-app-bar-nav-icon>
                    <v-toolbar-title><h5 class="font-weight-normal my-0">Cambiar Estado</h5></v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-tooltip top>
                        <template v-slot:activator="{ props }">
                            <v-btn @click="cerrarDialogEstado" icon v-bind="props">
                            <v-icon>mdi-close-circle</v-icon>
                            </v-btn>
                        </template>
                        <span>Cambiar Estado</span>
                    </v-tooltip>
                </v-app-bar>
                <v-main>
                    <v-container fluid>
                <v-card-text>
                    <v-select class="mt-0"
                        item-title="estado_nombre"
                        item-value="estado_id"
                        v-model="selectEstado"
                        :items="rsListaEstado"
						@update:modelValue="OnchangeEstado(selectEstado?.cita_id ?? null)"
                        label="Estados"
                        return-object
                        variant="underlined"
                        :rules="nRules"
                        hint="Seleccione el estado"
                        persistent-hint>
                    </v-select>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn   class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="CambiarEstado">CAMBIAR</v-btn>
                    <v-btn  class="text-none ms-4 text-black" color="white" size="small"  variant="elevated" @click="cerrarDialogEstado">CERRAR</v-btn>
                </v-card-actions>
            </v-container>
            </v-main>
            </v-layout>
            </v-card>
        </v-dialog>  
</template>
  
<script setup lang="ts">

    const props = defineProps<{
        id_cita: string
    }>();

    import { ref, onMounted, watch, isVNode,computed } from 'vue';
    import { format } from 'date-fns';
    import { nextTick } from 'vue'
    import { AuthStore } from '@/modules/common/stores/auth';
    import type { AuthStoreState } from '@/modules/common/types/index';
    import type { rsItem,rsPaciente,rsKey,rsInfo,rsListPaciente } from '@/modules/cita/types/createCitaInterface';
    import authApi from '@/api/api';
    import Swal from 'sweetalert2';
    import { useRouter } from 'vue-router';
    import type { Header } from '@/modules/common/types/index';
    import pdfMake from 'pdfmake/build/pdfmake';
    import pdfFonts from 'pdfmake/build/vfs_fonts';
    import type { TDocumentDefinitions } from 'pdfmake/interfaces';
    import Paciente from '@/modules/common/views/Paciente.vue';
    import { VTimePicker } from 'vuetify/labs/VTimePicker';
    // Generador de reportes
    pdfMake.vfs = pdfFonts.pdfMake.vfs;

    const router = useRouter();

    const info = ref<rsInfo>({ noCita:'', fechaCreacion:'', fechaEstado:'',tipo:'',medico:'',id_estado:'', estado:'', paciente:'', idPaciente:'', cedula:'', direccion:'', mail:'', telefono:'', localidad:'',lugar:'',observacion:'',cedulaMedico:'',detalle:'',sintoma:''})
    const rsMessage =  ref('');
    const rsNotas = ref<any>([]); 
    const textNota = ref('');
    const notaPublica = ref(1);
    const rsPaciente= ref<any>([]); 
    let statusCita = ref<boolean>(true);
    const medico = ref<any>([]);
    
    const authStore = AuthStore() as AuthStoreState;
    // const servicioStore = ServicioStore();
    // Control Acceso
    let accessAdmin = ref<boolean>(false);
    let access_abono = ref<boolean>(false);
    let access_estado = ref<boolean>(false);
    let access_tecnico = ref<boolean>(false);
    let access_parte = ref<boolean>(false);
    let access_remision = ref<boolean>(false);
    let access_imprimir = ref<boolean>(false);
    let access_eliminar = ref<boolean>(false);
    let access_ed_servicio = ref<boolean>(false);

    // Se usa cuando necesitamos superponer el swal
    const MySwal = Swal.mixin({
    didOpen: (toast) => 
    {
        // Ajustar el z-index programáticamente
        toast.parentElement!.style.zIndex = '9999';
    }
    });

    // Validacion
    let valid:boolean = true;
    let nRules = 
    [
        (v: string): boolean | string => !!v || 'Requerido'
    ];

    let numberRule = 
    [
        (v: any): boolean | string  => (!isNaN(parseFloat(v)) && parseFloat(v) >= 0) || 'Valor > 0'
    ];

    let aRule = 
    [
        (v: any): boolean | string => 
        {
            // Primera validación: Verificar si el valor no es vacío
            const requiredRule = !!v || 'Requerido';
            
            // Segunda validación: Verificar si el valor es un número válido y mayor o igual a 0
            const numberRule = (!isNaN(parseFloat(v)) && parseFloat(v) >= 0) || 'Valor > 0';

            // Combinar ambas validaciones
            return requiredRule === true && numberRule === true;
        }
    ];

    // Cambiar Estado
    let dialogEstado = ref<boolean>(false);
    let rsListaEstado = ref<any>([]);
    let selectEstado = ref<any>('');
    let tramiteCambio = ref<boolean>(false);
    let detalleRepair = ref<boolean>(false);
    let detalleNote = ref<string>('');
    let autorizacionCambio = ref<string>('');

    /*const listaGeneral =ref<rsListaGeneral[]>([]);*/

    // --------------------------------
    // - Hooks
    // --------------------------------   

    onMounted(async () => 
    {   
       await controlPermiso();
       await getCita();
       await getNotas();
       await getEstados();
       await getMedico();
        
    });


    // Props Rutas
    const goToEditCita = () => 
    {
        router.push({name:'cita.edit', params:{accion:1,id_cita:props.id_cita.toString()}})
    }

    // --------------------------------
    // - Eventos
    // --------------------------------


    const abrirDialogEstado = () => 
    {   
        dialogEstado.value = true;
    };
    
    const cerrarDialogEstado = () => 
    {   
        selectEstado.value = null;
        dialogEstado.value = false;
        detalleRepair.value=false;
        tramiteCambio.value=false;
    };

 
    // --------------------------------
    // - Funciones de Usuario
    // --------------------------------
    const onScroll: any = () => { return };
    
    const getNotas = async () => 
    {   
        await authApi.get(`api/cita/nota/${props.id_cita}`).then((res) => {
            rsNotas.value = res.data;        
        });
    };

    const getCita = async () =>
    {
        await authApi.get(`api/cita/search/cita/${props.id_cita}`).then((res) => 
        {
            const rs = res.data;

            if (rs.length===0)
            {
                Swal.fire('Oops!', 'Servicio no Encontrado', 'warning');
            }
            else
            {     
                console.log(res.data);             
                 info.value.noCita   = rs.cit_no_cita; 
                 info.value.fechaCreacion = format(new Date(rs.created_at),'yyyy-MM-dd HH:mm:ss');
                 info.value.fechaEstado = rs.cita_fecha;
                 info.value.estado  = rs.estado_cita.estado_nombre;
                 info.value.tipo  = rs.tipo_cita.tip_cita_nombre;
                 info.value.id_estado  = rs.estado_cita.estado_id;

                 info.value.medico  = rs.medico.med_nombre;
                 info.value.cedulaMedico  = rs.medico.med_cedula;
                 

                 info.value.idPaciente = rs.paciente.pac_id
                 info.value.paciente   = rs.paciente.pac_nombre;
                 info.value.cedula    = rs.paciente.pac_cedula;
                 info.value.direccion = rs.paciente.pac_direccion;
                 info.value.telefono  = rs.paciente.pac_telefono;
                 info.value.mail = rs.paciente.pac_email;
                 info.value.localidad = rs.paciente.localidad.loc_nombre;
        
                 info.value.observacion = rs.cita_observacion;            
                 info.value.detalle = rs.cita_detalle;
                 info.value.sintoma = rs.cita_sintoma;  

              //   PrintFactura = (rs.ser_factura == 0) ? false : true   
            

            //     if(rs.estado.est_final==1)
            //     {
            //         statusCita.value = false
            //     }

            //     if(rs.estado.est_id==7)
            //     {
            //         editCosto.value = false
            //     }

               // rsInfo.usuario = rs.ser_id_usuario
            }    
        });       
    };

    const asignarTecnico = async () => 
    {
        // const isValid = await form_tecnico.value?.validate()

        // if(isValid.valid) 
        // {
        //     if (!selectTecnico.value) 
        //     {
        //         console.error('No se ha seleccionado ningún técnico');
        //         return;
        //     };

        //     const data = 
        //     {
        //         id_servicio: props.id_servicio,
        //         keys: 
        //     {
        //         usu_id: selectTecnico.value.usu_id,
        //         usu_nombre: selectTecnico.value.usu_nombre
        //     }
        //     };

        //     await authApi.post('api/servicio/process/asignarTecnico',data).then(res =>
        //     {
        //         rsInfo.value.tecnico = selectTecnico.value?.usu_nombre ?? '';
        //         rsInfo.value.estado = 'PROCESO DE REPARACION'
        //         getNotas()
        //         dialogTecnico.value = false;
        //         Swal.fire("Ok!","Técnico asignado con éxito", "success")
        //     });
        // }    
    };

    const getEstados = async () => 
    {
        await authApi.get('api/cita/estadoCita').then((res) => 
        {
            rsListaEstado.value = res.data;     
        });
    }

    const OnchangeEstado = (idEstado: number | null | undefined) =>
    {
        if (idEstado === undefined) 
        {
           
            return;
        }   

        if(idEstado == 6)
        {
            tramiteCambio.value = false
            detalleRepair.value = true
            detalleNote.value = ''
            return
        }

        detalleRepair.value = false
        tramiteCambio.value = false
    }

    const newNota = async () => 
    {
        // Validar si el campo de texto está vacío
        if (!textNota.value || textNota.value.trim() === '') 
        {
            Swal.fire('Oops!', 'Ingrese el detalle de la nota', 'warning');
            return;
        }

        // Realizar la solicitud POST
        await authApi.post('api/cita/nota', 
        {
            id: props.id_cita,
            nota: textNota.value,
            status: 1,
        });

        // Limpiar el campo de texto y refrescar las notas
        textNota.value = '';
        await getNotas(); // Asegúrate de que `getNota` esté definida
        Swal.fire('Ok!', 'Registro creado con éxito', 'success');
    
    };
    
    const CambiarEstado = async () => 
    {
        console.log(selectEstado.value);
        if (!selectEstado.value?.estado_id) {
            return
        }

        if (selectEstado.value?.estado_id === info.value?.id_estado) {
            return
        }

        const params = 
        {
            id_cita: props.id_cita,
            id_estado: selectEstado.value?.estado_id,
            estado: selectEstado.value?.estado_nombre,
        };
        await authApi.post('api/cita/process/cambiarEstado',params).then(res =>
        {
            if (res.data===0)
            {
                dialogEstado.value = false
                Swal.fire("Oops!","Ocurrio un problema, intente de nuevo", "warning")
            }
            else
            {
                dialogEstado.value = false
                getNotas()
                info.value.estado = selectEstado.value?.estado_nombre
                access_estado.value = true
                getCita()
                Swal.fire("Ok!","Informacion actualizada con éxito", "success")
            }
        })
    };

    const getMedico = async () =>
    {
        await authApi.get('api/base/medico').then(res => {
            medico.value = res.data
        });
    };

    const formatDate = (date:any) =>
    {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
    };

    const controlPermiso = () => 
    {
      const componente51 = authStore.permisos.find((p:any) => p.per_id_componente === 51);

      if(authStore.user.rol.rol_tipo <= 2) 
      {
        accessAdmin.value = true;
      }
      
      if (componente51 ) 
      {
        const rs = JSON.parse(componente51.per_acceso);
        
        access_abono.value = rs.Agregar_Abono === "1";
        access_estado.value = rs.Cambiar_Estado === "1";
        access_tecnico.value = rs.Cambiar_Tecnico === "1";
        access_parte.value = rs.Solicitar_Parte === "1";
        access_remision.value = rs.Generar_Remision === "1";
        access_imprimir.value = rs.Imprimir_Servicio === "1";
        access_eliminar.value = rs.Eliminar_Abono === "1";
        
      }
      
      if (authStore.permisos.find((p:any) => p.per_id_componente === 53)) 
      {
        access_ed_servicio.value = true;
      }
      
    };

    // Imprimir Servicio
    const printCita = async () => 
    {
        // const res:TDocumentDefinitions = await servicioStore.configReportPrint(rsInfo.value);
    
        // const win = window.open('', '_blank', 'width=800,height=600,top=85,left=50');
        
        //  if (win) {
        //      pdfMake.createPdf(res).print({}, win);
        //  };
    };


    const getMaestroDocumento = async () =>
    {
        // await authApi.get(`api/facturacion/search/maestroRemision/${props.id_servicio}`).then(res =>
        // {
        //     if (res.data.length > 0)
        //     {
        //         rsKey2.value.estado      = res.data[0].fac_estado
        //         rsKey2.value.noDocumento = res.data[0].fac_numero
        //         rsKey2.value.noServicio  = res.data[0].ser_no_servicio
        //         rsKey2.value.fecha       = res.data[0].fac_fecha_emision
        //         rsKey2.value.medioPago   = res.data[0].med_pag_nombre
        //         rsKey2.value.descuento   = res.data[0].fac_descuento
        //         rsKey2.value.total       = res.data[0].fac_total
        //         rsKey2.value.subtotal    = res.data[0].fac_subtotal
        //         rsKey2.value.idMaestro   = res.data[0].fac_id
        //     }
        // });
    };

    const getDetalleDocumento = async (id:any) =>
    {
        // await authApi.get(`api/facturacion/search/detalleRemision/${id}`).then(res =>
        // {
        //     if (res.data.length > 0)
        //     {
        //         listaGeneral2.value=[]
        //         res.data.forEach((rs:any, i:any) => {
        //             listaGeneral2.value.push({row: i, item:rs.item, valor:rs.fac_det_valor, descuento:rs.fac_det_descuento, cantidad:rs.fac_det_cantidad, subtotal:rs.fac_det_subtotal});
        //         });
        //     }
        // });
    }

</script>