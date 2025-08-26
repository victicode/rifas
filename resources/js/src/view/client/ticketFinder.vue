<script setup>
import { useRoute } from 'vue-router';
import { ref } from 'vue';
import { useOrderStore } from '@/services/store/order.store';
import moment from 'moment';
import numberUtils from '@/utils/numberUtils.js';
import ticketsFinderItems from '@/components/client/ticketsFinderItems.vue';

const route = useRoute()
const search = ref('')
const numberFormat = numberUtils.numberFormat
const searchType = ref(1)
const orders = ref([])
const orderStore = useOrderStore()

const tickets = ref([])
const showSection = ref(false)
const ready = ref(true)
const orderPendigs = ref([])

const findOrder = () => {
  ready.value = false
  const ci = parseInt(search.value.replace(/\./g, ''))
  
  orderStore.findOrdersByCiClient(ci, route.params.id)
  .then((response) => {
    orders.value = response.data
    orderPendigs.value =  orders.value.filter(item => item.status == 1)
    formatTicket()
    setTimeout(() => {
      ready.value = true
      showSection.value = true
    }, 1000);
  }).catch(() => {
      ready.value = true
  })
}
const formatTicket = () => {
  let ticketsArray = []
  orders.value.forEach((item) => {
    ticketsArray.push(...item.tickets)
  })
  tickets.value = ticketsArray
}



</script>
<template>
  <div class="px-5 md:px-10 pb-20" style="height: -webkit-fill-available;" >
    <div>

      <div>
        <div class="text-center text-h5 mt-8 text-black text-bold">
          VERIFICADOR DE BOLETOS
        </div>
        <div class="text-stone-600 text-center text-subtitle2 text-bold my-4">
          Ingresa tu número de cedula y presiona en buscar
        </div>
      </div>
      <div class="md:px-20 md:mx-20">
  
        <section id="searchTicket" class="w-full  px-4 py-3 md:py-3 mt-4 bg-white">
          <q-form
            class="md:px-5 md:pb-5 pb-2 "
            style="height: 100%; "
            @submit="findOrder()"
          >
            <div class=" py-1 mt-1">
              <q-input
                v-model="search"
                label="Busqueda de tickets"
                placeholder="Ingresa tu número de cedula"
                mask="###.###.###"
                maxlength="10"
                reverse-fill-mask
                class=" searchTicketForm__input w-full"
                :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
              />
              <div class="flex justify-center">
                <q-btn color="primary"  class="md:py-8 md:mt-5 mt-3" icon-right="search" type="submit" label="Buscar tickets" style="padding:10px 20px; border-radius:0.5rem" />
              </div>
            </div>
          </q-form>
          <div>
    
          </div>
        </section>
      </div>
      <div>
        <template v-if="ready">
          <div v-if="showSection">
  
            <div  v-if="tickets.length > 0" class="mt-5">
              <div class="text-center text-h5 mt-4 mb-2 text-black text-bold">
               🎫 {{ tickets.length }} Tickets comprados
              </div>
              <div class="row">
                <ticketsFinderItems v-for="ticket in tickets" :ticket="ticket" :client="orders[0].client" :key="ticket.id" />
              </div>
              
              <div class="text-center text-h5 my-8 text-black text-bold">
                Gracias por tu compra y mucha suerte🤩
              </div>
            </div>
            <div v-else>
              <div class="text-center text-h5 mt-8 text-black text-bold" v-if="orderPendigs.length == 0" >
                No tienes tickets comprados
              </div>
            </div>
            <div v-if="orderPendigs.length > 0" class="text-center text-subitlte1 text-bold text-black mt-5">
                Tienes {{ orderPendigs.length }} orden(es) pendiente de aprobación. <br> Al momento de ser aprobada se te asignaran tus tickets y los podras visualizar aqui
            </div>
          </div>
        </template>
        <template v-else>
          <div class="flex flex-center mt-24">
            <q-spinner-tail
              color="primary"
              size="5em"
            />
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
    

<style lang="scss">
#searchTicket{
  border-radius: 1rem;
  box-shadow: 0px 5px 5px 0px #99999934;
}
.searchTicketForm__input {

  &.quantity input{
    font-size: 1.5rem;
    text-align: center;
    
  }
  & input{
    padding-bottom: 0px!important;
  }
  & .q-field__label{
    transform: translateY(11%)
  }
  &.quantity.q-field--focused .q-field__label, &.quantity.q-field--float .q-field__label{
    z-index: 100;
    background: white!important;
    font-weight: 600;
    width: max-content;
    padding: 0px 10px;
    font-size: 0.8rem;
    text-align: center;
    transform: translateY(-110%) !important;
  }
  &.q-field--focused .q-field__label, &.q-field--float .q-field__label{
    z-index: 100;
    background: white!important;
    font-weight: 600;
    width: max-content;
    padding: 0px 10px;
    font-size: 0.8rem;
    transform: translateY(-110%) translateX(-0.5rem) !important;
    border: 0px;
  }
  
  & .q-field__native{
    padding-top: 15px!important;
    font-weight: 600;
  }
  & .q-field__append{
    transform: translateY(5%)
  }
}
</style>

