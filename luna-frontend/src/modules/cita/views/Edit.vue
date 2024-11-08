<template>
    
    <v-list>
        <v-list-item>
          <template v-slot:prepend>
          <v-avatar>
              <v-icon icon="mdi-newspaper-plus" color="pink" size="x-large"></v-icon>
          </v-avatar>
          </template>
          <v-list-item-title><h4 class="font-weight-bold my-0">Editar Cita</h4></v-list-item-title>
          <v-list-item-subtitle class="text-caption grey--text">Cita>Procesos>Editar Cita</v-list-item-subtitle>
        </v-list-item>
    </v-list>

    <div class="my-3">
      <v-row class="px-lg-5">
        <v-col cols="12">
          <v-card outlined>
             <v-toolbar color="pink lighten-1" density="compact">
                  <v-app-bar-nav-icon> <v-icon>mdi-clipboard-list-outline</v-icon></v-app-bar-nav-icon>
                  <v-toolbar-title><h5 class="font-weight-normal my-0">{{  tituloCita }}</h5></v-toolbar-title>
                  <v-spacer></v-spacer>
                    <v-icon>mdi mdi-book-information-variant</v-icon>
              </v-toolbar>

              <v-form
                  ref="form_main"
                  v-model="valid"
                  lazy-validation>

                  <div  class="mx-2 px-2">
                      <v-row class="pt-3">
                      <v-col cols="12" md="4">
                          <v-select
                            v-model="keys.tipo"
                            :items="listTiposCitas"
                            item-title="tip_cita_nombre"
                            item-value="tip_cita_id"
                            return-object
                            label="Tipo Cita"
                            @update:modelValue=""
                            required
                            :rules="nRules"
                            variant="underlined"
                            density="compact">
                            <v-icon slot="prepend" color="blue-grey darken-1">mdi-format-list-text</v-icon>
                          </v-select>
                      </v-col>
                      <v-col cols="12" md="4">
                          <v-text-field
                          v-model="keys.localidad"
                          return-object
                          label="Localidad"
                          required
                          :rules="nRules"
                          variant="underlined"
                          density="compact">
                          <v-icon slot="prepend" color="blue-grey darken-1">mdi-format-list-text</v-icon>
                          </v-text-field>
                      </v-col>
                      </v-row>
                      <v-row>
                      <v-col cols="12" sm="4">
                          <v-text-field
                            label="Identificacion paciente"
                            v-model="keys.cedula"
                            density="compact"
                            background-color="grey lighten-5"
                            required
                            disabled
                            :rules="nRules">
                          </v-text-field>
                      </v-col>
                      <v-col cols="12" sm="4">
                          <v-text-field
                          v-model="keys.paciente"
                          label="paciente"
                          append-icon="mdi-account"
                          variant="underlined"
                          density="compact"
                          disabled>
                          </v-text-field>
                      </v-col>
                      <v-col cols="12" sm="4">
                          <v-text-field
                          v-model="keys.direccion"
                          label="Direccion"
                          append-icon="mdi-map-marker"
                          variant="underlined"
                          density="compact"
                          disabled>
                          </v-text-field>
                      </v-col>
                      </v-row>
                      <v-text-field v-model="keys.detalle" label="Detalle"  required :rules="nRules" maxlength="250" variant="underlined" density="compact"></v-text-field>
                      <v-text-field v-model="keys.observacion" label="Observación" variant="underlined" density="compact"></v-text-field>
                      <v-text-field v-model="keys.sintoma" label="Sintoma" maxlength="250" variant="underlined" density="compact"></v-text-field>
                  </div>
              </v-form>
              <v-card-actions>

                <v-row dense class="mx-2 mb-2 mt-2">
                      <v-btn
                          class="ma-1"
                          color="gray"
                          label
                          @click="modificarServicio"
                          size="small"
                          variant="elevated"
                          prepend-icon="mdi mdi-note-plus">
                          <template v-slot:prepend>
                            <v-icon color="green"></v-icon>
                          </template>
                          MODIFICAR SERVICIO
                      </v-btn>

                      <v-btn
                          class="ma-1"
                          color="gray"
                          label
                          @click="goToVolver"
                          size="small"
                          variant="elevated"
                          prepend-icon="mdi mdi-keyboard-return">
                          <template v-slot:prepend>
                            <v-icon color="blue"></v-icon>
                          </template>
                          VOLVER
                      </v-btn>
                </v-row>
              </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </div>
