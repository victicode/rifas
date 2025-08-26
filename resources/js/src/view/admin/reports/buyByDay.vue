<script setup>
import { onMounted, ref } from 'vue';
import { useRifaStore } from '@/services/store/rifas.store';
import { useOrderStore } from '@/services/store/order.store';
import util from '@/utils/numberUtils'

const numberFormat = util.numberFormat


const orderStore = useOrderStore()
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
          Ventas del día
        </h5>
      </div>
    </div>
    <div class="mt-12 md:mt-4 " >
      <div class="row">
        <div class="col-md-6 col-12 md:pr-16 md:pl-2 px-2">
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
      <template v-if="ready">
        shiiii
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

</style>