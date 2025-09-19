<script setup>
import { useRoute } from 'vue-router';
import { onMounted, ref } from 'vue';
import { useClientStore } from '@/services/store/client.store';
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net';
import numberUtils from '@/utils/numberUtils.js';

import viewClientModal from '@/components/admin/client/viewClientModal.vue';
import deleteClientModal from '@/components/admin/client/deleteClientModal.vue';
import stadisticsClientModal from '@/components/admin/client/stadisticsClientModal.vue';
DataTable.use(DataTablesLib);

const lastPage = ref(1);
const actualPage = ref(1)
const route = useRoute()
const search = ref('')
const numberFormat = numberUtils.numberFormat
const searchType = ref(1)
const clients = ref([])
const clientStore = useClientStore()
const loading = ref(true) 
const showModal = ref('')
const selectedClient = ref({})
const closeModal = () => {
  showModal.value = ''
}
const optionsTable = { 
  scrollX: false,
  columnDefs: [
    { 
      className:'text-center',
      targets: 0,
      visible: true,
      render: function (data, type, full, meta) {
        return full.name
      }
    },
    { 
      className:'text-center',
      targets: 1,
      visible: true,
      render: function (data, type, full, meta) {

        return numberFormat(full.ci)
      }
    },
    {
      className:'text-center',
      targets: 2,
      sorteable:false,
      render: function (data, type, full, meta) {
       
        return full.email
      }
    },
    {
      className:'text-center',
      targets: 3,
      sorteable:false,
      render: function (data, type, full, meta) {
       
        return full.phone
      }
    },
    {
      // Actions
      className:'text-center',
      targets: 4,
      orderable: false,
      render: function (data, type, full, meta) {
        let view = ` `
        view += `
          <button data-client="${full.id}" class="viewClient q-btn q-btn-item non-selectable no-outline q-btn--flat q-btn--round text-black q-btn--actionable q-focusable q-hoverable mx-0" tabindex="0" type="button">
            <span data-client="${full.id}" class="q-focus-helper" tabindex="-1"></span>
            <span data-client="${full.id}" class="q-btn__content text-center col items-center q-anchor--skip justify-center row">
              <i data-client="${full.id}" class="q-icon notranslate material-icons" aria-hidden="true" role="img">visibility</i>
            </span>
          </button>
          <button data-client="${full.id}" class="reportClient q-btn q-btn-item non-selectable no-outline q-btn--flat q-btn--round text-black q-btn--actionable q-focusable q-hoverable mx-0" tabindex="0" type="button">
            <span data-client="${full.id}" class="q-focus-helper" tabindex="-1"></span>
            <span data-client="${full.id}" class="q-btn__content text-center col items-center q-anchor--skip justify-center row">
              <i data-client="${full.id}" class="q-icon notranslate material-icons" aria-hidden="true" role="img">bar_chart</i>
            </span>
          </button>
          <button data-client="${full.id}" class="deleteClient q-btn q-btn-item non-selectable no-outline q-btn--flat q-btn--round text-black q-btn--actionable q-focusable q-hoverable mx-0" tabindex="0" type="button">
            <span data-client="${full.id}" class="q-focus-helper" tabindex="-1"></span>
            <span data-client="${full.id}" class="q-btn__content text-center col items-center q-anchor--skip justify-center row">
             <i data-client="${full.id}"  class="q-icon notranslate material-icons" aria-hidden="true" role="img">delete</i>
            </span>
          </button>
          `
        return view
      }
    },
  ],
  language: {
    sLengthMenu: '_MENU_',
    search: '',
    searchPlaceholder: 'Buscar...',
    processing: "Procesando...",
    lengthMenu: "Mostrar _MENU_ registros",
    zeroRecords: "No se encontraron resultados",
    emptyTable: "Ningún dato disponible en esta tabla",
    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
    infoFiltered: "(filtrado de un total de _MAX_ registros)",
    info: "Mostrando _END_ de _TOTAL_ registros",
    paginate: {
      next: "Siguiente",
      previous: "Anterior"
    },
    
  },
  pageLength: 15,
  order:[9, 'asc'],
  dom:
  '<"mb-3" t>',													
}

const activeOptionsTable = () => {
  document.querySelectorAll('.viewClient').forEach( item => {
    item.addEventListener('click', event => {
      selectedClient.value = clients.value.find((item) => item.id == event.target.dataset.client)
      showModal.value = 'view'
    })	
  })
  document.querySelectorAll('.updateClient').forEach( item => {
    item.addEventListener('click', event => {
      selectedClient.value = clients.value.find((item) => item.id == event.target.dataset.client)
      showModal.value = 'update'
    })	
  })
  document.querySelectorAll('.reportClient').forEach( item => {
    item.addEventListener('click', event => {
      selectedClient.value = clients.value.find((item) => item.id == event.target.dataset.client)
      showModal.value = 'report'
    })	
  })
  document.querySelectorAll('.deleteClient').forEach( item => {
    item.addEventListener('click', event => {
      selectedClient.value = clients.value.find((item) => item.id == event.target.dataset.client)
      showModal.value = 'delete'
    })	
  })
}
const getClients = () => {
  const data = {
    search: search.value,
    searchType: searchType.value,
    page:actualPage.value
  }
  clientStore.getPaginationClients(data)
  .then((response) => {
    lastPage.value = response.data.last_page
    loading.value = false
    clients.value = response.data.data
    setTimeout(() => {
      
      activeOptionsTable()
    }, 1000);
  })
}

