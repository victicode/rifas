<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
import conditionsAndTermsModal from '@/components/client/conditionsAndTermsModal.vue';
import multipleRifaActive from '@/components/client/multipleRifaActive.vue';
const rifaStore = useRifaStore()
const rifasActive = ref([])
const screenDectectd = window.screen.width < 780 ? 1 : 5
const getActiveRifas = () =>{
  rifaStore.getRifasActive()
  .then((response) => {
    rifasActive.value = response.data
  })
}
const slide = ref(1)
const slidex = ref(1)

onMounted(() => {
  getActiveRifas()
})
</script>

<template>
  <div class="">
    <div class="mb-2 mt-2 text-center text-white text-h5 text-bold bg-black">
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
    <div v-if="rifasActive.length > 0" class="row mt-0 px-4 md:justify-center justify-start">
      <multipleRifaActive v-for="rifa in rifasActive" :rifa="rifa" :key="rifa.id" class="my-4 md:my-0 md:mt-4 col-12 col-md-3 md:px-3 " />
    </div>
    <div>

    </div>
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
    <conditionsAndTermsModal />
  </div>
</template>
<style lang="scss">
</style>