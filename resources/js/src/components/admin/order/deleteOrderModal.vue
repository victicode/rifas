<script setup>
import { Notify } from 'quasar'
import { ref, watch} from 'vue';
import { useOrderStore } from '@/services/store/order.store';

  const props = defineProps({
    dialog: Boolean,
    order: Object
  })

  const emit = defineEmits(['updateList', 'closeModal'])
  const order = ref(props.order)
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const orderStore = useOrderStore()


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

  const deleteOrder = () => {

    loadingShow(true)
 
    orderStore.deleteOrder(order.value.id)
    .then((response) => {
        setTimeout(() => {
          loading.value = false;
          showNotify('negative', 'Orden borrada con exito')
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

  watch(() => props.order, (newValue) => {
    order.value = newValue
  });


</script>
<template>
   <q-dialog v-model="dialog" class="deleteOrderDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 deleteOrder"
          style="height: 100%; "
          @submit="deleteOrder()"
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                Borrar orden #[{{ order.number }}]
              </div>
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-5 mt-0">
              <q-card-section class="q-pt-none q-px-sm ">
                <div class="px-2">
                   <div class="text-h6 text-center text-black text-bold">
                    ¿Seguro que deseas Borrar la orden #{{ order.number }}?
                  </div>
                  <div class="row mt-1 ">
                    
                    <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0 my-2 flex justify-between" v-for="(item, key) in order.data" :key="key">
                      <div style="font-size:1rem; font-weight:600">{{item.title}}</div>
                      <div style="font-size:1rem; font-weight:600">{{item.value}}</div>
                    </div>  
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
.deleteOrderDialog{
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
.deleteOrder{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.content__modalSectionRifa{
    overflow: auto;
    max-height: max-content;

}




</style>