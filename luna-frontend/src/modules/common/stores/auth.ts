import { defineStore } from 'pinia';
import authApi from '@/api/api';
import type { AuthStoreState, UserForm } from '@/modules/common/types/index';

const csrf = () => authApi.get(`${import.meta.env.VITE_API_URL}/sanctum/csrf-cookie`);

export const AuthStore = defineStore('AuthStore', 
{
  state: (): AuthStoreState => ({
    user: null,
    empresa: null,
    if_authenticated: false,
    permisos: [], 
  }),
  getters: {},
  actions: 
  {
    async loginUser(userForm: UserForm) 
    {
      const { email, password } = userForm;
      
      let form_data = new FormData();
      form_data.append('email', email);
      form_data.append('password', password);      

      try 
      {
        await csrf();
        await authApi.post('login', form_data);  
      
        // Carga Datos Usuario                 
        await this.setUserActivo(); 

        // Carga Permisos
        await this.getPermisos(); 

        this.if_authenticated = true;

        return {ok: true};
      } 
      catch (error) 
      {
        return {ok: false};
      } 
      
    },

    async userLogout() 
    {
      try 
      {        
        await authApi.post('logout');
        this.if_authenticated = false;
        this.user = null;
        this.empresa = null;
        this.permisos = []; 
        return {ok: true};
      } 
      catch (error) 
      {
        return {ok: false};
      }
    },

    async setUserActivo() 
    {
      const response = await authApi.get('api/base/info/usuarioActivo');
      this.user = response.data.user;
      this.empresa = response.data.empresa;
    },   
    
    async getPermisos() 
    {
      const response = await authApi.get(`api/base/info/permisoComponente/${this.user.rol.rol_id}`);
      this.permisos = response.data; 
    },
  },
});
