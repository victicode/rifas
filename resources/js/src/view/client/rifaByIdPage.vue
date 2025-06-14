<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';
import numberUtils from '@/utils/numberUtils.js';
import buyRifaModal from '@/components/client/buyRifaByClientModal.vue'
moment.updateLocale('en', {
  months : [
      "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
      "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
  ]
});
const rifaStore = useRifaStore();
const rifa = ref({})
const loading = ref(false)
const route = useRoute()
const getRifaById = () => {
  rifaStore.getRifaById(route.params.id)
  .then((response) =>{
    rifa.value = response.data
    setTimeout(() => {
      loading.value = true
    }, 2000);

  })
}
const modal = ref(false)
const showModal = () => {
  modal.value = true
}
const closeModal = () => {
  modal.value = false
}
const formatDate = (date) => {
  return moment(date).format('DD') +' de '+ moment(date).format('MMMM') + ' de ' + moment(date).format('YYYY')  
}
const orderCreate = () => {
  console.log('eeeee')
}
onMounted(() => {
  getRifaById()
})
</script>
<template>
  <div class="md:px-8 px-5">
    <div v-if="loading">
      <div class="row nd:pt-6 pt-3">
        <div class="col-md-4 col-12 md:pt-3 md:pr-8 relative flex md:justify-center">
          <img :src="rifa.configuration.banner_img" alt="" class="img__bannerRifa">
          <div class="badge__buyRifa py-1 px-4" style=" " >
            Compra ahora!
          </div>
        </div>
        <div class="col-md-5 col-12 md:pt-2 pt-4 md:px-2">
          <div class="flex w-full justify-between items-center">
            <div>
              <div class="text-bold text-black text-h4">
                {{ rifa.title }}
              </div>
              
              <div class="text-stone-800 text-subtitle1 text-bold hiddenx md:block">
                Boleto: Bs. {{ numberUtils.numberFormat(rifa.configuration.price) }},00
              </div>
            </div>
            <div class="flex mt-5 md:mt-2 items-end w-full justify-between">
              <div class="date__show md:px-5 px-2 py-3 text-bold">
                <q-icon name="event" size="1.6rem" class="mr-2" />
                {{ formatDate()}}
                
              </div>
              <div class="text-stone-800 text-subtitle2 blockx md:hidden text-end" style="font-size:1rem" >
                Boleto: <br> 
                <b style="font-size:1.2rem">Bs. {{ numberUtils.numberFormat(rifa.configuration.price) }},00</b>
              </div>
            </div>
            
          </div>
          <div class="blockx md:hidden my-3">
            <q-btn  color="blur" size="0.72rem" unelevated class=" button__BuyRifa w-2/6" text-color="white" style="border-radius:0.5rem" @click="showModal()">
              <div class="py-2 text-subtitle2">
                Comprar Ahora
              </div>
            </q-btn>
            <q-linear-progress stripe rounded size="1.5rem" class="mt-3" :value="0" color="primary" track-color="grey-8" style="border-radius: 2rem;" >
              <div class="absolute-full flex flex-center ">
                <q-badge color="white" text-color="black" class="text-bold" :label="'Vendidos: '+0+'%'" />
              </div>
            </q-linear-progress>
            <q-btn  color="blur" size="0.72rem" unelevated class=" mt-4 button__BuyRifa w-2/6" text-color="white" style="border-radius:0.5rem" >
              <div class="py-2 text-subtitle2">
               Consultar mis tickets
              </div>
            </q-btn>
            <q-btn  color="blur" size="0.72rem" unelevated class=" mt-4 button__BuyRifa w-2/6" text-color="white" style="border-radius:0.5rem" >
              <div class="py-2 text-subtitle2">
               Detalles de la rifa
              </div>
            </q-btn>
          </div>
          <div class="md:mt-8 mt-4 text-stone-700 text-subtitle1 text-bold hiddenx md:block">
            {{ rifa.description }}
          </div>
        </div>
        <div class="col-md-3 col-12 md:pt-3 pt-4 mb-4 md:pl-8 ">
          <div class="rifa_content py-3 px-2">
            <div class="text-bold text-center text-black text-h4">
              Premios
            </div>
            <div>
              <div v-for="(reward, index) in rifa.rewards" :key="reward.id" class="q-mb-sm q-pt-sm q-pb-sm  mx-2" style="border-bottom: 1px solid darkgray;">
                <div class="flex justify-center items-center column">
                  <div class="text-h6 text-bold text-black">
                    Premio n° {{ index+1 }}
                  </div>
                  <q-chip color="black" text-color="white"  class="">
                    <div class="text-caption">
                      Se juega a las {{moment(rifa.due_date +' '+reward.reward_time).format('hh:mm A')}}
                    </div>
                  </q-chip>
                </div>
                <div class="py-2 text-subtitle1 text-bold text-center text-black">
                  {{ reward.title }}
                </div>
              </div>
            </div>
            <div class="pt-2 px-5 ">
              <q-btn  color="blur" size="0.72rem" unelevated class=" button__BuyRifa w-2/6" text-color="white" style="border-radius:0.5rem" @click="showModal()" >
                <div class="py-1 text-subtitle2">
                  Comprar Ahora
                </div>
              </q-btn>
              <q-linear-progress stripe rounded size="1.5rem" class="mt-3" :value="0" color="primary" track-color="grey-8" style="border-radius: 2rem;" >
                <div class="absolute-full flex flex-center ">
                  <q-badge color="white" text-color="black" class="text-bold" :label="'Vendidos: '+0+'%'" />
                </div>
              </q-linear-progress>
            </div>
          </div>
        </div>
      </div>
      <div>
        <buyRifaModal :dialog="modal" :rifa="rifa" @orderSuccessful="orderCreate" @closeModal="closeModal()"/>
      </div>
    </div>
    <div v-else class="flex justify-center mt-24">
      <q-spinner-tail
        color="primary"
        class="mt-24 "
        size="5rem"
      />
    </div>
  </div>
</template>
<style lang="scss">
.button__BuyRifa{
  width: 100%;
  transition: all 1s ease;
  &::before{
    transition: all 0.5s  ease;
  }
  &:hover::before{
    background: #129186!important;
  }
}
.rifa_content{
  border: 1px solid rgb(70, 70, 70);
  border-radius: 0.5rem;
  background: white;
}
.date__show{
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  color: black;
  border-radius: 0.5rem;
  background: rgb(130 130 130 / 13%);
  width: fit-content;
}
.img__bannerRifa{
  height: 95%;
  object-fit: cover;
  width: 100%;
  border-radius: 1rem;
  border: 1px solid rgb(70, 70, 70);
  box-shadow: 0px 0.5rem 1rem 0px darkgrey;
}
.badge__buyRifa{
  font-weight:bold;
  border-radius:0.5rem; 
  background:#39e1d2; width:fit-content;
  display: flex;
  font-size: 1rem;
  color: rgb(255, 255, 255);
  position: absolute;
  top: 1rem;
  right: 2.5rem;
  animation: bounce_buy 1s infinite alternate-reverse;
}
@media (max-width: 768px){
  .img__bannerRifa{
    height: 25rem;
  }
  .badge__buyRifa{
    right: 0.5rem;
  }
}
@keyframes bounce_buy {
  0%{
    opacity: 1;
  }
  50%{
    opacity: 1;
  }

  100%{
    opacity: 0;
  }
  
}
</style>