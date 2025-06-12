<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
import conditionsAndTermsModal from '@/components/client/conditionsAndTermsModal.vue';
import multipleRifaActive from '@/components/client/multipleRifaActive.vue';
const rifaStore = useRifaStore()
const rifasActive = ref([])

const getActiveRifas = () =>{
  rifaStore.getRifasActive()
  .then((response) => {
    rifasActive.value = response.data
  })
}
onMounted(() => {
  getActiveRifas()
})
</script>

<template>
  <div class="">
    <div class="mt-3 text-center text-white text-h5 text-bold bg-black">
      Nuestras Rifas actuales
    </div>
    <div v-if="rifasActive.length > 0" class="row mt-3 px-4">
      <multipleRifaActive v-for="rifa in rifasActive" :rifa="rifa" :key="rifa.id" class="my-4 col-12 col-md-3 " />
    </div>
    <div>

    </div>
    <conditionsAndTermsModal />
  </div>
</template>