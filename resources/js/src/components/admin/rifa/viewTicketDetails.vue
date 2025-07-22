<script setup>
import { ref, watch} from 'vue';
import { Notify } from 'quasar'
import moment from 'moment';
import numberUtils from '@/utils/numberUtils.js';
import whatsapp from '@/assets/images/logo/WhatsApp2.webp'

const numberFormat = numberUtils.numberFormat 
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
const showNotify = (type,text) => {
  Notify.create({
    color:type,
    message: text,
    timeout:2000
  })
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
        <div class="close__button">
          <q-btn round color="primary" icon="close" @click="hideModal()" />
        </div>
        <q-card-section class="text-center q-pb-xs">
          <div class="text-h5 text-center text-bold mb-2 md:pt-8 text-black">
           Ticket #{{ ticketFormat(ticket.number) }}
          </div>
          
          <q-chip class="" color="positive" text-color="white" >
              <div class="px-3">
                Pagado
              </div>
          </q-chip>
        </q-card-section>
        <section class="content__modalSectionOrder px-5 " >
          <div class="" style="overflow-x:hidden">
            <div class=" text-center mt-2" style="font-size:1.2rem; font-weight:bold">
              Información del Ticket
            </div>
            <div class="mt-2 row">
              <div class=" col-12 col-md-6  mb-3" >
                <div class="text-subtitle1 text-bold  text-stone-500">
                  Fecha de pago:
                </div>
                <div class="text-subtitle1 text-black text-bold">
                  {{moment(ticket.order.created_at).format('DD/MM/YYYY h:mm:ss a')}}
                </div>
              </div>
              <div class=" col-12 col-md-6  mb-3 md:text-end" >
                <div class="text-subtitle1 text-bold  text-stone-500">
                  Número de orden:
                </div>
                <div class="text-subtitle1 text-black text-bold">
                  #{{ticket.order.number}}
                </div>
              </div>
            </div>
            <div class=" text-center pt-2" style="font-size:1.2rem; font-weight:bold; border-top: 1px solid darkgrey">
              Datos del comprador
            </div>
            <div class="my-2 row">
              <div class="col-md-6 col-12 mb-3 " >
                <div class="text-subtitle1 text-bold  text-stone-500">
                  Comprador:
                </div>
                <div class="text-subtitle1 text-black text-bold">
                  {{ticket.order.client.name}}
                </div>
              </div>
              <div class="col-md-6 col-12 mb-3 md:text-end" style="cursor:pointer " >
                <div class="text-subtitle1 text-bold  text-stone-500 ">
                  Télefono:
                </div>
                <div class="text-subtitle1 text-black text-bold">
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
              <div class="col-md-6 col-12 mb-3 " >
                <div class="text-subtitle1 text-bold  text-stone-500">
                  Correo:
                </div>
                <div class="text-subtitle1 text-black text-bold">
                  {{ticket.order.client.email}}
                </div>
              </div>
              <div class="col-md-6 col-12 mb-3 md:text-end" >
                <div class="text-subtitle1 text-bold  text-stone-500">
                  Estado:
                </div>
                <div class="text-subtitle1 text-positive text-bold">
                  Aprobado
                </div>
              </div>
            </div>
          </div>
        </section>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">

.contactWhatsapp{

  width: 1.8rem;
  height: 1.8rem;

  border-radius: 35%;
  display: flex;
  background: #26b83e;
  box-shadow: 0px 2px 5px 0px #adadad;
  justify-content: center;
  align-items: center;
}

.dialog_documentCardOrder {
  max-height: 95vh!important;
  margin-left: 10%;
  min-width: 700px !important;
  max-width: 800px !important;
  overflow: visible !important;
  position: relative;
  height: 85vh;
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