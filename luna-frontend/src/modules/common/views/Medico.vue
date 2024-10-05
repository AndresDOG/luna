<template>
    <v-list>
        <v-list-item>
            <template v-slot:prepend>
            <v-avatar>
                <v-icon icon="mdi-file-table-outline" color="pink" size="x-large"></v-icon>
            </v-avatar>
        </template>
            <v-list-item-title><h4 class="font-weight-bold my-0">Contacto</h4></v-list-item-title>
            <v-list-item-subtitle class="text-caption grey--text">Contacto->Medico / Especialista</v-list-item-subtitle>
        </v-list-item>
    </v-list>
    <div class="my-3">
        <v-row class="px-lg-5">
            <v-col cols="12">

                <v-chip
                    v-if="access_cmedico"
                    class="ma-2"
                    color="pink"
                    label
                    variant="outlined"
                    @click="ModNewMedico">
                    <v-avatar left>
                        <v-icon color="pink">mdi-account-circle</v-icon>
                    </v-avatar>
                    Nuevo Medico
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
                            @click:append-outer="getSearchMedico"
                            @keyup.enter="getSearchMedico"
                            hide-default-header
                            disable-filtering
                            disable-sort
                            variant="underlined">
                        </v-text-field>
                    </v-card-title>

                    <div class="mt-5 mx-1 px-1">

                    <v-data-table
                        :headers="headers"
                        :items="medicos">
                        <template v-slot:item.tipo_identidad.tip_ide_codigo="{ item }">
                            <span class="blue-grey--text">{{ item.tipo_identidad.tip_ide_codigo }}</span>
                        </template>

                        <template v-slot:item.med_tipo_contacto="{ item }">
                            <div v-if="item.med_tipo_contacto==0">
                              <v-chip syze="small" variant="outlined" color="green" dark>MEDICO NUCLEAR</v-chip>
                            </div>
                            <div v-if="item.med_tipo_contacto==1">
                              <v-chip syze="small" variant="outlined" color="blue" dark>MEDICO ESPECIALISTA</v-chip>
                            </div>
                            <div v-if="item.med_tipo_contacto==2">
                              <v-chip syze="small" variant="outlined" color="green" dark>MEDICO</v-chip>
                            </div>
                        </template>
                        <template v-slot:item.especialidad.esp_nombre="{ item }">
                              <v-chip syze="small" variant="outlined" color="blue" dark>{{ item.especialidad.esp_nombre }}</v-chip>
                        </template>

                        <template v-slot:item.med_cedula="{ item }">
                            <span class="blue-grey--text"> {{ item.med_cedula }}</span>
                        </template>
                        <template v-slot:item.med_nombre="{ item }">
                            <span class="text-truncate blue-grey--text" style="max-width: 150px;"> {{ item.med_nombre }}</span>
                        </template>
                        <template v-slot:item.idaccion="{ item }">

                            <v-tooltip top>
                                <template v-slot:activator="{ props }">
                                  <v-icon v-if="access_emedico" v-bind="props"  color="blue-grey" @click="ModEditMedico(item)">mdi-pencil</v-icon>
                                </template>
                                <span>Editar Contacto</span>
                            </v-tooltip>
                            <span class="mx-1"></span>
                            <v-tooltip top>
                                <template v-slot:activator="{ props }">
                                  <v-icon v-if="access_emedico" v-bind="props"  color="pink" @click="eliminarMedico(item.med_id)">mdi-trash-can</v-icon>
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
    <v-dialog v-model="newMedico" max-width="400px">
        <v-card>
        <v-toolbar
            dark
            density="compact"
            color="blue">
            <v-app-bar-nav-icon> <v-icon>mdi-account-arrow-right</v-icon></v-app-bar-nav-icon>
            <v-toolbar-title><h5 class="font-weight-normal my-0">Crear Contacto</h5></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="cerrarDialogCrearMedico" icon>
            <v-icon>mdi-close-circle</v-icon>
            </v-btn>
        </v-toolbar>

        <v-card-text>

            <v-form
            ref="form"
            v-model="valid"
            lazy-validation>

            <v-select class="mb-3"
                v-model="addMedico.tipo_contacto"
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
                v-model="addMedico.tipo_identidad"
                :items="items.identidad"
                item-title="tip_ide_nombre"
                item-value="tip_ide_id"
                label="Tipo Identidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
            </v-select>

            <v-text-field class="mb-3" label="Identidad" v-model="addMedico.cedula" required density="compact" variant="underlined" :rules="nRules" maxlength="40"></v-text-field>
            <v-text-field class="mb-3" label="Nombre" v-model="addMedico.nombre" required density="compact" variant="underlined" :rules="nRules" maxlength="150"></v-text-field>
            <v-text-field class="mb-3" label="Dirección" v-model="addMedico.direccion" required density="compact" variant="underlined" :rules="nRules" maxlength="255"></v-text-field>
            <v-text-field class="mb-3" label="Teléfono" v-model="addMedico.telefono" required density="compact" variant="underlined" :rules="nRules" maxlength="100"></v-text-field>
            <v-autocomplete class="mb-3"
                v-model="addMedico.departamento"
                :items="items.departamento"
                item-title="dep_nombre"
                item-value="dep_id"
                label="Departamento"
                @update:model-value="getModMedicoLocalidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
            </v-autocomplete>
            <v-autocomplete
                v-model="addMedico.localidad"
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
            <v-autocomplete
                v-model="addMedico.especialidad"
                :items="especialidades"
                item-title="esp_nombre"
                item-value="esp_id"
                label="Especializacion"
                required
                density="compact"
                variant="underlined"
                :rules="nRules"
                >
            </v-autocomplete>
            <v-text-field class="mb-3" density="compact" variant="underlined" label="E-mail" v-model="addMedico.mail" maxlength="255"></v-text-field>
            </v-form>

        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="crearMedico">CREAR</v-btn>
            <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="cerrarDialogCrearMedico">CERRAR</v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- Modificar medico -->
    <v-dialog v-model="EditClient" max-width="400px">
        <v-card>
            <v-toolbar
            dark
            density="compact"
            color="blue">
            <v-app-bar-nav-icon> <v-icon>mdi-account-edit-outline</v-icon></v-app-bar-nav-icon>
            <v-toolbar-title><h5 class="font-weight-normal my-0">Modificar Medico</h5></v-toolbar-title>
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
                v-model="medico.tipo_contacto"
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
                v-model="medico.tipo_identidad"
                :items="items.identidad"
                item-title="tip_ide_nombre"
                item-value="tip_ide_id"
                label="Tipo Identidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
            </v-select>

                <v-text-field class="mb-3"  label="Cedula" v-model="medico.cedula" required density="compact" variant="underlined" :rules="nRules" maxlength="40"></v-text-field>
                <v-text-field class="mb-3"  label="Nombre" v-model="medico.nombre" required density="compact" variant="underlined" :rules="nRules" maxlength="150"></v-text-field>
                <v-text-field class="mb-3"  label="Dirección" v-model="medico.direccion" required density="compact" variant="underlined" :rules="nRules" maxlength="255"></v-text-field>
                <v-text-field class="mb-3"  label="Teléfono" v-model="medico.telefono" required density="compact" variant="underlined" :rules="nRules" maxlength="100"></v-text-field>

                <v-autocomplete class="mb-3" 
                v-model="medico.departamento"
                :items="items.departamento"
                item-title="dep_nombre"
                item-value="dep_id"
                label="Departamento"
                @update:model-value="getModMedicoLocalidad"
                required
                density="compact"
                variant="underlined"
                :rules="nRules">
                </v-autocomplete>
                <v-autocomplete
                v-model="medico.localidad"
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
                <v-autocomplete
                v-model="medico.especialidad"
                :items="especialidades"
                item-title="esp_nombre"
                item-value="esp_id"
                label="Especializacion"
                required
                density="compact"
                variant="underlined"
                :rules="nRules"
                >
                </v-autocomplete>
                <v-text-field class="mb-3"  label="E-mail" density="compact" variant="underlined" v-model="medico.mail" maxlength="255"></v-text-field>
            </v-form>

            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="text-none ms-3 text-white"  color="grey-darken-3" size="small" variant="elevated" @click="modificarMedico">MODIFICAR</v-btn>
                <v-btn class="text-none ms-4 text-black" color="white" size="small" variant="elevated" @click="EditClient=false">CERRAR</v-btn>
                </v-card-actions>
        </v-card>
    </v-dialog>


