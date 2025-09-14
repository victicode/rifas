<script setup>
import whatsapp from '@/assets/images/logo/WhatsApp2.webp'

</script>
<template>

  <!-- <a
  href="https://web.whatsapp.com/send/?phone=584245391538&text=Hola%2C+tengo+una+duda+y+requiero+de+su+apoyo" -->
  <a
    href="https://wa.link/lbn858"
    target="_blank" 
    rel="noopener noreferrer"
    class="buttonWhatsapp"
  >
    <div style="" class="px-2 py-1 bg-primary text-white support_label">
      Soporte
    </div>
    <img :src="whatsapp" alt="" style="width:60%;">
  </a>
</template>


<style lang="scss">
.buttonWhatsapp{
  position: fixed;
  width: 4.3rem;
  height: 4.3rem;
  bottom: 5%;
  right: 2%;
  border-radius: 35%;
  display: flex;
  background: #26b83e;
  z-index: 100;
  box-shadow: 0px 5px 10px 0px #adadad;
  justify-content: center;
  align-items: center;
}
.support_label{
  position:absolute; width:fit-content; border-radius:0.4rem; bottom:-2rem; font-size:0.8rem;
  animation: intermidate 3s infinite;
}

@keyframes intermidate {
  0%{
    opacity: 1;
  }
  50%{
    opacity: 0;
  }
  100%{
    opacity: 1;
  }
}
</style>