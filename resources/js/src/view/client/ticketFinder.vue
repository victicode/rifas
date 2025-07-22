<script setup>
import { useRoute } from 'vue-router';
import { ref } from 'vue';
import { useOrderStore } from '@/services/store/order.store';
import moment from 'moment';
import numberUtils from '@/utils/numberUtils.js';

const route = useRoute()
const search = ref('')
const numberFormat = numberUtils.numberFormat
const searchType = ref(1)
const orders = ref([])
const orderStore = useOrderStore()


</script>
<template>
  <div>
    <section id="filterAndSearch" class="w-full  px-4 py-2 md:py-3 mt-4">
      <div class="">
        <div class=" mb-1 px-2 md:px-0">
          <q-input
            v-model="search"
            label="Busqueda"
            placeholder="N° de orden / Cédula de identidad / Referencia de pago / Monto pagado"
            class=" searchOrderForm__input"
          >
            <template v-slot:append>
              <q-icon name="search" color="primary" />
            </template>
          </q-input>
        </div>
        <div class="mt-4">
          <div class="text-black text-subtitle1 text-bold">
            Buscar por:
          </div>
           <div class="row">
              <div class="col-md-2 col-6">
                <q-radio v-model="searchType" :val="1" class="text-black" label="N° de orden" />
              </div>
              <div class="col-md-2 col-6">
                <q-radio v-model="searchType" :val="2" class="text-black" label="CI de cliente" />
              </div>
              <div class="col-md-2 col-6">
                <q-radio v-model="searchType" :val="3" class="text-black" label="Referencia de pago" />
              </div>
              <div class="col-md-2 col-6">
                <q-radio v-model="searchType" :val="4" class="text-black" label="Monto pagado" />
              </div>
            </div>
        </div>
      </div>
      <div>

      </div>
    </section>
  </div>
</template>