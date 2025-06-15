<script setup>
import { onMounted, ref } from 'vue';


const loading = ref(false);

onMounted(() => {
  setTimeout(() => {
    loading.value = true
  }, 2000);
})
</script>
<template>

  <div class="h-5/6">
    <template v-if="loading">

      <div class="text-black mt-5 text-center text-h5"> 
        <div>Gracias por su compra</div>
        <div>
          Tu orden de compra ha sido procedada satisfactoriamente, en las próximas 24 horas estarás recibiendo un correo y un whatsapp con tus tickets, 
          siempre puedes consultar tu compra en nuestro botones de consulta, también puedes usar el whatsapp de soporte si teines alguna duda.
        </div>
        <div>
          <q-btn 
            style="border-radius: 0.5rem;" 
            size="" color="primary"  class=" my-5" 
          >
           <div class="q-py-sm">
              Consulta tus tickets aqui
           </div>
          </q-btn>
        </div>
        <div>
          <div></div>
          <div>Simplemente selecciona la rifa en la que participaste ingresa tu número de cedula en el verificador de tickets y presiona el boton de buscar. Se mostrará toda la información de tu compra y tus tickets asignados </div>
        </div>
      </div>
      
    </template>
    <template v-else>
      <div class="flex column items-center justify-center h-full q-py-sm">
        <q-spinner-tail
          color="black"
          size="6rem"
        />
      </div>
    </template>
  </div>
</template>