<script setup>
import { ref } from 'vue';
import numberUtils from '@/utils/numberUtils.js';

const props = defineProps({
  rifa: Object,
})

const rifa = ref(props.rifa)
</script>
<template>
  <div class="row bg-white pb-2 rifa_containerbuy">
    <div class="col-12 px-0 relative" >
      <img :src="rifa.configuration.banner_img" alt="" style="" class="img__rifa">
      <div class="badge__buy py-1 px-4" style=" " >
        Compra ahora!
      </div>
    </div>
    <div class="col-12 px-4">
      <div class="px-4 pt-3 pb-2 flex column justify-between" style=" flex-wrap: nowrap;">
        <div class="rifa__item--title w-full ellipsis text-h5 text-black text-center">{{rifa.title}}</div>
      </div>
      <div class="q-mt-sm">
        <q-linear-progress stripe rounded size="1.5rem" :value="0" color="primary" track-color="grey-8" style="border-radius: 2rem;" >
          <div class="absolute-full flex flex-center ">
            <q-badge color="white" text-color="black" class="text-bold" :label="'Vendidos: '+0+'%'" />
          </div>
        </q-linear-progress>
      </div>
      <div class="flex justify-end my-3">
        <q-btn  color="blur" size="0.72rem" unelevated class=" button__BuyRifa w-full" text-color="white" style="border-radius:0.5rem" >
          <div class="py-1">
            Comprar Tickets
          </div>
        </q-btn> 
        <q-btn  color="blur" size="0.72rem" unelevated class=" button__BuyRifa w-full mt-2" text-color="white" style="border-radius:0.5rem" >
          <div class="py-1">
            Consultar mis tickets
          </div>
        </q-btn> 
      </div>
    </div>
  </div>
</template>
<style lang="scss">
.rifa_containerbuy{
  box-shadow: 0px 1px 5px 0px #8f8f8f;
  border-radius: 1rem;
  overflow: hidden;
  border: 1px solid #585858;
}
.text-blur{
  color: #39e1d2!important;

}
.bg-blur{
  background-color: #39e1d2!important;
}
.img__rifa{


  height: max-content;
  object-fit: cover;
  max-height: 25rem;
  width: 100%;
}
.button__BuyRifa{
  width: 100%;
}
.badge__buy{
  font-weight:bold;
  border-radius:0.5rem; background:#39e1d2; width:fit-content;
  display: flex;
  font-size: 1rem;
  color: rgb(255, 255, 255);
  position: absolute;
  top: 0.7rem;
  right: 0.2rem;
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