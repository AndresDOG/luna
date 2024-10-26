<template>
    
      <!-- Encabezado -->
      <v-list>
        <v-list-item>
            <template v-slot:prepend>
                    <v-avatar>
                        <v-icon icon="mdi-file-table-outline" color="pink" size="x-large"></v-icon>
                    </v-avatar>
                </template>
            <v-list-item-title><h4 class="font-weight-bold my-0">Accesos</h4></v-list-item-title>
            <v-list-item-subtitle>Configuracion>Accesos</v-list-item-subtitle>
        </v-list-item>
      </v-list>

      <div class="my-3">
        <v-row class="px-lg-5">
          <v-col cols="12">
            <v-card outlined >
              <v-toolbar flat density="compact" color="grey-lighten-4">
                <template v-slot:prepend>
                    <v-avatar>
                        <v-icon color="pink"  icon="mdi-clipboard-list-outline"></v-icon>
                    </v-avatar>
                </template>
                <v-toolbar-title><h5 class="font-weight-bold my-0">Criterio de Búsqueda</h5></v-toolbar-title>
                &nbsp;
                <template v-slot:append>
                    <v-avatar>
                        <v-icon color="blue-grey"  icon="mdi-information-outline"></v-icon>
                    </v-avatar>
                </template>
              </v-toolbar>

              <v-form ref="form" v-model="valid" lazy-validation>
                <v-card-text class="pb-0">
                  <v-row>
                    <v-col cols="12" sm="4">
                      <v-select
                        v-model="selectedRole"
                        :items="roles"
                        item-title="rol_nombre"
                        item-value="rol_id"
                        label="Rol"
                        density="compact"
                        variant="underlined"
                        required
                        :rules="mRules"
                        persistent-hint
                      >
                        <v-icon slot="prepend" color="blue-darken-2">mdi-view-list</v-icon>
                      </v-select>
                    </v-col>
                    <v-col cols="4" sm="3">
                      <v-chip
                        class="ma-1"
                        color="info"
                        variant="outlined"
                        @click="getNodos"
                        syze="small"
                        label>
                        <v-avatar left>
                          <v-icon color="info">mdi-clipboard-text-search-outline</v-icon>
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

      <!-- Tabs y contenido -->
      <v-row class="px-lg-5">
        <v-col cols="12">
          <v-card outlined>
            <v-card-item>
              <template v-slot:prepend>
                    <v-avatar>
                        <v-icon color="pink"  icon="mdi-folder-outline"></v-icon>
                    </v-avatar>
                </template> 
                <v-card-title><h5 class="font-weight-bold my-0">Componentes</h5></v-card-title>
            </v-card-item>
            <v-tabs slider-color="pink" v-model="tab" align-tabs="center" stacked v-if="componentesRaiz.length > 0" fixed-tabs color="indigo" bg-color="grey-lighten-4" icons-and-text>
              <v-tab v-for="(componenteRaiz, index) in componentesRaiz" :key="index" >
                <v-icon color="indigo">{{ `mdi-${componenteRaiz.com_icono}` }}</v-icon>
                {{ componenteRaiz.com_nombre }}
              </v-tab>
            </v-tabs>

            <v-tabs-window v-model="tab" v-if="componentesRaiz.length > 0">
              <v-tabs-window-item v-for="(componenteRaiz, index) in componentesRaiz" :key="index">
                <v-card>
                  <v-card-text>
                    <v-list>
                      <v-list-item v-for="(componenteFinal, index2) in getNodosFinales(componenteRaiz)" :key="index2">
                        <v-list-item-content>
                          <v-row align="center" justify="space-between">
                            <v-col cols="1">
                              <v-icon color="pink">mdi mdi-account-key</v-icon>
                            </v-col>
                            <v-col>
                              {{ getNodosPadres(componenteFinal) }} - {{ componenteFinal.com_nombre }}
                            </v-col>
                            <v-row align="center" justify="center" style="height: 100%;">
                                <!-- v-switch dentro del contenedor -->
                                <v-col>
                                  <v-switch color="primary" :true-value="true":false-value="false" v-model="componenteFinal.marcado" @update:model-value="toggleMarcado(componenteFinal)" inset></v-switch>
                                </v-col>
                            </v-row>
                            <v-col>
                              <v-chip size="large" variant="outlined"  v-if="componenteFinal.marcado && componenteFinal.com_criterio != null" @click="getAccesos(componenteFinal); selectedComponenteFinal = componenteFinal" color="green" outlined>
                                <v-icon title="Asignar Permisos" >mdi mdi-lock-open-check-outline</v-icon>
                              </v-chip>
                            </v-col>
                          </v-row>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-card-text>
                </v-card>
              </v-tabs-window-item>
            </v-tabs-window>
          </v-card>
        </v-col>
      </v-row>

      <!-- V-dialog para mostrar los accesos -->
      <v-dialog v-model="openDialogo" max-width="400px">
        <v-card>
          <v-toolbar dark density="compact" color="blue">
            <v-app-bar-nav-icon>
              <v-icon>mdi-account-arrow-right</v-icon>
            </v-app-bar-nav-icon>
            <v-toolbar-title><h5 class="font-weight-normal my-0">Accesos</h5></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
              <v-icon @click="openDialogo = false">mdi-close-circle</v-icon>
            </v-btn>
          </v-toolbar>

          <v-card-text>
            <!-- Mostrar las Funciones y el estado de estas -->
            <v-list density="compact" v-if="Object.keys(accesos).length > 0">
                <v-list-item v-for="(value, key) in accesos" :key="key">
                <v-list-item-content>
                    <v-row align="center" justify="space-between">
                    <!-- Icono -->
                    <v-col cols="1">
                        <v-icon color="pink">mdi mdi-playlist-check</v-icon>
                    </v-col>
                    <!-- Nombre de la función -->
                    <v-col cols="6">{{ cleanKey(key) }}</v-col>
                    <!-- Switch de estado -->
                    <v-row align="center" justify="center" style="height: 100%;">
                      <v-col cols="4">
                        <v-switch  v-model="estadoAccesos[key]" color="pink" @update:model-value="toggleAcceso(key)" inset></v-switch>
                      </v-col>
                    </v-row>
                    </v-row>
                </v-list-item-content>
                </v-list-item>
            </v-list>
            </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="openDialogo = false">CERRAR</v-btn>
        </v-card-actions>
  </v-card>
