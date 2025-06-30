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
    order.value.client = [
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
      // {
      //   title: 'Rifa',
      //   value: '['+props.order.rifa.title+']'
      // },
      
    ]
    order.value.order = [
      {
        title: 'Rifa',
        value: '['+props.order.rifa.title+']'
      },
      {
        title: 'Precio',
        value: props.order.method_pay.coin.symbol+' '+ numberFormat(props.order.rifa.configuration.price) 
      },
      {
        title: 'Tickets',
        value:  'x'+props.order.quantity
      },
      
      {
        title: 'Total',
        value: props.order.method_pay.coin.symbol+' '+ numberFormat(props.order.amount)
      },
      
    ]
    order.value.facturation = [
      {
        title: 'Medio de pago',
        value: props.order.method_pay.name
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
      <q-card class="dialog_documentCardOrder" style="border-radius:1rem">
        <div class="close__button">
          <q-btn round color="primary" icon="close" @click="hideModal()" />
        </div>
        <q-card-section class="text-center q-pb-xs">
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
            <div class="my-2 row">
              <div v-for="(item,index) in order.client" :key="index" :class="'col-6 mb-1 ' + (index%2 == 0 ? 'justify-start ':'justify-end text-end')" >
                <div class="text-subtitle2 text-bold  text-stone-500">
                  {{item.title}}:
                </div>
                <div class="text-subtitle2 text-black text-bold">
                  {{item.value}}
                </div>
              </div>
            </div>
          </div>
          <div class="mt-4">
            <div class="my-2">
              <table style="width:100%">
                <thead class="tablePayViewHead">
                    <tr>
                      <th class="text-subtitle2" v-for=" (item,key) in order.order" :key="key " :class="key==0 ? 'text-start':'text-center'">
                        {{ item.title }}
                      </th>

                    </tr>
                </thead>
                <tbody> 
                  <tr>
                    <th class="text-subtitle2 text-bold py-3" v-for=" (item,key) in order.order" :key="key" :class="key==0 ? 'text-start':'text-center'">
                      {{ item.value }}
                    </th>
                  </tr>
                  
                </tbody>
              </table>
              <div class=" pt-4" style="border-top: 1px solid darkgray;">
                <div class="row">
                  <div v-for="(item,index) in order.facturation" :key="index" :class="'col-6 mb-1 flex ' + (index%2 == 0 ? 'justify-start ':'justify-end text-end')" >
                    <div class="text-subtitle2 text-bold  text-stone-500 mr-1">
                      {{item.title}}:
                    </div>
                    <div class="text-subtitle2 text-black text-bold">
                      {{item.value}}
                    </div>
                  </div>
                </div>
                <div class="mt-2">
                  <img :src="props.order.vaucher" alt="" class="w-full h-full">
                </div>
              </div>
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