</template>
<script setup lang="ts">

import { ref, onMounted } from 'vue';
import { AuthStore } from '@/modules/common/stores/auth';
import type { rsEspecialidad, rsItems } from '@/modules/common/types/contactoInterface';
import type { AuthStoreState } from '@/modules/common/types/index';
import authApi from '@/api/api';
import Swal from 'sweetalert2';
import type { Header } from '@/modules/common/types/index';

// Variables globales
const especialidades = ref([]);
const especialidad = ref({id:0,nombre:''});
const medicos = ref([]);
const medico = ref({id:0, tipo_contacto: '', tipo_identidad:'',cedula:'', nombre:' ', direccion:' ',telefono:'',departamento:'',localidad:'', namelocalidad:'', mail:'',especialidad:''})
const items = ref<rsItems>({identidad:[],departamento:[],localidad:[]});

const newMedico = ref<boolean>(false);
const addMedico = ref({tipo_contacto: '', tipo_identidad:'', cedula:'', nombre:'', direccion:'', telefono:'', departamento:'', localidad:'', mail:'', especialidad:''});
const tipoContact = ref([{title: 'Medico', value:1},{title: 'Proveedor', value:2},{title: 'Ambos', value:3}]);

const EditClient = ref<boolean>(false);
const ButtonEditClient = ref<boolean>(false);

