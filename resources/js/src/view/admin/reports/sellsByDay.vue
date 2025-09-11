<script setup>
import { onMounted, ref } from 'vue';

import { useOrderStore } from '@/services/store/order.store';
import util from '@/utils/numberUtils'
import moment from 'moment';
const orderStore = useOrderStore()
const numberFormat = util.numberFormat
const totalSell = ref(0)
const myLocale = {
  /* starting with Sunday */
  days: 'Domingo_Lunes_Martes_Miércoles_Jueves_Viernes_Sábado'.split('_'),
  daysShort: 'Dom_Lun_Mar_Mié_Jue_Vie_Sáb'.split('_'),
  months: 'Enero_Febrero_Marzo_Abril_Mayo_Junio_Julio_Agosto_Septiembre_Octubre_Noviembre_Diciembre'.split('_'),
  monthsShort: 'Ene_Feb_Mar_Abr_May_Jun_Jul_Ago_Sep_Oct_Nov_Dic'.split('_'),
  firstDayOfWeek: 1, // 0-6, 0 - Sunday, 1 Monday, ...
  format24h: true,
  pluralDay: 'dias'
}
const lastPage = ref(1);
const loading = ref(false)
const ready = ref(true)
const queryDate = ref({
  since:'',
  until:'',
  page:1
})
const orders = ref([])
const notSearch = ref(false)
const getReportTicketBuyByDay= () => {
  loading.value = 
  ready.value = false

  const dataFormatted = {
    since: moment(queryDate.value.since).format('YYYY-MM-DD'),
    until: moment(queryDate.value.until).format('YYYY-MM-DD'),
    page: queryDate.value.page
  }
  orderStore.reportSellsByDay(dataFormatted)
  .then(({data}) => {
    loading.value = false
    ready.value = true
    notSearch.value = true

    orders.value = data.data
    lastPage.value = data.last_page
    sumaTotal()
  })
  .catch(() => {
    loading.value = false
    ready.value = true

  })
  
}
const sumaTotal = () => {
  orders.value.forEach((order) => {
    totalSell.value = order.amount + totalSell.value
  })
}
const optionsFn = (date) =>{
  return   date < moment(Date.now()+86400000).format('YYYY/MM/DD')
}


</script>
<template>
  <div class="h-full">
    <div class="flex items-center justify-between ">
      <div class="flex items-center">
        <div class="icon_badge q-pa-sm flex items-center justify-center">
          <q-icon name="local_activity" color="white" size="2rem"/>
        </div>
        <h5 class="text-black font-bold ml-2" >
          Ventas del día
        </h5>
      </div>
    </div>
    <div class="mt-12 md:mt-5 " >
      <q-form
        @submit="getReportTicketBuyByDay()"
      >
        <div class="row items-center">
          <div class="col-6 col-md-5 md:pr-4 pr-1">
            <q-input outlined class="q-pb-none" v-model="queryDate.since" mask="date" :rules="['date']" label="Desde">
              <template v-slot:append>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-date :locale="myLocale" :options="optionsFn"  v-model="queryDate.since">
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Aceptar" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>
          <div class="col-6 col-md-5 md:pl-4 md:pr-2 pl-1">
            <q-input outlined class="q-pb-none" v-model="queryDate.until" mask="date" :rules="['date']" label="Hasta">
              <template v-slot:append>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-date :locale="myLocale" :options="optionsFn"  v-model="queryDate.until">
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Aceptar" color="primary" flat />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>
          <div class="col-12 col-md-1 md:px-2 md:py-0 py-2">
            <q-btn type="submit" unelevated class="searchButton" color="primary" :loading="loading">
              <div class="w-full py-1">
                Buscar
              </div>
            </q-btn>
          </div>
          <div class="col-12 col-md-1 md:pb-0 pb-2" v-if="orders.length > 0">
            <!-- <q-btn type="submit" unelevated class="searchButton" color="red-8" :loading="loading">
              <div class="w-full py-1">
                Exportar
              </div>
            </q-btn> -->
          </div>
        </div>
      </q-form>
      <template v-if="ready">
        <div v-if="notSearch" class="pb-5">
          <div v-if="orders.length > 0"  class="md:mt-5 " style="overflow: hidden; border-left:1px solid black; border-right:1px solid black; border-top:1px solid black; border-radius: 0.5rem;">
            <div class="row  tab_report">
              <div class="py-4 px-2 col-3 text-bold text-center text_tabReport bg-black text-white">Fecha</div>
              <div class="py-4 px-1 col-3 text-bold text-center text_tabReport bg-black text-white">N° Orden</div>
              <div class="py-4 px-1 col-3 text-bold text-center text_tabReport bg-black text-white">Metodo</div>
              <div class="py-4 px-2 col-3 text-bold text-end text_tabReport bg-black text-white">Monto</div>
            </div>
            <div class="row  tab_report" v-for="order in orders" :key="order.id">
              <div class="py-4 px-1 md:px-2 col-3 text-bold text-black text-center text_tabReport">{{ moment(order.created_at).format('DD/MM/YYYY') }}</div>
              <div class="py-4 px-1 md:px-2 col-3 text-bold text-black text-center text_tabReport">#{{ order.number }}</div>
              <div class="py-4 px-1 md:px-2 col-3 text-bold text-black text-center text_tabReport">{{ order.method_pay.name }}</div>
              <div class="py-4 px-1 md:px-2 col-3 text-bold text-black text-end text_tabReport">Bs. {{ numberFormat(order.amount) }}</div>
            </div>
            <div class="row  tab_report items-center justify-end" >
              <div class="text-bold text-black text-end text_tabReport">
                Total Vendido:
              </div>
              <div class="py-4 pl-4 md:pr-2 pr-1 text-bold text-black text-end text_tabReport">Bs. {{ numberFormat(totalSell) }}</div>
              
            </div>
          </div>
          <div class="mt-16 text-center text-h6 text-bold text-black" v-else > 
            No encontramos resultado 🥺<br> prueba con otro rango de fechas 📆
          </div>
        </div>
        <div class="mt-16 text-center text-h5 text-bold text-black" v-else > 
          Selecciona las fechas 📅
        </div>
      </template>
      <template v-else>
        <div class="flex column items-center justify-center h-full py-16">
          <q-spinner-tail
            color="black"
            size="4rem"
          />
        </div>
      </template>
    </div>
  </div>
</template>
<style lang="scss">
.tab_report{
  border-bottom: 1px solid rgb(12, 12, 12);
  // overflow: hidden;
}
.searchButton{
  width: 100%;
  border-radius: 0.5rem;
}
</style>