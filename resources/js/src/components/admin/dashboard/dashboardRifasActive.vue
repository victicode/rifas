<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import moment from 'moment';
const rifaStore = useRifaStore()
const rifas = ref([])
const router = useRouter()
const ready = ref(false)

const getActiveRifas = () => {
  rifaStore.getRifasActive()
  .then((response) => {
    rifas.value = response.data
  })
}
onMounted(() => {
  getActiveRifas()
})
</script>
<template>
<div class="col-md-5 col-12 md:px-1">
  <div class="card__dash ">
    <div class="text-white text-h6 py-2 text-bold text-center bg-black">
      Rifas Activas
    </div>
    <div class=" mt-2 pb-5 " v-if="rifas.length > 0" >
      <table style="width: 100%;">
        <thead>
          <th class="text-black th_dash py-2 " style="width: 32%;">Nombre</th>
          <th class="text-black th_dash py-2 ">Se juega</th>
          <th class="text-black th_dash py-2 " style="width:18%">% vendido</th>
          <th class="text-black th_dash py-2 ">Compras por aprobar</th>
        </thead>
        <tbody class="py-8">
          <tr v-for="rifa in rifas" :key="rifa.id" class="" style="border-bottom: 1px solid darkgray;">
            <td class="td_dash text-black text-center text-bold py-4 px-1" style="width: 32%;">
              <div @click="router.push('/admin/rifas')" class="redirectItem">
                {{rifa.title}}
              </div>
            </td>
            <td class="td_dash text-black text-center text-bold py-4 px-1"> {{moment(rifa.due_date).format('DD/MM/YYYY')}}</td>
            <td class="td_dash text-black text-center text-bold py-4 px-1" style="width:16%">
              {{(rifa.soldTickets * 10).toFixed(2)}}%</td>
            <td class="td_dash text-black text-center text-bold py-4 px-1">
              <div @click="router.push('/admin/pays')" class="redirectItem">
                {{rifa.orders_pending.length}}
              </div>
            </td>
          </tr>
        </tbody>
  
      </table>
    </div>
  </div>
</div>
</template>
<style lang="scss">
.redirectItem{
  text-decoration: underline;
  cursor: pointer;
  color: rgb(3, 3, 101);
}
.th_dash{
  border-bottom: 1px solid darkgray;
  font-size: 0.85rem;
  width: 25%;
}
.td_dash{
  border-right: 1px solid darkgray;
  border-left: 1px solid darkgray;
  
  width: 25%;
  &:last-child{
    border-right: 0;
  }
  &:first-child{
    border-left: 0;
  }
}
</style>
