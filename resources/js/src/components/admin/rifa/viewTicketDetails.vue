<script setup>
import { ref, watch} from 'vue';
import { Notify } from 'quasar'
import moment from 'moment';
import whatsapp from '@/assets/images/logo/WhatsApp2.webp'


const emit = defineEmits(['closeModal'])
const props = defineProps({
  dialog: Boolean,
  ticket: Object,
})


const dialog  = ref(props.dialog); 
const ticket = ref(props.ticket)

const hideModal = () => {
  emit('closeModal')
}

const ticketFormat = (ticket) => {
  let max = '0000'
  let ticketFormat = ticket+''
  return max.substring(0, (4 - ticketFormat.length))+ticketFormat

}
const formatPhone = () => {
  let string = ticket.value.order.client.phone
  let modifiedSentence = string.replace('-', '')

  return '58'+modifiedSentence.substring(1)

}
watch(() => props.dialog, (newValue) => {
  dialog.value = newValue

});
watch(() => props.ticket, (newValue) => {
  ticket.value = newValue
});

  
</script>
<template>
   <q-dialog v-model="dialog" class="orderViewDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_documentCardOrder "  style="border-radius:1rem">
        <div class="close__button" @click="hideModal()" style="z-index:10">
          <q-btn round color="primary" icon="close"  />
        </div>
        <div class="ticketItemContainerModal pt-4 pb-8 px-4" >
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
            <div class="mt-6">


              <div class="flex items-center justify-between mt-3 text-bold text-subtitle1 text-black pb-1" style="border-bottom:1px solid darkgrey">
                <div>Fecha de pago</div>
                <div class="ml-2"> {{moment(ticket.order.created_at).format('DD/MM/YYYY h:mm:ss a')}}</div>
              </div>
              <div class="flex items-center justify-between mt-3 text-bold text-subtitle1 text-black pb-1" style="border-bottom:1px solid darkgrey">
                <div>Número de orden:</div>
                <div class="ml-2">#{{ticket.order.number}}</div>
              </div>
              <div class="flex items-center justify-between mt-3 text-bold text-subtitle1 text-black pb-1" style="border-bottom:1px solid darkgrey">
                <div>Comprador:</div>
                <div class="ml-2">{{ ticket.order.client.name }}</div>
              </div>
              <div class="flex items-center justify-between mt-3 text-bold text-subtitle1 text-black pb-1" style="border-bottom:1px solid darkgrey">
                <div>Contacto:</div>
                <div class="ml-2">
                  <a 
                    class="flex items-center md:justify-end" 
                    target="_blank" 
                    :href="'https://api.whatsapp.com/send?phone='+formatPhone()+'&text=Felicidades%2C%20eres%20el%20ganador!!!!'" >
                    <div class="text-decoration-underline" style="text-decoration:underline">
                      {{ticket.order.client.phone}}
                    </div>

                    <div class="contactWhatsapp ml-2 mb-1">
                      <img :src="whatsapp" style="width:65%;" class="" alt="">
                    </div>
                  </a>
                </div>
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
      </q-card>
    </q-dialog>
</template>
<style lang="scss"> 
.ticketItemContainerModal{
  border-radius: 1rem!important;
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
<style lang="scss">

.contactWhatsapp{

  width: 1.3rem;
  height: 1.3rem;

  border-radius: 35%;
  display: flex;
  background: #26b83e;
  box-shadow: 0px 2px 5px 0px #adadad;
  justify-content: center;
  align-items: center;
}

.dialog_documentCardOrder {

  margin-left: 10%;
  min-width: 700px !important;
  max-width: 800px !important;
  overflow: visible !important;
  position: relative;
  height: fit-content;
  border-radius: 2rem;
  overflow: hidden;
}
.content__modalSectionOrder{
  overflow: auto; max-height: 80%; 
}
@media (max-width: 768px){
  .content__modalSectionOrder{
    min-height: 76%;
    max-height: 76%;

  }
  .orderViewDialog .q-dialog__inner--minimized{
    padding: 24px 0.5rem;
  }
  .dialog_documentCardOrder {
    margin-left: 0%;
    min-width: 100%!important;
    max-width: 800px!important; 
  }
}
</style>