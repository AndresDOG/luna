<template>
    <v-list>
        <v-list-item>
            <template v-slot:prepend>
                <v-avatar>
                    <v-icon icon="mdi-magnify" color="pink" size="x-large"></v-icon>
                </v-avatar>
            </template>
            <v-list-item-title><h4 class="font-weight-bold my-0">Consulta Paciente</h4></v-list-item-title>
            <v-list-item-subtitle class="text-caption grey--text">Contacto>Consulta>Paciente</v-list-item-subtitle>
        </v-list-item>
    </v-list>

    <div class="my-3">
        <v-row class="px-lg-5">
            <v-col cols="12">
                <v-card outlined>
                    <v-toolbar flat density="compact" color="grey-lighten-4">
                        <template v-slot:prepend>
                            <v-avatar>
                                <v-icon color="pink"  icon="mdi-clipboard-list-outline"></v-icon>
                            </v-avatar>
                        </template>
                        <v-toolbar-title><h5 class="text-subtitle-1 font-weight-bold">Criterio de Busqueda</h5></v-toolbar-title>
                        <template v-slot:append>
                            <v-avatar>
                                <v-icon  icon="mdi-information-outline"></v-icon>
                            </v-avatar>
                        </template>
                    </v-toolbar>
                    <v-form
                        ref="form_main"
                        v-model="valid"
                        lazy-validation>
                    <v-card-text class="pb-0">
                        <v-row>
                            <v-col cols="12" sm="3">
                                <v-text-field
                                v-model="parametro"
                                @keyup.enter="getConsulta"
                                label="Buscar"
                                dense
                                required
                                :rules="nRules"
                                variant="underlined"
                                density="compact"
                                prepend-icon="mdi-layers-search-outline"
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="12" sm="3">
                                <v-select
                                v-model="selCriterio"
                                :items="itemCriterios"
                                item-title="title"
                                item-value="value"
                                label="Criterio"
                                class="v-text-field-input"
                                dense
                                required
                                :rules="mRules"
                                persistent-hint
                                variant="underlined"
                                density="compact"
                                >
                                <template v-slot:prepend>
                                    <v-icon color="info">mdi-view-list</v-icon>
                                </template>
                                </v-select>
                            </v-col>
                            <v-col cols="4" sm="3">
                                <v-chip
                                size="default"
                                class="ma-1"
                                color="info"
                                variant="outlined" 
                                @click="getConsulta"
                                label
                                >
                                <v-avatar left>
                                    <v-icon color="blue">mdi-clipboard-text-search-outline</v-icon>
                                </v-avatar>
                                    Buscar
                                </v-chip>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-form>
                </v-card>
            </v-col>
        </v-row>
    </div>

    <v-row class="px-lg-5">
        <v-col cols="12">
            <v-card outlined>
                <v-toolbar density="compact" flat color="grey-lighten-4">
                    <v-app-bar-nav-icon><v-icon color="pink">mdi-clipboard-list-outline</v-icon></v-app-bar-nav-icon>
                    <v-toolbar-title><h5 class="font-weight-normal my-0">Resultado de la Busqueda</h5></v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-icon>mdi-information-outline</v-icon>
                </v-toolbar>
                <v-card-title class="d-flex align-center pe-2 mt-2">
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Buscar"
                        single-line
                        hide-details
                        variant="underlined"
                    >
                    </v-text-field>
                </v-card-title>
                <v-card-text class="pb-0">
                    <v-data-table class="text-caption" 
                        :headers="headers"
                        :items="itemConsulta"
                        :search="search"
                    >
                        <template v-slot:item.pac_cedula="{ item }">
                            <v-chip variant="outlined" size="small" color="blue" dark>{{ item.pac_cedula }}</v-chip>
                        </template>
                        <template v-slot:item.pac_nombre="{ item }">
                            <span class="text-blue-grey-darken-1"> {{ item.pac_nombre }}</span>
                        </template>
                        <template v-slot:item.pac_telefono="{ item }">
                            <span class="text-blue-grey-darken-1"> {{ item.pac_telefono }}</span>
                        </template>
                        <template v-slot:item.idaccion="{ item }">
                            <v-tooltip top>
                                <template v-slot:activator="{ props }">
                                    <v-icon v-if="access_vpaciente" v-bind="props" density="compact" color="cyan" @click="goToViewService(item.pac_id)">mdi-eye</v-icon>
                                </template>
                                <span>Ver Paciente</span>
                            </v-tooltip>
                            <span class="mx-1"></span>
                            <v-tooltip top>
                                <template v-slot:activator="{ props }">
                                    <v-icon v-if="access_ctratamiento" v-bind="props" density="compact" color="green" @click="abrirDialog(item.pac_id)">mdi mdi-plus-outline</v-icon>
                                </template>
                                <span>Asignar Tratamiento</span>
                            </v-tooltip>
                            <span class="mx-1"></span>
                            <v-tooltip top>
                                <template v-slot:activator="{ props }">
                                    <v-icon  v-bind="props" density="compact" color="red" @click="mostrarTratamientos(item.pac_id)">mdi mdi-clipboard-list-outline</v-icon>
                                </template>
                                <span>Ver Tratamientos</span>
                            </v-tooltip>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-col>
        <!-- Asginar Tratamiento -->
    <v-dialog v-model="New" max-width="400px">
        <v-card>
            <v-toolbar
                dark
                density="compact"
                color="blue">
                <v-app-bar-nav-icon> <v-icon>mdi-account-arrow-right</v-icon></v-app-bar-nav-icon>
                <v-toolbar-title><h5 class="font-weight-normal my-0">Asignar Tratamiento</h5></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn @click="New = false; nombre=''" icon>
                <v-icon>mdi-close-circle</v-icon>
                </v-btn>
            </v-toolbar>

            <v-card-text>
                
                <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation>

                    <v-select 
                    v-model="seltratamiento"
                    :items="tratamientos"
                    item-title="tra_nombre"
                    item-value="tra_id"
                    label="Tratamiento"
                    class="mb-3"
                    dense
                    required
                    :rules="mRules"
                    persistent-hint
                    variant="underlined"
                    density="compact"
                    >
                    <template v-slot:prepend>
                        <v-icon color="info">mdi-view-list</v-icon>
                    </template>
                    </v-select>
                </v-form>
                
                          <v-menu class="mb-3"
                          v-model="menu"
                          :close-on-content-click="false"
                          transition="scale-transition"
                          offset-y
                          max-width="290px"
                          min-width="290px">
                          <template v-slot:activator="{ props }">
                              <v-text-field
                              v-model="fechaInicio"
                              label="Fecha Inicio"
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
                      
                          <v-spacer></v-spacer>
                          <v-menu class="mb-3"
                          v-model="menu2"
                          :close-on-content-click="false"
                          transition="scale-transition"
                          offset-y
                          max-width="290px"
                          min-width="290px">
                          <template v-slot:activator="{ props }">
                              <v-text-field
                              v-model="fechaFin"
                              label="Fecha Fin"
                              persistent-hint
                              prepend-icon="mdi-calendar"
                              readonly
                              density="compact"
                              variant="underlined"
                              :rules="dateRules"
                              v-bind="props">
                              </v-text-field>
                              </template>
                          <v-date-picker color="primary" header-color="primary" width="300" height="470" elevation="5" v-model="date2"  no-title @update:model-value="formatDate"></v-date-picker>
                          </v-menu>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="asignarTratamiento()">Asignar</v-btn>
                <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="New=false; nombre=''">CERRAR</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    </v-row>
    <v-dialog v-model="dialogTratamientos" max-width="700px">
        <v-card >
            <v-toolbar
            density="compact"
            color="blue">
            <v-app-bar-nav-icon> <v-icon>mdi-account-multiple-plus-outline</v-icon></v-app-bar-nav-icon>
            <v-toolbar-title><h5 class="font-weight-normal my-0">Consulta Tratamientos</h5></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="dialogTratamientos = false" icon>
            <v-icon>mdi-close-circle</v-icon>
            </v-btn>
        </v-toolbar>
            <v-card-text class="pa-4">
                <v-data-table
                    :headers="headersTratamientos"
                    :items="tratamientosPaciente"
                    :search="searchTratamientos"
                    class="elevation-1 rounded-lg"
                >
                    <template v-slot:top>
                        <v-card-title class="py-0">
                        <v-text-field
                            v-model="searchTratamientos"
                            label="Buscar"
                            append-icon="mdi-send"
                            class="mx-4 mt-4"
                            density="compact"
                            hide-default-header
                            disable-filtering
                            disable-sort
                            variant="underlined"
                        ></v-text-field>
                    </v-card-title>
                    </template>
                    <template  v-slot:item.tra_nombre="{ item }">
                        <span class="text-blue-grey-darken-1"> {{ item.tra_nombre }}</span>
                    </template>
                    <template v-slot:item.fec_inicio="{ item }">
                            <div>
                              <v-chip syze="small" variant="outlined" color="info" dark>{{ item.fec_inicio }}</v-chip>
                            </div>
                    </template>
                    <template v-slot:item.fec_fin="{ item }">
                            <div>
                              <v-chip syze="small" variant="outlined" color="info" dark>{{ item.fec_fin }}</v-chip>
                            </div>
                    </template>
                    <template v-slot:item.estado="{ item }">
                            <div v-if="item.estado==3">
                              <v-chip syze="small" variant="outlined" color="red" dark>SUSPENDIDO</v-chip>
                            </div>
                            <div v-if="item.estado==5">
                              <v-chip syze="small" variant="outlined" color="blue" dark>TERMINADO</v-chip>
                            </div>
                            <div v-if="item.estado==1">
                              <v-chip syze="small" variant="outlined" color="green" dark>ACTIVO</v-chip>
                            </div>
                    </template>
                </v-data-table>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="dialogTratamientos=false">CERRAR</v-btn>
                </v-card-actions>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script setup lang="ts">

    import { ref, onMounted, watch,computed } from 'vue';
    import { AuthStore } from '@/modules/common/stores/auth';
    import type { AuthStoreState } from '@/modules/common/types/index';
    import { useRouter } from 'vue-router';
    import authApi from '@/api/api';
    import Swal from 'sweetalert2';
    import type { Header } from '@/modules/common/types/index';
    import type { CriterioItem } from '@/modules/common/types/consultaInterface';
