<script setup>
import { onMounted, ref, watch} from 'vue';
import { Notify } from 'quasar'
import moment from 'moment';
import numberUtils from '@/utils/numberUtils.js';
  const numberFormat = numberUtils.numberFormat 
  const emit = defineEmits(['closeModal'])
  const props = defineProps({
    dialog: Boolean,
    order: Object,
  })

  const order = ref(props.order); 
  const dialog = ref(props.dialog); 


  const hideModal = () => {
    emit('closeModal')
  }

  watch(() => props.dialog, (newValue) => {

    dialog.value = newValue
  });
  watch(() => props.order, (newValue) => {
    dialog.value = newValue

  });

  
</script>
<template>
   <q-dialog v-model="dialog" class="orderViewDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_documentCardOrder" style="border-radius:1rem">
        <div class="close__button">
          <q-btn round color="primary" icon="close" @click="hideModal()" />
        </div>
        <q-card-section class="text-center q-pb-xs">
          <div class="text-h5 text-center text-bold mb-2">
           Tickets asignados
          </div>
          <div class="flex flex-center">
            <div class="text-subtitle1 text-bold mr-1">
              Cantidad de tickets:
            </div>
            <q-chip 
            class="" 
            color="positive"
            text-color="white" 
            :label="order.tickets_count" />
          </div>
        </q-card-section>
        <section class="content__modalSection px-5">
          <div class="">
            <div class=" row">
              <div class="flex col-6">
                <div style="font-weight:500">Titular:</div>
                <div class="ml-2 text-bold">{{ order.client.name }}</div>
              </div>
              <div class="flex col-6 justify-end">
                <div style="font-weight:500">C.I:</div>
                <div class="ml-2 text-bold">{{ numberFormat(order.client.ci) }}</div>
              </div>
              <div class="flex col-12 mt-2">
                <div style="font-weight:500">Contacto:</div>
                <div class="ml-2 text-bold"> {{ order.client.phone }} / {{order.client.email}} </div>
              </div>
            </div>
            <div class="mt-5 mb-4 row">
              <div v-for="(item,index) in order.tickets" :key="index" class="col-3 col-md-2 my-2 px-1  " >
                <div class="text-subtitle1 text-bold  text-white text-center md:py-2" style="background:black; border-radius:2rem;">
                  {{item.number}}
                </div>
              </div>
            </div>
          </div>


        </section>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">
.tablePayViewHead{
  & th{
    border-bottom: 1px solid darkgray;
  }
}

.item__orderModal:not(.it-0){
  border-top: 1px solid darkgrey;
}

.dialog_documentCardOrder {
  height: max-content !important;
  max-height: 48rem !important;
  margin-left: 10%;
  min-width: 700px!important;
  max-width: 800px!important; 
  overflow: visible!important;
  position: relative;
}
@media (max-width: 768px){
  

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