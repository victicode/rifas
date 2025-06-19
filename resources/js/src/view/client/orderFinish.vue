<script setup>
import { onMounted, ref } from 'vue';
import logo from '@/assets/images/logo/logo2.jpeg'
import { useRoute, useRouter } from 'vue-router';
import { useOrderStore } from '@/services/store/order.store' 
import { Notify } from 'quasar'

const orderStore = useOrderStore()
const loading = ref(false);
const route = useRoute()
const router = useRouter()
const goTo = (url) => {
  router.push(url)
}
const showNotify = (type,text) => {
  Notify.create({
    color:type,
    message: text,
    timeout:2000
  })
}
const order = ref({})
const getOrderById = () => {
  orderStore.getOrderById(route.params.id)
  .then((response) => {

    order.value = response.data

    setTimeout(() => {
      loading.value = true
      
    }, 1000);
  })
  .catch((response) => {
    console.log(response)
    showNotify('negative', 'compra no valida')
  })
}
onMounted(() => {
  getOrderById()
  
})
</script>
<template>

  <div class="h-full md:px-12" :style="`background:url('${logo}')`" style="position: relative; background-position: -3rem 0rem;" >
    <div class="overflowBody"/>
    <template v-if="loading">

      <div class="text-black pt-5 pb-10 md:px-12 px-5 text-center relative card_presentation" style="z-index:2"> 
        <div class="flex justify-center w-full md:my-5 my-2">
          <q-icon name="check_circle_outline" size="8rem" color="positive"/>
        </div>
        <div class="text-blur md:mb-5 mb-2  text-bold text__finish">Gracias por su compra</div>
        <div class="text-black text-h6 ">
          Tu orden de compra ha sido procedada satisfactoriamente, en las próximas 24 horas estarás recibiendo un correo y un whatsapp con tus tickets, 
          siempre puedes consultar tu compra en nuestro botones de consulta, también puedes usar el whatsapp de soporte si tienes alguna duda.
        </div>
        <div>
          
          <q-btn 
            style="border-radius: 0.5rem;" 
            size="" color="blur"  class="md:mt-4 md:mb-4 mb-2 mt-4 md:mx-2 md:my-2 mx-0" 
            @click="goTo('/rifa/'+order.rifa_id)"
          >
           <div class="q-py-sm">
              Realizar otra compra
           </div>

          </q-btn>
          <q-btn 
            style="border-radius: 0.5rem;" 
            size="" color="primary"  class="md:mt-4 md:mb-4 mt-2 mb-4 md:mx-2 mx-0" 
            @click="goTo('/tickets/finder?order='+order.id)"
          >
           <div class="q-py-sm">
              Consulta tus tickets aqui
           </div>

          </q-btn>
        </div>
        <div>
          <div></div>
          <div class="text-black text-h6">Simplemente selecciona la rifa en la que participaste ingresa tu número de cedula en el verificador de tickets y presiona el boton de buscar. Se mostrará toda la información de tu compra y tus tickets asignados </div>
        </div>
      </div>
      
    </template>
    <template v-else>
      <div class="flex column items-center justify-center h-full q-py-sm relative" style="z-index:2">
        <q-spinner-tail
          color="white"
          size="6rem"
        />
      </div>
    </template>
  </div>
</template>
<style lang="scss">
.text__finish{
  font-size: 3rem;
}
.card_presentation{
  background: white;
  border-bottom-left-radius: 2rem;
  border-bottom-right-radius: 2rem;


}
.overflowBody{
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
  background: rgba(46, 46, 46, 0.523);
  backdrop-filter: blur(0rem);
}
@media (max-width: 768px){
  .text__finish{

    font-size: 2.1rem;
  }
}
</style>