import { log } from 'console';
    // Para usar Rutas
    const router = useRouter();

    // Variables Globales
    const New = ref<boolean>(false);
    const paciente = ref(0);
    const nombre = ref<string>('');
    const fechaInicio = ref(new Date().toISOString().split('T')[0]);
    const fechaFin = ref(new Date().toISOString().split('T')[0]);
    const parametro = ref<string>('');
    const selCriterio = ref<string>('');
    const tratamientos = ref([]);
    const seltratamiento = ref<string>('');

    // Datapicker
    const date1 = ref(new Date());
    const date2 = ref(new Date());
    const menu = ref<boolean>(false);
    const menu2 = ref<boolean>(false);
    
    const itemCriterios = ref<CriterioItem[]>([
        { title: "Cedula", value: 1 },
        { title: "Telefono", value: 3 },
        { title: "Nombre", value: 2 },
    ]);

    // Formularios
    const form_main = ref<any>(null);
    const form = ref<any>(null);

    // Datatable
    const search = ref<string>('');
    const headers:(Header)[] = 
    [
       
        { title: "CEDULA", key: "pac_cedula", sortable: false },
        { title: "NOMBRE", key: "pac_nombre", sortable: false },
        { title: "TELEFONO", key: "pac_telefono", sortable: false },
        { title: 'ACCION', key: 'idaccion',sortable: false }
    ]

    const dialogTratamientos = ref<boolean>(false);
    const tratamientosPaciente = ref<any[]>([]);
    const searchTratamientos = ref<string>('');

    const headersTratamientos: Header[] = 
    [
        { title: "TRATAMIENTO", key: "tra_nombre", sortable: false },
        { title: "FECHA INICIO", key: "fec_inicio", sortable: false },
        { title: "FECHA FIN", key: "fec_fin", sortable: false },
        { title: "ESTADO", key: "estado", sortable: false },
    ];

    const itemConsulta = ref<[]>([]);

    // Validacion y Reglas
    const authStore = AuthStore() as AuthStoreState;
    const accessAdmin = ref<boolean>(false);
    const access_vpaciente = ref<boolean>(false);
    const access_ctratamiento = ref<boolean>(false);

    let valid:boolean = true;
    let nRules = 
    [
        (v: string): boolean | string => !!v || 'Requerido'
    ];
    const mRules = 
    [
        (m: string | null): boolean | string => !!m || 'Seleccione un criterio'
    ];

    const dateRules = 
    [
        (v: string): boolean | string => !!v || 'Requerido',
        (v: string): boolean | string => 
        {
            if (v === fechaFin.value && new Date(v) < new Date(fechaInicio.value)) 
            {
                return 'Rango no valido';
            }
            return true;
        }
    ];

    onMounted(() => 
    {
        getTratamiento();
        controlPermiso();
    });

    // Watcher Para Fechas
    watch(date1, (val) => 
    {
        if (val) 
        {
            fechaInicio.value = formatDate(val);
        }
    });

    watch(date2, (val) => {
    if (val) {
        // Validamos que la fecha fin no sea menor que la fecha inicio
        if (new Date(val) < new Date(fechaInicio.value)) {
            
            date2.value = new Date(fechaInicio.value);
            fechaFin.value = fechaInicio.value;
            return;
        }
        fechaFin.value = formatDate(val);
    }
});

    const formatDate = (newDate: Date) => 
    {
        const year = newDate.getFullYear()
        const month = String(newDate.getMonth() + 1).padStart(2, '0')
        const day = String(newDate.getDate()).padStart(2, '0')
        return `${year}-${month}-${day}`
    }

    // Router
    const goToViewService = (item: number) => 
    {
        router.push({name:'service.view',params:{id_servicio: item.toString()}})
    }

    const getConsulta = async () =>
    {
        const isValid = await form_main.value?.validate()

        if(isValid.valid)
        {

            itemConsulta.value = [];
            const params = 
            {
                parametro: parametro.value,
                criterio: selCriterio.value,
            };

            console.log(params);
            

            await authApi.post("api/base/info/consultaSimple", params).then((res) => 
            {
                if (res.data == 0) 
                {
                    Swal.fire("Oops!", "Paciente no encontrado", "warning");
                    return;
                }
                
                itemConsulta.value = res.data;
            });
        }
    }

    const getTratamiento = async () =>
    {
        authApi.get('api/base/tratamiento').then((res) =>
        {
            tratamientos.value = res.data;
        });

    };

    const mostrarTratamientos = async (pacienteId: number) => 
    {
        await authApi.get(`api/base/search/obtenerTratamientos/${pacienteId}`).then(res =>
        {
            
            tratamientosPaciente.value = res.data;
            dialogTratamientos.value = true;
        })
    };

    const abrirDialog = (pac_id:any) => 
    {
        New.value = true;
        paciente.value = pac_id
    };

    const asignarTratamiento = async () =>
    {
        const isValid = await form.value?.validate()

        if(isValid.valid)
        {
            if (new Date(fechaFin.value) < new Date(fechaInicio.value)) 
            {
                return;
            }
            const params = {paciente:paciente.value,tratamiento:seltratamiento.value,fechaInicio: fechaInicio.value, fechaFin:fechaFin.value};
            await authApi.post('api/base/process/asignarTratamiento',params).then(res =>
            {
                if (res.data === 0)
                {
                    Swal.fire("Oops!","Ocurrio un error al crear el registro, verifique los datos, el registro puede estar creado ya", "error")
                }
                else
                {
                    New.value = false
                    nombre.value = ''
                   
                    Swal.fire("OK!", "Registro creado con éxito", "success");
                }
            })
        }
    }

    const controlPermiso = () =>
    {
    
        const componente7 = authStore.permisos.find((p:any) => p.per_id_componente === 7);
        if(componente7)
        {
            const rs =  JSON.parse(componente7.per_acceso);

            access_vpaciente.value = rs.Ver_Paciente === "1" ? true : false;
            access_ctratamiento.value = rs.Asignar_Tratamiento === "1" ? true : false;
            
        }

    }
</script>