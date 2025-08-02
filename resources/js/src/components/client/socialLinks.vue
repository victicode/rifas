
<script setup>
import logo from '@/assets/images/logo/logo3.png'
import instagram from '@/assets/icons/instagram3.svg';
import tiktok from '@/assets/icons/tiktok3.svg';

</script>
<template>
  <div class="w-full bg-black">
    <div class="pt-8 pb-8">
      <div class=" text-white px-5 py-2 my-4  text-center socialTitle" >
        Recuerda seguirnos en nuestras redes sociales para que no te pierdas de nada.
      </div>
      <div class="flex flex-center">
        <img :src="instagram" alt="" class="iconSocial md:mx-8 mx-2">
        <img :src="tiktok" alt="" class="iconSocial md:mx-8 mx-2">

        
      </div>
    </div>
  </div>
</template>
<style lang="scss" >
.socialTitle{
  font-size: 1.3rem;
  font-weight: bold;
}
.iconSocial{
  height: 5rem;
  filter: contrast(0) brightness(2);
  transition: all 0.5s ease;
  &:hover{
    filter: contrast(0) brightness(1);
  }
}

@media (max-width: 780px) {
  .iconSocial{
    height: 4rem;
    filter: contrast(0) brightness(2);
  }
  .socialTitle{
    font-size: 1.11rem;
  }
}
</style>