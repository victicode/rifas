<script setup>
import logo from '@/assets/images/logo/logo3.png'
import supergana from '@/assets/images/logo/supergana.png'
import { inject } from 'vue';
import conalot from '@/assets/images/logo/conalot.png'

import { useRouter } from 'vue-router';
const emitter = inject('emitter')
const router = useRouter()
const showSidebar = () => {
  emitter.emit('showSidebarClient')
}
</script>
<template>
  <header class="h-full header__client">
    <div class=" h-full md:px-6 md:py-0 py-1 mkBG row items-center" style="    background-image: url('https://vergacionderifa.com/static/img/trama.svg');" >
      <div class="flex items-center  md:justify-start col-6 col-md-2 mb-0 md:mb-0 pl-3 md:pl-0">
        <!-- <img :src="logo" alt=""  style="width:5.5rem" @click="router.push('/home')"> -->
        <div class="text-white text-bold  rubik-glitch gradient-text gold-gradient" >
           Gana con Frank
        </div>
      </div>
      <div class="flex md:hiddenx justify-end col-6 ">
        <img :src="supergana" alt=""  style="width:3rem; height:3rem" class="mr-2">
        <img :src="conalot" alt=""  style="width:3rem; height:3rem" class="ml-2">
        <q-btn flat round  color="white" class="mx-2" text-color="white"  @click="showSidebar()" >
          <q-icon name="menu" size="1.7rem" />
        </q-btn>
      </div>
      <div class="col-12 col-md-5 md:pl-0  hiddenx md:flex">
        <ul class="flex justify-center md:justify-start">
          <RouterLink activeClass="active_link" to="/home" >
            <li class="text-white mx-3 nav_header--items" >
              <div class="px-2">
                Inicio
              </div>
            </li>
          </RouterLink>
          <RouterLink activeClass="active_link" to="/winners" >
            <li class="text-white mx-3 nav_header--items" >
              <div class="px-2">
                Ganadores
              </div>
            </li>
          </RouterLink>
          <RouterLink activeClass="active_link" to="/support">
            <li class="text-white mx-3 nav_header--items" >
              <div class="px-2">
                Soporte
              </div>
            </li>
          </RouterLink>
        </ul>
      </div>
      <div class="col-12 col-md-5 md:pl-0  hiddenx md:flex justify-end">
        <img :src="supergana" alt=""  style="width:3.5rem; height:3.5rem" class="mx-4">
        <img :src="conalot" alt=""  style="width:3.5rem; height:3.5rem" class="mx-4">
      </div>
      <!-- <div class="col-12 col-md-4 flex md:justify-end">
        <q-btn  outline style="border-radius: 0.5rem;" color="white" class="mx-2" text-color="white" icon="settings" />
        <q-btn  outline style="border-radius: 0.5rem;" color="white" class="mx-2" text-color="white" icon="notifications" />
      </div> -->
    </div>
  </header>
</template>
<style lang="scss">

.gradient-text {
    background: linear-gradient(45deg, #650202, #e52e71, #e10b0b);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradient 5s ease infinite;
}

@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
.rubik-glitch {
  font-family: "Rubik Glitch", system-ui!important;
  font-weight: 400!important;
  font-style: normal!important;
  font-size: 2rem;
}

.mkBG{
  background-color: #300707;
  // background-color:  #8f0002ff;

 
}
.header__client{
  box-shadow: 0px 2px 5px 0px darkgray;
}
.logo-text {
  font-family: "Braah One", sans-serif;
  font-weight: 400;
  font-style: normal;
  // color: #000000;
  color: #fff;
  // -webkit-text-stroke: 2px #ffffff;
  font-size: 1.9rem;
  text-shadow: 0px 5px 5px rgba(82, 82, 82, 0.406);
}
.active_link {
  & .nav_header--items{
    color: #000!important;
    & div{
      background: #d6d8e2;
    }
    &::before{
      background: #d6d8e2;
      transform: translateX(0rem);

    }
  }
  
}
.nav_header--items{
  border-radius:0.7rem;
  transition: all 0.5s ease;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  font-size: 1.15rem;
  height: 1.82rem;
  font-weight: bold;
  & div {
    z-index: 2;
    position: relative;
    transition: background 0.5s ease;
    
  }
  &::before{
    content: "";
    
    transition: all 0.5s ease;
    height: 2.1rem;
    width: 100%;
    z-index: 0;
    display: block;
    position: absolute;
    transform: translateX(-10rem);
  }
  &:hover{
    color: #000!important;
    & div{
      background: #d6d8e2;
    }
    &::before{
      background: #d6d8e2;
      transform: translateX(0rem);

    }
  }
}
@media (max-width: 768px){
.rubik-glitch {

  font-size: 1.51rem;
}
}

</style>