</template>

<script setup lang="ts">
  
  const props = defineProps<{
        id_cita: string,
        accion: number | string;
    }>();

  import { ref, onMounted, watch } from 'vue';
  import { nextTick } from 'vue'
  import { AuthStore } from '@/modules/common/stores/auth';
  import type { AuthStoreState } from '@/modules/common/types/index';
  import { useRouter } from 'vue-router';
  //import type { rsInfo } from '@/modules/Cita/types/index';
  import authApi from '@/api/api';
  import Swal from 'sweetalert2';
  import type { Header } from '@/modules/common/types/index';
  import pdfMake from 'pdfmake/build/pdfmake';
  import pdfFonts from 'pdfmake/build/vfs_fonts';
  import type { TDocumentDefinitions } from 'pdfmake/interfaces';

  const router = useRouter();

  // Generador de reportes
  pdfMake.vfs = pdfFonts.pdfMake.vfs;
  //const servicioStore = ServicioStore();

  // Global Varying
  const tituloCita = ref<string>('');
  const keys = ref<any>({noCita:'', fechaCreacion:'', fechaEstado:'',tipo:'',medico:'',id_estado:'', estado:'', paciente:'', idPaciente:'', cedula:'', direccion:'', mail:'', telefono:'', localidad:'',lugar:'',observacion:'',cedulaMedico:'',detalle:'',sintoma:''});

  //Permisos y accesos
  const authStore = AuthStore() as AuthStoreState;
  const access_c_model = ref<boolean>(false);
  const access_c_store = ref<boolean>(false);
  const access_c_customer = ref<boolean>(false);
  const access_e_customer = ref<boolean>(false);

      // Report
  //const info = ref<rsInfo>({ noservicio:'', fechacreacion:'', fechaestado:'', lugar:'',tipo:'',tecnico:'', seguimiento:'', id_estado:'', estado:'', paciente:'', idPaciente:'', cedula:'', direccion:'', mail:'', telefono:'', localidad:'',namelocalidad:'', marca:'', producto:'', modelo:'', serie1:'', serie2:'', falla:'',accesorio:'',observacion:'',cedulatecnico:'',idalmacen:'',almacen:'',almacen_nit:'',fechacompra:'',plu:'',referencia:'',factura:'',facturaventa:'',fechafactura:'',docreferencia:''});
      // Datapicker
  const date = ref(new Date());
  const menu = ref<boolean>(false);

  const listTiposCitas = ref<any[]>([]);

  //Formularios y validacion
  const form_main = ref<any>(null);

  // Validacion
  let valid:boolean = true;
  let nRules = 
  [
      (v: string): boolean | string => !!v || 'Requerido'
  ];
  const mRules = 
  [
      (m: string | null): boolean | string => !!m || 'No existe el modelo'
  ];
      
  onMounted(async () => 
  {
      //await ControlPermiso()
      
      await getCita()
  });
      // Watcher para date
  watch(date, (val) => 
  {
    if (val) {
      keys.value.fechacompra = formatDate(val);
    }
  });

  // Router
  const goToVolver = () => 
  {
    props.accion === 1 ? router.push({name:'service.view',params:{id_cita: props.id_cita}}) : router.push({name:'consulta.simple'}) 
  }

  
  const getTipoCita = async () =>
  {
    authApi.get('api/servicio/search/tipoCita').then((res) =>
    {
        listTiposCitas.value = res.data;
    });
  };

  const getCita = async () =>
  {

    authApi.get(`api/cita/search/cita/${props.id_cita}`).then(res =>
    {
        console.log(props.id_cita);
        
    if (res.data.length===0)
    {
        Swal.fire("Oops!","Cita no Encontrada", "warning")
    }
    else
    {
        var rs = res.data;

        tituloCita.value = 'Cita No.: ' + rs.cit_no_cita

        keys.value.noCita   = rs.cit_no_cita; 
        keys.value.fechaCreacion = rs.created_at;
        keys.value.fechaEstado = rs.cita_fecha;
        keys.value.estado  = rs.estado_cita.estado_nombre;
        keys.value.tipo  = rs.tipo_cita.tip_cita_nombre;
        keys.value.id_estado  = rs.estado_cita.estado_id;

        keys.value.medico  = rs.medico.med_nombre;
        keys.value.cedulaMedico  = rs.medico.med_cedula;
        

        keys.value.idPaciente = rs.paciente.pac_id
        keys.value.paciente   = rs.paciente.pac_nombre;
        keys.value.cedula    = rs.paciente.pac_cedula;
        keys.value.direccion = rs.paciente.pac_direccion;
        keys.value.telefono  = rs.paciente.pac_telefono;
        keys.value.mail = rs.paciente.pac_email;
        keys.value.localidad = rs.paciente.localidad.loc_nombre;

        keys.value.observacion = rs.cita_observacion;            
        keys.value.detalle = rs.cita_detalle;
        keys.value.sintoma = rs.cita_sintoma;  

    }
    })
  };

  const modificarServicio = async () =>
  {
    const isValid = await form_main.value?.validate()

    if(isValid.valid)
    {
      const params = {keys:keys.value}
      await authApi.put(`api/servicio/servicio/${props.id_cita}`,params).then(res =>
      {
        
        if (res.data === 0) 
        {
          Swal.fire("Oops!", "Ocurrio un error al modificar el registro, verifique los datos", "error")
        }
        else
        {
            Swal.fire("Ok", "Registro modificado con éxito", "success")
            if(props.accion == 1)
            {
                router.push({name:'service.view',params:{id_cita: props.id_cita}});
            }
        }
      })
    }
  };

  const formatDate = (date:any) =>
  {
    if (!date) return null

    const [year, month, day] = date.split('-')
    return `${year}/${month}/${day}`
  };

  const downloadReport = (no_servicio:string) =>
  {
    // info.value.noservicio    = no_servicio
    // info.value.fechacreacion = new Date().toJSON().slice(0,10).replace(/-/g,'/')
    // info.value.tipo          = keys.value.idtipo.tip_ser_nombre
    // info.value.paciente       = paciente.value.nombre
    // info.value.cedula        = paciente.value.cedula
    // info.value.direccion     = paciente.value.direccion
    // info.value.localidad     = paciente.value.namelocalidad
    // info.value.telefono      = paciente.value.telefono
    // info.value.producto      = keys.value.modelo.producto.pro_nombre;
    // info.value.modelo        = keys.value.modelo.mod_nombre
    // info.value.marca         = keys.value.modelo.marca.mar_nombre
    // info.value.serie1        = keys.value.serie1.toUpperCase()
    // info.value.almacen       = keys.value.idalmacen.alm_nombre
    // info.value.fechacompra   = keys.value.fechacompra
    // info.value.falla         = keys.value.falla.toUpperCase()
    // info.value.accesorio     = keys.value.accesorio.toUpperCase()
    // info.value.observacion   = keys.value.observacion.toUpperCase()
    // info.value.docreferencia = keys.value.docreferencia

    printServicio()
  };

  const printServicio = async () => 
  {
    //   const res:TDocumentDefinitions = await servicioStore.configReportPrint(info.value);
  
    //   const win = window.open('', '_blank', 'width=800,height=600,top=85,left=50');
      
    //    if (win) {
    //        pdfMake.createPdf(res).print({}, win);
    //    };
  
  };

  const limpiarDatos = async () => 
  {
    
    Object.keys(keys.value).forEach((key) => {
      (keys.value as any)[key] = '';
    });

  };

  const LimpiarDatos = async () => 
  {
    limpiarDatos();
    await resetearValidacion();
  };

  const resetearValidacion = async () => 
  {
    if (form_main.value) {
      await nextTick();
      form_main.value.resetValidation();
    }
  };

  const ControlPermiso =  async () =>
  {
    // const componente11 = authStore.permisos.find(p => p.per_id_componente === 11);
    // if(componente11)
    // {
    //   const rs =  JSON.parse(componente11.per_acceso);

    //   access_c_model.value = rs.Crear_Modelo === "1" ? true : false;
    //   access_c_store.value = rs.Crear_Almacen === "1" ? true : false;
    //   access_c_customer.value = rs.Crear_paciente === "1" ? true : false;
    //   access_e_customer.value = rs.Editar_paciente === "1" ? true : false;
    // }
  };
      
</script>
