<script setup>
import { ref } from 'vue';
import numberUtils from '@/utils/numberUtils.js';
import { useRouter } from 'vue-router';

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
  <div>
    <div class="row bg-white pb-1 rifa_containerbuy">
      <div class="col-12 px-0 relative" style="height:68%" >
        <img :src="rifa.configuration.banner_img" alt="" style="" class="img__rifa">
        <div class="badge__buy py-1 px-4" style=" " >
          Compra ahora!
        </div>
      </div>
      <div class="col-12 px-4" style="height:32%">
        <div class="px-4 pt-1 pb-2 flex column justify-between" style=" flex-wrap: nowrap;">
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
          <q-btn  color="blur" size="0.72rem" unelevated class=" button__BuyRifa w-full" text-color="white" style="border-radius:0.5rem"  @click="goTo('rifa', rifa.id)">
            <div class="py-1 text-subtitle2">
              Comprar Tickets
            </div>
          </q-btn> 
          <q-btn  color="blur" size="0.72rem" unelevated class=" button__BuyRifa w-full mt-2" text-color="white" style="border-radius:0.5rem" @click="goTo('finder', rifa.id)">
            <div class="py-1 text-subtitle2">
              Consultar mis tickets
            </div>
          </q-btn> 
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss">
.rifa_containerbuy{
  height: 37rem;
  box-shadow: 0px 1px 5px 0px #2b2b2b;
  border-radius: 1rem;
  overflow: hidden;
  border: 1px solid #2b2b2b;
}

.img__rifa{


  height: 100%;
  object-fit: cover;
  
  width: 100%;
}
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
.badge__buy{
  font-weight:bold;
  border-radius:0.5rem; 
  background:#aa13a4; width:fit-content;
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