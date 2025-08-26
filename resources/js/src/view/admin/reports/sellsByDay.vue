<script setup>
import { onMounted, ref } from 'vue';

import { useOrderStore } from '@/services/store/order.store';
import util from '@/utils/numberUtils'
import moment from 'moment';
const orderStore = useOrderStore()
const numberFormat = util.numberFormat
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

const getReportTicketBuyByDay= () => {
  loading.value = true
  const dataFormatted = {
    since: moment(queryDate.value.since).format('YYYY-MM-DD'),
    until: moment(queryDate.value.until).format('YYYY-MM-DD'),
    page: queryDate.value.page
  }
  orderStore.reportSellsByDay(dataFormatted)
  .then(({data}) => {
    loading.value = false
    orders.value = data.data
    lastPage.value = data.last_page
  })
  .catch(() => {
    loading.value = false
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
          <div class="col-12 col-md-2 md:pl-4 pt-4">
            <q-btn type="submit" unelevated class="searchButton" color="primary" :loading="loading">
              <div class="w-full py-1">
                Buscar
              </div>
            </q-btn>
          </div>
        </div>
      </q-form>
      <template v-if="ready">
        <div v-if="orders.length > 0">
          <div>
            
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
.searchButton{
  width: 100%;
  border-radius: 0.5rem;
}
</style>