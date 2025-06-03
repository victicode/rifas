<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
const ready = ref(false)
const rifaStore = useRifaStore()
const actualPage = ref(1)
const rifas = ref([]) 
const getRifas = () => {
  ready.value = false

  const query = {
    page: actualPage.value
  }
  rifaStore.getRifas(query)
  .then((response) =>{
    rifas.value = response.data.data.data;
    setTimeout(() => {
      ready.value = true
    }, 2000);
  })
}
onMounted(() =>{
  getRifas()
})
</script>
<template>
  <div class="h-full">
    <div class="flex items-center justify-between ">
      <div class="flex items-center">
        <div class="icon_badge q-pa-sm flex items-center justify-center">
          <q-icon name="local_activity" color="white" size="2rem"/>
        </div>
        <h4 class="text-black font-bold ml-2" >
          Rifas
        </h4>
      </div>
      <div>
        <q-btn unelevated style="border-radius:0.4rem" icon="add" color="black" 
        class="q-py-sm mx-2" label="Agregar Rifa" no-caps />
      </div>
    </div>
    <template v-if="ready">
      <div class="flex column items-center justify-center h-full">
        <div class="flex items-center justify-center h-full">

          <template v-if="rifas.length > 0">
            <div v-for="rifa in rifas" :key="rifa.id" class="mx-5 rifa__item" style="" >
              <div style="height:5rem; width:5rem;">
  
              </div>
              <div class="px-4">
                <div class="rifa__item--title">{{rifa.title}}</div>
                <div></div>
                <div></div>
              </div>
            </div>
          </template>
          <template v-else>
            <div class="text-empty">
              No hay rifas creadas😢😢
            </div>
            <q-btn unelevated style="border-radius:0.4rem" icon="add" color="black" 
            class="q-py-md mt-5 mx-2" label="Agregar Rifa" no-caps />
          </template>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="flex column items-center justify-center h-full">
        <q-spinner-ball
          color="black"
          size="4rem"
        />
      </div>
    </template>
  </div>
</template>
<style lang="scss">
.icon_badge{
  height: 3rem;
  width: 3rem;
  border-radius: 0.3rem;
  background: black;
  border: 1px solid #7f7f7f;
}
.text-empty{
  font-size: 2.5rem;
  font-weight: 500;
  opacity: 1;
  color:black;
  text-align: center;
}
.rifa__item{
  box-shadow: 5px 0px 26px 0px #dfdfdf;
  display: flex;
  flex-direction: column;
  justify-content: center;
  width: 15rem; height: 20rem; 
  background:white;
  border-radius: 1.2rem;
  &--title{
    font-size:1.1rem; 
    font-weight:bold;
  }
}
</style>