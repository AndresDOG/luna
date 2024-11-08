<template>
  
  <v-list>
    <v-list-item>
      <template v-slot:prepend>
        <v-avatar>
          <v-icon color="pink" icon="mdi-monitor-dashboard" size="x-large"></v-icon>
        </v-avatar>
      </template>
      <v-list-item-title class="font-weight-bold my-0">Información Citas</v-list-item-title>
      <v-list-item-subtitle>Dashboard</v-list-item-subtitle>
    </v-list-item>
  </v-list>

  <div class="mt-3 mx-3 px-3">
  </div>

  <v-row class="px-lg-5">
    <v-col cols="12">
      <v-card>
        <v-toolbar color="grey-lighten-4" density="compact">
          <template v-slot:prepend>
            <v-avatar>
              <v-icon color="pink"  icon="mdi-clipboard-list-outline"></v-icon>
            </v-avatar>
          </template>

          <v-toolbar-title class="text-subtitle-1 font-weight-bold">Citas Asignadas</v-toolbar-title>           
          
          <template v-slot:append>
            <v-avatar>
              <v-icon @click="getservicioAsignado" icon="mdi-information-outline"></v-icon>
            </v-avatar>
          </template>
        </v-toolbar>

        <v-card-title class="d-flex align-center pe-2 mt-2">
          <v-spacer></v-spacer>

          <v-text-field
            v-model="search"
            density="compact"
            label="Search"
            prepend-inner-icon="mdi-magnify"
            variant="solo-filled"
            flat
            hide-details
            single-line
          ></v-text-field>
        </v-card-title>

        <v-card-text>
          
            <v-data-table             
              v-model:search="search"   
              :headers="headers"
              :items="itemConsulta"                            
              itemsLength="100"    
              :header-props="headerProps"      
              :loading="loading"                 
            >   
              <template v-slot:item.cit_no_cita="{ value }">
                <v-chip size="small" color="blue" variant="outlined">{{ value }}</v-chip>
              </template> 

              <template v-slot:item.tip_cita_nombre="{ value }">
                <v-chip size="small" color="green" variant="outlined">{{ value }}</v-chip>
              </template>

              <template v-slot:item.dias="{ value }">
                <div v-if="value > 5">
                  <v-badge color="warning" icon="mdi-alert" overlap>
                    <v-chip size="small" color="red">{{ value }}</v-chip>
                  </v-badge>
                </div>
                <div v-else>
                  <v-chip size="small" color="green">{{ value }}</v-chip>
                </div>
              </template>

              <template v-slot:item.cita_fecha="{ value }">
                <v-chip size="small" color="red" variant="outlined">{{ value }}</v-chip>
              </template>

              <template v-slot:item.estado_nombre="{ value }">
                <v-chip size="small" :color="eventColor(value)" variant="outlined">{{ value }}</v-chip>
              </template>

              <template v-slot:item.idaccion="{ item }: { item: any }">
                <v-tooltip text="Ver Cita" location="top">
                  <template v-slot:activator="{ props }">
                    <v-icon v-if="access_servicio" 
                      size="small" 
                      color="cyan" 
                      v-bind="props" 
                      icon="mdi-eye"
                      @click="goToServiceView(item.cita_id.toString())">
                    </v-icon>
                  </template>
                </v-tooltip>
              </template>

          </v-data-table>              
          
          <!-- <v-data-table :headers="headers" :items="itemConsulta" :search="search" class="elevation-1">
            <template v-slot:top>
              <v-toolbar flat>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details></v-text-field>
              </v-toolbar>
            </template>
            <template v-slot:item.ser_no_servicio="{ item }">
              <v-chip size="small" color="blue" outlined>{{ item.ser_no_servicio }}</v-chip>
            </template>
            <template v-slot:item.dias="{ item }">
              <div v-if="item.dias > 5">
                <v-badge color="warning" icon="mdi-alert" overlap>
                  <v-chip size="small" color="red">{{ item.dias }}</v-chip>
                </v-badge>
              </div>
              <div v-else>
                <v-chip size="small" color="green">{{ item.dias }}</v-chip>
              </div>
            </template>
            <template v-slot:item.est_nombre="{ item }">
              <v-chip size="small" :color="eventColor(item.ser_id_estado)" outlined>{{ item.est_nombre }}</v-chip>
            </template>
            <template v-slot:item.idaccion="{ item }">
              <v-tooltip text="Ver Servicio" location="top">
                <template v-slot:activator="{ props }">
                  <v-icon v-if="access_servicio" size="small" color="cyan" v-bind="props" @click="$router.push({ name: 'service.view', params: { idservice: item.ser_id } })">mdi-eye</v-icon>
                </template>
              </v-tooltip>
            </template>
          </v-data-table> -->
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
  
</template>

<script setup lang="ts">
  import { ref, computed , onMounted } from 'vue';
  import authApi from '@/api/api';
  import type { KpiEstados, Header} from '@/modules/common/types/index';
  import { useRouter } from 'vue-router';

  const router = useRouter();
  
  // --------------------------------
  // - Control Acceso
  // -------------------------------- 
    const accesView = ref(false);
    const access_servicio = ref(true);
    //const user = reactive({ rol: '' });
    
  // --------------------------------
  // - Data Table
  // --------------------------------
    const search = ref('');
    const loading = ref(false);
    const headerProps = computed(() => ({class: 'font-weight-bold text-medium-emphasis',}));
    const headers: (Header)[] = 
    [
      { title: 'CITA', align: 'center', key: 'cit_no_cita', sortable: false },
      { title: 'TIPO', align: 'center', key: 'tip_cita_nombre', sortable: false },
      { title: 'FECHA', align: 'start', key: 'cita_fecha', sortable: false },
      { title: 'MEDICO', align: 'start', key: 'med_nombre', sortable: false },
      { title: 'PACIENTE', align: 'start', key: 'pac_nombre', sortable: false },
      { title: 'ESTADO', align: 'start', key: 'estado_nombre', sortable: false },
      { title: 'ACCION', align: 'center', key: 'idaccion', sortable: false }
    ];
    const itemConsulta: any = ref([]);  

  // --------------------------------
  // - Cuadro Indicadores
  // --------------------------------
    const kpiEstados = ref<KpiEstados>({carga: 0, proceso: 0, repuesto: 0, soporte: 0, cambio: 0});
    
  // --------------------------------
  // - Hooks
  // --------------------------------
    onMounted(async () => {
      // await setUser();
      //  ControlPermiso();
      //  ControlAccess();
      //  getKpiEstado();
        getservicioAsignado();
    });
  // Ir a cita
    const goToServiceView = (id: any) => 
    {
      console.log(id);
      router.push({ name: 'cita.view', params: { id_cita: id } });     
    };

  // --------------------------------
  // - Funciones de Usuario
  // --------------------------------
    const getservicioAsignado = async () => 
    { 
      loading.value = true;     
      await authApi.get('api/cita/process/obtenerCitasAsign').then((res) => {
        itemConsulta.value = res.data;
        loading.value = false;
      });
    };

    function eventColor(estado: number): string 
    {
      if (estado === 2) return 'red';
      else if (estado === 3) return 'pink';
      else if (estado === 4) return 'indigo';
      else if (estado === 5) return 'green';
      else if (estado === 12) return 'cyan lighten-2';
      else return 'blue';
    }

</script>
