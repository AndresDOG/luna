<template>
    <v-list>
        <v-list-item>
            <template v-slot:prepend>
            <v-avatar>
                <v-icon icon="mdi-file-table-outline" color="pink" size="x-large"></v-icon>
            </v-avatar>
        </template>
            <v-list-item-title><h4 class="font-weight-bold my-0">Contacto</h4></v-list-item-title>
            <v-list-item-subtitle class="text-caption grey--text">Contacto->Paciente</v-list-item-subtitle>
        </v-list-item>
    </v-list>
    <div class="my-3">
        <v-row class="px-lg-5">
            <v-col cols="12">

                <v-chip
                    v-if="access_cpaciente"
                    class="ma-2"
                    color="pink"
                    label
                    variant="outlined"
                    @click="ModNewPaciente">
                    <v-avatar left>
                        <v-icon color="pink">mdi-account-circle</v-icon>
                    </v-avatar>
                    Nuevo Paciente
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
                        <v-toolbar-title><h5 class="font-weight-normal my-0">Contactos</h5></v-toolbar-title>
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
                            v-model="searchclient"
                            append-outer-icon="mdi-magnify"
                            label="Buscar"
                            single-line
                            @click:append-outer="getSearchPaciente"
                            @keyup.enter="getSearchPaciente"
                            hide-default-header
                            disable-filtering
                            disable-sort
                            variant="underlined">
                        </v-text-field>
                    </v-card-title>

                    <div class="mt-5 mx-1 px-1">

                    <v-data-table
                        :headers="headers"
                        :items="pacientes">
                        <template v-slot:item.tipo_identidad.tip_ide_codigo="{ item }">
                            <div >
                              <v-chip syze="small" variant="outlined" color="green" dark>{{ item.tipo_identidad.tip_ide_codigo }}</v-chip>
                            </div>
                        </template>

                        <template v-slot:item.pac_tipo_contacto="{ item }">
                            <div v-if="item.pac_tipo_contacto==0">
                              <v-chip syze="small" variant="outlined" color="red" dark>PACIENTE PREMIUM</v-chip>
                            </div>
                            <div v-if="item.pac_tipo_contacto==1">
                              <v-chip syze="small" variant="outlined" color="blue" dark>PACIENTE COMPLEMENTARIO</v-chip>
                            </div>
                            <div v-if="item.pac_tipo_contacto==2">
                              <v-chip syze="small" variant="outlined" color="green" dark>MEDICO / PROVEEDOR</v-chip>
                            </div>
                        </template>
                        <template v-slot:item.pac_cedula="{ item }">
                            <div >
                              <v-chip syze="small" variant="outlined" color="info" dark>{{ item.pac_cedula }}</v-chip>
                            </div>
                        </template>
                        <template v-slot:item.pac_nombre="{ item }">
                            <span class="text-blue-grey-darken-1"> {{ item.pac_nombre }}</span>
                        </template>
                        <template v-slot:item.pac_telefono="{ item }">
                            <span class="text-blue-grey-darken-1"> {{ item.pac_telefono }}</span>
                        </template>
                        <template v-slot:item.pac_email="{ item }">
                            <span class="text-blue-grey-darken-1"> {{ item.pac_email }}</span>
                        </template>
                        <template v-slot:item.localidad.loc_nombre="{ item }">
                            <span class="text-blue-grey-darken-1"> {{ item.localidad.loc_nombre }}</span>
                        </template>
                        <template v-slot:item.idaccion="{ item }">

                            <v-tooltip top>
                                <template v-slot:activator="{ props }">
                                  <v-icon v-if="access_epaciente" v-bind="props"  color="blue-grey" @click="ModEditPaciente(item)">mdi-pencil</v-icon>
                                </template>
                                <span>Editar Contacto</span>
                            </v-tooltip>
                            <span class="mx-1"></span>
                            <v-tooltip top>
                                <template v-slot:activator="{ props }">
                                  <v-icon v-if="access_epaciente" v-bind="props"  color="pink" @click="eliminarPaciente(item.pac_id)">mdi-trash-can</v-icon>
                                </template>
                                <span>Eliminar Contacto</span>
                            </v-tooltip>
                        </template>
                    </v-data-table>
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </div>

    <!-- Crear medico -->
    <v-dialog v-model="newPaciente" max-width="400px">
        <v-card>
        <v-toolbar
            dark
            density="compact"
            color="blue">
            <v-app-bar-nav-icon> <v-icon>mdi-account-arrow-right</v-icon></v-app-bar-nav-icon>
            <v-toolbar-title><h5 class="font-weight-normal my-0">Crear Contacto</h5></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="cerrarDialogCrearPaciente" icon>
            <v-icon>mdi-close-circle</v-icon>
            </v-btn>
        </v-toolbar>

        <v-card-text>

            <v-form
            ref="form"
            v-model="valid"
            lazy-validation>

            <v-select class="mb-3"
                v-model="addPaciente.tipo_contacto"
                :items="tipoContact"
                item-title="title"
                item-value="value"
                label="Tipo Conctacto"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
            </v-select>

            <v-select class="mb-3" 
                v-model="addPaciente.tipo_identidad"
                :items="items.identidad"
                item-title="tip_ide_nombre"
                item-value="tip_ide_id"
                label="Tipo Identidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
            </v-select>

            <v-text-field class="mb-3" label="Identidad" v-model="addPaciente.cedula" required density="compact" variant="underlined" :rules="nRules" maxlength="40"></v-text-field>
            <v-text-field class="mb-3" label="Nombre" v-model="addPaciente.nombre" required density="compact" variant="underlined" :rules="nRules" maxlength="150"></v-text-field>
            <v-text-field class="mb-3" label="Dirección" v-model="addPaciente.direccion" required density="compact" variant="underlined" :rules="nRules" maxlength="255"></v-text-field>
            <v-text-field class="mb-3" label="Teléfono" v-model="addPaciente.telefono" required density="compact" variant="underlined" :rules="nRules" maxlength="100"></v-text-field>
            <v-autocomplete class="mb-3"
                v-model="addPaciente.departamento"
                :items="items.departamento"
                item-title="dep_nombre"
                item-value="dep_id"
                label="Departamento"
                @update:model-value="getModPacienteLocalidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
                </v-autocomplete>
                <v-autocomplete
                v-model="addPaciente.localidad"
                :items="items.localidad"
                item-title="loc_nombre"
                item-value="loc_id"
                label="Localidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules"
                >
            </v-autocomplete>
            <v-text-field class="mb-3" density="compact" variant="underlined" label="E-mail" v-model="addPaciente.mail" maxlength="255"></v-text-field>
            </v-form>

        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="crearPaciente">CREAR</v-btn>
            <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="cerrarDialogCrearPaciente">CERRAR</v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- Modificar Paciente -->
    <v-dialog v-model="EditClient" max-width="400px">
        <v-card>
            <v-toolbar
            dark
            density="compact"
            color="blue">
            <v-app-bar-nav-icon> <v-icon>mdi-account-edit-outline</v-icon></v-app-bar-nav-icon>
            <v-toolbar-title><h5 class="font-weight-normal my-0">Modificar Contacto</h5></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="EditClient = false" icon>
                <v-icon>mdi-close-circle</v-icon>
            </v-btn>
            </v-toolbar>
            <v-card-text>

            <v-form
                ref="form"
                v-model="valid"
                lazy-validation
            >

            <v-select class="mb-3"
                v-model="paciente.tipo_contacto"
                :items="tipoContact"
                item-title="title"
                item-value="value"
                label="Tipo Conctacto"
                required
                :rules="nRules"
                density="compact"
                variant="underlined"
                >
            </v-select>

            <v-select class="mb-3" 
                v-model="paciente.tipo_identidad"
                :items="items.identidad"
                item-title="tip_ide_nombre"
                item-value="tip_ide_id"
                label="Tipo Identidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
            </v-select>

                <v-text-field class="mb-3"  label="Cedula" v-model="paciente.cedula" required density="compact" variant="underlined" :rules="nRules" maxlength="40"></v-text-field>
                <v-text-field class="mb-3"  label="Nombre" v-model="paciente.nombre" required density="compact" variant="underlined" :rules="nRules" maxlength="150"></v-text-field>
                <v-text-field class="mb-3"  label="Dirección" v-model="paciente.direccion" required density="compact" variant="underlined" :rules="nRules" maxlength="255"></v-text-field>
                <v-text-field class="mb-3"  label="Teléfono" v-model="paciente.telefono" required density="compact" variant="underlined" :rules="nRules" maxlength="100"></v-text-field>
                
                <v-autocomplete class="mb-3" 
                v-model="paciente.departamento"
                :items="items.departamento"
                item-title="dep_nombre"
                item-value="dep_id"
                label="Departamento"
                @update:model-value="getModPacienteLocalidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
                </v-autocomplete>
                <v-autocomplete
                v-model="paciente.localidad"
                :items="items.localidad"
                item-title="loc_nombre"
                item-value="loc_id"
                return-object
                label="Localidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
                </v-autocomplete>
                <v-text-field class="mb-3"  label="E-mail" density="compact" variant="underlined" v-model="paciente.mail" maxlength="255"></v-text-field>
            </v-form>

            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="modificarPaciente">MODIFICAR</v-btn>
                <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="EditClient=false">CERRAR</v-btn>
                </v-card-actions>
        </v-card>
    </v-dialog>


