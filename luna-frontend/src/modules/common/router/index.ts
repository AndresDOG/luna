import type {RouteRecordRaw} from 'vue-router';
import LoginForm from '@/modules/common/components/LoginForm.vue';
import Dashboard from '@/modules/common/views/Dashboard.vue';
import Usuario from '@/modules/common/views/Usuario.vue';

const loginRoutes: Array<RouteRecordRaw> = 
[
  {
    path: '/',
    component: LoginForm,
  }
];

export default loginRoutes;