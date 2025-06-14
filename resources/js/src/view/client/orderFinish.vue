<script setup>
import { onMounted, ref } from 'vue';


const loading = ref(false);

onMounted(() => {
  setTimeout(() => {
    loading.value = true
  }, 2000);
})
</script>
<template>

  <div class="h-5/6">
    <template v-if="loading">

      <div class="text-black mt-5 text-center text-h5"> 
        Pagina en construcción
      </div>
      
    </template>
    <template v-else>
      <div class="flex column items-center justify-center h-full q-py-sm">
        <q-spinner-tail
          color="black"
          size="6rem"
        />
      </div>
    </template>
  </div>
</template>