</template>
<script setup lang="ts">

import { ref, onMounted } from 'vue';
import { AuthStore } from '@/modules/common/stores/auth';
import type { rsItems } from '@/modules/common/types/contactoInterface';
import type { AuthStoreState } from '@/modules/common/types/index';
import authApi from '@/api/api';
import Swal from 'sweetalert2';
import type { Header } from '@/modules/common/types/index';

// Variables globales
const pacientes = ref([]);
const paciente = ref({id:0, tipo_contacto: '', tipo_identidad:'',cedula:'', nombre:' ', direccion:' ',telefono:'',departamento:'',localidad:'', namelocalidad:'', mail:'',tratamiento:''})
const items = ref<rsItems>({identidad:[],departamento:[],localidad:[]});


const newPaciente = ref<boolean>(false);
const addPaciente = ref({tipo_contacto: '', tipo_identidad:'', cedula:'', nombre:'', direccion:'', telefono:'', departamento:'', localidad:'', mail:'', tratamiento:''});
const tipoContact = ref([{title: 'Paciente', value:1},{title: 'Proveedor', value:2},{title: 'Ambos', value:3}]);

const EditClient = ref<boolean>(false);
const ButtonEditClient = ref<boolean>(false);

// Formularios
const form = ref<any>(null);

// Permisos
const authStore = AuthStore() as AuthStoreState;
const access_cpaciente = ref<boolean>(false);
const access_epaciente = ref<boolean>(false);
const access_dpaciente = ref<boolean>(false);

