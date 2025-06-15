<script setup>
import logo from '@/assets/images/logo/logo.webp'

</script>
<template>
  <header class="h-full header__client">
    <div class=" h-full md:px-6 md:py-5 py-1 bg-stone-950 row items-center" style="    background-image: url('https://vergacionderifa.com/static/img/trama.svg');" >
      <div class="flex items-center justify-center md:justify-start col-12 col-md-3 mb-0 md:mb-0">
        <img :src="logo" alt="" style="width: 3.2rem;" >
        <div class=" md:ml-3 logo-text ">
          Gana con la Hija linda
        </div>
      </div>
      <div class=" col-12 col-md-9 md:pl-5">
        <ul class="flex justify-center md:justify-start">
          <RouterLink activeClass="active_link" to="/home" >
            <li class="text-white mx-3 nav_header--items" >
              <div class="px-2">
                Inicio
              </div>
            </li>
          </RouterLink>
          <RouterLink activeClass="active_link" to="/rifas" >
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
      <!-- <div class="col-12 col-md-4 flex md:justify-end">
        <q-btn  outline style="border-radius: 0.5rem;" color="white" class="mx-2" text-color="white" icon="settings" />
        <q-btn  outline style="border-radius: 0.5rem;" color="white" class="mx-2" text-color="white" icon="notifications" />
      </div> -->
    </div>
  </header>
</template>
<style lang="scss">
.header__client{
  box-shadow: 0px 2px 5px 0px darkgray;
}
.logo-text {
  font-family: "Braah One", sans-serif;
  font-weight: 400;
  font-style: normal;
  color: #ffffff;

  -webkit-text-stroke: 2px #aa13a4;
  font-size: 1.9rem;
  text-shadow: 0px 5px 5px rgba(82, 82, 82, 0.406);
}
.active_link {
  & .nav_header--items{
    color: #aa13a4!important;
    &::before{
      transform: translateX(0rem);
    }
  }
}
.nav_header--items{
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
    background: #0c0a09;
  }
  &::before{
    content: "";
    background: #aa13a4;
    transition: all 0.5s ease;
    height: 2.1rem;
    width: 100%;
    z-index: 0;
    display: block;
    position: absolute;
    transform: translateX(-10rem);
  }
  &:hover{
    color: #aa13a4!important;

    &::before{
      transform: translateX(0rem);

    }
  }
}
</style>
