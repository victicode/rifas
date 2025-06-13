<script setup>
import logo from '@/assets/images/logo/logo.png'

</script>
<template>
  <header class="h-full header__client">
    <div class=" h-full md:px-6 md:py-5 py-4 bg-stone-950 row items-center" style="    background-image: url('https://vergacionderifa.com/static/img/trama.svg');" >
      <div class="flex items-center justify-center md:justify-start col-12 col-md-3 mb-2 md:mb-0">
        <img :src="logo" alt="" style="height:3rem">
        <div class=" mx-3 logo-text">
          RIFAS APP
        </div>
      </div>
      <div class=" col-12 col-md-9">
        <ul class="flex justify-center md:justify-start">
          <RouterLink activeClass="active_link" to="/home" >
            <li class="text-white mx-3 nav_header--items" >
              <div>
                Home
              </div>
            </li>
          </RouterLink>
          <RouterLink activeClass="active_link" to="/rifas" >
            <li class="text-white mx-3 nav_header--items" >
              <div>
                Ganadores
              </div>
            </li>
          </RouterLink>
          <RouterLink activeClass="active_link" to="/support">
            <li class="text-white mx-3 nav_header--items" >
              <div>
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
  color: #39e1d2;
  font-size: 2rem;
  text-shadow: 0px 5px 5px rgba(82, 82, 82, 0.406);
}
.active_link {
  & .nav_header--items{
    color: #39e1d2!important;
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
    background: #39e1d2;
    transition: all 0.5s ease;
    height: 2.1rem;
    width: 100%;
    z-index: 0;
    display: block;
    position: absolute;
    transform: translateX(-10rem);
  }
  &:hover{
    color: #39e1d2!important;

    &::before{
      transform: translateX(0rem);

    }
  }
}
</style>