// Datatable
const searchclient = ref<string>('');
const headers:(Header)[] = 
[
    { title: 'CONTACTO', key: 'pac_tipo_contacto',align: 'center', sortable: false },
    { title: 'TIPO DOC',key: 'tipo_identidad.tip_ide_codigo', sortable: false},
    { title: 'IDENTIFICACION',key: 'pac_cedula', sortable: false},
    { title: 'NOMBRE',key: 'pac_nombre', sortable: false},
    //{ title: 'DIRECCION', key: 'pac_direccion',sortable: false },
    { title: 'TELEFONO', key: 'pac_telefono',sortable: false },
    { title: 'MAIL', key: 'pac_email',sortable: false },
    { title: 'LOCALIDAD', key: 'localidad.loc_nombre',sortable: false },
    { title: 'ACCION', key: 'idaccion',sortable: false },
];

// Validacion
let valid:boolean = true;
let nRules = 
[
(v: string): boolean | string => !!v || 'Requerido'
];

onMounted(async () => 
{
    await controlPermiso();
});

// Eventos
const cerrarDialogCrearPaciente = () =>
{
    newPaciente.value = false;
    addPaciente.value = {tipo_contacto: '', tipo_identidad:'', cedula:'', nombre:'', direccion:'', telefono:'', departamento:'', localidad:'', mail:'',tratamiento:''}
}

// Methods
const getSearchPaciente = async () =>
{
    await authApi.get(`api/base/search/pacientev/${searchclient.value.toUpperCase()}`).then(res => 
    {
        if (res.data.length===0) 
        {
            pacientes.value = []
        }
        else
        {
            pacientes.value = res.data
        }
    })

};

