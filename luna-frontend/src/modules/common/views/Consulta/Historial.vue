<template>
     <v-list>
        <v-list-item>
            <template v-slot:prepend>
                <v-avatar>
                    <v-icon icon="mdi-magnify" color="pink" size="x-large"></v-icon>
                </v-avatar>
            </template>
            <v-list-item-title><h4 class="font-weight-bold my-0">Historial Clinico</h4></v-list-item-title>
            <v-list-item-subtitle class="text-caption grey--text">Contacto>Consulta>Historial Clinico</v-list-item-subtitle>
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
        <v-row class="px-lg-5">
        <v-col cols="12">
          <v-card>
            <v-toolbar flat density="compact" color="grey-lighten-4">
                        <template v-slot:prepend>
                            <v-avatar>
                                <v-icon color="pink"  icon="mdi-clipboard-list-outline"></v-icon>
                            </v-avatar>
                        </template>
                        <v-toolbar-title><h5 class="text-subtitle-1 font-weight-bold">Resultado de la Busqueda</h5></v-toolbar-title>
                        <template v-slot:append>
                            <v-avatar>
                                <v-icon  icon="mdi-information-outline"></v-icon>
                            </v-avatar>
                        </template>
            </v-toolbar>
            <v-card-title class="text-h5 py-4 px-4 d-flex justify-space-between align-center">
              
              <!-- Filtro de Fecha -->
              <v-menu v-model="menuDate" :close-on-content-click="false" transition="scale-transition">
                <template v-slot:activator="{ props }">
                  <v-btn color="primary" v-bind="props" prepend-icon="mdi-calendar">
                    Filtrar por Fecha: {{ selectedDate ? formatDate(selectedDate) : 'Seleccionar' }}
                  </v-btn>
                </template>
                <v-card min-width="300">
                  <v-card-text class="pa-0">
                    <v-date-picker v-model="selectedDate" @update:model-value="menuDate = false"></v-date-picker>
                  </v-card-text>
                </v-card>
              </v-menu>
            </v-card-title>
  
            <!-- Información del paciente -->
            <v-card-text v-if="paciente">
  <v-row>
    <v-col cols="12" md="6">
      <v-sheet color="grey-lighten-4" class="pa-4" elevation="2" rounded="lg">
        <v-subheader  class="text-h6"><v-chip
    class="ma-1"
    color="primary"
    text-color="white"
    outlined
    rounded
    style="font-weight: bold; font-size: 1.1rem;"
>
    <v-avatar left>
        <v-icon color="white">mdi-information-outline</v-icon>
    </v-avatar>
    DATOS
