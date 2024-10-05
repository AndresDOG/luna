<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>Login</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <v-form @submit.prevent="login">
              <v-text-field
                v-model="formData.email"
                label="mail"
                required
              ></v-text-field>
              <v-text-field
                v-model="formData.password"
                label="Password"
                type="password"
                required
              ></v-text-field>
              <v-btn type="submit" color="primary" class="mt-4" block>Login</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
  
<script setup lang="ts">

  import {ref} from 'vue';
  import {storeToRefs} from 'pinia';
  import {AuthStore} from '@/modules/common/stores/auth';
  import Swal from 'sweetalert2';
  import {useRouter} from 'vue-router';
  
  const formData=ref({ email:'',password:''});  
  const {loginUser} = AuthStore();
  const {if_authenticated} = storeToRefs(AuthStore());   
  const router= useRouter();
  
  const login = async () => 
  {
  
    const {ok} = await loginUser(formData.value);
    
    if (ok) 
    {     
      router.push('/dashboard');     
    } 
    else 
    {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo conectar, intente de nuevo'        
      })
    } 
  }
    
</script> 