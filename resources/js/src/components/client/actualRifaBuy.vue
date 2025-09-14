<script setup>
import { ref } from 'vue';
import numberUtils from '@/utils/numberUtils.js';
import { useRouter } from 'vue-router';
import countdownVue from '@/components/layouts/countdown.vue';

const props = defineProps({
  rifa: Object,
})

const router = useRouter()
const goTo = (type, id) => {
  const url = '/'+type+'/'+id
  router.push(url)
};
const rifa = ref(props.rifa)

</script>
<template>
  <div class="w-full bg-black">
    <div class="pt-8 pb-8" v-if="Object.values(rifa).length > 0">
      <div class=" text-white px-5 py-2 my-1 text-center actualRifa__subtitle" >
          Participa  y se nuestro proximo ganador!
      </div>
      <div class="text-white text-center mt-2 text-subtitle1 mx-5" >
        <div class="  pb-1 " >
          <div class="px-0 relative md:w-3/12 w-11/12 mx-auto bounceImge" style=" overflow:hidden; border-radius:2rem" >
            <img :src="rifa.configuration.banner_img" alt=""  class="img__rifa">
          </div>
          <div class="px-4 md:w-4/12 mx-auto mt-5">
            <div class="q-mt-sm">
              <!-- <q-linear-progress class="py-4" stripe rounded size="1.5rem" :value="(rifa.soldTickets/100)" color="grey-8" track-color="grey-8" style="border-radius: 2rem;" >
                <div class="absolute-full flex flex-center ">
                  <q-badge color="white" text-color="black" class="text-bold" :label="'Vendidos: '+rifa.soldTickets+'%'" />
                </div>
              </q-linear-progress> -->
            </div>
            <div class="flex justify-end my-4 md:px-11">
              <q-btn  color="white" size="0.72rem" unelevated class=" button__BuyRifa w-full" text-color="black" style="border-radius:0.5rem"  @click="goTo('rifa', rifa.id)">
                <div class="py-1 text-subtitle2">
                  Comprar Tickets
                </div>
              </q-btn> 

            </div>
          </div>
        </div>
      </div>
      <div class="">
        <countdownVue :targetDate="new Date(rifa.due_date)" />
      </div>
    </div>

  </div>
</template>
<style lang="scss" >
.actualRifa{
  font-weight: bold;
  font-size: 1.8rem;
  &__subtitle{
    font-size: 1.4rem;
  font-weight: bold;

  }
}

.bounceImge{
  animation:  bounceImge 2s infinite alternate-reverse;
}
@media (max-width: 780px) {

}

@keyframes bounceImge {
  0%{
    transform: translateY(-0.2rem);
  }
  50%{
    transform: translateY(0.2rem);
  }
  100%{
    transform: translateY(-0.2rem);
  }
}
</style>