const getModPacienteIdentidad = async () =>
{
    authApi.get('api/base/info/tipoIdentidad').then((res) =>
    {
        items.value.identidad = res.data;
    });
};

const getModPacienteDepartamento = async () =>
{
    authApi.get('api/base/info/departamento').then((res) =>
    {
    items.value.departamento = res.data;
    });
};

const getModPacienteLocalidad = async () =>
{
    const iddepto = newPaciente.value ? addPaciente.value.departamento : paciente.value.departamento
    await authApi.get(`api/base/info/localidad/${iddepto}`).then((res) =>
    {
        items.value.localidad = res.data;
    });
};

const ModNewPaciente = async  () =>
{
    newPaciente.value=true;
    await getModPacienteIdentidad();
    await getModPacienteDepartamento();

};

const ModEditPaciente = async (item:any) =>
{
    EditClient.value=true;
    await getModPacienteIdentidad();
    await getModPacienteDepartamento();

    paciente.value.id = item.pac_id
    paciente.value.tipo_contacto = item.pac_tipo_contacto + 1
    paciente.value.tipo_identidad = item.pac_id_tipo_identidad
    paciente.value.cedula = item.pac_cedula
    paciente.value.nombre = item.pac_nombre
    paciente.value.direccion = item.pac_direccion
    paciente.value.telefono =  item.pac_telefono
    paciente.value.departamento = item.localidad.loc_id_departamento
    getModPacienteLocalidad()
    paciente.value.localidad = item.pac_id_localidad
    paciente.value.mail = item.pac_email

    console.log(paciente.value);
};

const crearPaciente = async () =>
{

    const isValid = await form.value?.validate()

    if(isValid.valid)
    {
        const params = {keys:addPaciente.value}
        authApi.post('api/base/paciente',params).then(res =>
        {
            if (res.data === 0) {
                Swal.fire("Oops!","Ocurrio un error al crear el registro, verifique los datos", "error")
            }
            else
            {
                Swal.fire("OK!", "Contacto Creado", "success");
                newPaciente.value = false
                limpiarPaciente(addPaciente.value)
                // getPaciente()
            }
        })
    }
};

const modificarPaciente = async () =>
{
    const isValid = await form.value?.validate()

    if(isValid.valid)
    {
        const params = {keys:paciente.value}
        authApi.put(`api/base/paciente/${paciente.value.id}`,params).then(res =>
        {
        if (res.data === 0) {
            Swal.fire("Oops!","Ocurrio un error al crar el registro, verifique los datos", "error")
        }
        else
        {

            EditClient.value = false
            limpiarPaciente(paciente.value)
            //getmedico()
            getSearchPaciente()
            Swal.fire("OK!", "Regisro modificado con éxito", "success");
        }
        })
    }
};

const eliminarPaciente = async (id:number) =>
{
    Swal.fire({
        icon: 'question',
        title: 'Eliminar Contacto',
        text: 'Confirma eliminar registro?',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
        showCloseButton: false,
        cancelButtonColor:'#FF0000'
    }).then((result) =>
    {
        if(result.value)
        {
            authApi.delete(`api/base/paciente/${id}`).then(res =>
            {
                if (res.data === 0) 
                {

                    Swal.fire("Oops!","No se puede eliminar registro, contacto tiene registros relacionados", "error")
                }
                else
                {
                    getSearchPaciente()
                    Swal.fire("Ok!","Paciente eliminado con éxito", "success")
                }
            })


        }
    })
};

const limpiarPaciente = (paciente:any) =>
{
    Object.keys(paciente).forEach((key) => 
    {
        (paciente as any)[key] = '';
    });
};

const controlPermiso =  async () =>
{
    const componente11 = authStore.permisos.find((p:any) => p.per_id_componente === 11);
    if(componente11)
    {
        const rs =  JSON.parse(componente11.per_acceso);

        access_cpaciente.value = rs.Crear_Paciente === "1" ? true : false;
        access_epaciente.value= rs.Editar_Paciente === "1" ? true : false;
        access_dpaciente.value = rs.Eliminar_Paciente === "1" ? true : false;
    }
};
</script>