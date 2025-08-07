<script setup>
import { Notify } from 'quasar'
import { ref, watch} from 'vue';
import { useClientStore } from '@/services/store/client.store';

  const props = defineProps({
    dialog: Boolean,
    client: Object
  })

  const emit = defineEmits(['updateList', 'closeModal'])
  const client = ref(props.client)
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const clientStore = useClientStore()


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

  const deleteDataPay = () => {

    loadingShow(true)


    clientStore.deleteClient(client.value.id)
    .then((response) => {
        setTimeout(() => {
          loading.value = false;
          showNotify('negative', 'Cliente borrado con exito')
          updateList()
        },1000)
    })
    .catch((response) => {
      console.log(response)
      loadingShow(false)
      showNotify('negative', response)
    })
  }

  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });

  watch(() => props.client, (newValue) => {
    client.value = newValue
  });


</script>
<template>
   <q-dialog v-model="dialog" class="createPayMethodDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 order__form"
          style="height: 100%; "
          @submit="deleteDataPay()"
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                Borrar cliente
              </div>
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-5 mt-0">
              <q-card-section class="q-pt-none q-px-sm ">
                <div class="px-2">
                   <div class="text-h6 text-center text-black">
                    ¿Seguro que deseas borrar a?
                  </div>
                  <div class="text-center mt-6">
                    <q-chip color="black" text-color="white"  >
                      <div class="px-2 md:px-1">
                        {{ client.name }}
                      </div>
                    </q-chip>
                  </div>
                </div>
              </q-card-section>
            </section>
          </div>
          <section>
            <div class="flex justify-evenly mt-5">
              <q-btn label="Cerrar"  color="black"  class="q-mx-sm " style="width: 55%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="hideModal()" />
              <q-btn label="Borrar"  color="negative" type="submit" style="width: 35%; border-radius: 0.8rem; padding: 0.7rem 0px;" :loading="loading"/>
            </div>
          </section>
        </q-form>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">
.statusInput.q-field--auto-height.q-field--labeled .q-field__control-container{
  padding-top: 10px;
}
.createPayMethodDialog{
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
.order__form{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.content__modalSectionRifa{
    overflow: auto;
    max-height: max-content;

}

.q-item__label{

  color: black!important;
}
.q-item--active{
  & .q-item__label{

    color: goldenrod!important;
  }
}
.createPayMethodForm__input {
  &.q-field--standard.q-field--readonly .q-field__control:before {
    
    border-bottom-style:solid!important

  }
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
    width: 100%;
    padding: 0px 10px;
    font-size: 0.8rem;
    text-align: center;
    transform: translateY(-110%) !important;
  }
  &.q-field--focused .q-field__label, &.q-field--float .q-field__label{
    z-index: 100;
    background: white!important;
    font-weight: 600;
    width: 100%;
    padding: 0px 10px;
    font-size: 0.8rem;
    transform: translateY(-110%) translateX(-0.5rem) !important;
  }
  
  & .q-field__native{
    padding-top: 15px!important;
    font-weight: 600;
  }
  & .q-field__append{
    transform: translateY(5%)
  }
}

</style>