<template>
    <v-list>
        <v-list-item>
            <template v-slot:prepend>
                <v-avatar>
                    <v-icon icon="mdi-magnify" color="pink" size="x-large"></v-icon>
                </v-avatar>
            </template>
            <v-list-item-title><h4 class="font-weight-bold my-0">Consulta Cita</h4></v-list-item-title>
            <v-list-item-subtitle class="text-caption grey--text">Cita>Consulta>Cita</v-list-item-subtitle>
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
                        <template v-slot:item.cit_no_cita="{ item }">
                            <v-chip variant="outlined" size="small" color="blue" dark>{{ item.cit_no_cita }}</v-chip>
                        </template>
                        <template v-slot:item.estado_nombre="{ item }">
                            <v-chip variant="outlined" size="small" :color="eventColor(item.estado_id)">{{ item.estado_nombre }}</v-chip>
                        </template>
                        <template v-slot:item.pac_nombre="{ item }">
                            <span class="text-caption blue-grey--text">{{ item.pac_nombre }}</span>
                        </template>
                        <template v-slot:item.idaccion="{ item }">
                            <v-tooltip v-if="access_vcita" top>
                                <template v-slot:activator="{ props }">
                                    <v-icon  v-bind="props" density="compact" color="cyan" @click="goToViewCita(item.cita_id)">mdi-eye</v-icon>
                                </template>
                                <span>Ver Cita</span>
                            </v-tooltip>
                            <span class="mx-1"></span>
                            <v-tooltip v-if="access_ecita" top>
                                <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props"  density="compact" color="blue-grey" @click="goToEditCita(item.cita_id)">mdi-pencil</v-icon>
                                </template>
                                <span>Editar Cita</span>
                            </v-tooltip>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

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

// Para usar Rutas
const router = useRouter();

// Variables Globales
const noCita = ref<string>('');
const selCriterio = ref<string>('');
const parametro = ref<string>('');
const itemCriterios = ref<CriterioItem[]>([
    { title: "Cedula", value: 1 },
    { title: "Telefono", value: 3 },
    { title: "Nombre", value: 2 },
    { title: "No Cita", value: 4 },
]);

// Formularios
const form_main = ref<any>(null);

// Datatable
const search = ref<string>('');
const headers:(Header)[] = 
[
    {
        title: "CITA",
        align: "start",
        key: "cit_no_cita",
        sortable: false,
    },
    {
        title: "TIPO",
        key: "tip_cita_nombre",
        sortable: false,
    },
    
      { title: 'FECHA', align: 'start', key: 'cita_fecha', sortable: false },
      { title: 'MEDICO', align: 'start', key: 'med_nombre', sortable: false },
      { title: 'PACIENTE', align: 'start', key: 'pac_nombre', sortable: false },
      { title: 'ESTADO', align: 'start', key: 'estado_nombre', sortable: false },
      { title: 'ACCION', align: 'center', key: 'idaccion', sortable: false }
]
const itemConsulta = ref<[]>([]);

// Validacion y Reglas
const authStore = AuthStore() as AuthStoreState;
const accessAdmin = ref<boolean>(false);
const access_vcita = ref<boolean>(false);
const access_ecita = ref<boolean>(false);

let valid:boolean = true;
let nRules = 
[
    (v: string): boolean | string => !!v || 'Requerido'
];
const mRules = 
[
    (m: string | null): boolean | string => !!m || 'Seleccione un criterio'
];

onMounted(() => 
{
    controlPermiso();
});

// Router
const goToViewCita = (item: number) => 
{
    router.push({name:'cita.view',params:{id_cita: item.toString()}})
}

const goToEditCita = (item: number) => 
{
    router.push({name:'cita.edit', params:{accion:0,id_cita: item.toString()}})
}

// Methods
function eventColor(estado: number): string 
    {
      if (estado === 1) return 'blue';
      else if (estado === 4) return 'pink';
      else if (estado === 3) return 'green';
      else if (estado === 5) return 'red';
      else return 'blue';
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

        await authApi.post("api/cita/info/consultaSimple", params).then((res) => 
        {
            console.log(params);
            if (res.data == 0) 
            {
                Swal.fire("Oops!", "Cita no encontrado", "warning");
                return;
            }

            if (res.data == 1) 
            {
                Swal.fire("Oops!", "Cita no esta asignado al técnico", "warning");
                return;
            }

            itemConsulta.value = res.data;
        });
    }
}

const controlPermiso = () =>
{
   if(authStore.user.rol.rol_tipo = 1) 
   {
     accessAdmin.value = true;
   }

   const componente21 = authStore.permisos.find((p:any) => p.per_id_componente === 21);
    if(componente21)
    {
        const rs =  JSON.parse(componente21.per_acceso);

        access_vcita.value = rs.Ver_Cita === "1" ? true : false;
        access_ecita.value= rs.Editar_Cita === "1" ? true : false;
    }   
}
 


</script>