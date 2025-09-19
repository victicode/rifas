<script setup>
import { onMounted, ref } from 'vue';
import { useRifaStore } from '@/services/store/rifas.store';

import util from '@/utils/numberUtils'

const numberFormat = util.numberFormat
const modelRifa = ref({
  id: 0,
  title:'Selecciona una rifa'
})
const rifasOption = ref([{
  id: 0,
  title:'Selecciona una rifa'
}])
const pole = [ 
  '🥇',
  '🥈',
  '🥉',
  '🏅',
  '🏅',
]

const selectedRifa = ref({})
const ready = ref(true)
const rifaStore = useRifaStore()
const getRifas = () => {
  rifaStore.getAllRifas()
  .then((response) => {
    rifasOption.value = [
      {
        id:0,
        title:'Selecciona una rifa'
      },
      ...response.data
    ]
    
  })
}
const getReportByRifaId = (e) => {

  if(e.id == 0){ 
    selectedRifa.value = {}
    return
  }
  ready.value = false
  rifaStore.getRifaWithReport(e.id)
  .then((response) => {
    selectedRifa.value = response.data 
    setTimeout(() => {
      ready.value = true
    }, 100);
  })
}

onMounted(() => {
    getRifas()
})
</script>
<template>
  <div class="h-full">
    <div class="flex items-center justify-between ">
      <div class="flex items-center">
        <div class="icon_badge q-pa-sm flex items-center justify-center">
          <q-icon name="local_activity" color="white" size="2rem"/>
        </div>
        <h5 class="text-black font-bold ml-2" >
          Estadisticas de rifa
        </h5>
      </div>
    </div>
    <div class="mt-12 md:mt-4 " >
      
      <template v-if="ready">
        <div class="row md:mt-5 mt-6" v-if="Object.values(selectedRifa).length > 0">
          <div class="col-md-6 col-12 px-2">
            <div class="row">
              <div class=" col-12 px-2">
                <q-select 
                  v-model="modelRifa"
                  option-value="id"
                  option-label="title"
                  behavior="menu"
                  color="primary"
                  :options="rifasOption"
                  class="createOrderForm__input"
                  @update:model-value="getReportByRifaId"
                >
                  <template v-slot:option="scope">
                    <q-item v-bind="scope.itemProps">
                      <q-item-section>
                        <q-item-label>{{ scope.opt.title }}</q-item-label>
                        <q-item-label caption v-if="scope.opt.id!=0">
                          {{ numberFormat(scope.opt.tickets_count) }} ticket(s) vendidos
                        </q-item-label>
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
                <div >
                  <div>

                  </div>
                </div>
              </div>
            </div>
            <div >
              <div style="" class="img__slot flex flex-center md:w-4/6 w-8/12 mx-auto bg-slate-300"> 
                <img :src="selectedRifa.configuration.banner_img" alt="" style=" object-fit:cover">
              </div>
            </div>
          </div>
          <div class="col-md-6 md:pl-16 md:pr-2 col-12 mt-6 px-0 md:mt-0 md:pt-0" >
            <div class="card__report mb-5 ">
              <div class="text-white text-h6 py-2 text-bold text-center bg-black">
                Top más tickets comprado🔥
              </div>
              <div class=" mt-2 py-2 mx-2" >
                <table style="width: 100%;">
                  <thead>
                    <tr>

                      <th class="text-black th_repot px-2 py-2 text-left">Puesto</th>
                      <th class="text-black th_repot px-2 py-2 text-left">Nombre</th>
                      <th class="text-black th_repot px-2 py-2 text-center thr">Compras</th>
                      <th class="text-black th_repot px-2 py-2 text-center">Tickets</th>
                    </tr>
  
                  </thead>
                  <tbody class="py-8">
                    <tr class="" style="border-top: 1px solid darkgray;" v-for="(item, key) in selectedRifa.mostBuy" :key="key">
                      <td class="td_report text-black text-left text-bold py-4 px-3">
                        {{ key+1 }} {{ pole[key] }}
                      </td>
                      <td class="td_report text-black text-left text-bold py-4 px-2">
                        {{ item ? item.client.name  : 'Sin datos' }}
                      </td>
                      <td class="td_report text-black text-center text-bold thr *:py-4 px-2">
                        {{ item ? item.orders : 'Sin datos' }}
                      </td>
                      <td class="td_report text-black text-center text-bold py-4 px-2">
                        {{ item ? item.quantity  : 'Sin datos' }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="row md:mt-5 mt-6" v-else>
          <div class="col-md-6 col-12 md:pr-16 md:pl-2 px-2">
            <div class="row">
              <div class=" col-12 px-2">
                <q-select 
                  v-model="modelRifa"
                  option-value="id"
                  option-label="title"
                  behavior="menu"
                  color="primary"
                  :options="rifasOption"
                  class="createOrderForm__input"
                  @update:model-value="getReportByRifaId"
                >
                  <template v-slot:option="scope">
                    <q-item v-bind="scope.itemProps">
                      <q-item-section>
                        <q-item-label>{{ scope.opt.title }}</q-item-label>
                        <q-item-label caption v-if="scope.opt.id!=0">
                          {{ numberFormat(scope.opt.tickets_count) }} ticket(s) vendidos
                        </q-item-label>
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
                <div >
                  <div>

                  </div>
                </div>
              </div>
            </div>
            <div class="pt-5">
              <div style="" class="img__slot flex flex-center md:w-4/6 w-9/12 mx-auto bg-slate-300"> 
                <div class="text-h6 text-gray-500">
                  Selecciona una rifa
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-12 mt-6 md:mt-0" >
            <div class="card__report mb-5 ">
              <div class="text-white text-h6 py-2 text-bold text-center bg-black">
               Top más tickets comprado🔥
              </div>
              <div class=" mt-2 py-2 mx-2" >
                <table style="width: 100%;">
                  <thead>
                    <tr>

                      <th class="text-black th_repot px-2 py-2 text-left">Puesto</th>
                      <th class="text-black th_repot px-2 py-2 text-left">Nombre</th>
                      <th class="text-black th_repot px-2 py-2 text-center">Compras</th>
  
                      <th class="text-black th_repot px-2 py-2 text-center">Tickets</th>
                    </tr>
  
                  </thead>
                </table>
                <div class="text-h6 text-black my-8 text-center">
                  Selecciona una rifa
                </div>
              </div>
            </div>
          </div>
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
.card__report{
  background: white;
  border-radius: 1rem;
  overflow:hidden;
  box-shadow: 5px 0px 26px 0px #dfdfdf;
  border: 1px solid black;

}
.img__slot{
  height:30rem; border-radius: 1rem; overflow: hidden;
}
.th_report{
  border-bottom: 1px solid rgb(0, 0, 0);
  padding: 0.8rem 0rem;
  font-size: 0.95rem;
  width: 35%;  
  &:first-child{
    width: 10%;
  }
  &:last-child{
    width: 20%;
  }
  &.thr{
    width: 20%;

  }
  
}
.td_report{
  font-size: 0.95rem;
  width: 35%;
  &:first-child{
    width: 10%;
  }
  &.thr{
    width: 20%;

  }
  &:last-child{
    width: 20%;
  }
}
.q-item--active{
  & .q-item__label{

    color: goldenrod!important;
  }
}
.searchRifaForm__input {

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
@media (max-width: 768px){
  .img__slot{
    height:20rem; border-radius: 1rem
  }
}
</style>