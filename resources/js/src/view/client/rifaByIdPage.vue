<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

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
onMounted(() => {
  getRifaById()
})
</script>
<template>
  <div class="px-12">
    <div v-if="loading">
      <div class="row mt-8">
        <div class="col-md-6">
          <img :src="rifa.configuration.banner_img" alt="">
        </div>
        <div class="col-md-6"></div>
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