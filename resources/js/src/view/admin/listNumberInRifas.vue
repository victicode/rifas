<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import numberUtils from '@/utils/numberUtils.js';
import viewTicketDetails from '@/components/admin/rifa/viewTicketDetails.vue';
const numberFormat = numberUtils.numberFormat
const rifaStore = useRifaStore()
const route = useRoute()
const ready = ref(false)
const tickets = ref([])
const arrayTickets = ref([])

const rifa = ref([])
const viewOnlySold = ref(true)
const showTickets = ref(10000)
const showModal = ref('')
const selectedTicket = ref({})
const finder = ref('')

const ticketFormat = (ticket) => {
  let max = '0000'
  let ticketFormat = ticket+''
  return max.substring(0, (4 - ticketFormat.length))+ticketFormat

}

const allTickets = new Array(10000).fill(0).map((_, i) => (ticketFormat(i)));

const checkNumber = (number) => {
 return tickets.value.findIndex(item => ticketFormat(item.number) == number) != -1
}
const changeShowTickets = () => {
  console.log(viewOnlySold.value)
  console.log(allTickets)
  if(viewOnlySold.value){

    showTickets.value = arrayTickets.value 
    return
  }
  showTickets.value = allTickets
}
const getAllTickets = (id) => {
  rifaStore.getTicketsByRifa(id)
  .then((response) => {
    // console.log(response)
    rifa.value = response.data.rifa
    tickets.value = response.data.tickets

    arrayTickets.value = getArrayTickets(response.data.tickets)
    setTimeout(() => {
      changeShowTickets()
      ready.value = true
    }, 2000);
  })
}
const getArrayTickets = (tickets) => {
  let vendidos  = []
  tickets.forEach((item) => {
    vendidos.push(ticketFormat(item.number))
  })
  
  return vendidos
}
const openModal = (modal, number) =>{
  selectedTicket.value = tickets.value.find(item => item.number == number)
  showModal.value = modal
}
const ticketFinder = (e) => {

  let toFilter = viewOnlySold.value ? arrayTickets.value : allTickets
  if(finder.value == '') {
    changeShowTickets()
    return 
  }
  showTickets.value = toFilter.filter(elemento => (elemento+'').includes(finder.value));
}

const closeModal = () => {
  showModal.value = ''
}
onMounted(() => {
  getAllTickets(route.params.id)
})
</script>
<template>
  <div class="h-full">
    <div class="mt-1 md:mt-4 ">
      <div class="text-center text-h5 text-black text-bold">
        {{  rifa.title }}
      </div>
    </div>
    <div class="row items-center">

      <div class="text-start col-md-10 col-12">
        <q-checkbox v-model="viewOnlySold" @update:model-value="changeShowTickets()">
          <div class="text-subtitle1 mt-1 text-black text-bold">
            Mostrar los vendidos
          </div>
        </q-checkbox>
      </div>
      <div class="text-end text-subtitle1 mt-2 text-black text-bold col-md-2 col-12">
        Vendidos: {{ numberFormat(rifa.tickets_count) }}/10.000
      </div>
    </div>
    <div class="mb-7 mt-3 md:w-1/3 w-full">
      <q-input 
        v-model="finder" 
        maxlength="4" 
        type="number" 
        label="Buscar ticket" 
        class=" finderTicketForm__input"
        clearable=""
        @update:model-value="ticketFinder"
      />
    </div>
    <template v-if="ready">
      <div class="mt-4" >
        <template v-if="tickets.length > 0 || showTickets.length > 0">
          <div class="row items-center md:justify-start  w-100">
            <div v-for="item in showTickets" :key="item" class="  col-3 col-md-1 text-center  px-1 md:px-1 md:my-2 my-3 " >
              <div class="ticket__content py-2" :class="{'payded': checkNumber(item)}" @click="checkNumber(item) ? openModal('view', item) : ''">
                {{ item }} 
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="h-full flex column items-center justify-center md:mt-12">
            <div class="text-empty">
              No hay tickets comprados😢😢
            </div>
          </div>
        </template>
      </div>
    </template>
    <template v-else>
      <div class="flex column items-center justify-center h-full q-py-sm">
        <q-spinner-tail
          color="black"
          size="4rem"
        />
      </div>
    </template>
    <template v-if="Object.values(selectedTicket).length > 0">
      <viewTicketDetails :dialog="(showModal == 'view')" :ticket="selectedTicket" @closeModal="showModal = ''"  />
  
    </template>
  </div>
</template>
<style lang="scss">
.finderTicketForm__input {
  &.q-field--standard.q-field--readonly .q-field__control:before {
    
    border-bottom-style:solid!important

  }
  & input{
    padding-bottom: 0px!important;
  }
  & .q-field__label{
    transform: translateY(11%)
  }
  &.quantity.q-field--focused .q-field__label, &.quantity.q-field--float .q-field__label{
    z-index: 100;
    font-weight: 600;
    width: 100%;
    padding: 0px 10px;
    font-size: 0.8rem;
    text-align: center;
    transform: translateY(-110%) !important;
  }
  &.q-field--focused .q-field__label, &.q-field--float .q-field__label{
    z-index: 100;
    font-weight: 600;
    width: 100%;
    padding: 0px 10px;
    font-size: 0.8rem;
    transform: translateY(-110%) translateX(-0.5rem) !important;
  }
  
  & .q-field__native{
    padding-top: 15px!important;
    font-weight: 600;
  }
  & .q-field__append{
    transform: translateY(5%)
  }
}
.ticket__content{
  background: transparent;
  border: 3px solid black;
  color: black;
  font-weight: bold;
  font-size: 1rem;
  border-radius: 1rem;
  box-shadow: 0px 2px 5px 0px #adadad;

  &.payded{
    background: black;
    color: white;
    cursor:pointer
  }
}
.text-empty{
  font-size: 2.5rem;
  font-weight: bold;
  opacity: 1;
  color:black;
  text-align: center;
}
@media (max-width: 768px){
  .text-empty{
    font-size: 1.6rem;
  }
}
</style>