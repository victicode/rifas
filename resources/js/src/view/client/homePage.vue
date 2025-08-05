<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
import conditionsAndTermsModal from '@/components/client/conditionsAndTermsModal.vue';
import multipleRifaActive from '@/components/client/multipleRifaActive.vue';
import benefitStadistics from '@/components/client/benefitStadistics.vue';
import FAQ from '@/components/client/FAQ.vue';
import actualRifaBuy from '@/components/client/actualRifaBuy.vue';
import socialLinks from '@/components/client/socialLinks.vue';


const rifaStore = useRifaStore()
const rifasActive = ref([])
const screenDectectd = window.screen.width < 780 ? 1 : 5
const ready = ref(false)
const getActiveRifas = () =>{
  ready.value = false
  rifaStore.getRifasActive()
  .then((response) => {
    rifasActive.value = response.data
    setTimeout(() => {
      ready.value = true
    }, 500);
  })
}
const slide = ref(1)
const slidex = ref(1)


onMounted(() => {
  getActiveRifas()
})
</script>

<template>
  <div class=" pb-0" style="position: relative; background-position: -3rem 0rem;" >
  <!-- <div class=" " :style="`background:url('${logo}')`" style="position: relative; background-position: -3rem 0rem;" > -->

    <!-- <div class="overflowBody"/> -->

    <div style="position: relative; z-index: 2;">
      <div class="mb-2 mt-0 text-center text-white text-h5 text-bold bg-black">
        <q-carousel
          animated
          v-model="slide"
          infinite
          :autoplay="4000"
          :transition-duration="2000"
          transition-prev="slide-right"
          transition-next="slide-left"
          style="height: fit-content; background-color: black;"
        >
          <q-carousel-slide :name="1" class="q-py-xs">
            <div class="flex items-center justify-center item__slide" style="    " >
              <div class="mx-5" v-for="j in screenDectectd" :key="j" >Nuestras Rifas actuales</div>
            </div>
          </q-carousel-slide>
          <q-carousel-slide class="q-py-xs" :name="2" >
            <div class="flex items-center justify-center item__slide" >
              <div class="mx-5" v-for="j in screenDectectd" :key="j" >!!GUSTOO!!</div>
            </div>
          </q-carousel-slide>
        </q-carousel>
      </div>
      <template v-if="ready">
        <div v-if="rifasActive.length > 0" class="row mt-0 px-4 md:justify-center justify-start">
          <multipleRifaActive v-for="rifa in rifasActive" :rifa="rifa" :key="rifa.id" class="my-4 md:my-0 md:mt-4 col-12 col-md-3 md:px-3 " />
        </div>
        <div v-else  class="py-10">
          <div class="text-h5 text-black text-bold text-center">
            No hay rifas activas 😪😪
          </div>
          <div class="text-h6 text-black text-bold text-center">
            Puedes volver en breve que tendremos mas sorpresas para ti🤯
          </div>
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

      <div class="mt-4 text-center text-white text-h5 text-bold bg-black">
        <q-carousel
          animated
          v-model="slidex"
          infinite
          :autoplay="4000"
          :transition-duration="2000"
          transition-prev="slide-left"
          transition-next="slide-right"
          style="height: fit-content; background-color: black;"
        >
          <q-carousel-slide :name="2" class="q-py-xs">
            <div class="flex items-center justify-center item__slide" style="    " >
              <div class="mx-5" v-for="j in screenDectectd" :key="j" >Nuestras Rifas actuales</div>
            </div>
          </q-carousel-slide>
          <q-carousel-slide class="q-py-xs" :name="1" >
            <div class="flex items-center justify-center item__slide" >
              <div class="mx-5" v-for="j in screenDectectd" :key="j" >!!GUSTOO!!</div>
            </div>
          </q-carousel-slide>
        </q-carousel>
      </div>
    </div>
    <benefitStadistics />
    <actualRifaBuy :rifa="rifasActive[0]" v-if="rifasActive.length > 0"/>
    <FAQ />
    <conditionsAndTermsModal />
    <socialLinks />
  </div>
</template>
<style lang="scss">
.overflowBody{
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
  background: rgba(46, 46, 46, 0.185);
  backdrop-filter: blur(0.08rem);
}
</style>

