<script setup>
import {  inject, onMounted, ref, watch} from 'vue';

const emitter = inject('emitter')
const showXs = ref( window.screen.width < 780 ? false : true)

const showMovil = () => {
  window.screen.width < 780 
  ? showXs.value = !showXs.value
  : ''
  
}


onMounted(() =>{
  emitter.on('showSidebar', showMovil) 

})
</script>
<template>
  <aside class="h-full md:w-1/6 w-5/6 aside__menu q-px-md md:block"  :class="showXs ? 'blockW' : 'hiddenW'">
    <div>
      <div style="position: absolute;right: -2.5rem; top: 0rem;" class=" md:hidden">
        <q-btn rounded unelevated icon="arrow_back_ios" class="close_buttonSidebar q-py-md" size="lg" color="dark" text-color="white"  @click="showXs = false"/>
      </div>
      <div class="text-white text-center text-h6 py-6 title__app-aside">
        RIFAS APP
      </div>
      <div class="q-px-md " style="border-top: 1px solid darkgrey;">
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/dashboard" @click="showMovil()">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Inicio
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/rifas" @click="showMovil()">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Rifas
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/pagos" @click="showMovil()">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Pagos
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/pay_methods" @click="showMovil()">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Metodos de pago
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/clients" @click="showMovil()">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Clientes
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/configuration" @click="showMovil()">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Sistema
            </div>
          </RouterLink>
        </div>
      </div>
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
.aside__menu{
  box-shadow: 5px 0px 26px 0px #9a9a9a;
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
 .aside__menu{
  position: absolute;
  z-index: 2;
 }
}

</style>