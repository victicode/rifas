<script setup>
import { useMethodPayStore } from '@/services/store/methodPay.store';
import { onMounted, ref } from 'vue';
import updatePayMethodModal from '@/components/admin/payMethods/updatePayMethodModal.vue';

import viewDataPayInMethodModal from '@/components/admin/payMethods/viewDataPayInMethodModal.vue';

const ready = ref(false)
const methodPayStore = useMethodPayStore()
const payMethods = ref([]) 
const showModal = ref('')
const selectedPayMethod = ref({})
const openModal = (modal, method) =>{
  selectedPayMethod.value = payMethods.value.find(item => item.id == method)
  showModal.value = modal
}
const getMethods = () => {
  ready.value = false
  methodPayStore.getPayMethods()
  .then((response) =>{
    payMethods.value = response.data

    setTimeout(() => {
      ready.value = true
    }, 1000);
  })
}

const closeModal = () => {
  showModal.value = ''
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
          Métodos de pago
        </h4>
      </div>
    </div>
    <template v-if="ready">
      <div class="mt-12 md:mt-4 ">
        <template v-if="payMethods.length > 0">
          <div class="row items-center md:justify-start  methods__container w-100">
            <div v-for="(payMethod, key) in payMethods" :key="key" class="  col-12 col-md-3 px-1 md:px-2 md:my-2 my-3" >
              <div style="border-radius:1rem; overflow:hidden; border: 2px solid black " class=" w-full bg-white" >
                <div class="p-3 bg-black text-white method-item__header text-center flex flex-center">
                  {{ payMethod.name }}
                  <q-chip color="white" text-color="black"   class="  px-2 md:px-1">
                    ({{ payMethod.coin.symbol }})
                  </q-chip>

                </div>
                <div class="py-1" >
                  <div class="flex py-1 my-1 items-center justify-between" style="border-bottom: 1px solid darkgrey">
                    <div class="text-black method-item__text px-4 ">
                      Datos vinculados: 
                    </div>
                    <div class="text-black method-item__text px-4 ">
                      {{ payMethod.data_pay_count }}
                    </div>
                  </div>
                </div>
                <div class="flex justify-between p-2">
                  <div>
                    <q-chip :color="payMethod.status == 1 ? 'positive' : 'negative'" text-color="white"  class="">
                      <div class="px-2 md:px-1">
                        {{payMethod.status_label}}
                      </div>
                    </q-chip>
                  </div>
                  <div class="flex justify-end ">
                    <q-btn round color="green-8" size="0.8rem" class="mx-1 button__actionRifa" text-color="white" icon="account_balance_wallet"  @click="openModal('dataPay', payMethod.id)"/> 

                    <q-btn round color="primary" size="0.8rem" class="mx-1 button__actionRifa" text-color="white" icon="settings"  @click="openModal('update', payMethod.id)"/> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="h-full flex column items-center justify-center md:mt-12">
            <div class="text-empty">
              No hay métodos de pagos creados😢😢
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

    <template v-if="Object.values(selectedPayMethod).length > 0">
      
      <viewDataPayInMethodModal :dialog="(showModal == 'dataPay')" :payMethod="selectedPayMethod" @closeModal="closeModal()" />

      <updatePayMethodModal :dialog="(showModal == 'update')" :payMethod="selectedPayMethod" @closeModal="closeModal()" @updateList="getMethods()" />
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