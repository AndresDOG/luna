<template>
        <v-list>
            <v-list-item>
                <template v-slot:prepend>
                    <v-avatar>
                        <v-icon icon="mdi-file-table-outline" color="pink" size="x-large"></v-icon>
                    </v-avatar>
                </template>
            <v-list-item-title><h4 class="font-weight-bold my-0">Usuarios</h4></v-list-item-title>
            <v-list-item-subtitle class="text-caption grey--text">Configuración>Usuarios</v-list-item-subtitle>
            </v-list-item>
        </v-list>
        <div class="my-3">
            <v-row class="px-lg-5">
                <v-col cols="12">

                    <v-chip v-if="access_cusuario"
                        class="ma-2"
                        color="pink"
                        label
                        variant="outlined"
                        @click="modNew">
                        <v-avatar left>
                            <v-icon color="pink">mdi-account-circle</v-icon>
                        </v-avatar>
                        Nuevo Usuario
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
                            <v-toolbar-title><h5 class="font-weight-normal my-0">Usuarios</h5></v-toolbar-title>
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
                                hide-details
                                variant="underlined">
                            </v-text-field>
                        </v-card-title>

                        <div class="mt-5 mx-1 px-1">

                        <v-data-table
                            class="text-caption"
                            :headers="headers"
                            :items="usuarios"
                            :search="search">

                            <template v-slot:item.usu_cedula="{ item }">
                                <span class="blue-grey--text"> {{ item.usu_cedula }}</span>
                            </template>
                            <template v-slot:item.usu_nombre="{ item }">
                                <span class="text-truncate blue-grey--text" style="max-width: 150px;"> {{ item.usu_nombre }}</span>
                            </template>

                            <template v-slot:item.rol_tipo="{ item }">
                                <div v-if="item.rol_tipo==1">
                                  <v-chip syze="small" variant="outlined" color="orange" dark>{{ item.rol_nombre }}</v-chip>
                                </div>
                                <div v-if="item.rol_tipo==2">
                                  <v-chip syze="small" variant="outlined" color="red" dark>{{ item.rol_nombre }}</v-chip>
                                </div>
                                <div v-if="item.rol_tipo==3">
                                  <v-chip syze="small" variant="outlined" color="blue" dark>{{ item.rol_nombre }}</v-chip>
                                </div>
                                <div v-if="item.rol_tipo==4">
                                  <v-chip syze="small" variant="outlined" color="green" dark>{{ item.rol_nombre }}</v-chip>
                                </div>
                            </template>

                             <template v-slot:item.usu_servicio="{ item }">
                                <div v-if="item.usu_servicio==1">
                                    <v-icon color="light-green">mdi-check-bold</v-icon>
                                </div>
                            </template>

                            <template v-slot:item.idaccion="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ props }">
                                      <v-icon v-bind="props" v-if="access_eusuario" density="compact" color="blue-grey" @click="ModEdit(item)">mdi-pencil</v-icon>
                                    </template>
                                    <span>Editar Cliente</span>
                                </v-tooltip>
                                <span class="mx-1"></span>
                                <v-tooltip top>
                                    <template v-slot:activator="{ props }">
                                    <v-icon v-bind="props" v-if="access_dusuario && authStore.user.usu_cedula != item.usu_cedula" density="compact" color="pink" @click="eliminar(item.usu_id)">mdi-trash-can</v-icon>
                                    </template>
                                    <span>Eliminar Cliente</span>
                                </v-tooltip>


                            </template>
                        </v-data-table>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </div>

        <!-- Crear Usuario -->
        <v-dialog v-model="New" max-width="400px">
            <v-card>
            <v-toolbar
                density="compact"
                color="blue">
                <v-app-bar-nav-icon> <v-icon>mdi-account-arrow-right</v-icon></v-app-bar-nav-icon>
                <v-toolbar-title><h5 class="font-weight-normal my-0">Crear Usuario</h5></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn @click="New = false" icon>
                <v-icon>mdi-close-circle</v-icon>
                </v-btn>
            </v-toolbar>

            <v-card-text>

                <v-form
                ref="form"
                v-model="valid"
                lazy-validation>

                    <v-text-field class="mt-5" label="Nombre" v-model="add.nombre"  :rules="[rules.required]" density="compact" variant="underlined"></v-text-field>
                    <v-text-field class="mt-3" label="Identidad" v-model="add.cedula" :rules="[rules.required]" density="compact" variant="underlined"></v-text-field>

                    <v-select
                        class="mt-3"
                        v-model="add.rol"
                        :items="roles"
                        item-title="rol_nombre"
                        item-value="rol_id"
                        label="Rol"
                        required
                        density="compact"
                        variant="underlined"
                        :rules="[rules.required]">
                    </v-select>

                    <v-text-field class="mt-3" label="E-mail" v-model="add.email" :rules="[rules.required, rules.mail]" density="compact" variant="underlined"></v-text-field>
                    <v-text-field class="mt-3" label="Nickname" v-model="add.nickname" :rules="[rules.required]" density="compact" variant="underlined"></v-text-field>

                    <v-text-field
                        :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                        :rules="[rules.required, rules.min]"
                        :type="show ? 'text' : 'password'"
                        v-model="add.password"
                        label="Password"
                        hint="Al menos 6 caracteres"
                        class="input-group--focused"
                        density="compact"
                        variant="underlined"
                        @click:append="show = !show">
                    </v-text-field>

                    <v-switch
                        v-model="add.servicio"
                        inset
                        label="Realiza Cita"
                        color="primary">
                    </v-switch>
                </v-form>

            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="crear">CREAR</v-btn>
                <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="New=false">CERRAR</v-btn>
            </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Modificar Cliente -->
        <v-dialog v-model="Edit" max-width="400px">
            <v-card>
                <v-toolbar
                density="compact"
                color="blue">
                <v-app-bar-nav-icon> <v-icon>mdi-account-edit-outline</v-icon></v-app-bar-nav-icon>
                <v-toolbar-title><h5 class="font-weight-normal my-0">Modificar Usuario</h5></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn @click="Edit = false" icon>
                    <v-icon>mdi-close-circle</v-icon>
                </v-btn>
                </v-toolbar>
                <v-card-text>

                    <v-form
                        ref="form"
                        v-model="valid"
                        lazy-validation
                    >

                        <v-text-field density="compact" variant="underlined" class="mt-5" label="Nombre" v-model="usuario.nombre" :rules="[rules.required]"></v-text-field>
                        <v-text-field density="compact" variant="underlined" class="mt-3" label="Identidad" v-model="usuario.cedula" :rules="[rules.required]"></v-text-field>

                        <v-select
                            class="mt-3"
                            v-model="usuario.rol"
                            :items="roles"
                            item-title="rol_nombre"
                            item-value="rol_id"
                            label="Rol"
                            density="compact" variant="underlined"
                            :disabled="!access_arol"
                            :rules="[rules.required]">
                        </v-select>

                        <v-text-field density="compact" variant="underlined" class="mt-3" label="E-mail" v-model="usuario.email" :rules="[rules.required, rules.mail]"></v-text-field>
                        <v-text-field density="compact" variant="underlined" class="mt-3" label="Nickname" v-model="usuario.nickname" :rules="[rules.required]"></v-text-field>

                        <v-text-field
                            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                            :rules="[rules.min]"
                            :type="show ? 'text' : 'password'"
                            v-model="usuario.password"
                            label="Password"
                            hint="Al menos 8 caracteres"
                            class="mt-3 input-group--focused"
                            density="compact" variant="underlined"
                            @click:append="show = !show">
                        </v-text-field>
                        <v-row>
                            <v-col>
                                <v-switch
                                    class="mt-3"
                                    v-model="usuario.servicio"
                                    color="primary"
                                    inset
                                    label="Realiza Cita"
                                    :true-value="1"
                                    :false-value="0"
                                    :disabled="!access_rservice">
                                </v-switch>
                            </v-col>
                            <v-col>
                                <v-switch
                                    class="mt-3"
                                    v-model="usuario.activo"
                                    color="primary"
                                    inset
                                    label="Activo"
                                    :true-value="1"
                                    :false-value="0"
                                    :disabled="!access_activo">
                                </v-switch>
                            </v-col>
                        </v-row>

                    </v-form>

                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="Modificar">MODIFICAR</v-btn>
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
    const usuarios = ref([]);
    const show = ref<boolean>(false);

    const usuario = ref({id:0, nombre:'', cedula:'', rol:'', servicio:0, email:'', nickname:'', password:'',activo:0});
    const roles = ref([]);
    const New = ref<boolean>(false);
    const add = ref({nombre:'', cedula:'', rol:'', servicio:0 , email:'', nickname:'', password:''});
    const Edit = ref<boolean>(false);

    // Formulario
    const form = ref<any>(null);

    // Datatable
    const search = ref<string>('');
    const headers:(Header)[] = 
    [
        { title: 'NOMBRE',key: 'usu_nombre', sortable: false},
        { title: 'CEDULA',key: 'usu_cedula', sortable: false},
        { title: 'ROL',key: 'rol_tipo', align: 'center', sortable: false},
        { title: 'SERVICIO', key: 'usu_servicio',align: 'center', sortable: false },
        { title: 'NICKNAME', key: 'usu_nickname',sortable: false },
        { title: 'MAIL', key: 'email',sortable: false },
        { title: 'ACCION', key: 'idaccion',sortable: false },
    ];

    // Se usa cuando necesitamos superponer el swal
    const MySwal = Swal.mixin({
    didOpen: (toast) => 
    {
        // Ajustar el z-index programáticamente
        toast.parentElement!.style.zIndex = '9999';
    }
    });

    // Validacion y Permisos
    const authStore = AuthStore() as AuthStoreState;
    const valid = ref<boolean>(true);
    const rules = ref(
    {
        required: (value: any): boolean | string => !!value || 'Requerido',
        
        min: (v: string): boolean | string => v.length >= 8 || 'Min 8 characters',
        
        mail: (value: string): boolean | string => {
            const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return pattern.test(value) || 'Invalid e-mail.'}
    });

    const access_cusuario = ref<boolean>(false);
    const access_eusuario = ref<boolean>(false);
    const access_dusuario = ref<boolean>(false);
    const access_arol = ref<boolean>(false);
    const access_rservice = ref<boolean>(false);
    const access_activo = ref<boolean>(false);
    

    onMounted(async () => 
    {
        await controlPermiso();
        await getLista();
        await getRol();
    })

    // Methods

    
    const getLista = async () =>
    {
        await authApi.get('api/base/usuario').then(res =>
        {
            usuarios.value = res.data
        });

    };

    const getRol = async () =>
    {
        await authApi.get('api/base/rol').then(res =>
        {
            roles.value = res.data

        });

    };

    const modNew = () => 
    {
        limpiar();
        New.value = true;
    }

    const ModEdit = (item:any) => 
    {

        
        usuario.value.id = item.usu_id
        usuario.value.nombre = item.usu_nombre
        usuario.value.cedula = item.usu_cedula
        usuario.value.rol = item.usu_id_rol
        usuario.value.activo = item.usu_activo
        usuario.value.servicio = item.usu_servicio
        usuario.value.nickname = item.usu_nickname
        usuario.value.password = '********'
        usuario.value.email = item.email
        Edit.value=true;

    };

    const crear = async () =>
    {

        const isValid = await form.value?.validate()

        if(isValid.valid) 
        {
            const params = {keys:add.value}

            await authApi.post('api/base/usuario',params).then(res =>
            {
                if (res.data === 0) 
                {
                    MySwal.fire("Oops!","Ocurrio un error al crear el registro, verifique que el correo y nickname no sean existentes.", "error")
                }
                else
                {
                    MySwal.fire("OK!", "Registro creado con éxito", "success");
                    New.value = false
                    getLista()
                    getRol()
                    limpiar()
                }
            })
        }
    };

    const Modificar = async () =>
    {
        
        const isValid = await form.value?.validate()

        if(isValid.valid) 
        {
            const params = {keys:usuario.value}

            authApi.put(`api/base/usuario/${usuario.value.id}`,params).then(res =>
            {
                if (res.data === 0) 
                {
                    Swal.fire("Oops!","Ocurrio un error al modificar el registro, verifique que el correo y nickname no sean existentes", "error")
                }
                else
                {
                    Swal.fire("OK!", "Regisro modificado con éxito", "success");
                    Edit.value = false
                    getLista()
                    getRol()
                }
            })
        }
    };

    const eliminar = async (id: number) =>
    {
        Swal.fire({
            icon: 'question',
            title: 'Eliminar Usuario',
            text: 'Confirma eliminar registro de usuario?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
            showCloseButton: false,
            cancelButtonColor:'#FF0000'
        }).then((result) =>
        {
            if(result.value)
            {
                authApi.delete(`api/base/usuario/${id}`).then(res =>
                {
                    if (res.data === 0)
                    {
                        Swal.fire("Oops!","No se puede eliminar regisro", "error")
                    }
                    else
                    {
                        getLista()
                        Swal.fire("Ok!","Cliente eliminado con éxito", "success")
                    }
                })
            }

        })
    };

    const limpiar = () =>
    {
        add.value.nombre = ''
        add.value.cedula = ''
        add.value.rol = ''
        add.value.servicio = 0
        add.value.email = ''
        add.value.nickname = ''
        add.value.password =''
    };
    
    const controlPermiso =  async () =>
    {
        const componente8 = authStore.permisos.find((p:any) => p.per_id_componente === 8);
        if(componente8)
        {
            const rs =  JSON.parse(componente8.per_acceso);

            access_cusuario.value = rs.Crear_Usuario === "1" ? true : false;
            access_eusuario.value= rs.Editar_Usuario === "1" ? true : false;
            access_dusuario.value = rs.Eliminar_Usuario === "1" ? true : false;
            access_arol.value = rs.Asignar_Rol  === "1" ? true : false;
            access_rservice.value = rs.Realiza_Servicio === "1" ? true : false;
            access_activo.value = rs.Activo === "1" ? true : false;
        }
    };

</script>