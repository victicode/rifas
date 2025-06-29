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

  const order = ref({
    facturation:[],
    order:[],
  })
  const dialog    = ref(props.dialog); 

  const formatInfo = () =>{
    order.value.facturation = [
      {
        title: 'Nombre de cliente',
        value: props.order.client.name
      },
      {
        title: 'Télefono',
        value: props.order.client.phone
      },
      {
        title: 'Correo',
        value: props.order.client.email
      },
      {
        title: 'Fecha de pago',
        value: moment(props.order.pay_date).format('DD/MM/YYYY')
      },
      {
        title: 'Rifa',
        value: '['+props.order.rifa.title+''
      },
      
    ]
    order.value.order = [
      {
        title: 'Tickets',
        value:  'x'+props.order.quantity
      },
      {
        title: 'Método de pago',
        value: props.order.method_pay.name
      },
      {
        title: 'Monto pago',
        value: props.order.method_pay.coin.symbol+' '+ numberFormat(props.order.amount)
      },
      {
        title: 'N° Referencia',
        value: "#"+props.order.reference
      },
      
    ]
  }
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
  watch(() => props.dialog, (newValue) => {

    dialog.value = newValue
    formatInfo()
  });
  watch(() => props.order, (newValue) => {
    dialog.value = newValue

  });
  onMounted(() => {
    formatInfo()
  })
  
</script>
<template>
   <q-dialog v-model="dialog" class="orderViewDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_documentCard" style="border-radius:1rem">
        <div class="close__button">
          <q-btn round color="primary" icon="close" @click="hideModal()" />
        </div>
        <q-card-section class="text-center">
          <div class="text-h5 text-center text-bold mb-2">
           Orden [#{{props.order.number}}]
          </div>
          <q-chip class="" :color="props.order.status == 1 ? 'warning' : props.order.status == 2 ? 'positive' :'negative'" text-color="white" >
              <div class="px-3">
                {{props.order.status_label}}
              </div>
            </q-chip>
        </q-card-section>
        <section class="content__modalSection px-5">
          <div class="">
            <div class="text-subtitle1 text-bold">Datos del cliente</div>
            <div class="my-2 row">
              <div v-for="(item,index) in order.facturation" :key="index" :class="'col-6 mb-1 ' + (index%2 == 0 ? 'justify-start ':'justify-end text-end')" >
                <div class="text-subtitle2 text-bold  text-stone-500">
                  {{item.title}}:
                </div>
                <div class="text-subtitle1 text-black text-bold">
                  {{item.value}}
                </div>
              </div>
            </div>
          </div>
          <div class="mt-3">
            <div class="text-subtitle1 text-bold">Datos de facturación:</div>
            <div class="my-2">
              <table style="width:100%">
                <thead class="tablePayHead">
                    <tr>
                      <th>Tickets</th>
                      <th>Precio</th>
                      <th>Total</th>

                    </tr>
                </thead>
                <tbody> 
                  <tr>
                    <td>
                      {{props.order.ticket}}
                    </td>
                  </tr>
                  
                </tbody>
              </table>

              <!-- <div v-for="(item,index) in order.order" :key="index" :class="'item__orderModal flex items-center my-1 py-1 px-5 it-' +index" >
                <div class="text-subtitle1 text-bold  text-stone-500 mr-1 ">
                  {{item.title}}:
                </div>
                <div class="text-subtitle1 text-black text-bold ">
                  {{item.value}}
                </div>
              </div> -->
            </div>
          </div>

        </section>
        <!-- <q-card-section class="q-pt-none">
          <div class="flex justify-end mt-0" >
            <q-btn style="border-radius: 0.5rem;" size="0.8rem" color="primary"  class="" label="Editar" :loading="loading"  />
          </div>
        </q-card-section> -->
      </q-card>
    </q-dialog>
</template>
<style lang="scss">

.item__orderModal:not(.it-0){
  border-top: 1px solid darkgrey;
}

.dialog_documentCard {
  margin-left: 10%;
  min-width: 600px!important;
  max-width: 800px!important; 
  overflow: visible!important;
  position: relative;
}
@media (max-width: 768px){
  

  .orderViewDialog .q-dialog__inner--minimized{
    padding: 24px 0.5rem;
  }
  .dialog_documentCard {
    margin-left: 0%;
    min-width: 100%!important;
    max-width: 800px!important; 
  }
}
</style>