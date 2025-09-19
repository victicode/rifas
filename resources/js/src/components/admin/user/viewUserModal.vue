<script setup>
import { ref, watch} from 'vue';

  const props = defineProps({
    dialog: Boolean,
    user: Object
  })

  const emit = defineEmits(['closeModal'])
  const user = ref(props.user)
  const dialog = ref(props.dialog);

  const hideModal = () => {
    emit('closeModal')
  }

  
  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });

  watch(() => props.user, (newValue) => {
    user.value = newValue
  });


</script>
<template>
   <q-dialog v-model="dialog" class="viewClientDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <div
          class="md:px-5 pb-5 user__cont"
          style="height: 100%; "
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                Datos de usuario
              </div>
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-5 mt-5 px-2">
              <div class="row" style="border: 1px solid lightgray; border-radius: 0.5rem;">
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                   Nombre:
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                   {{user.name}}
                </div>
                <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                  Correo:
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                  {{ user.email }}
                </div>
                <!-- <div class="col-6 text-subtitle2 text-bold px-4 py-6" style="border-bottom: 1px solid lightgray; border-right: 1px solid lightgray;">
                  Password:
                </div>
                <div class="col-6 text-subtitle2 text-bold text-end px-4 py-6" style="border-bottom: 1px solid lightgray; ">
                  {{ user.email }}
                </div> -->

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
.user__cont{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.content__modalSectionRifa{
    overflow: auto;
    max-height: max-content;

}




</style>