<script setup> 
import whatsapp from '@/assets/images/logo/WhatsApp2.webp';
</script>
<template>
  <div class="px-4">
    <div class="text-center text-h4 mt-8 text-black text-bold">
      Nos aseguramos de que tengas la mejor atención 🤩
    </div>
    <div class="text-stone-600 text-center text-subtitle1 text-bold my-4">
     ¿Tienes dudas?, ¿tuviste algún incidente en tu compra?, ¿Quieres saber como pagar por zelle?
     <br>
     Contacta con nuestro soporte de whatsapp y te ayudaremos
    </div>
    <div class="text-center">
      <a
        href="https://wa.link/d7m9po"
        target="_blank" 
        rel="noopener noreferrer"

      >
        <q-btn color="green-8" no-caps style="border-radius:0.7rem; padding:0.7rem 2rem">
          <div class="flex items-center" style="font-size:1rem" >
            Soporte de whatsapp
            <img :src="whatsapp"  class="ml-2" style="width: 1.2rem;" />
          </div>
        </q-btn>
      </a>
    </div>
  </div>
</template>