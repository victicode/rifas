<script setup>
import { useRoute, useRouter } from 'vue-router';
import { onMounted, ref } from 'vue';
import { useUserStore } from '@/services/store/user.store';
import numberUtils from '@/utils/numberUtils.js';
import { Notify } from 'quasar'

const route = useRoute()
const router = useRouter()
const user = ref({})
const userStore = useUserStore()
const loading = ref(false) 
const showModal = ref('')
const ready = ref(false)
const loadingShow = (state) => {
  loading.value = state;
}
const showNotify = (type,text) => {
  Notify.create({
    color:type,
    message: text,
    timeout:2000
  })
}

const getUserById = () => {
  ready.value = false
  userStore.findUserById(route.params.id)
  .then((response) => {
    user.value = response.data
    ready.value = true
  })
}
const updateUser = () => {

  loadingShow(true)
  userStore.updateUser(user.value, user.value.id )
  .then((response) => {
    if(response.code !==200) throw response

    showNotify('positive', 'Usuario modificado con exito')

    setTimeout(() => {
      getUserById()
      loading.value = false
    },1000)
  })
  .catch((response) => {
    console.log(response)
    loadingShow(false)
    showNotify('negative', response)
  })
}
onMounted(() => {
  getUserById()
})
</script>
<template>
  <div class="h-full">
    <div class="row items-center ">
      <div class="flex items-center col-md-6 col-12">
        <div class="icon_badge q-pa-sm flex items-center justify-center cursor-pointer pointer" @click="router.go(-1)">
          <q-icon name="arrow_back" color="white" size="1.5rem"/>
        </div>
        <h5 class="text-black font-bold ml-2" >
          Configuración de perfil
        </h5>
      </div>
    </div>
    <div class="row md:pt-8 pt-8" v-if="ready">
      <div class="col-md-2 col-12 flex flex-center ">
        <div class="profileLetter flex flex-center ">
          {{ user.name.charAt(0).toUpperCase() }}
        </div>
      </div>
      <div class="col-12 col-md-10">
        <q-form
          class="row w-full"
          style="height: 100%; "
          @submit="updateUser()"
        >
          <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb- mt-12" >
            <q-input
              v-model="user.name"
              label="Nombre y apellidos"
              class=" configUser"
              color="primary"
              outlined
              :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
            />
          </div>  
          <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0" >
            <q-input
              v-model="user.email"
              label="Correo electronico"
              class=" configUser"
              color="primary"
              outlined
              :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
            />
          </div>  
          <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0" >
            <q-input
              v-model="user.password"
              label="Contraseña"
              class=" configUser"
              color="primary"
              outlined
              :type="isPwd ? 'password' : 'text'"
              hint="Dejar en blanco si deseas consevar la contraseña actual"
            >
              <template v-slot:append>
                <q-icon
                  :name="isPwd ? 'eva-eye-off-outline' : 'eva-eye-outline'"
                  class="cursor-pointer"
                  @click="isPwd = !isPwd"
                />
              </template>
            </q-input>
          </div>  
          <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0 pt-5" >
            <q-btn label="Actualizar"   color="blur" type="submit" style="width: 50%; border-radius: 0.8rem; padding: 0.7rem 0px; width: 100%;" :loading="loading"/>
          </div>  
        </q-form>
      </div>
      
      
    </div>
    <div class="flex flex-center w-full mt-24  pt-24" v-else>
      <q-spinner
        color="primary"
        size="6rem"
        :thickness="5"
      />
    </div>

  </div>
</template>

<style lang="scss">
.profileLetter{
  height: 10rem;
  width: 10rem;
  background: black;
  color:white;
  font-weight: 900;
  font-size: 5rem;
  border-radius: 2rem;
  box-shadow: 0px 5px 5px 0px rgb(206, 206, 206);
}
.configUser {
  &.q-field--standard.q-field--readonly .q-field__control:before {
    
    border-bottom-style:solid!important;
    border-radius: 0.8rem;

  }
  &.q-field--outlined .q-field__control:before{
    border-radius: 0.8rem;
  }
  &.q-field--outlined .q-field__control{
    border-radius: 0.8rem;
  }
  & input{
    padding-bottom: 0px!important;
  }
  & .q-field__label{
    transform: translateY(11%)
  }
  &.quantity.q-field--focused .q-field__label, &.quantity.q-field--float .q-field__label{
    z-index: 100;
    background: white!important;
    font-weight: 600;
    width: auto;
    padding: 0px 20px;
    font-size: 0.15em;
    text-align: center;
    transform: translateY(-135%) translateX(-0.1%) !important;
  }
  &.q-field--focused .q-field__label, &.q-field--float .q-field__label{
    z-index: 100;
    background: #f5f5f4;
    color: black;
    font-weight: 600;
    width: auto;
    padding: 0px 15px;
    font-size: 0.8rem;
    transform: translateY(-135%) translateX(-0.1%) !important;
  }
  
  & .q-field__native{
    padding-top: 10px!important;
    font-weight: 600;
  }
  & .q-field__append{
    transform: translateY(5%)
  }
}
@media (max-width: 768px){

}
</style>