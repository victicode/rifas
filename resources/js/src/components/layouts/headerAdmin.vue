<script setup>
import { useAuthStore } from '@/services/store/auth.services';
import { ref,onMounted, inject } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia'
import moment from 'moment';
import notificationModule from '@/components/layouts/notificationModule.vue';

const router = useRouter()
const clock = ref(moment().format('DD/MM/YYYY, h:mm:ss a'))
const emitter = inject('emitter')
const loading = ref(false)

const { user } = storeToRefs(useAuthStore());
const showSidebar = () => {
  emitter.emit('showSidebar')
}
const logout = () => {
  loading.value = true;
  useAuthStore().logout()
  .then((response) => {
    setTimeout(() => {
      
      router.push('/login')
      loading.value = false;
    }, 1000);
  })
}

onMounted(() => {
  setInterval(() => {
    clock.value = moment().format('DD/MM/YYYY, h:mm:ss a')
  }, 1000);
})

</script>
<template>
  <div class="px-3 py-2">
    <div class="  px-2 header__container" >
      <div class="flex md:justify-end justify-between items-center h-full ">
        <div class="blockx md:hiddenx">
          <q-btn flat round  color="white" class="mx-2" text-color="white" icon="menu" @click="showSidebar()" />
        </div>
  
        <div class="flex items-center">
          <div class="text-white text-subtitle1  hiddenx md:block" style="font-weight: 500;">
            {{ clock }}
          </div>
          <q-btn flat round color="white" class="mx-1" text-color="white" icon="settings" @click="router.push('/admin/system/config-user/'+user.id)"/>
          <notificationModule />
          <q-btn outline style="color: white;" :loading="loading" class="mx-1" label="Cerrar Sesion"  @click="logout()"/>
        </div>
      </div> 
    </div>
  </div>
</template>
<style lang="scss">
.header__container {
  background: rgb(17, 17, 17);
  height: 80px;
  border-radius: 1rem;
  overflow: hidden; 
  box-shadow: 0px 5px 26px 0px #9a9a9a;

}
</style>