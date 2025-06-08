<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
import numberUtils from '@/utils/numberUtils.js'
import moment from 'moment';
import createRifaModal from '@/components/admin/rifa/createRifaModal.vue';
import rewardsModal from '@/components/admin/rifa/rewardsModal.vue'
import updateRifaModal from '@/components/admin/rifa/updateRifaModal.vue';

const ready = ref(false)
const rifaStore = useRifaStore()
const actualPage = ref(1)
const rifas = ref([]) 
const showModal = ref('')
const lastPage = ref(0);
const selectedRifa = ref({})
const openModal = (modal, rifa) =>{
  selectedRifa.value = rifas.value.find(item => item.id == rifa)
  showModal.value = modal
}
const getRifas = () => {
  ready.value = false

  const query = {
    page: actualPage.value
  }
  rifaStore.getRifas(query)
  .then((response) =>{
    lastPage.value = response.data.data.last_page
    rifas.value = response.data.data.data;
    setTimeout(() => {
      ready.value = true
    }, 1000);
  })
}
const updateReward = (data) => {
  const index = rifas.value.findIndex((item) => item.id == data.id)
  rifas.value[index].rewards = data.rewards 
}
const closeModal = () => {
  showModal.value = ''
}
onMounted(() =>{
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
        <h4 class="text-black font-bold ml-2" >
          Rifas
        </h4>
      </div>
      <div>
        <q-btn unelevated style="border-radius:0.4rem" icon="add" color="black" 
        class="q-py-sm mx-2" label="Agregar Rifa" no-caps @click="showModal = 'create'" />
      </div>
    </div>
    <template v-if="ready">
      <div class="mt-12 md:mt-4 ">
          <template v-if="rifas.length > 0">
            <div class="row items-center md:justify-start  rifas__container w-100">
              <div v-for="rifa in rifas" :key="rifa.id" class="md:px-2 col-12 col-md-3">
                <div  class=" rifa__item my-5 md:my-2 q-pb-md " style="" >
                  <div class=" rifa__item--imgContainer">
                    <img :src="rifa.configuration.banner_img" class="rifa__item--img" />
                    <div style="position: absolute; top: 0; right: 1px;">
                      <q-chip color="white" text-color="primary"  class="">
                        <div class="text-caption">
                          Se juega el {{moment(rifa.due_date).format('DD/MM/YYYY')}}
                        </div>
                      </q-chip>
                    </div>
                  </div>
                  <div class="px-4 pt-3 flex column justify-between" style="height: 46%; flex-wrap: nowrap;">
                    <div class="rifa__item--title w-full ellipsis">{{rifa.title}}</div>
                    <div class="q-pt-sm">
                      <div class="my-1 text-subtitle2"> • Cantidad de tickets: <b>{{ numberUtils.numberFormat(rifa.configuration.quantity_tickets) }}</b></div>
                      <div class="my-1 text-subtitle2"> • Valor del Ticket:    <b>{{ numberUtils.numberFormat(rifa.configuration.price) }} Bs</b></div>
                      <div class="my-1 text-subtitle2"> • Compra minima: <b>{{ rifa.configuration.minimus_buy }} tickets</b></div>
                      <div class="my-1 text-subtitle2"> • Creada el: <b>{{ moment(rifa.created_at).format('DD/MM/YYYY') }}</b></div>
                      <div class="flex q-my-sm justify-between items-center"> 
                        <q-chip :color="rifa.status == 1 ? 'positive' : 'negative'" text-color="white"  class="">
                          <div class="px-3">
                            {{rifa.status_label}}
                          </div>
                        </q-chip>
                        <div class="flex justify-end">
                          <q-btn round color="primary" size="0.81rem" class="mr-1" text-color="white" icon="emoji_events" @click="openModal('rewards', rifa.id)" /> 
                          <q-btn round color="primary" size="0.81rem" class="mx-1" text-color="white" icon="settings"  @click="openModal('update', rifa.id)"/>
                          <q-btn round color="negative" size="0.81rem" class="ml-1" text-color="white" icon="delete" />
                        </div>
                      </div>
                      <div v-if="rifa.status == 1 " class="q-mt-sm">
                        <q-linear-progress stripe rounded size="1.3rem" :value="0" color="primary" track-color="grey-8" style="border-radius: 2rem;" >
                          <div class="absolute-full flex flex-center ">
                            <q-badge color="white" text-color="black" class="text-bold" :label="'Vendidos: '+0+'%'" />
                          </div>
                        </q-linear-progress>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="q-pa-md flex flex-center">
              <q-pagination
                v-model="actualPage"
                color="primary"
                outline
                active-design="unelevated"
                active-color="primary"
                active-text-color="white"
                :max="lastPage"
                :max-pages="6"
                boundary-numbers
                @update:model-value="getRifas()"
                gutter="0.5rem"
              />
            </div>
          </template>
          <template v-else>
            <div class="h-full flex column items-center justify-center">
              <div class="text-empty">
                No hay rifas creadas😢😢
              </div>
              <q-btn unelevated style="border-radius:0.4rem" icon="add" color="black" 
              class="q-py-md mt-5 mx-2" label="Agregar Rifa" no-caps @click="showModal = 'create'" />
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
    <createRifaModal :dialog="(showModal == 'create')" @closeModal="closeModal()"  @updateList="getRifas()"/>
    <template v-if="Object.values(selectedRifa).length > 0">
      <rewardsModal :dialog="(showModal == 'rewards')"    :rifa="selectedRifa"  @closeModal="closeModal()" @updateReward="updateReward" />
      <updateRifaModal :dialog="(showModal == 'update')"  :rifa="selectedRifa"  @closeModal="closeModal()" @updateList="getRifas()" />
    </template>
  </div>
</template>
<style lang="scss">
.rifas__container{
  flex-direction: row;
}
.icon_badge{
  height: 3rem;
  width: 3rem;
  border-radius: 0.3rem;
  background: black;
  border: 1px solid #7f7f7f;
}
.text-empty{
  font-size: 2.5rem;
  font-weight: bold;
  opacity: 1;
  color:black;
  text-align: center;
}
.rifa__item{
  box-shadow: 5px 0px 26px 0px #dfdfdf;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: 100%; 
  height: 32rem!important; 
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
  .rifas__container{
    flex-direction: column;
  }
  .rifa__item{
    height: 33rem!important; 

  }
  .text-empty{
    font-size: 1.8rem;
  }
}
</style>