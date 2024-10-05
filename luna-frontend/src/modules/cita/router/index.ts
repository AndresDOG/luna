import type {RouteRecordRaw} from 'vue-router';

const citaRoutes: Array<RouteRecordRaw> = 
[
    {
        name: 'cita.create',
        path: '/cita/create/',
        component: () =>import('@/modules/cita/views/Create.vue')
    }
];

export default citaRoutes;