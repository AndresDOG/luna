<template>
        <v-list>
            <v-list-item>
                <template v-slot:prepend>
                    <v-avatar>
                        <v-icon icon="mdi-file-table-outline" color="pink" size="x-large"></v-icon>
                    </v-avatar>
                </template>
                <v-list-item-title><h4 class="font-weight-bold my-0">Rol</h4></v-list-item-title>
                <v-list-item-subtitle class="text-caption grey--text">Configuracion>Rol</v-list-item-subtitle>
            </v-list-item>
        </v-list>
        <div class="my-3">
            <v-row class="px-lg-5">
                <v-col cols="12">

                    <v-chip
                        v-if="access_crol"
                        class="ma-2"
                        color="pink"
                        label
                        variant="outlined"
                        @click="ModNew">
                        <v-avatar left>
                            <v-icon color="pink">mdi-account-circle</v-icon>
                        </v-avatar>
                        Nuevo Rol
                    </v-chip>

                    <v-card outlined>
                        <v-toolbar
                            flat
                            density="compact"
                            color="grey-lighten-4"> 
                            <template v-slot:prepend>
                                <v-avatar>
                                    <v-icon color="pink"  icon="mdi-clipboard-list-outline"></v-icon>
                                </v-avatar>
                            </template>
                            <v-toolbar-title><h5 class="font-weight-normal my-0">Rol</h5></v-toolbar-title>
                            <v-spacer></v-spacer>
                            <template v-slot:append>
                                <v-avatar>
                                    <v-icon color="blue-grey"  icon="mdi-information-outline"></v-icon>
                                </v-avatar>
                            </template>
                        </v-toolbar>
                        <v-card-title class="d-flex align-center pe-2 mt-2">
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Buscar"
                                single-line
                                variant="underlined"
                                hide-details>
                            </v-text-field>
                        </v-card-title>

                        <div class="mt-5 mx-1 px-1">

                        <v-data-table
                            :headers="headers"
                            :items="listado"
                            :search="search">

                            <template v-slot:item.rol_tipo="{ item }">
                                <div v-if="item.rol_tipo==1">
                                  <v-chip size="small" variant="outlined" color="orange" dark>{{ "SUPER ADMIN"}}</v-chip>
                                </div>
                                <div v-if="item.rol_tipo==2">
                                  <v-chip size="small" variant="outlined" color="red" dark>{{ "ADMINISTRATIVO"}}</v-chip>
                                </div>
                                <div v-if="item.rol_tipo==3">
                                  <v-chip size="small" variant="outlined" color="blue" dark>{{ "OPERATIVO" }}</v-chip>
                                </div>
                                <div v-if="item.rol_tipo==4">
                                  <v-chip size="small" variant="outlined" color="green" dark>{{ "TECNICO" }}</v-chip>
                                </div>
                            </template>

                            <template v-slot:item.idaccion="{ item }">
                                <v-tooltip top>
                                    <template v-slot:activator="{ props }">
                                      <v-icon v-if="access_erol"  v-bind="props" density="compact" color="blue-grey" @click="ModEdit(item)">mdi-pencil</v-icon>
                                    </template>
                                    <span>Editar</span>
                                </v-tooltip>
                                <span class="mx-1"></span>
                                <v-tooltip top>
                                    <template v-slot:activator="{ props }">
                                      <v-icon v-if="access_drol"  v-bind="props" density="compact" color="pink" @click="eliminar(item.rol_id)">mdi-trash-can</v-icon>
                                    </template>
                                    <span>Eliminar</span>
                                </v-tooltip>
                            </template>
                        </v-data-table>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </div>

        <!-- Crear -->
        <v-dialog v-model="New" max-width="400px">
            <v-card>
                <v-toolbar
                    dark
                    density="compact"
                    color="blue">
                    <v-app-bar-nav-icon> <v-icon>mdi-account-arrow-right</v-icon></v-app-bar-nav-icon>
                    <v-toolbar-title><h5 class="font-weight-normal my-0">Crear Rol</h5></v-toolbar-title>
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

                        <v-text-field
                            class ="my-5"
                            density="compact"
                            variant="underlined"
                            label="Rol"
                            v-model="nombre"
                            required
                            :rules="nRules">
                        </v-text-field>
                        <v-select
                            class ="my-5"
                            density="compact"
                            variant="underlined"
                            label="Tipo"
                            v-model="tipo"
                            :items="[
                                        { title: 'ADMINISTRATIVO', value: 2 },
                                        { title: 'OPERATIVO', value: 3 },
                                        { title: 'TECNICO', value: 4 }
                                    ]"
                            :rules="nRules"
                        ></v-select>

                    </v-form>

                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="crear">CREAR</v-btn>
                    <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="New=false; nombre=''">CERRAR</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Modificar -->
        <v-dialog v-model="Edit" max-width="400px">
            <v-card>
                <v-toolbar
                    dark
                    density="compact"
                    color="blue">
                    <v-app-bar-nav-icon> <v-icon>mdi-account-edit-outline</v-icon></v-app-bar-nav-icon>
                    <v-toolbar-title><h5 class="font-weight-normal my-0">Modificar Rol</h5></v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn @click="Edit = false" icon>
                        <v-icon>mdi-close-circle</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>

                    <v-form
                        ref="form2"
                        v-model="valid"
                        lazy-validation
                    >
                        <v-text-field
                            class ="my-5"
                            density="compact"
                            variant="underlined"
                            label="Rol"
                            v-model="data.nombre"
                            required
                            :rules="nRules">
                        </v-text-field>
                        <v-select
                            class ="my-5"
                            density="compact"
                            variant="underlined"
                            label="Tipo"
                            v-model="data.tipo"
                            :items="[
                                        { title: 'ADMINISTRATIVO', value: 2 },
                                        { title: 'OPERATIVO', value: 3 },
                                        { title: 'TECNICO', value: 4 }
                                    ]"
                            :rules="nRules"
                        ></v-select>

                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="modificar">MODIFICAR</v-btn>
                    <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="Edit=false">CERRAR</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    
