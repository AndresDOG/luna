<template>
    
    <v-list>
        <v-list-item>
          <template v-slot:prepend>
          <v-avatar>
              <v-icon icon="mdi mdi-shape-square-plus" color="pink" size="x-large"></v-icon>
          </v-avatar>
          </template>
          <v-list-item-title><h4 class="font-weight-bold my-0">Crear Cita</h4></v-list-item-title>
          <v-list-item-subtitle class="text-caption grey--text">Cita>Procesos>Crear Cita</v-list-item-subtitle>
        </v-list-item>
    </v-list>

    <div class="my-3">
      <v-row class="px-lg-5">
        <v-col cols="12">
          <v-card outlined>
             <v-toolbar color="pink lighten-1" density="compact">
                  <v-app-bar-nav-icon> <v-icon>mdi-clipboard-list-outline</v-icon></v-app-bar-nav-icon>
                  <v-toolbar-title><h5 class="font-weight-normal my-0">Informacion de la Cita</h5></v-toolbar-title>
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
                            v-model="keys.idtipo"
                            :items="items.tipocita"
                            item-title="tip_cita_nombre"
                            item-value="tip_cita_id"
                            return-object
                            label="Tipo Servicio"
                            @update:modelValue="terapia = keys.idtipo.tip_cita_id == 2  ? true : false"
                            required
                            :rules="nRules"
                            variant="underlined"
                            density="compact">
                            <v-icon slot="prepend" color="blue-grey darken-1">mdi-format-list-text</v-icon>
                          </v-select>
                      </v-col>
                      <v-col cols="12" md="4">
                          <v-autocomplete
                            v-model="keys.idMedico"
                            :items="medico"
                            @input="onSearchInput($event)"
                            item-title="med_nombre"
                            item-value="med_id"
                            return-object
                            label="Medico"
                            required
                            :rules="nRules"
                            variant="underlined"
                            density="compact">
                            <v-icon slot="prepend" color="blue-grey darken-1">mdi-format-list-text</v-icon>
                          </v-autocomplete>
                      </v-col>
                                          
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="3">
                          <v-menu
                          v-model="menu"
                          :close-on-content-click="false"
                          transition="scale-transition"
                          offset-y
                          max-width="290px"
                          min-width="290px">
                          <template v-slot:activator="{ props }">
                              <v-text-field
                              v-model="keys.fecha"
                              label="Fecha"
                              hint="YYYY-MM-DD"
                              persistent-hint
                              prepend-icon="mdi-calendar"
                              readonly
                              density="compact"
                              variant="underlined"
                              :rules="nRules"
                              v-bind="props">
                              </v-text-field>
                              </template>
                          <v-date-picker color="primary" header-color="primary" width="300" height="470" elevation="5" v-model="date1"  no-title @update:model-value="formatDate"></v-date-picker>
                          </v-menu>
                      </v-col>
                        <v-col
                        cols="11"
                        sm="5"
                      >
                        <v-text-field
                          v-model="hora"
                          :active="menu2"
                          :focus="menu2"
                          variant="underlined"
                          :rules="nRules"
                          density="compact"
                          label="Selecciona la hora"
                          prepend-icon="mdi-clock-time-four-outline"
                          readonly
                        >
                          <v-menu
                            v-model="menu2"
                            :close-on-content-click="false"
                            activator="parent"
                            transition="scale-transition"
                          >
                            <v-time-picker use-seconds color="green-lighten-1" ampm-in-title scrollable v-if="menu2" format="ampm" v-model="hora" @update:model-value="onTimeChange" @update:hour="onHourUpdate" 
                            @update:minute="onMinuteUpdate" @update:period="onPeriodUpdate" full-width></v-time-picker>
                          </v-menu>
                        </v-text-field>
                      </v-col>
                      </v-row>
                      <v-row>
                      <v-col cols="12" sm="4">
                          <v-text-field
                            label="Identificacion Paciente"
                            v-model="paciente.cedula"
                            @keyup.enter="getPaciente"
                            density="compact"
                            background-color="grey lighten-5"
                            required
                            prepend-icon="mdi-account"
                            :rules="nRules">
                            <template v-slot:append>
                              
                              <v-tooltip location="top" text="Buscar Cedula">
                                <template v-slot:activator="{ props }">
                                  <v-icon v-bind="props" color="blue" @click="getPaciente" >mdi mdi-send</v-icon>
                                </template>
                               </v-tooltip>

                               <v-tooltip location="top" text="Consulta Paciente">
                                <template v-slot:activator="{ props }">
                                  <v-icon v-bind="props"class="ml-2" color="blue darken-2" @click="SearchClientMod=true; listPaciente=[]; searchclient=''">mdi-database-search</v-icon>
                                </template>
                               </v-tooltip>

                               <v-tooltip location="top" text="Crear Cliente">
                                <template v-slot:activator="{ props}"> 
                                  <v-icon v-bind="props"  color="pink" @click="ModNewPacientee" >mdi-plus-box-multiple</v-icon>
                                </template>
                               </v-tooltip>

                            </template>
                          </v-text-field>
                      </v-col>
                      <v-col cols="12" sm="4">
                          <v-text-field
                          v-model="paciente.nombre"
                          label="Cliente"
                          append-icon="mdi-account"
                          variant="underlined"
                          density="compact"
                          disabled>
                          </v-text-field>
                      </v-col>
                      <v-col cols="12" sm="4">
                          <v-text-field
                          v-model="paciente.direccion"
                          label="Direccion"
                          append-icon="mdi-map-marker"
                          variant="underlined"
                          density="compact"
                          disabled>
                          </v-text-field>
                      </v-col>
                      
                        <v-col cols="12" sm="8">
                          <v-select v-if="terapia" class="mb-3"
                            density="compact"
                            v-model="addPaciente.tratamiento"
                            :items="tratamientosPaciente"
                            item-title="tra_nombre"
                            item-value="tra_id"
                            label="Tratamiento"
                            required
                            :rules="nRules"
                            variant="underlined">
                          </v-select>
                        </v-col>
                        <v-col cols="12" sm="4">
                        </v-col>
                   
                    
                    
                      </v-row>
                      <v-text-field v-model="keys.detalle" label="Detalle"  required :rules="nRules" maxlength="250" variant="underlined" density="compact"></v-text-field>
                      <v-text-field v-model="keys.sintoma" label="Sintomas" maxlength="250" variant="underlined" density="compact"></v-text-field>
                      <v-text-field v-model="keys.observacion" label="Observación" variant="underlined" density="compact"></v-text-field>

                      
                  </div>
              </v-form>
              <v-card-actions>
                <v-row dense class="mx-2 mb-2 mt-2">
                      <v-btn
                          class="ma-1"
                          color="gray"
                          label
                          @click="CrearCita"
                          size="small"
                          variant="elevated"
                          prepend-icon="mdi mdi-note-plus">
                          <template v-slot:prepend>
                            <v-icon color="green"></v-icon>
                          </template>
                          CREAR CITA
                      </v-btn>

                      <v-btn
                          class="ma-1"
                          color="gray"
                          label
                          @click="LimpiarDatos"
                          size="small"
                          variant="elevated"
                          prepend-icon="mdi mdi-autorenew">
                          <template v-slot:prepend>
                            <v-icon color="red"></v-icon>
                          </template>
                          LIMPIAR DATOS
                      </v-btn>
                </v-row>
              </v-card-actions>
          </v-card>

        </v-col>
      </v-row>
    </div>

    <!--              -->
    <!-- Modals Forms -->
    <!--              -->

    <!-- Form Consulta Cliente -->
    <v-dialog v-model="SearchClientMod" max-width="700px">
        <v-card>
        <v-toolbar
            density="compact"
            color="blue">
            <v-app-bar-nav-icon> <v-icon>mdi-account-multiple-plus-outline</v-icon></v-app-bar-nav-icon>
            <v-toolbar-title><h5 class="font-weight-normal my-0">Consulta Cliente</h5></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="cerrarDialogConsulta" icon>
            <v-icon>mdi-close-circle</v-icon>
            </v-btn>
        </v-toolbar>
            <v-card-title class="py-0">
            <v-text-field
                v-model="searchclient"
                append-icon="mdi-send"
                label="Buscar"
                @click:append="getSearchPaciente"
                @keyup.enter="getSearchPaciente"
                hide-default-header
                disable-filtering
                disable-sort
                variant="underlined">
            </v-text-field>
            </v-card-title>
        <v-data-table
            :headers="headerclient"
            :items="listPaciente"
            height="280"
        >
            <template v-slot:item.action="{ item }">
            <v-btn x-small @click="getSearchPacienteSelectItem(item)" dark color="pink">add</v-btn>
            </template>
        </v-data-table>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="SearchClientMod=false">CERRAR</v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- From Crear Cliente -->
    <v-dialog v-model="NewPaciente" max-width="400px" max-height="700px">
        <v-card>
        <v-toolbar
            density="compact"
            color="blue">
            <v-app-bar-nav-icon> <v-icon>mdi-account-arrow-right</v-icon></v-app-bar-nav-icon>
            <v-toolbar-title><h5 class="font-weight-normal my-0">Crear Cliente</h5></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="cerrarDialogCrear" icon>
            <v-icon>mdi-close-circle</v-icon>
            </v-btn>
        </v-toolbar>

        <v-card-text>

            <v-form
            ref="form_paciente_add"
            v-model="valid"
            lazy-validation>

            <v-select class="mb-3"
              density="compact"
              v-model="addPaciente.tipo_contacto"
              :items="tipoContact"
              item-title="text"
              item-value="value"
              label="Tipo Conctacto"
              required
              :rules="nRules"
              variant="underlined">
            </v-select>

            <v-select
                density="compact"
                v-model="addPaciente.tipo_identidad"
                :items="items.identidad"
                item-title="tip_ide_nombre"
                item-value="tip_ide_id"
                label="Tipo Identidad"
                required
                :rules="nRules"
                variant="underlined">
            </v-select>

            <v-text-field density="compact" label="Identidad" v-model="addPaciente.cedula" required :rules="nRules" maxlength="40" variant="underlined"></v-text-field>
            <v-text-field density="compact" label="Nombre" v-model="addPaciente.nombre" required :rules="nRules" maxlength="150" variant="underlined"></v-text-field>
            <v-text-field density="compact" label="Dirección" v-model="addPaciente.direccion" required :rules="nRules" maxlength="255" variant="underlined"></v-text-field>
            <v-text-field density="compact" label="Teléfono" v-model="addPaciente.telefono" required :rules="nRules" maxlength="100" variant="underlined"></v-text-field>
            <v-autocomplete
                v-model="addPaciente.departamento"
                :items="items.departamento"
                item-title="dep_nombre"
                item-value="dep_id"
                label="Departamento"
                @update:model-value="getModPacienteLocalidad"
                required
                :rules="nRules"
                variant="underlined">
                </v-autocomplete>
                <v-autocomplete
                v-model="addPaciente.localidad"
                :items="items.localidad"
                item-title="loc_nombre"
                item-value="loc_id"
                label="Localidad"
                required
                :rules="nRules"
                density="compact"
                variant="underlined">
            </v-autocomplete>
            <v-text-field density="compact" label="E-mail" v-model="addPaciente.mail" maxlength="255" variant="underlined"></v-text-field>
            </v-form>

        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="CrearPaciente">CREAR</v-btn>
            <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="cerrarDialogCrear">CERRAR</v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script setup lang="ts">

  import { ref, onMounted, watch, isVNode,computed } from 'vue';
  import { nextTick } from 'vue'
  import { AuthStore } from '@/modules/common/stores/auth';
  import type { AuthStoreState } from '@/modules/common/types/index';
  import type { rsItem,rsPaciente,rsKey,rsInfo,rsListPaciente } from '@/modules/cita/types/createCitaInterface';
  import authApi from '@/api/api';
  import Swal from 'sweetalert2';
  import type { Header } from '@/modules/common/types/index';
  import pdfMake from 'pdfmake/build/pdfmake';
  import pdfFonts from 'pdfmake/build/vfs_fonts';
  import type { TDocumentDefinitions } from 'pdfmake/interfaces';
  import Paciente from '@/modules/common/views/Paciente.vue';
  import { VTimePicker } from 'vuetify/labs/VTimePicker';
  // Generador de reportes
  pdfMake.vfs = pdfFonts.pdfMake.vfs;

  // Global Varying
  const items = ref<rsItem>({tipocita:[], identidad:[],departamento:[],localidad:[],tratamiento:[]});
  const paciente = ref<rsPaciente>({tipo_identidad:'', tipo_contacto:'', cedula:'', nombre:'', direccion:' ',telefono:'',departamento:'',localidad:'', mail:'',tratamiento:''});
  const medico = ref<any>([]);
  const keys = ref<rsKey>({idtipo:'', idPaciente:'',idMedico:'',fecha: new Date().toISOString().split('T')[0], observacion:'', idTratamieto:'', sintoma:'',detalle:''});
  const terapia = ref<boolean>(false);
  const tratamientosPaciente = ref<any[]>([]);

  //Permisos y accesos
  const authStore = AuthStore() as AuthStoreState;
  const access_c_customer = ref<boolean>(false);

      // Report
  const info = ref<rsInfo>({ noCita:'', fechaCreacion:'', fechaEstado:'',tipo:'',medico:'',id_estado:'', estado:'', paciente:'', idPaciente:'', cedula:'', direccion:'', mail:'', telefono:'', localidad:'', observacion:'',cedulaMedico:'',detalle:'',sintoma:''});
      // Datapicker
  const date1 = ref(new Date());
  const date2 = ref(new Date());
  const hora = ref<any>('00:00');
  const menu = ref<boolean>(false);
  const menu2 = ref<boolean>(false);

      // Modulos
  const SearchClientMod = ref<boolean>(false);
  const searchclient = ref<string>('');

  const headerclient:(Header)[] = 
  [ 
      {title: 'CC/NIT', key: 'pac_cedula', align: 'start', sortable: false},
      {title: 'NOMBRE', key: 'pac_nombre', sortable: false},
      {title: 'ACCION', key: 'action', sortable: false } 
  ];

  const listPaciente = ref<rsListPaciente[]>([]);

  //Formularios y validacion
  const form_paciente_add = ref<any>(null);
  const form_main = ref<any>(null);
  const form_paciente_edit = ref<any>(null);


  const NewPaciente = ref<boolean>(false);
  const addPaciente = ref<rsPaciente>({tipo_identidad:'', tipo_contacto:'', cedula:'', nombre:' ', direccion:' ',telefono:'',departamento:'',localidad:'', mail:'',tratamiento:''});
  const tipoContact = ref([{text: 'Cliente', value:1},{text: 'Provedor', value:2},{text: 'Ambos', value:3}]);

  const EditPaciente = ref<boolean>(false);
  const ButtonEditPaciente = ref<boolean>(false);

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
      await getTipoCita()
  });

  // Watcher Para Fechas
  watch(date1, (val) => 
    {
        if (val) 
        {
          keys.value.fecha = formatDate(val);
        }
    });
  
    // Eventos dialogos
  const cerrarDialogCrear = () =>
  {
    NewPaciente.value = false;
    LimpiaraddPaciente()
  }

  const cerrarDialogConsulta = () => 
  {
    SearchClientMod.value = false;

  }
  
  const getTipoCita = async () =>
  {
    authApi.get('api/cita/info/tipoCita').then((res) =>
    {
      items.value.tipocita = res.data;
    });
  };

  const getTratamiento = async () =>
  {
    authApi.get('api/cita/tratamiento').then((res) =>
    {
        items.value.tratamiento= res.data;
    });
  };


  const getPaciente = async () =>
  {
    if (paciente.value.cedula==='') {
      Swal.fire("Oops!","Es necesario ingresar identificacion", "warning")
      ButtonEditPaciente.value = false
      return
    }

    paciente.value.nombre = '';
    paciente.value.direccion = '';
    authApi.get(`api/base/search/paciente/${paciente.value.cedula}`).then(res => {
      if (res.data.length===0)
      {
        ButtonEditPaciente.value = false
        if (access_c_customer.value)
        {
          Swal.fire(
          {
              title: 'Oops!',
              text: "Cliente no encontrado, Desea Crearlo??",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Crear'
          }).then((result) =>
          {
              if (result.value)
              {
                  ModNewPacientee();
              }
              else
              {
                  keys.value.idPaciente= ''
              }
          })
        }
        else
        {
          keys.value.idPaciente= ''
          Swal.fire(
          {
              title: 'Oops!',
              text: "Cliente no encontrado, No tiene permisos para crear cliente",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',

          })
        }
      }
      else
      {
        keys.value.idPaciente=res.data[0].pac_id
        getModPacienteIdentidad();
        paciente.value.tipo_contacto  = res.data[0].pac_tipo_contacto+1
        paciente.value.tipo_identidad = res.data[0].pac_id_tipo_identidad
        paciente.value.cedula = res.data[0].pac_cedula
        paciente.value.nombre = res.data[0].pac_nombre
        paciente.value.direccion = res.data[0].pac_direccion
        paciente.value.telefono = res.data[0].pac_telefono
        getModPacienteDepartamento();
        paciente.value.departamento = res.data[0].localidad.loc_id_departamento
        getModPacienteLocalidad();
        paciente.value.localidad = res.data[0].localidad.loc_id
        paciente.value.mail = res.data[0].pac_email
        ButtonEditPaciente.value = true
        mostrarTratamientos(keys.value.idPaciente)
      }
    })
  };

  const onSearchInput = (event: InputEvent) => 
    {
      const inputElement = event.target as HTMLInputElement;
      const val = inputElement.value;

      if (val && val.length > 0) 
      {
        const criterio = val.replace("/", "%2F");
        
      authApi.get(`api/base/search/medicof/'${criterio.toUpperCase()}'`)
        .then((res) => 
        {
          if (res.data.length > 0) 
          {
            medico.value = res.data;
          } else 
          {
            keys.value.idMedico=''
          }
        })
      } 
    };

  const getModPacienteIdentidad = async () =>
  {
    await authApi.get('api/base/info/tipoIdentidad').then((res) =>
    {
      items.value.identidad = res.data;
    });
  };

  const getModPacienteDepartamento = async () =>
  {
    await authApi.get('api/base/info/departamento').then((res) =>
    {
      items.value.departamento = res.data;
    });
  };

  const getModPacienteLocalidad = async () =>
  {
    const iddepto = NewPaciente.value ? addPaciente.value.departamento : paciente.value.departamento
    await authApi.get(`api/base/info/localidad/${iddepto}`).then((res) =>
    {
      items.value.localidad = res.data;
    });
  };

  const getSearchPaciente = async () =>
  {
    await authApi.get(`api/base/search/pacientev/${searchclient.value.toUpperCase()}`).then(res => {
      if (res.data.length===0) {
        listPaciente.value = []
      }
      else{
        listPaciente.value = res.data
      }
    })
  };

  const getSearchPacienteSelectItem = (item: any) =>
  {
    keys.value.idPaciente=item.pac_id
    paciente.value.tipo_contacto  = item.pac_tipo_contacto+1
    paciente.value.tipo_identidad = item.pac_id_tipo_identidad
    paciente.value.cedula = item.pac_cedula
    paciente.value.nombre = item.pac_nombre
    paciente.value.direccion = item.pac_direccion
    paciente.value.telefono = item.pac_telefono
    paciente.value.departamento = item.localidad.loc_id_departamento
    getModPacienteLocalidad()
    paciente.value.localidad = item.loc_id
    paciente.value.mail = item.pac_email
    paciente.value.tratamiento = item.tratamiento.tra_nombre
    SearchClientMod.value = false
    ButtonEditPaciente.value = true
  };

  const mostrarTratamientos = async (pacienteId: any) => 
  {
      await authApi.get(`api/base/search/obtenerTratamientos/${pacienteId}`).then(res =>
      {
          
          tratamientosPaciente.value = res.data;
          
      })
  };
  
  const ModNewPacientee = () =>
  {
    NewPaciente.value=true;
    getModPacienteIdentidad();
    getModPacienteDepartamento();
    addPaciente.value.cedula= paciente.value.cedula
  };

  const ModEditPacientee = async () =>
  {
    await getModPacienteIdentidad();
    await getModPacienteDepartamento();
    await getModPacienteLocalidad();
    EditPaciente.value = true;
  };

  const CrearPaciente = async () =>
  {
    const isValid = await form_paciente_add.value.validate()

    if(isValid.valid)
    {
      const params = {keys:addPaciente}
      await authApi.post('api/base/cliente',params).then(res =>
      {
        if (res.data === 0) 
        {
          Swal.fire("Oops!","Ocurrio un error al crear el registro, verifique los datos", "error")
        }
        else
        {
          Swal.fire("OK!", "Cliente Creado", "success");
          NewPaciente.value = false

          keys.value.idPaciente = res.data
          paciente.value.tipo_contacto = addPaciente.value.tipo_contacto+1
          paciente.value.tipo_identidad = addPaciente.value.tipo_identidad
          paciente.value.cedula = addPaciente.value.cedula
          paciente.value.nombre = addPaciente.value.nombre.toUpperCase()
          paciente.value.direccion =  addPaciente.value.direccion.toUpperCase()
          paciente.value.telefono =  addPaciente.value.telefono
          paciente.value.departamento = addPaciente.value.departamento
          getModPacienteLocalidad()
          paciente.value.localidad = addPaciente.value.localidad
          paciente.value.mail = addPaciente.value.mail

          LimpiaraddPaciente()
          ButtonEditPaciente.value = true
        }
      })
    }
  };

  const CrearCita = async () =>
  {
    const isValid = await form_main.value?.validate()

    if(isValid.valid)
    {
      concatFechas()
      keys.value.fecha
      const params = {keys:keys.value}
      await authApi.post('api/cita/cita',params).then(res =>
      {
        console.log(params);
        if (res.data === 0) {
          Swal.fire("Oops!", "Ocurrio un error al crear el registro, verifique los datos", "error")
        }
        else{
          Swal.fire({
            title: 'Registro Creado!',
            text: "Se creo el servicio No.: "+res.data,
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Imprimir Servicio',
            cancelButtonText: 'Ok'
          }).then((result) => {
            if (result.value) {
              downloadReport(res.data);
            }
            LimpiarDatos();
          })
        }
      })
    }
  };


  const formatDate = (newDate: Date) => 
  {
      const year = newDate.getFullYear()
      const month = String(newDate.getMonth() + 1).padStart(2, '0')
      const day = String(newDate.getDate()).padStart(2, '0')
      return `${year}-${month}-${day}`
  }

  const onTimeChange = (newTime: string) => 
  {
    formatDate(date1.value)
    hora.value = newTime;
    console.log(`Fecha: ${keys.value.fecha} , Hora: ${hora.value}`);
  };

  const concatFechas = () => 
  {
    const fecha = keys.value.fecha; // '2024-10-02'
    
    // Asegúrate de que hora tenga el formato HH:mm:ss
    const [hours, minutes, seconds] = hora.value.split(':');
    const formattedHour = `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}:${seconds.padStart(2, '0')}`;

    // Concatenar la fecha y la hora
    const formattedDateTime = `${fecha} ${formattedHour}`;

    // Asignar el nuevo valor a keys.value.fecha
    keys.value.fecha = formattedDateTime;
    console.log(`Fecha y Hora actualizadas: ${keys.value.fecha}`);
  };

  const onHourUpdate = (hour: number) => 
  {
      console.log('Updated hour:', hour);
  };

  const onMinuteUpdate = (minute: number) => 
  {
      console.log('Updated minute:', minute);
  };

  const onPeriodUpdate = (newPeriod: 'am' | 'pm') => 
  {
      console.log('Updated period:', newPeriod);
      // Lógica opcional para AM/PM
  };

  const downloadReport = (no_cita:string) =>
  {
    info.value.noCita    = no_cita
    info.value.fechaCreacion = new Date().toJSON().slice(0,10).replace(/-/g,'/')
    info.value.tipo          = keys.value.idtipo.tip_ser_nombre
    info.value.paciente       = paciente.value.nombre
    info.value.cedula        = paciente.value.cedula
    info.value.direccion     = paciente.value.direccion
    info.value.localidad     = paciente.value.localidad
    info.value.telefono      = paciente.value.telefono
    info.value.sintoma     = keys.value.sintoma.toUpperCase()
    info.value.observacion   = keys.value.observacion.toUpperCase()
    info.value.detalle = keys.value.detalle.toUpperCase()

    // printServicio()
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
    Object.keys(paciente.value).forEach((key) => {
      (paciente.value as any)[key] = '';
    });

    ButtonEditPaciente.value = false;

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

  const LimpiaraddPaciente = () =>
  {
    Object.keys(addPaciente.value).forEach((key) => 
    {
        (addPaciente.value as any)[key] = '';
    });
  };
</script>
