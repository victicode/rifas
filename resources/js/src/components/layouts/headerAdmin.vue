<script setup>
import { useAuthStore } from '@/services/store/auth.services';
import moment from 'moment';
import { storeToRefs } from 'pinia'
import { ref,onMounted, inject } from 'vue';

const clock = ref(moment().format('DD/MM/YYYY, h:mm:ss a'))
const emitter = inject('emitter')
onMounted(() => {
  setInterval(() => {
    clock.value = moment().format('DD/MM/YYYY, h:mm:ss a')
  }, 1000);
})

const showSidebar = () => {
  emitter.emit('showSidebar')
}

</script>
<template>
  <div class="px-3 py-2">
    <div class="  px-2 header__container" >
      <div class="flex justify-between items-center h-full">
        <div>
          <q-btn flat round  color="white" class="mx-2" text-color="white" icon="menu" @click="showSidebar()" />
        </div>
  
        <div class="flex items-center">
          <div class="text-white text-subtitle1  hiddenx md:block" style="font-weight: 500;">
            {{ clock }}
          </div>
          <q-btn flat round color="white" class="mx-2" text-color="white" icon="settings" />
          <q-btn flat round color="white" class="mx-2" text-color="white" icon="notifications" />
          <q-btn outline style="color: white;" class="mx-2" label="Cerrar Sesion" />
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