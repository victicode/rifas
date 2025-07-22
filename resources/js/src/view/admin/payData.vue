<script setup>
import { useMethodPayStore } from '@/services/store/methodPay.store';
import { onMounted, ref } from 'vue';
import createDataPayModal from '@/components/admin/payMethods/createDataPayModal.vue';
import updateDataPayModal from '@/components/admin/payMethods/updateDataPayModal.vue';
import deleteDataPayModal from '@/components/admin/payMethods/deleteDataPayModal.vue';

import numberUtils from '@/utils/numberUtils.js';
import bankLabels from '@/utils/bankLabelUtils';

const ready = ref(false)
const methodPayStore = useMethodPayStore()
const dataPays = ref([]) 
const showModal = ref('')
const selectedDataPay = ref({})
const openModal = (modal, method) =>{
  selectedDataPay.value = dataPays.value.find(item => item.id == method)
  showModal.value = modal
}
const getMethods = () => {
  ready.value = false
  methodPayStore.getMethodsData()
  .then((response) =>{
    dataPays.value = formatMethodData(response.data)

    setTimeout(() => {
      ready.value = true
    }, 1000);
  })
}
const formatMethodData = (data) => {
  data.forEach(element => {
      element.data = JSON.parse(element.data) 
  });
  return data
}
const closeModal = () => {
  showModal.value = ''
}
const bankLabelAssign = (code) => {
    return bankLabels.find((bank) => bank.code == code)? bankLabels.find((bank) => bank.code == code).name  : 'S/B'
}
onMounted(() =>{
  getMethods()
})
</script>
<template>
  <div class="h-full">
    <div class="flex items-center justify-between ">
      <div class="flex items-center">
        <div class="icon_badge q-pa-sm flex items-center justify-center">
          <q-icon name="local_activity" color="white" size="2rem"/>
        </div>
        <h4 class="text-black font-bold ml-2" >
          Datos de pago
        </h4>
      </div>
      <div class="md:mt-0 mt-2">
        <q-btn unelevated style="border-radius:0.4rem" icon="add" color="black" 
        class="q-py-sm md:mx-2 " label="Agregar datos de pago" no-caps @click="showModal = 'create'" />
      </div>
    </div>
    <template v-if="ready">
      <div class="mt-12 md:mt-4 ">
          <template v-if="dataPays.length > 0">
            <div class="row items-center md:justify-start  methods__container w-100">
              <div v-for="(dataPay, key) in dataPays" :key="key" class="  col-12 col-md-3 px-1 md:px-2 md:my-2 my-3" >
                <div style="border-radius:1rem; overflow:hidden; border: 2px solid black; height:15rem; overflow:hidden;  " class=" w-full bg-white flex column justify-between" >
                  <div class="p-3 bg-black text-white method-item__header text-center">
                    {{ dataPay.method_type.name }}
                  </div>
                  <div class="py-1" >
                    <div v-for="(item, index) in dataPay.data" :key="index" class="flex py-1 my-1 items-center justify-between" style="border-bottom: 1px solid darkgrey">
                      <div class="text-black method-item__text px-4 ">
                        {{ item.title }}: 
                      </div>
                      <div class="text-black method-item__text px-4 ">
                        <span v-if="item.title=='Banco'" class="mr-1"> 
                          {{ 
                           bankLabelAssign(item.value)
                          }}
                        </span>
                        <span v-if="item.title=='Banco'">(</span>{{ item.title == 'Documento' ? numberUtils.numberFormat(item.value) : item.value }}<span v-if="item.title=='Banco'">)</span>
                      </div>
                    </div>
                  </div>
                  <div class="flex justify-between p-2">
                    <div>
                      <q-chip :color="dataPay.status == 1 ? 'positive' : 'negative'" text-color="white"  class="">
                        <div class="px-2 md:px-1">
                          {{dataPay.status_label}}
                        </div>
                      </q-chip>
                    </div>
                    <div class="flex justify-end ">
                      <q-btn round color="primary" size="0.8rem" class="mx-1 button__actionRifa" text-color="white" icon="settings"  @click="openModal('update', dataPay.id)"/> 
                      <q-btn round color="negative" size="0.8rem" class="ml-1 button__actionRifa" text-color="white" icon="delete"  @click="openModal('delete', dataPay.id)"/>  
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="h-full flex column items-center justify-center md:mt-12">
              <div class="text-empty">
                No hay datos de pagos creados😢😢
              </div>
              <q-btn unelevated style="border-radius:0.4rem" icon="add" color="black" 
              class="q-py-md mt-5 mx-2" label="Agregar método de pago" no-caps @click="showModal = 'create'" />
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
    <createDataPayModal :dialog="(showModal == 'create')" @closeModal="closeModal()"  @updateList="closeModal(); getMethods()"/>
    
    <template v-if="Object.values(selectedDataPay).length > 0">
      <updateDataPayModal :dialog="(showModal == 'update')" :dataPay="selectedDataPay" @closeModal="closeModal()" @updateList="getMethods()" />
      <deleteDataPayModal :dialog="(showModal == 'delete')" :dataPay="selectedDataPay" @closeModal="closeModal()" @updateList="getMethods()" />
    </template>
  </div>
</template>
<style lang="scss">
.method-item__header{
  font-size: 1rem;
  font-weight: bold;
}
.method-item__text{
  font-size: 1rem;
  font-weight: 500;
}
.methods__container{
  flex-direction: row;
}

.text-empty{
  font-size: 2.5rem;
  font-weight: bold;
  opacity: 1;
  color:black;
  text-align: center;
}
.method__item{
  box-shadow: 5px 0px 26px 0px #dfdfdf;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: 100%; 
  height: 33rem!important; 
  overflow: hidden;
  background:white;
  border-radius: 1.2rem;
  &--title{
    font-size:1.3rem; 
    font-weight:bold;
  }
  &--imgContainer{
    height: 54%; overflow: hidden;
    position: relative;
  }
  &--img{
    width: 100%; height: 100%; border-radius: 1rem; object-fit: cover;
  }
}
@media (max-width: 768px){
  .methods__container{
    flex-direction: column;
  }
  .method__item{
    height: 33rem!important; 

  }
  .text-empty{
    font-size: 1.8rem;
  }
}
</style>