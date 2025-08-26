<script setup>

import { ref, watch} from 'vue';
import { useClientStore } from '@/services/store/client.store';
import utils from '@/utils/numberUtils.js';
import moment from 'moment';

  const props = defineProps({
    dialog: Boolean,
    client: Object
  })

  const emit = defineEmits(['updateList', 'closeModal'])
  const client = ref(props.client)
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const clientStore = useClientStore()
  const numberFormat = utils.numberFormat

  
  const hideModal = () => {
    emit('closeModal')
  }


  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });

  watch(() => props.client, (newValue) => {
    client.value = newValue
  });
const formatPhone = () => {
  let string = client.value.phone
  let modifiedSentence = string.replace('-', '')

  return '58'+modifiedSentence.substring(1)
}

</script>
<template>
   <q-dialog v-model="dialog" class="viewClientDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <div
          class="md:px-5 pb-5 clienStatic__cont"
          style="height: 100%; "
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                Estadistica de cliente
              </div>
            </q-card-section>
            <section class="row">
               <div class="col-6 text-subtitle2 text-bold px-4 py-2" >
                  Cliente: <br>
                  {{ client.name }}
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-2">
                  Contacto: <br>
                  {{client.phone}}
                </div>
            </section>
            <section class="content__modalSectionRifa md:mt-5 mt-5 px-2">
              <div class="row items-center" style="border: 1px solid lightgray; border-radius: 0.5rem;">
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                  Total de rifas  jugadas
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                   {{client.rifasParticipate}} {{ client.rifasParticipate > 1 ? 'rifas' : 'rifa' }}
                </div>
                <div class="col-6 text-subtitle2 text-bold  px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                  Total de pagos:
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end  px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                 {{ client.orders.length }} pagos
                </div>
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                  Total de tickets :
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                  {{ client.totalTicketsBuy }} tickets
                </div>
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                  Total de ticket ganador:
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                   {{ client.totalWin.length }} {{ client.totalWin.length > 1 ? 'veces' : 'vez' }}
                </div>
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                  Mayor compra
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                   {{ client.mostQuantity }} tickets
                </div>
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-right: 1px solid lightgray;">
                  Última rifa participada
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 " style="">
                   {{ client.orders[client.orders.length-1] ? client.orders[client.orders.length-1].rifa.title : '---' }} <br>
                   {{
                    client.orders[client.orders.length-1] 
                    ? moment(client.orders[client.orders.length-1].created_at).format('DD/MM/YYYY') 
                    : '----'
                  }}
                </div>
                

              </div>
            </section>
          </div>
          <section>
            <div class="flex justify-center mt-5">
              <q-btn label="Cerrar"  color="black"  class="q-mx-sm " style="width: 80%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="hideModal()" />
            </div>
          </section>
        </div>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">

.contactWhatsapp{

  width: 1.3rem;
  height: 1.3rem;

  border-radius: 35%;
  display: flex;
  background: #26b83e;
  box-shadow: 0px 2px 5px 0px #adadad;
  justify-content: center;
  align-items: center;
}
.statusInput.q-field--auto-height.q-field--labeled .q-field__control-container{
  padding-top: 10px;
}
.viewClientDialog{
  margin-left: 0%;
  min-width: 100%!important;
  max-width: 800px!important; 
  overflow: visible!important;
  position: relative;
  & .dialog_document{
    border-radius: 0px !important;
    height: 100%;
    max-height: 100vh!important;
  }
  & .q-dialog__inner--minimized{
    padding: 0px;
  }
}
.clienStatic__cont{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.content__modalSectionRifa{
    overflow: auto;
    max-height: max-content;

}




</style>