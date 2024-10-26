<template>
  <v-app id="inspire">
    <v-app-bar color="blue-darken-3">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-app-bar-title>{{ userAuth.empresa.emp_nombre }}</v-app-bar-title>
      <v-spacer />
      <v-btn @click="goToCita()" icon><v-icon>mdi-newspaper-plus</v-icon></v-btn>
      <v-btn @click="goToConsulta()" icon><v-icon>mdi-magnify</v-icon></v-btn>
      <v-btn icon><v-icon>mdi-file-search-outline</v-icon></v-btn>
      <v-btn @click="goToDashboard()" icon><v-icon>mdi-apps</v-icon></v-btn>
      <v-list bg-color="blue-darken-2" class="py-1 d-none d-sm-flex" nav rounded="xs">
        <v-list-item :prepend-avatar="imageSrc" :subtitle="userAuth.user.rol.rol_nombre" :title="userAuth.user.usu_nombre"></v-list-item>
      </v-list>
      <v-btn @click="logout" icon><v-icon>mdi-logout</v-icon></v-btn>
      <v-btn icon="$vuetify" variant="tonal"></v-btn>
    </v-app-bar>

    <v-navigation-drawer v-model="drawer">
      <v-list density="compact" expand nav>
        <v-list-item color="indigo" to="/dashboard">
          <v-list-item-title >
            <v-icon size="large" class="mr-2" color="pink">mdi-monitor-dashboard</v-icon>
            Dashboard
          </v-list-item-title>
        </v-list-item>

        <template v-for="(item, i) in menu" :key="`menu-item-${i}`">
          <v-list-group v-if="item.children && item.children.length" :key="`group-${i}`" color="indigo">
            <template v-slot:activator="{ props }">
              <v-list-item color="indigo" v-bind="props">
                <v-list-item-title>
                  <v-icon size="large" color="indigo" class="mr-2">{{ `mdi-${item.com_icono}` }}</v-icon>
                  {{ item.com_nombre }}
                </v-list-item-title>
              </v-list-item>
            </template>

            <template v-for="(subItem, j) in item.children" :key="`sub-item-${j}`">
              <v-list-group v-if="subItem.children && subItem.children.length" :key="`sub-group-${j}`" class="ml-4">
                <template v-slot:activator="{ props }">
                  <v-list-item  color="indigo" v-bind="props">
                    <v-list-item-title color="indigo">
                      <v-icon size="large" color="grey-darken-1" class="mr-2">{{ `mdi-${subItem.com_icono}` }}</v-icon>
                      {{ subItem.com_nombre }}
                    </v-list-item-title>
                  </v-list-item>
                </template>

                <v-list-item color="indigo" v-for="(subSubItem, k) in subItem.children" :key="`sub-sub-item-${k}`" :to="subSubItem.com_path" class="ml-8">
                  <v-list-item-title  color="indigo">
                    {{ subSubItem.com_nombre }}
                    <v-icon size="large" class="ms-2">{{ `mdi-${subSubItem.com_icono}` }}</v-icon>
                  </v-list-item-title>
                </v-list-item>
              </v-list-group>

              <v-list-item v-else :key="`sub-item-${j}`" :to="subItem.com_path" class="ml-4">                   
                <v-list-item-title>
                  <v-icon color="grey-darken-1" size="large">{{ `mdi-${subItem.com_icono}` }}</v-icon>
                  {{ subItem.com_nombre }}
                </v-list-item-title>
              </v-list-item>
            </template>
          </v-list-group>

          <!-- <v-list-item v-else :key="`item-${i}`" :to="item.com_path" color="indigo">
           
              <v-icon>{{ `mdi-${item.com_icono}` }}</v-icon>
           
            <v-list-item-title>{{ item.com_nombre }}</v-list-item-title>
          </v-list-item> -->
        </template>

        <v-list-item @click="logout">
          <v-list-item-title>
            <v-icon class="mr-2" color="pink">mdi-logout</v-icon>
            Logout
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-main class="bg-grey-lighten-3">
      <router-view></router-view>
    </v-main>

    <v-footer :inset="footer.inset" color="grey-lighten-1" app>
      <span class="px-4 text-caption font-weight-bold"> unisimon.dev &copy;{{ new Date().getFullYear() }}</span>
      <v-spacer></v-spacer>
      <span class="px-4 text-caption font-weight-bold"> v-luna - v.1.0</span>
    </v-footer>
  </v-app>
</template>


<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import imageSrc from '../../../assets/default.png';
import { AuthStore } from '@/modules/common/stores/auth';
import authApi from '@/api/api';
import type { MenuItem } from '@/modules/common/types/index';

const drawer = ref(true);
const footer = { inset: true };
const menu = ref<MenuItem[]>([]);
const router = useRouter();
const { userLogout } = AuthStore();

const userAuth = AuthStore();

async function logout() {
  const { ok } = await userLogout();
  if (ok) router.push('/');
}

// Hooks
onMounted(async () => {
  await getMenu();
});

const goToCita = () => 
{
  router.push({ name: 'cita.create'})
}

const goToConsulta = () => 
{
  router.push({ name: 'common.consultaPaciente'})
}

const goToDashboard = () => 
{
  router.push('/dashboard');
}

// Funciones de Usuario
const getMenu = async () => {
  try {
    const response = await authApi.get('api/base/info/menu');
    menu.value = buildMenuTree(response.data);
  } catch (error) {
    console.error('Error fetching menu:', error);
  }
};

const buildMenuTree = (data: MenuItem[]): MenuItem[] => {
  const map = new Map<number, MenuItem>();
  const roots: MenuItem[] = [];

  data.forEach((item) => {
    const newItem: MenuItem = { ...item, children: []};
    map.set(newItem.com_id, newItem);
    if (newItem.com_padre_id === null) {
      roots.push(newItem);
    }
  });

  const buildTree = (node: MenuItem | undefined) => {
    if (!node || !node.com_id) return;

    const children = data.filter((child) => child.com_padre_id === node.com_id);
    children.forEach((child) => {
      const newNode = map.get(child.com_id);
      if (newNode) {
        buildTree(newNode);
        node.children?.push(newNode);
      }
    });
  };
  

  roots.forEach((root) => buildTree(root));

  return roots;
};
</script>

