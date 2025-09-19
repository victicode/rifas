<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/services/store/auth.services';
import { storeToRefs } from 'pinia'

const { user } = storeToRefs(useAuthStore());
const router = useRouter()
const goTo = (url) => {
  router.push(url)
}
</script>
<template>
  <div class="h-full">
    <div class="flex items-center justify-between ">
      <div class="flex items-center">
        <div class="icon_badge q-pa-sm flex items-center justify-center">
          <q-icon name="local_activity" color="white" size="2rem"/>
        </div>
        <h4 class="text-black font-bold ml-2" >
          Configuración
        </h4>
      </div>
    </div>
    <div class="mt-12 md:mt-4 ">
      <div class="row items-top md:justify-start w-100 md:mt-10">
        <div class="col-md-2 col-6 mb-6 md:mt-0">
          <div @click="goTo('/admin/system/config-user/'+user.id)">
            <div class="buttons_report q-pa-sm flex items-center justify-center">
              <q-icon name="build_circle" color="white" size="2rem"/>
            </div>
            <div class="text-center text-subtitle1 text-black mt-1 text-bold cursor-pointer">Configuración de usuario</div>
          </div>
        </div>
        <div class="col-md-2 col-6 mb-6 md:mt-0">
          <div @click="goTo('/admin/system/users')">
            <div class="buttons_report q-pa-sm flex items-center justify-center">
              <q-icon name="account_circle" color="white" size="2rem"/>
            </div>
            <div class="text-center text-subtitle1 text-black mt-1 text-bold cursor-pointer">Crear Usuarios</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss">
.buttons_report{
  border-radius: 0.5rem;
  background: black;
  width: 40%;
  box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.593);
  height: 4rem;
  margin: auto;
  cursor: pointer;
  transition: all 0.5s ease;
  &.notD{
    opacity: 0.4;
    cursor: not-allowed;
  }
  &:hover {

    transform: scale(1.1);
  }
}
@media (max-width: 768px){

}
</style>