// Formularios
const form = ref<any>(null);

// Permisos
const authStore = AuthStore() as AuthStoreState;
const access_cmedico = ref<boolean>(false);
const access_emedico = ref<boolean>(false);
const access_dmedico = ref<boolean>(false);

// Datatable
const searchclient = ref<string>('');
const headers:(Header)[] = 
[
    { title: 'CONTACTO', key: 'med_tipo_contacto',align: 'center', sortable: false },
    { title: 'TIPO DOC',key: 'tipo_identidad.tip_ide_codigo', sortable: false},
    { title: 'IDENTIFICACION',key: 'med_cedula', sortable: false},
    { title: 'NOMBRE',key: 'med_nombre', sortable: false},
    { title: 'ESPECIALIZACION',key: 'especialidad.esp_nombre', sortable: false},
    //{ title: 'DIRECCION', key: 'med_direccion',sortable: false },
    { title: 'TELEFONO', key: 'med_telefono',sortable: false },
    { title: 'MAIL', key: 'med_email',sortable: false },
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
    await getEspecialdad();
});

// Eventos
const cerrarDialogCrearMedico = () =>
{
    newMedico.value = false;
    addMedico.value = {tipo_contacto: '', tipo_identidad:'', cedula:'', nombre:'', direccion:'', telefono:'', departamento:'', localidad:'', mail:'',especialidad:''}
}

// Methods
const getSearchMedico = async () =>
{
    await authApi.get(`api/base/search/medicov/${searchclient.value.toUpperCase()}`).then(res => 
    {
        if (res.data.length===0) 
        {
            medicos.value = []
        }
        else
        {
            medicos.value = res.data
        }
    })

};

