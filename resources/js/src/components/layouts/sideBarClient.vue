<script setup>
import {  inject, onMounted, ref, watch} from 'vue';
import logo from '@/assets/images/logo/logoCars2.webp'

const emitter = inject('emitter')
const showXs = ref( window.screen.width < 780 ? false : true)

const showMovil = () => {
  window.screen.width < 780 
  ? showXs.value = !showXs.value
  : ''
  
}


onMounted(() =>{
  emitter.on('showSidebarClient', showMovil) 
})

</script>
<template>
  <aside class="h-full md:w-1/6 w-5/6 aside__menuClient q-px-md blockx md:hiddenx" :class="showXs ? 'blockW' : 'hiddenW'">
    <div>
      <div style="position: absolute;right: -2.5rem; top: 0rem;" class=" md:hidden">
        <q-btn rounded unelevated icon="arrow_back_ios" class="close_buttonSidebar q-py-md" size="lg" color="dark" text-color="white"  @click="showXs = false"/>
      </div>
      <div class="my-4">
        <img :src="logo" alt=""  style="width:12rem" class="mx-auto">
      </div>
      <ul class="text-center mt-8">
          <RouterLink activeClass="active_link"  @click="showMovil()"  to="/home" >
            <li class="text-white my-5 mx-5 nav_header--items" >
              <div class="px-2">
                Inicio
              </div>
            </li>
          </RouterLink>
          <RouterLink activeClass="active_link"  @click="showMovil()"  to="/winners" >
            <li class="text-white my-5 mx-5 nav_header--items" >
              <div class="px-2">
                Ganadores
              </div>
            </li>
          </RouterLink>
          <RouterLink activeClass="active_link"  @click="showMovil()"  to="/support">
            <li class="text-white my-5 mx-5 nav_header--items" >
              <div class="px-2">
                Soporte
              </div>
            </li>
          </RouterLink>
        </ul>
    </div>
  </aside>
</template>
<style lang="scss">
.hiddenW{
  width: 5rem;
  transform: translateX(-50rem);
}
.blockW{
  width: w-5/6;
}
.aside__menuClient{
  box-shadow: 5px 0px 26px 0px #1e1e1e;
  background: #111111;
  position: relative;
  transition: all 0.8s ease;
}
.close_buttonSidebar{
  box-shadow: 10px 0px 10px 0px #2b2b2b86;

}
.item__menu-aside{
  
  background: #f9fafb;
  border-radius: 0.8rem;
  cursor: pointer;
  transition: all  ease 0.5s;
  color: black;
  &:hover{
    background: #414141;
    & .text-center {
      color: white
    }
  }
  &:has(.active_link){
    background: #414141;
    & .text-center {
      color: white
    }

  }

}
@media (max-width: 768px){
 .aside__menuClient{
  position: absolute;
  z-index: 110;
 }
}

</style>