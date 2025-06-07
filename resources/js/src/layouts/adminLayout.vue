<script setup>
import sideBar from '@/components/layouts/sideBar.vue';
import headerAdmin from '@/components/layouts/headerAdmin.vue';
import {  inject, onMounted, ref, watch} from 'vue';
import { useAuthStore } from '@/services/store/auth.services';
import { Notify } from 'quasar'
import { useRoute, useRouter } from 'vue-router';
  const store = useAuthStore();
  const router = useRouter();
  const route = useRoute()
  const user = ref({})
  const emitter = inject('emitter')
  const showXs = ref(false)
    // listen
  const showNotify = (type,text) => {
    Notify.create({
      color:type,
      message: text,
      position:'top',
      timeout:2000
    })
  }
  // methods
  const getCurrentUser = () =>{
    store.currentUser().then((data)=>{
      if(data.status !== 200 ) throw data
      user.value = data.data
    }).catch((e) => { 
      showNotify ( 'negative', 'El tiempo activo de su sesión ha caducado, vuelve a iniciar sesión')
      router.push('/login')
    })
  }
  const showMovil = () => {
    console.log('se logró')
    showXs.value = !showXs.value
  }
  watch(route, () => {
    getCurrentUser()
  });

  onMounted(() =>{
    getCurrentUser()
    emitter.on('showSidebar', showMovil) 

  })
</script>
<template>
  <section class="h-full flex">
    <sideBar  />
    <div class="md:w-5/6 w-full h-screen container__page">
      <headerAdmin />
      <section class=" py-5 md:px-8 px-4 h-5/6" >
        <router-view v-slot="{ Component }">
          <transition name="fade">
            <component :is="Component" />
          </transition>
        </router-view>
      </section>
    </div>
  </section>
</template>
<style lang="scss">
.container__page{
  overflow: auto;
}


</style>