</template>
<script setup lang="ts">

    import { ref, onMounted } from 'vue';
    import { AuthStore } from '@/modules/common/stores/auth';
    import type { AuthStoreState } from '@/modules/common/types/index';
    import authApi from '@/api/api';
    import Swal from 'sweetalert2';
    import type { Header } from '@/modules/common/types/index';

    // Variables globales
    const listado = ref([]);
    const nombre = ref<string>('');
    const tipo = ref<number | string>('');
    const New = ref<boolean>(false);
    const data = ref({id: 0, nombre:'',tipo:0})
    const Edit = ref<boolean>(false);

    // Formulario
    const form = ref<any>(null);
    const form2 = ref<any>(null);

    // Permisos
    const authStore = AuthStore() as AuthStoreState;
    const access_crol = ref<boolean>(false);
    const access_erol = ref<boolean>(false);
    const access_drol = ref<boolean>(false);

    // Datatable
    const search = ref<string>('');
    const headers:(Header)[] = 
    [
        { title: 'ROL',key: 'rol_nombre', sortable: false},
        { title: 'TIPO',key: 'rol_tipo', align: 'center', sortable: false},
        { title: 'ACCION',  align: 'end', key: 'idaccion',sortable: false }
    ];

    // Validacion y reglas
    const valid = ref<boolean>(true);
    let nRules = 
    [
    (v: string): boolean | string => !!v || 'Requerido'
    ];

    onMounted(async () => 
    {
       await controlPermiso()
       await getLista() 
    });

    // Methods
    const getLista = async () =>
    {
        authApi.get('api/base/rol').then((res) =>
        {
            listado.value = res.data;
        });

    };

    const ModNew = () =>
    {
        New.value=true;
    };

    const ModEdit = (item:any) =>
    {
        Edit.value=true;

        data.value.id = item.rol_id
        data.value.nombre = item.rol_nombre
        data.value.tipo = item.rol_tipo

    };

    const crear = async () =>
    {
        const isValid = await form.value?.validate()

        if(isValid.valid)
        {
            const params = {nombre:nombre.value, tipo:tipo.value};
            await authApi.post('api/base/rol',params).then(res =>
            {
                if (res.data === 0)
                {
                    Swal.fire("Oops!","Ocurrio un error al crear el registro, verifique los datos, el registro puede estar creado ya", "error")
                }
                else
                {
                    New.value = false
                    nombre.value = ''
                    tipo.value = 0;
                    getLista()
                    Swal.fire("OK!", "Registro creado con éxito", "success");
                }
            })
        }
    };

    const modificar = async () =>
    {
        const isValid = await form2.value?.validate()

        if(isValid.valid)
        {
            const params = {keys:data.value}
            await authApi.put(`api/base/rol/${data.value.id}`,params).then(res =>
            {
                if (res.data === 0)
                {
                    Swal.fire("Oops!","Ocurrio un error al crar el registro, verifique los datos, no se permiten registros duplicados", "error")
                }
                else
                {
                    Edit.value = false
                    getLista()
                    Swal.fire("OK!", "Regisro modificado con éxito", "success");
                }
            })
        }
    };

    const eliminar = async (id:number) =>
    {
        Swal.fire({
            icon: 'question',
            title: 'Eliminar Rol',
            text: 'Confirma eliminar registro de rol?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
            showCloseButton: false,
            cancelButtonColor:'#FF0000'
        }).then((result) =>
        {
            if(result.value)
            {
                authApi.delete(`api/base/rol/${id}`).then(res =>
                {
                    if (res.data === 0)
                    {
                        Swal.fire("Oops!","No se puede elimar regisro", "error")
                    }
                    else
                    {
                        getLista()
                        Swal.fire("Ok!","Registro eliminado con éxito", "success")
                    }
                });
            }
        })
    };

    const controlPermiso =  async () =>
    {
        const componente9 = authStore.permisos.find((p:any) => p.per_id_componente === 9);
        if(componente9)
        {
            const rs =  JSON.parse(componente9.per_acceso);

            access_crol.value = rs.Crear_Rol === "1" ? true : false;
            access_erol.value= rs.Editar_Rol === "1" ? true : false;
            access_drol.value = rs.Eliminar_Rol === "1" ? true : false;
        }
    };

</script>