onMounted(() => {
  getClients()
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
          Clientes
        </h4>
      </div>
    </div>
    <section id="filterAndSearch" class="w-full  px-4 py-2 md:py-3 mt-4">
      <div class="">
        <div class=" mb-1 px-2 md:px-0">
          <q-input
            v-model="search"
            label="Busqueda"
            placeholder="Cédula de identidad / Nombre completo / Correo electrónico"
            class=" searchClientForm__input"
            @update:model-value="getClients()"
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
               <q-radio v-model="searchType" :val="1" class="text-black" label="CI de cliente" />
             </div>
              <div class="col-md-2 col-6">
                <q-radio v-model="searchType" :val="2" class="text-black" label="Nombre" />
              </div>
              <div class="col-md-2 col-12">
                <q-radio v-model="searchType" :val="3" class="text-black" label="Correo electrónico" />
              </div>
            </div>
        </div>
      </div>
      <div>

      </div>
    </section>
    <section id="tablePays" class="p-0  md:mt-8 mt-4 relative">
        <DataTable class="display table_pay" :options="optionsTable" :data="clients" >
          <thead class="tablePayHead">
            <tr>
              <th>Nombre <b style="opacity:0; text-align:center">nnnnnnnnnn</b></th>
              <th>C.I. <b style="opacity:0; text-align:center">nnn</b></th>
              <th>Correo</th>
              <th>Contacto<b style="opacity:0; text-align:center">nnnn</b></th>
              <th>Acción<b style="opacity:0; text-align:center">nnnnnnnnnnn</b></th>
            </tr>
          </thead>
        </DataTable>
        <div class="pb-3 px-8 flex justify-end">
          <q-pagination
            v-model="actualPage"
            color="primary"
            outline
            active-design="unelevated"
            active-color="primary"
            active-text-color="white"
            :max="lastPage"
            :max-pages="6"
            boundary-numbers
            @update:model-value="getClients()"
            gutter="0.5rem"
          />
        </div>
        <div class="loader_table flex flex-center" v-if="loading">
          <q-spinner-tail
            color="white"
            size="4rem"
          />
        </div>
    </section>

    <template v-if="Object.values(selectedClient).length > 0">
      <viewClientModal :dialog="(showModal == 'view')"  :client="selectedClient"   @closeModal="closeModal()" />
      <deleteClientModal :dialog="(showModal == 'delete')"  :client="selectedClient"   @closeModal="closeModal() " @updateList="getClients()" />
      <stadisticsClientModal :dialog="(showModal == 'report')"  :client="selectedClient"   @closeModal="closeModal() "  />
    </template>
  </div>
</template>

<style lang="scss">
.loader_table{
  position: absolute; 
  background: rgba(0, 0, 0, 0.384); 
  left:0; 
  top:0; 
  right: 0; 
  bottom: 0;
  border-radius: 1rem;

}
.table_pay{

  border-top-left-radius: 1rem;
  border-top-right-radius: 1rem;

  overflow: hidden;
  & td{
    font-size: 0.96rem;
    font-weight: 700;
    color: rgb(70, 70, 70);

  }
  
}
.tablePayHead{
  & th {
    background: black;
    color: white;
    text-align: center!important;
  }
}
.searchClientForm__input {

  &.quantity input{
    font-size: 1.5rem;
    text-align: center;
    
  }
  & input{
    padding-bottom: 0px!important;
  }
  & .q-field__label{
    transform: translateY(11%)
  }
  &.quantity.q-field--focused .q-field__label, &.quantity.q-field--float .q-field__label{
    z-index: 100;
    background: white!important;
    font-weight: 600;
    width: max-content;
    padding: 0px 10px;
    font-size: 0.8rem;
    text-align: center;
    transform: translateY(-110%) !important;
  }
  &.q-field--focused .q-field__label, &.q-field--float .q-field__label{
    z-index: 100;
    background: white!important;
    font-weight: 600;
    width: max-content;
    padding: 0px 10px;
    font-size: 0.8rem;
    transform: translateY(-110%) translateX(-0.5rem) !important;
    border: 0px;
  }
  
  & .q-field__native{
    padding-top: 15px!important;
    font-weight: 600;
  }
  & .q-field__append{
    transform: translateY(5%)
  }
}

#filterAndSearch{
  background: white;
  border-radius: 1rem;
  box-shadow: 0px 0px 10px 0px rgb(218, 218, 218);
}
#tablePays{
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0px 0px 10px 0px rgb(218, 218, 218);
  & .datatable{
    overflow: auto;
  }
  & .dt-info{
    font-size: 0.9rem;
    font-weight: 700;
  }
  & .dt-paging-button{
    &:not(.next):not(.previous){
      border-radius: 5px;
      background: transparent;
      font-size: 1.2rem;
      color: black!important;
      border: 1px solid rgb(46, 46, 46);
      padding:0.1rem 0.5rem ;
    }

    &.current{
      background: black;
      font-size: 1.2rem;
      color: white!important;
    }
    &.next, &.previous {
      font-size: 0.95rem;
      color: black!important;
      font-weight: 700;
    }
  }
}
</style>