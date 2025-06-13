<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';
import numberUtils from '@/utils/numberUtils.js';
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
    loading.value = true

  })
}
const formattDate = (date) => {
  return moment(date).format('DD') +' de '+ moment(date).format('MMMM') + ' de ' + moment(date).format('YYYY')  
}
onMounted(() => {
  getRifaById()
})
</script>
<template>
  <div class="md:px-8 px-5">
    <div v-if="loading">
      <div class="row pt-6">
        <div class="col-md-4 md:pr-8 relative flex md:justify-center">
          <img :src="rifa.configuration.banner_img" alt="" class="img__banner">
          <div class="badge__buy py-1 px-4" style=" " >
            Compra ahora!
          </div>
        </div>
        <div class="col-md-5 pt-5 md:px-2">
          <div class="flex w-full justify-between items-center">
            <div>
              <div class="text-bold text-black text-h3">
                {{ rifa.title }}
              </div>
              <div class="text-stone-800 text-subtitle1 text-bold hiddenx md:block">
                Boleto: Bs {{ numberUtils.numberFormat(rifa.configuration.price) }}
              </div>
            </div>
            <div>
              <div class="date__show px-5 py-3 ">
                <q-icon name="event" size="1.6rem" class="mr-2" />
                {{ formattDate()}}
                
              </div>
              <div class="text-stone-800 text-subtitle1 text-bold block md:hidden">
                Boleto: Bs {{ numberUtils.numberFormat(rifa.configuration.price) }}
              </div>
            </div>
          </div>
          <div class="mt-8 text-stone-700 text-subtitle1 text-bold">
            {{ rifa.description }}
          </div>
        </div>
        <div class="col-md-3 pt-12 md:pl-8 ">
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
            <div class="pt-2 px-5 hiddenx md:block">
             <q-btn  color="blur" size="0.72rem" unelevated class=" button__BuyRifa w-2/6" text-color="white" style="border-radius:0.5rem" >
                <div class="py-1 text-subtitle2">
                  Comprar Ahora
                </div>
              </q-btn>
            </div>
          </div>
        </div>
      </div>
      <div>

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
.img__banner{
  height: 100%;
  border-radius: 1rem;
  border: 1px solid rgb(70, 70, 70);
  box-shadow: 0px 0.5rem 1rem 0px darkgrey;
}
.badge__buy{
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