import type {RouteRecordRaw} from 'vue-router';
import {createRouter, createWebHistory} from 'vue-router';
import {AuthStore} from '@/modules/common/stores/auth';

import commonRoutes from '@/modules/common/router/common';
import citaRoutes from '@/modules/cita/router/index';


import Dashboard from '@/modules/common/views/Dashboard.vue';
import Usuario from '@/modules/common/views/Usuario.vue';
import loginRoutes from '@/modules/common/router/index';
import Kpi from '@/modules/common/views/Kpi.vue';



const routes: RouteRecordRaw[] = [
  ...loginRoutes,  
  {
      path: '/dashboard',    
      component: Dashboard,   
      meta: { requiresAuth: true },
       children: [
         {
           path: '',
           redirect: '/dashboard/about',
         },
         {
           path: 'about',        
           component: Kpi,       
         },
         ...citaRoutes,
         ...commonRoutes
         
        ]
     },
  
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => 
{
  const authStore = AuthStore();
  if (to.matched.some((record) => record.meta.requiresAuth)) 
  {
    if (!authStore.if_authenticated) {
      next('/');
    } 
    else 
    {
      next();
    }
  } 
  else 
  {
    next();
  }
  
});

export default router;