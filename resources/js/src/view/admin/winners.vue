<script setup>
import { useWinnerStore } from '@/services/store/winner.store';
import { onMounted, ref } from 'vue';
import numberUtils from '@/utils/numberUtils.js'
import moment from 'moment';
import createWinnersModal from '@/components/admin/winners/createWinnersModal.vue';
import updatewinnerModal from '@/components/admin/rifa/updateRifaModal.vue';

import deletewinnerModal from '@/components/admin/rifa/deleteRifaModal.vue';
import { useRouter } from 'vue-router';

const router = useRouter()
const ready = ref(false)
const winnerStore = useWinnerStore()
const winners = ref([]) 
const showModal = ref('')
const lastPage = ref(0);
const actualPage = ref(1)
const selectedWinner = ref({})
const openModal = (modal, winner) =>{
  selectedWinner.value = winners.value.find(item => item.id == winner)
  showModal.value = modal
}
const getWinner = () => {
  ready.value = false

  const query = {
    page: actualPage.value
  }
  winnerStore.getWinners(query)
  .then((response) =>{
    lastPage.value = response.data.last_page
    winners.value = response.data.data;
    setTimeout(() => {
      ready.value = true
    }, 1000);
  })
}

const closeModal = () => {
  showModal.value = ''
}
onMounted(() =>{
  getWinner()
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
          Ganadores
        </h4>
      </div>
      <div>
        <q-btn unelevated style="border-radius:0.4rem" icon="add" color="black" 
        class="q-py-sm mx-2" label="Agregar ganador" no-caps @click="showModal = 'create'" />
      </div>
    </div>
    <template v-if="ready">
      <div class="mt-12 md:mt-4 ">
          <template v-if="winners.length > 0">
            <div class="row items-center md:justify-start  winners__container w-100">
              <div v-for="winner in winners" :key="winner.id" class="md:px-3 col-12 col-md-3">
                <div  class=" winner__item my-5 md:my-2 q-pb-md " style="" >
                  <div class=" winner__item--imgContainer bg-red-600">
                    <img :src="winner.winner_photo" alt="" style="width:100%; object-fit:cover; position:absolute; z-index:0">
                    <div class="flex column flex-center h-full  " style="position:relative; z-index:3">
                      <div class="bg-black text-white text-center mb-2 py-1s  px-5 text-subtitle1" 
                      style="border-radius:0.5rem">
                        Ganador
                      </div>
                      <div class="bg-black w-full text-white text-center text-h4 py-2">
                        {{ winner.ticket.number }}
                      </div>
                    </div>
                  </div>
                  <div class="px-4 pt-3 flex column justify-between" style="height: -webkit-fill-available; flex-wrap: nowrap;">
                    <div class="winner__item--title w-full ellipsis text-stone-500 text-center">{{winner.rifa.title}}</div>
                    <div class="pt-1">
                      <div class="mb-1  text-stone-800 " > 
                        <div class="text-center text-subtitle1">
                           <b>Premio: </b>
                        </div>
                        <div class="text-subtitle2 mt-1">
                          {{winner.reward.title}}

                        </div>
                      </div>
                    </div>
                    <div class=" pt-2" style="border-top: 1px solid black my-4"> 
                      <div class="flex">
                        <q-btn round :color="'blue-10'" size="0.72rem" class="mx-1 button__actionwinner" text-color="white" icon="cached" @click="openModal('status', winner.id)" >
                          <q-tooltip class="bg-black text-white text-body2" :offset="[10, 10]">
                            Actualizar estado
                          </q-tooltip>
                        </q-btn> 
                        <q-btn round color="negative" size="0.72rem" class="ml-1 button__actionwinner" text-color="white" icon="delete" @click="openModal('delete', winner.id)" >
                          <q-tooltip class="bg-negative text-white text-body2" :offset="[10, 10]">
                            Borrar Ganador
                          </q-tooltip>
                        </q-btn>
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
                @update:model-value="getWinner()"
                gutter="0.5rem"
              />
            </div>
          </template>
          <template v-else>
            <div class="h-full flex column items-center justify-center">
              <div class="text-empty">
                No hay ganadores todavia😪
              </div>
              <q-btn unelevated style="border-radius:0.4rem" icon="add" color="black" 
              class="q-py-md mt-5 mx-2" label="Agregar ganador" no-caps @click="showModal = 'create'" />
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
    <createWinnersModal :dialog="(showModal == 'create')" @closeModal="closeModal()"  @updateList="getWinner()"/>
    
    <template v-if="Object.values(selectedWinner).length > 0">
     
      <updatewinnerModal :dialog="(showModal == 'update')"  :winner="selectedWinner"  @closeModal="closeModal()" @updateList="getWinner()" />
      <deletewinnerModal :dialog="(showModal == 'delete')"  :winner="selectedWinner"  @closeModal="closeModal()" @updateList="getWinner()" />
    </template>
  </div>
</template>
<style lang="scss">
.winners__container{
  flex-direction: row;
}

.text-empty{
  font-size: 2.5rem;
  font-weight: bold;
  opacity: 1;
  color:black;
  text-align: center;
}
.winner__item{
  box-shadow: 5px 0px 26px 0px #dfdfdf;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: 100%; 
  height: 26rem!important; 
  overflow: hidden;
  background:white;
  border-radius: 1.2rem;
  &--title{
    font-size:1.3rem; 
    font-weight:bold;
  }
  &--imgContainer{
    height:100%; overflow: hidden;
    position: relative;
  }
  &--img{
    width: 100%; height: 100%; border-radius: 1rem; object-fit: cover;
  }
}
@media (max-width: 768px){
  .winners__container{
    flex-direction: column;
  }
  .winner__item{
    height: 36rem!important; 

  }
  .text-empty{
    font-size: 1.8rem;
  }
}
</style>