</v-chip></v-subheader>
        <v-divider></v-divider>
        
        <v-list>
          <v-list-item prepend-icon="mdi mdi-account">
            <v-list-item-content>
              <v-list-item-title><strong>Nombre:</strong> {{ paciente.pac_nombre }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item prepend-icon="mdi mdi-card-account-details-outline">
            <v-list-item-content >
              <v-list-item-title><strong>Cédula:</strong> {{ paciente.pac_cedula }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item prepend-icon="mdi mdi-calendar-account-outline">
            <v-list-item-content>
              <v-list-item-title><strong>Fecha Nacimiento:</strong> {{ formatDate(new Date(paciente.pac_fecha_nacimiento)) }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item prepend-icon="mdi-email">
            <v-list-item-content>
              <v-list-item-title><strong>Email:</strong> {{ paciente.pac_email }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item prepend-icon="mdi-phone">
            <v-list-item-content>
              <v-list-item-title><strong>Teléfono:</strong> {{ paciente.pac_telefono }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-sheet>
    </v-col>
  </v-row>
</v-card-text>
  
            <!-- Tabs para secciones de Tratamientos y Citas -->
            <v-tabs slider-color="pink" fixed-tabs color="indigo" bg-color="grey-lighten-4" icons-and-text v-model="selectedTab" grow>
              <v-tab value="0">Tratamientos</v-tab>
              <v-tab value="1">Citas</v-tab>
            </v-tabs>
  
            <v-window v-model="selectedTab">
              <!-- Tab de Tratamientos -->
              <v-window-item value="0">
                <v-card-text>
                  <v-data-table :items="filteredTratamientos" :headers="tratamientoHeaders" item-key="tra_id" class="elevation-1">
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
                </v-card-text>
              </v-window-item>
  
              <!-- Tab de Citas -->
              <v-window-item value="1">
                <v-card-text>
                  <v-data-table :items="filteredCitas" :headers="citaHeaders" item-key="cit_no_cita" class="elevation-1">
                    <template v-slot:item.cit_no_cita="{ value }">
                <v-chip size="small" color="blue" variant="outlined">{{ value }}</v-chip>
              </template> 

              <template v-slot:item.tip_cita_nombre="{ value }">
                <v-chip size="small" color="green" variant="outlined">{{ value }}</v-chip>
              </template>
              <template v-slot:item.cita_fecha="{ value }">
                <v-chip size="small" color="red" variant="outlined">{{ value }}</v-chip>
              </template>

              <template v-slot:item.estado_nombre="{ value }">
                <v-chip size="small" :color="eventColor(value)" variant="outlined">{{ value }}</v-chip>
              </template>
                  </v-data-table>
                  <div v-if="!filteredCitas.length" class="text-center pa-4">No hay citas registradas para la fecha seleccionada</div>
                </v-card-text>
              </v-window-item>
            </v-window>
          </v-card>
        </v-col>
      </v-row>
    
    </div>
  
      
  </template>
  <script setup lang="ts">
  import { ref, computed, watch, onMounted } from 'vue'
  import Swal from 'sweetalert2'
  import authApi from '@/api/api';
  import type { Header } from '@/modules/common/types/index';
  import type { CriterioItem } from '@/modules/common/types/consultaInterface';
  
  const loading = ref(false)
  const paciente = ref<any>(null)
  const tratamientos = ref<any[]>([])
  const citas = ref<any[]>([])
  const selectedTab = ref(0)
  const menuDate = ref(false)
  const selectedDate = ref<any>(null); // Cambiado a any
  const itemConsulta = ref<any[]>([])
  const parametro = ref('')
  const selCriterio = ref(null)
  const valid = ref(false)
  
  const nRules = 
    [
        (v: string): boolean | string => !!v || 'Requerido'
    ];
   const mRules = 
    [
        (m: string | null): boolean | string => !!m || 'Seleccione un criterio'
    ];

  // Configuración de criterios de búsqueda
  const itemCriterios = ref<CriterioItem[]>([
        { title: "Cedula", value: 1 },
        { title: "Telefono", value: 3 },
        { title: "Nombre", value: 2 },
    ]);

    function eventColor(estado: number): string 
    {
      if (estado === 2) return 'red';
      else if (estado === 3) return 'pink';
      else if (estado === 4) return 'indigo';
      else if (estado === 5) return 'green';
      else if (estado === 12) return 'cyan lighten-2';
      else return 'blue';
    }
  
  // Configuración de estados de citas
  const estadosCita = {
    1: { nombre: 'PROGRAMADA', color: 'info' },
    2: { nombre: 'CONFIRMADA', color: 'success' },
    3: { nombre: 'EN CURSO', color: 'warning' },
    4: { nombre: 'FINALIZADA', color: 'grey' },
    5: { nombre: 'CANCELADA', color: 'error' }
  }
  
  // Headers de tablas
  const tratamientoHeaders: Header[] = [
    { title: 'Tratamiento', key: 'tra_nombre' },
    { title: 'Fecha Inicio', key: 'fec_inicio' },
    { title: 'Fecha Fin', key: 'fec_fin' },
    { title: 'Estado', key: 'estado' }
  ]
  
  const citaHeaders: Header[] = [
    { title: 'No. Cita', key: 'cit_no_cita' },
    { title: 'Fecha', key: 'cita_fecha' },
    { title: 'Médico', key: 'med_nombre' },
    { title: 'Tipo de Cita', key: 'tip_cita_nombre' },
    { title: 'Estado', key: 'estado_nombre' }
  ]
  
  // Función para formatear fecha
  const formatDate = (date: Date) => `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`
  
  // Función para obtener datos de citas y tratamientos del paciente
  const obtenerCitasYTratamientos = async (pacienteId: number) => {
    loading.value = true
    try {
      const { data: citasData } = await authApi.get(`api/cita/search/obtenerCitaPaciente/${pacienteId}`)
      const { data: tratamientosData } = await authApi.get(`api/base/search/obtenerTratamientos/${pacienteId}`)
      citas.value = citasData
      tratamientos.value = tratamientosData
    } catch (error) {
      Swal.fire("Error", "No se pudo cargar la información del paciente", "error")
    } finally {
      loading.value = false
    }
  }
  
  
  // Función para consultar paciente
  const getConsulta = async () => {
    if (!valid.value) return
  
    itemConsulta.value = []
    const params = { parametro: parametro.value, criterio: selCriterio.value }
    const { data } = await authApi.post("api/base/info/consultaSimple", params)
    
    if (data === 0) {
      Swal.fire("Oops!", "Paciente no encontrado", "warning")
      return
    }
  
    itemConsulta.value = data
    paciente.value = itemConsulta.value[0]
    await obtenerCitasYTratamientos(paciente.value.pac_id)
  }
  
  // Computed para filtrar tratamientos y citas por fecha seleccionada
  const filteredTratamientos = computed(() => selectedDate.value ? tratamientos.value.filter(t => formatDate(new Date(t.fec_inic)) === formatDate(selectedDate.value) || formatDate(new Date(t.rec_fin)) === formatDate(selectedDate.value)) : tratamientos.value)
  const filteredCitas = computed(() => selectedDate.value ? citas.value.filter(c => formatDate(new Date(c.cita_fecha)) === formatDate(selectedDate.value)) : citas.value)
  
  // Al montar el componente
  onMounted(() => getConsulta())
  </script>
  <style scoped>
  .v-table {
    background: transparent !important;
  }
  </style>