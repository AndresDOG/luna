<template>
    <v-list>
      <v-list-item>
        <template v-slot:prepend>
          <v-avatar>
            <v-icon icon="mdi-file-table-outline" color="pink" size="x-large"></v-icon>
          </v-avatar>
        </template>
        <v-list-item-title><h4 class="font-weight-bold my-0">Equipo</h4></v-list-item-title>
        <v-list-item-subtitle class="text-caption grey--text">Contacto->Parametros->Equipo</v-list-item-subtitle>
      </v-list-item>
    </v-list>
    <div class="my-3">
      <v-row class="px-lg-5">
        <v-col cols="12">
          <v-card>
            <v-toolbar flat density="compact" color="grey-lighten-4">
              <template v-slot:prepend>
                <v-avatar>
                  <v-icon color="pink" icon="mdi-clipboard-list-outline"></v-icon>
                </v-avatar>
              </template>
              <v-toolbar-title><h5 class="text-subtitle-1 font-weight-bold">Gestión de Equipos</h5></v-toolbar-title>
              <template v-slot:append>
                <v-avatar>
                  <v-icon icon="mdi-information-outline"></v-icon>
                </v-avatar>
              </template>
            </v-toolbar>
            <v-card-actions>
              <v-chip
                class="ma-2"
                color="pink"
                label
                variant="outlined"
                @click="openDialog('create')">
                <v-avatar left>
                  <v-icon color="pink">mdi-account-circle</v-icon>
                </v-avatar>
                Añadir Equipo
              </v-chip>
            </v-card-actions>
            <v-data-table
              :headers="equipoHeaders"
              :items="equipos"
              :items-per-page="5"
              class="elevation-1">
              <template v-slot:item.equi_estado="{ item }">
                <div v-if="item.equi_estado == 5">
                  <v-chip size="small" variant="outlined" color="red" dark> INACTIVO </v-chip>
                </div>
                <div v-if="item.equi_estado == 1">
                  <v-chip size="small" variant="outlined" color="blue" dark> ACTIVO </v-chip>
                </div>
              </template>
              <template v-slot:item.actions="{ item }">
                <v-tooltip top>
                  <template v-slot:activator="{ props }">
                    <v-icon v-bind="props" color="blue-grey" @click="openDialog('edit', item)">mdi-pencil</v-icon>
                  </template>
                  <span>Editar Equipo</span>
                </v-tooltip>
                <span class="mx-1"></span>
                <v-tooltip top>
                  <template v-slot:activator="{ props }">
                    <v-icon v-bind="props" color="pink" @click="deleteEquipo(item.equi_id)">mdi-trash-can</v-icon>
                  </template>
                  <span>Eliminar Equipo</span>
                </v-tooltip>
              </template>
            </v-data-table>
          </v-card>
        </v-col>
      </v-row>
  
      <v-dialog v-model="dialog" max-width="600px">
        <v-card>
          <v-toolbar dark density="compact" color="blue">
            <v-app-bar-nav-icon> 
              <v-icon>mdi-account-arrow-right</v-icon>
            </v-app-bar-nav-icon>
            <v-toolbar-title v-if="isEditMode"><h5 class="font-weight-normal my-0">Editar Equipo</h5></v-toolbar-title>
            <v-toolbar-title v-else><h5 class="font-weight-normal my-0">Añadir Equipo</h5></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="closeDialog" icon>
              <v-icon>mdi-close-circle</v-icon>
            </v-btn>
          </v-toolbar>
  
          <v-card-text>
            <v-text-field
              variant="underlined"
              v-model="form.equi_nombre"
              label="Nombre"
              required
            ></v-text-field>
            <v-select
              variant="underlined"
              v-model="form.equi_tipo"
              :items="tiposEquipos"
              label="Tipo"
              required
            ></v-select>
            <v-select
              variant="underlined"
              v-model="form.equi_estado"
              :items="estadosEquipos"
              label="Estado"
              required
            ></v-select>
          </v-card-text>
          <v-card-actions>
            <v-btn class="text-none ms-3 text-white" color="grey-darken-3" size="small" variant="elevated" @click="saveEquipo">CREAR</v-btn>
            <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="closeDialog">CERRAR</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
  
      <v-row class="px-lg-5">
        <v-col cols="12">
          <v-card>
            <v-toolbar flat density="compact" color="grey-lighten-4">
              <template v-slot:prepend>
                <v-avatar>
                  <v-icon color="pink" icon="mdi-clipboard-list-outline"></v-icon>
                </v-avatar>
              </template>
              <v-toolbar-title><h5 class="text-subtitle-1 font-weight-bold">Gestión de Mantenimientos</h5></v-toolbar-title>
              <template v-slot:append>
                <v-avatar>
                  <v-icon icon="mdi-information-outline"></v-icon>
                </v-avatar>
              </template>
            </v-toolbar>
            <v-card-actions>
              <v-chip
                class="ma-2"
                color="pink"
                label
                variant="outlined"
                @click="openMaintenanceDialog('create')">
                <v-avatar left>
                  <v-icon color="pink">mdi-account-circle</v-icon>
                </v-avatar>
                Añadir Mantenimiento
              </v-chip>
            </v-card-actions>
            <v-data-table
              :headers="mantenimientoHeaders"
              :items="mantenimientos"
              :items-per-page="5"
              class="elevation-1">
              <template v-slot:item.actions="{ item }">
                <v-tooltip top>
                  <template v-slot:activator="{ props }">
                    <v-icon v-bind="props" color="blue-grey" @click="openMaintenanceDialog('edit', item)">mdi-pencil</v-icon>
                  </template>
                  <span>Editar Equipo</span>
                </v-tooltip>
                <span class="mx-1"></span>
                <v-tooltip top>
                  <template v-slot:activator="{ props }">
                    <v-icon v-bind="props" color="pink" @click="deleteMantenimiento(item.mant_id)">mdi-trash-can</v-icon>
                  </template>
                  <span>Eliminar Equipo</span>
                </v-tooltip>
              </template>
            </v-data-table>
          </v-card>
        </v-col>
      </v-row>
  
      <v-dialog v-model="maintenanceDialog" max-width="600px">
        <v-card>
          <v-toolbar dark density="compact" color="blue">
            <v-app-bar-nav-icon><v-icon>mdi-account-arrow-right</v-icon></v-app-bar-nav-icon>
            <v-toolbar-title v-if="isMaintenanceEditMode"><h5 class="font-weight-normal my-0">Editar Mantenimiento</h5></v-toolbar-title>
            <v-toolbar-title v-else><h5 class="font-weight-normal my-0">Añadir Mantenimiento</h5></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="closeMaintenanceDialog" icon>
              <v-icon>mdi-close-circle</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <v-menu class="mb-3"
              v-model="menu"
              :close-on-content-click="false"
              transition="scale-transition"
              offset-y
              max-width="290px"
              min-width="290px">
              <template v-slot:activator="{ props }">
                <v-text-field
                  v-model="formMant.mant_fecha"
                  label="Fecha"
                  persistent-hint
                  prepend-icon="mdi-calendar"
                  readonly
                  density="compact"
                  variant="underlined"
                  :rules="nRules"
                  v-bind="props">
                </v-text-field>
              </template>
              <v-date-picker color="primary" header-color="primary" width="300" height="470" elevation="5" v-model="date1" no-title @update:model-value="formatDate"></v-date-picker>
            </v-menu>
            <v-text-field
              variant="underlined"
              v-model="formMant.mant_detalle"
              label="Detalle del Mantenimiento"
            ></v-text-field>
            <v-select
              variant="underlined"
              v-model="formMant.mant_equi_id"
              :items="equipos"
              item-title="equi_nombre"
              item-value="equi_id"
              label="Equipo"
              required
            ></v-select>
          </v-card-text>
          <v-card-actions>
            <v-btn class="text-none ms-3 text-white" color="grey-darken-3" size="small" variant="elevated" @click="saveMantenimiento">CREAR</v-btn>
            <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="closeMaintenanceDialog">CERRAR</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted, watch } from 'vue';
  import authApi from '@/api/api';
  import type { Header } from '@/modules/common/types/index';
  import Swal from 'sweetalert2';
  
  // Headers for the data tables
  const equipoHeaders: Header[] = [
    { title: 'ID', key: 'equi_id' },
    { title: 'Nombre', key: 'equi_nombre' },
    { title: 'Tipo', key: 'equi_tipo' },
    { title: 'Estado', key: 'equi_estado' },
    { title: 'Acciones', key: 'actions', sortable: false },
  ];
  
  const mantenimientoHeaders: Header[] = [
    { title: 'ID', key: 'mant_id' },
    { title: 'Fecha', key: 'mant_fecha' },
    { title: 'Detalle', key: 'mant_detalle' },
    { title: 'Equipo', key: 'equi_nombre' },
    { title: 'Acciones', key: 'actions', sortable: false },
  ];
  
  // Data
  const tiposEquipos = ref(['Imagenología', 'Instrumentación', 'Suministros', 'Software']);
  const estadosEquipos = ref([
    { title: 'ACTIVO', key: 1 },
    { title: 'INACTIVO', key: 5 },
  ]);
  
  const equipos = ref([]);
  const mantenimientos = ref([]);
  const dialog = ref(false);
  const maintenanceDialog = ref(false);
  const isEditMode = ref(false);
  const isMaintenanceEditMode = ref(false);
  const form = ref({ equi_nombre: '', equi_tipo: '', equi_estado: '' });
  const formMant = ref({ mant_fecha: new Date().toISOString().split('T')[0], mant_detalle: '', mant_equi_id: '' });
  const menu = ref(false);
  let equipoId = ref<any>('');
  let mantId = ref<any>('');
  
  // Validation rules
  let nRules = [
    (v: string): boolean | string => !!v || 'Requerido'
  ];
  
  // Datapicker
  const date1 = ref(new Date());
  
  // Watcher for date changes
  watch(date1, (val) => {
    if (val) {
      formMant.value.mant_fecha = formatDate(val);
    }
  });
  
  // Fetch data methods
  const fetchEquipos = async () => {
    try {
      const response = await authApi.get('api/base/equipo');
      equipos.value = response.data;
    } catch (error) {
      console.error('Error fetching equipos:', error);
    }
  };
  
  const fetchMantenimientos = async () => {
    try {
      const response = await authApi.get('api/base/mantenimiento');
      mantenimientos.value = response.data;
    } catch (error) {
      console.error('Error fetching mantenimientos:', error);
    }
  };
  
  // Open dialog methods
  const openDialog = (mode: any, item: any = null) => {
    if (mode === 'edit') {
      isEditMode.value = true;
      form.value = { ...item }; // Load the equipment data to edit
      equipoId.value = item.equi_id; // Save the ID for updates
    } else {
      isEditMode.value = false;
      form.value = { equi_nombre: '', equi_tipo: '', equi_estado: '' };
    }
    dialog.value = true;
  };
  
  const openMaintenanceDialog = (mode: any, item: any = null) => {
    if (mode === 'edit') {
      isMaintenanceEditMode.value = true;
      formMant.value = { ...item }; // Load the maintenance data to edit
      mantId.value = item.mant_id; // Save the ID for updates
    } else {
      isMaintenanceEditMode.value = false;
      formMant.value = { mant_fecha: '', mant_detalle: '', mant_equi_id: '' };
    }
    maintenanceDialog.value = true;
  };
  
  // Close dialog methods
  const closeDialog = () => {
    dialog.value = false;
  };
  
  const closeMaintenanceDialog = () => {
    maintenanceDialog.value = false;
  };
  
  // Separate methods for saving
  const createEquipo = async () => {
    await authApi.post('api/base/equipo', form.value);
    fetchEquipos();
  };
  
  const updateEquipo = async () => {
    await authApi.put(`api/base/equipo/${equipoId.value}`, form.value);
    fetchEquipos();
  };
  
  const saveEquipo = async () => {
    if (isEditMode.value) {
      await updateEquipo();
      Swal.fire("OK!", "Equipo Creado", "success");
    } else {
      await createEquipo();
      Swal.fire("OK!", "Equipo Modificado", "success");
    }
    closeDialog();
  };
  
  const createMantenimiento = async () => {
    console.log(formMant.value);
    await authApi.post('api/base/mantenimiento',formMant.value);
    fetchMantenimientos();
  };
  
  const updateMantenimiento = async () => {
    await authApi.put(`api/base/mantenimiento/${mantId.value}`, formMant.value);
    fetchMantenimientos();
  };
  
  const saveMantenimiento = async () => {
    if (isMaintenanceEditMode.value) {
      await updateMantenimiento();
      Swal.fire("OK!", "Mantenimiento Creado", "success");
      fetchEquipos()
    } else {
      await createMantenimiento();
      Swal.fire("OK!", "Mantenimiento Modificado", "success");
      fetchEquipos()
    }
    closeMaintenanceDialog();
  };
  
  // Delete methods
  const deleteEquipo = async (id: any) => {
    try {
      await authApi.delete(`/api/base/equipo/${id}`);
      fetchEquipos();
    } catch (error) {
      console.error('Error deleting equipo:', error);
    }
  };
  
  const deleteMantenimiento = async (id: any) => {
    try {
      await authApi.delete(`/api/base/mantenimiento/${id}`);
      fetchMantenimientos();
    } catch (error) {
      console.error('Error deleting mantenimiento:', error);
    }
  };
  
  // Lifecycle hooks
  onMounted(() => {
    fetchEquipos();
    fetchMantenimientos();
  });
  
  // Format date function (add your implementation)
  const formatDate = (newDate: Date) => 
{
    const year = newDate.getFullYear()
    const month = String(newDate.getMonth() + 1).padStart(2, '0')
    const day = String(newDate.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
}
  </script>
  
  <style scoped>
  /* Add your styles here */
  </style>
  