const getEspecialdad = async () =>
{
    await authApi.get('api/base/especialidad').then(res => 
    {
        if (res.data.length===0) 
        {
            especialidades.value = []
        }
        else
        {
            especialidades.value = res.data
        }
    })

};

const getModMedicoIdentidad = async () =>
{
    authApi.get('api/base/info/tipoIdentidad').then((res) =>
    {
        items.value.identidad = res.data;
    });
};

const getModMedicoDepartamento = async () =>
{
    authApi.get('api/base/info/departamento').then((res) =>
    {
    items.value.departamento = res.data;
    });
};

const getModMedicoLocalidad = async () =>
{
    const iddepto = newMedico.value ? addMedico.value.departamento : medico.value.departamento
    await authApi.get(`api/base/info/localidad/${iddepto}`).then((res) =>
    {
        items.value.localidad = res.data;
    });
};

const ModNewMedico = async  () =>
{
    newMedico.value=true;
    await getModMedicoIdentidad();
    await getModMedicoDepartamento();
    await getEspecialdad();

};

const ModEditMedico = async (item:any) =>
{
    EditClient.value=true;
    await getModMedicoIdentidad();
    await getModMedicoDepartamento();

    medico.value.id = item.med_id
    medico.value.tipo_contacto = item.med_tipo_contacto + 1
    medico.value.tipo_identidad = item.med_id_tipo_identidad
    medico.value.cedula = item.med_cedula
    medico.value.nombre = item.med_nombre
    medico.value.direccion = item.med_direccion
    medico.value.telefono =  item.med_telefono
    medico.value.departamento = item.localidad.loc_id_departamento
    getModMedicoLocalidad()
    medico.value.localidad = item.med_id_localidad
    medico.value.mail = item.med_email
    medico.value.especialidad = item.especialidad.esp_id
};

const crearMedico = async () =>
{

    const isValid = await form.value?.validate()

    if(isValid.valid)
    {
        const params = {keys:addMedico.value}
        authApi.post('api/base/medico',params).then(res =>
        {
            if (res.data === 0) {
                Swal.fire("Oops!","Ocurrio un error al crear el registro, verifique los datos", "error")
            }
            else
            {
                Swal.fire("OK!", "Contacto Creado", "success");
                newMedico.value = false
                limpiarMedico(addMedico.value)
                // getmedico()
            }
        })
    }
};

const modificarMedico = async () =>
{
    const isValid = await form.value?.validate()

    if(isValid.valid)
    {
        const params = {keys:medico.value}
        authApi.put(`api/base/medico/${medico.value.id}`,params).then(res =>
        {
        if (res.data === 0) {
            Swal.fire("Oops!","Ocurrio un error al crar el registro, verifique los datos", "error")
        }
        else
        {

            EditClient.value = false
            limpiarMedico(medico.value)
            //getmedico()
            getSearchMedico()
            Swal.fire("OK!", "Regisro modificado con éxito", "success");
        }
        })
    }
};

const eliminarMedico = async (id:number) =>
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
            authApi.delete(`api/base/medico/${id}`).then(res =>
            {
                if (res.data === 0) 
                {

                    Swal.fire("Oops!","No se puede eliminar registro, contacto tiene registros relacionados", "error")
                }
                else
                {
                    getSearchMedico()
                    Swal.fire("Ok!","Medico eliminado con éxito", "success")
                }
            })


        }
    })
};

const limpiarMedico = (medico:any) =>
{
    Object.keys(medico).forEach((key) => 
    {
        (medico as any)[key] = '';
    });
};

const controlPermiso =  async () =>
{
    const componente10 = authStore.permisos.find((p:any) => p.per_id_componente === 10);
    if(componente10)
    {
        const rs =  JSON.parse(componente10.per_acceso);

        access_cmedico.value = rs.Crear_Medico === "1" ? true : false;
        access_emedico.value= rs.Editar_Medico === "1" ? true : false;
        access_dmedico.value = rs.Eliminar_Medico === "1" ? true : false;
    }
};
</script>