<script setup>
import { Notify } from 'quasar'
import { ref, watch} from 'vue';
import { useClientStore } from '@/services/store/client.store';
import whatsapp from '@/assets/images/logo/WhatsApp2.webp'
import utils from '@/utils/numberUtils.js';

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

  const loadingShow = (state) => {
    loading.value = state;
  }
  
  const hideModal = () => {
    emit('closeModal')
  }
  const updateList = () => {
    emit('updateList')
    hideModal()
  }

  const showNotify = (type,text) => {
    Notify.create({
      color:type,
      message: text,
      timeout:2000
    })
  }

  const formatPhone = () => {
    let string = client.value.phone
    let modifiedSentence = string.replace('-', '')

    return '58'+modifiedSentence.substring(1)
  }
  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });

  watch(() => props.client, (newValue) => {
    client.value = newValue
  });


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
                Datos de cliente
              </div>
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-5 mt-5 px-2">
              <div class="row" style="border: 1px solid lightgray; border-radius: 0.5rem;">
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                   Nombre:
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                   {{client.name}}
                </div>
                <div class="col-6 text-subtitle2 text-bold  px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                  CI:
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end  px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                 {{ numberFormat(client.ci) }}
                </div>
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                  Correo:
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                  {{ client.email }}
                </div>
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-right: 1px solid lightgray;">
                  Contacto:
                </div>
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="">
                  <a 
                    class="flex items-center justify-end" 
                    target="_blank" 
                    :href="'https://api.whatsapp.com/send?phone='+formatPhone()+'&text=Felicidades%2C%20eres%20el%20ganador!!!!'" >
                    <div class="text-decoration-underline" style="text-decoration:underline">
                      {{client.phone}}
                    </div>

                    <div class="contactWhatsapp ml-2 mb-1">
                      <img :src="whatsapp" style="width:65%;" class="" alt="">
                    </div>
                  </a>
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