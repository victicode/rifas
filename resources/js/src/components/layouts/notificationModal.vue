<script setup>
import { Notify } from 'quasar'
import { ref, watch} from 'vue';
import whatsapp from '@/assets/images/logo/WhatsApp2.webp'
import utils from '@/utils/numberUtils.js';

  const props = defineProps({
    dialog: Boolean,
    notifications: Object
  })

  const emit = defineEmits([ 'closeModal'])

  const dialog = ref(props.dialog);
  const notifications = ref(props.notifications);
  const hideModal = () => {
    emit('closeModal')
  }
  
  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });

  watch(() => props.client, (newValue) => {
    notifications.value = newValue
  });


</script>
<template>
   <q-dialog v-model="dialog" class="viewNotificationDialog" persistent backdrop-filter="blur(2px)">
      <q-card class="dialog_document" style="border-radius:1rem">
        <div
          class="md:px-5 pb-5 client__cont"
          style="height: 100%; "
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black pt-2 text-bold">
                Notificaciones
              </div>
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-5 mt-0 px-2">
              <div class="row" style="border-top: 1px solid lightgray; border-radius: 0.5rem;">
       

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
.viewNotificationDialog{
  margin-left: 0%;
  min-width: 10%!important;

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
.client__cont{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.content__modalSectionRifa{
    overflow: auto;
    max-height: max-content;

}
@media (max-width: 768px){

.viewNotificationDialog{
  margin-left: 0%;
  min-width: 10%!important;

  overflow: visible!important;
  position: relative;
  & .dialog_document{
    border-radius: 1rem !important;
    height: 95%;
    max-height: 100vh!important;
    min-width: 95%!important;
  }
  & .q-dialog__inner--minimized{
    padding: 0px;
  }

}
}



</style>