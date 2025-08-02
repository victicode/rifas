<script setup>
import logo from '@/assets/images/logo/logo3.png'
import supergana from '@/assets/images/logo/supergana.png'
import { inject } from 'vue';
import conalot from '@/assets/images/logo/conalot.png'

import { useRouter } from 'vue-router';
const emitter = inject('emitter')
const router = useRouter()

</script>
<template>
  <footer class=" footer__client py-5  bg-stone-950" style="background-image: url('https://vergacionderifa.com/static/img/trama.svg'); border-top: 2px solid darkgrey">
    <div class=" h-full md:px-6 md:py-0  items-center flex justify-between"  >
      <div class=" flex items-center">
        <img :src="logo" alt=""  style="width:5.5rem" @click="router.push('/home')">
        <div class="text-white ml-5 text-subtitle1">
          © Derechos reservados 2025
        </div>
      </div>
      <div class="md:pr-16 md:mr-12 mt-2 md:mt-0">
        <q-btn unelevated color="yellow-9" label="Comprar ahora"  @click="router.push('/home')"/>
      </div>
    </div>
  </footer>
</template>
<style lang="scss">
.footer__client{
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
</style>
