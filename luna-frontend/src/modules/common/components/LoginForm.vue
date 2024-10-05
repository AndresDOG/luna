<template>
    <v-container fluid style="height: 100vh; overflow: hidden; position: relative;">
      <div class="background-image"></div>
      <div class="form-container">
        <v-row justify="center" align="center" style="height: 100%;">
          <v-col cols="12" sm="8" md="6">
            <v-card class="mx-auto" :max-width="cardWidth" flat>
              <v-card-text>
                <v-form @submit.prevent="login">
                    <img class="LogoPc" src="../../../assets/logo.png" alt="Logo" style="margin-bottom: 20px;">
                    <img class="LogoMob" src="../../../assets/mobile.png" alt="Logo" style="margin-bottom: 20px;">
                    <div class="text-subtitle-2 text-medium-emphasis">Account</div>
                    <v-text-field
                        v-model="formData.email"
                        density="compact"
                        placeholder="Username"
                        prepend-inner-icon="mdi mdi-account-outline"
                        variant="outlined">
                    </v-text-field>
  
                    <div class="text-subtitle-2 text-medium-emphasis d-flex align-center justify-space-between">Password</div>
  
                    <v-text-field
                        v-model="formData.password"
                        :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                        :type="visible ? 'text' : 'password'"
                        density="compact"
                        placeholder="Enter your password"
                        prepend-inner-icon="mdi-lock-outline"
                        variant="outlined"
                        @click:append-inner="visible = !visible">
                    </v-text-field>                 
                    <v-btn
                        class="mb-8 font-weight-bold"
                        color="blue"
                        size="large"
                        variant="tonal"
                        block
                        type="submit">
                        LogIn
                    </v-btn>
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </div>
    </v-container>
  </template>
  
<script setup lang="ts">
    import { ref, onMounted, onUnmounted } from 'vue';     
    import {AuthStore} from '@/modules/common/stores/auth';
    import Swal from 'sweetalert2';
    import {useRouter} from 'vue-router';
    
    const visible = ref(false);
    const cardWidth: any = ref('');

    const formData=ref({ email:'',password:''});  
    const {loginUser} = AuthStore();    
    const router= useRouter();

    
    // Hooks
    onMounted(() => 
    {
        UpdateCarWitdh();
        window.addEventListener('resize', UpdateCarWitdh);
    });

    onUnmounted(() => 
    {
        window.removeEventListener('resize', UpdateCarWitdh);
    });

    // Funciones
    const UpdateCarWitdh = () => 
    {
        if (window.innerWidth < 1000 && window.innerHeight < 851) 
        {
          cardWidth.value = '90%';
        } 
        else 
        {
          cardWidth.value = null;
        }
    };

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
            });
        } 
    }

</script>

<style scoped>
  .background-image 
  {
    position: absolute;
    top: 0;
    left: 0;
    width: 60%;
    height: 100%;
    background-image: url('@/assets/fondo.jpg');
    background-size: cover;
    background-position: center;
  }
  
  .form-container 
  {
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .LogoMob 
  {
    display: none;
  } 
  
  @media screen and (max-width: 999px) 
  {
    .background-image {
      display: none;
    }
    .LogoMob {
      display: block;
    }
    .LogoPc {
      display: none;
    }
    .form-container {
      position: absolute;
      top: 0;
      right: 0;
      width: 90%;
      height: 100%;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }
  }
  
</style>
  