<script setup>
import { useRoute } from 'vue-router';
import { onMounted, ref } from 'vue';
import { useOrderStore } from '@/services/store/order.store';
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net';

DataTable.use(DataTablesLib);

const route = useRoute()
const search = ref('')
const searchType = ref(1)
const orders = ref([])
const orderStore = useOrderStore()
const columns = ref([
      { key: 'amount', label: 'Monto' },
      { key: 'quantity', label: 'Cantidad de tickets' },
      { key: 'status_label', label: 'Estado' },
    ]);
const getOrders = () => {
  const data = {
    search,
    searchType
  }
  orderStore.getPaginationOrders(data)
  .then((response) => {
    console.log(response.data.data)
    // orders.value = response.data.data
  })
 }
 onMounted(() => {
  getOrders()
 })
</script>
<template>
  <div class="h-full">
    <div class="text-h5 font-bold text-bold">
      • {{route.meta.titlePage}}
    </div>
    <section id="filterAndSearch" class="w-full  px-4 py-2 md:py-3 mt-4">
      <div class="">
        <div class=" mb-1 px-2 md:px-0">
          <q-input
            v-model="search"
            label="Busqueda"
            placeholder="N° de orden / Cédula de identidad / Referencia de pago / Monto pagado"
            class=" searchOrderForm__input"
          >
            <template v-slot:append>
              <q-icon name="search" color="primary" />
            </template>
          </q-input>
        </div>
        <div class="mt-4">
          <div class="text-black text-subtitle1 text-bold">
            Buscar por:
          </div>
           <div class="row">
              <div class="col-md-2 col-6">
                <q-radio v-model="searchType" :val="1" class="text-black" label="N° de orden" />
              </div>
              <div class="col-md-2 col-6">
                <q-radio v-model="searchType" :val="2" class="text-black" label="CI de cliente" />
              </div>
              <div class="col-md-2 col-6">
                <q-radio v-model="searchType" :val="3" class="text-black" label="Referencia de pago" />
              </div>
              <div class="col-md-2 col-6">
                <q-radio v-model="searchType" :val="4" class="text-black" label="Monto pagado" />
              </div>
            </div>
        </div>
      </div>
      <div>

      </div>
    </section>
    <section id="tablePays" class="p-8  md:mt-8 mt-2">
        <vue3-datatable :items="orders" :columns="columns"></vue3-datatable>

    </section>
  </div>
</template>

<style lang="scss">
.searchOrderForm__input {

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
  }
  
  & .q-field__native{
    padding-top: 15px!important;
    font-weight: 600;
  }
  & .q-field__append{
    transform: translateY(5%)
  }
}

#filterAndSearch{
  background: white;
  border-radius: 1rem;
}
#tablePays{
  background: white;
  border-radius: 1rem;
}
</style>