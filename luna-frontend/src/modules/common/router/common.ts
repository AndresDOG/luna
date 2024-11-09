import type {RouteRecordRaw} from 'vue-router';

const commonRoutes: Array<RouteRecordRaw> = 
[
    
    {
        name: 'common.usuario',
        path: '/common/usuario',
        component: () =>import('@/modules/common/views/Usuario.vue')
    },
    {
        name: 'common.rol',
        path: '/common/rol',
        component: () =>import('@/modules/common/views/Rol.vue')
    },
    {
        name: 'common.medico',
        path: '/common/medico',
        component: () =>import('@/modules/common/views/Medico.vue')
    },
    {
        name: 'common.paciente',
        path: '/common/paciente',
        component: () =>import('@/modules/common/views/Paciente.vue')
    },
    {
        name: 'common.tratamiento',
        path: '/common/tratamiento',
        component: () =>import('@/modules/common/views/Tratamiento.vue')
    },
    {
        name: 'common.especialidad',
        path: '/common/especialidad',
        component: () =>import('@/modules/common/views/Especialidad.vue')
    },
    {
        name: 'common.consultaPaciente',
        path: '/common/conspac',
        component: () =>import('@/modules/common/views/Consulta/AsignTra.vue')
    },
    {
        name: 'common.historial',
        path: '/common/historial',
        component: () =>import('@/modules/common/views/Consulta/Historial.vue')
    },
    {
        name: 'common.acceso',
        path: '/common/acceso',
        component: () =>import('@/modules/common/views/Acceso.vue')
    },
    {
        name: 'common.equipo',
        path: '/common/equipo',
        component: () =>import('@/modules/common/views/Equipo.vue')
    },
    
    
];

export default commonRoutes;