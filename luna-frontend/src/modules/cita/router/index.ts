import type {RouteRecordRaw} from 'vue-router';

const citaRoutes: Array<RouteRecordRaw> = 
[
    {
        name: 'cita.create',
        path: '/cita/create/',
        component: () =>import('@/modules/cita/views/Create.vue')
    },
    {
        name: 'cita.view',
        path: '/cita/view/:id_cita',
        component: () =>import('@/modules/cita/views/View.vue'),        
        props: route => ({id_cita: route.params.id_cita}),        
    },
    {
        name: 'cita.edit',
        path: '/cita/edit/:accion?/:id_cita?',
        component: () =>import('@/modules/cita/views/Edit.vue'),
        props: route => ({accion: route.params.accion, id_cita: route.params.id_cita}),
    },
    {
        name: 'consulta.simple',
        path: '/consulta/simple',
        component: () =>import('@/modules/cita/views/Consulta/Simple.vue'),
    },
];

export default citaRoutes;