</v-dialog>
</template>
<script setup lang="ts">

    import { ref, onMounted, computed } from 'vue';
    import authApi from '@/api/api';
    import { AuthStore } from '@/modules/common/stores/auth';

    const authStore = AuthStore();

    // Variables globales
    const selectedRole = ref<any>(null);
    const roles = ref([]);
    const tab = ref<any>(null);
    const components = ref([]);
    const componentesRaiz = ref<any>([]);
    const accesos = ref<any>({});
    const openDialogo = ref<boolean>(false);
    const selectedComponenteFinal = ref<any>(null);

    // Formularios
    const form = ref<any>(null);
    // Reglas
    const valid = ref<boolean>(true);
    let nRules = 
    [
        (v: string): boolean | string => !!v || 'Requerido'
    ];
    let mRules = 
    [
        (v: string): boolean | string => !!v || 'Selecciona un rol'
    ];
    
    onMounted( async () => 
    {
        await getRoles()
        await getComponents()
    });

    const estadoAccesos = computed(() => 
    {
      const estados: Record<string, boolean> = {};

      for (const key in accesos.value) 
      {
        if (Object.prototype.hasOwnProperty.call(accesos.value, key)) 
        {
          estados[key] = (accesos.value as Record<string, string>)[key] === "1";
        }
      }
        return estados;
    });

    const cleanKey = computed(() => 
    {
      return (key: any): any => 
      {
        return key.replace('_', ' ');
      };
    });

    // Methods
    const getRoles = async () => 
    {
      const res = await authApi.get('api/base/rol');
      roles.value = res.data;
    };

    const getComponents = async () => 
    {
      const res = await authApi.get('api/base/componente');
      components.value = res.data;
    }

    const getNodos = async () => 
    {
      const isValid = await form.value?.validate()

      if(isValid.valid)
      {
            componentesRaiz.value = [];
            tab.value = null;

            const response = await authApi.post('api/base/search/obtenerPermisos', { id_rol: selectedRole.value });
            const permisos = response.data;

            components.value.forEach((componente:any) => 
            {
                const permiso = permisos.find((permiso:any) => permiso.per_id_componente === componente.com_id);

                if (permiso) 
                {
                  componente.marcado = true;
                  componente.perm_id = permiso.perm_id; 
                } else 
                {
                  componente.marcado = false;
                  componente.perm_id = null; 
                }
            });

              componentesRaiz.value = components.value.filter((componente:any) => !componente.com_padre_id);
        }
    };

    const toggleMarcado = async (componente:any) => 
    {
      const per_id_rol = selectedRole.value;
      const per_id_componente = componente.com_id;
      const params = {per_id_rol: per_id_rol,per_id_componente: per_id_componente};

      console.log(componente);
      
      if (componente.marcado) 
      {
        
        await authApi.post('api/base/permiso', params).then(res =>
        {
          if (res.data != 0)
          {
            console.log(componente.marcado);
            componente.perm_id = res.data;
            componente.marcado = true;
          }
        })
      }else 
      {
        console.log(componente.marcado);
        
        const id = componente.perm_id;
        await authApi.delete(`api/base/permiso/${id}`);
        componente.marcado = false;
      }
      
      
    };

    const getNodosFinales = (raiz:any) => 
    {
      const nodos = components.value.filter((c:any) => c.com_padre_id === raiz.com_id);
      let nodosFinales:any = [];

      if (nodos.length === 0) 
      {
        nodosFinales.push(raiz);
      } else 
      {
        nodos.forEach((nodo:any) => 
        {
          const nodoshijos = components.value.filter((c:any) => c.com_padre_id === nodo.com_id);
          if (nodoshijos.length === 0) 
          {
            nodosFinales.push(nodo);
          } else 
          {
            const nodosNodosFinales = getNodosFinales(nodo);
            nodosFinales = nodosFinales.concat(nodosNodosFinales);
          }
        });
      }
      return nodosFinales;
    };

    const getNodosPadres = (componenteFinal:any) =>
    {
        const componentePadre:any = components.value.find((c:any) => c.com_id === componenteFinal.com_padre_id);

        return componentePadre.com_nombre;
    };

    const getAccesos = async (componenteFinal:any) => 
    {
      const per_id_rol = selectedRole.value;
      const per_id_componente = componenteFinal.com_id;
      const params = {id_rol: per_id_rol,id_componente: [per_id_componente]};

      const response = await authApi.post('api/base/search/obtenerAccesos', params);
      const data = JSON.parse(response.data[0].per_acceso);

      accesos.value = {};

      Object.assign(accesos.value, data);

      openDialogo.value = true;
    };

    const toggleAcceso = async (key: any) => 
    {
      if (!selectedComponenteFinal.value) 
      {
        return;
      }

      let value: any;
      if (accesos.value[key] === "1") 
      {
        value = "0";
      } else if (accesos.value[key] === "0") 
      {
        value = "1";
      } else 
      {
        return; 
      }

      // Actualizar el valor en el objeto accesos
      accesos.value[key] = value;

      // Actualizar todos los accesos después de guardar el cambio
      await guardarAccesos(selectedComponenteFinal.value);
      await authStore.getPermisos()

    };

    const guardarAccesos = async (componenteFinal:any) =>
    {
      const per_id_rol = selectedRole.value;
      const per_id_componente = componenteFinal.com_id;
      const params = {id_rol: per_id_rol,id_componente: per_id_componente,per_acceso: JSON.stringify(accesos.value)};
      await authApi.post('api/base/process/updatePermiso', params);
    }
</script>