<script setup>
import { ref } from 'vue';
import moment from 'moment';
const props = defineProps({
  ticket: Object,
  client: Object,

})

const client = ref(props.client)
const ticket = ref(props.ticket)
const ticketFormat = (ticket) => {
  let max = '0000'
  let ticketFormat = ticket+''
  return max.substring(0, (4 - ticketFormat.length))+ticketFormat

}
</script>
<template>
  <div class="col-12 col-md-3 px-2 my-2">
    <div class="ticketItemContainer py-4 px-3" >
      <div style="z-index:2; position:relative">
        <div class="my-2 text-h6 text-center text-black" style="text-decoration:underline; cursor:pointer">
          <!-- <img :src="ticket.rifa.configuration.banner_img" alt="" style="border-radius:0.5rem; width:80%; margin:auto" > -->
           {{ ticket.rifa.title }}
        </div>

        <div class=" text-h6 text-center text-bold" >
          <div style="border-radius:1rem; width:fit-content; margin:auto" class="px-6 py-1 text-white bg-black">
            🎟{{ ticketFormat(ticket.number) }}
          </div>
        </div>
        <div class="mt-5">
          <div class="flex items-center justify-between mt-3 text-bold text-subtitle1 text-black pb-1" style="border-bottom:1px solid darkgrey">
            <div>Comprador:</div>
            <div class="ml-2">{{ client.name }}</div>
          </div>
          <div class="flex items-center justify-between mt-3 text-bold text-subtitle1 text-black pb-1" style="border-bottom:1px solid darkgrey">
            <div>Contacto:</div>
            <div class="ml-2">{{client.phone}}</div>
          </div>
          <div class="flex items-center justify-between mt-3 text-bold text-subtitle1 text-black pb-1" style="border-bottom:1px solid darkgrey">
            <div>Estado:</div>
            <div class="ml-2 flex items-center">
              Aprobado <q-icon name="check_circle" color="positive" size="1.2rem" class="ml-1" />
            </div>
          </div>
          <div class="flex items-center justify-between mt-3 text-bold text-subtitle1 text-black pb-1" style="border-bottom:1px solid darkgrey">
            <div>Se juega:</div>
            <div class="ml-2 flex items-center">
              {{ moment(ticket.rifa.due_date).format('DD/MM/YYYY') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss"> 
.ticketItemContainer{
  border-radius: 1rem;
  background-color: white;
  box-shadow: 0px 20px 20px 0px #86868621;
  background-size: 8rem;
  position: relative;
  background-repeat: no-repeat;
  background-position: center;
  &::before {
    content: "";
    border-radius: 1rem;
    background-image: url('https://ganaconlahijalinda.com/build/assets/logo3-D48xUC7E.png');
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background-size: 9rem;
    background-repeat: no-repeat;
    background-position: center;
    height: 100%;

    opacity: 0.2;
    z-index: 1; /* Asegura que la imagen esté detrás del contenido */
  }
}

</style>