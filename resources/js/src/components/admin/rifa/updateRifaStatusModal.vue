<script setup>
import { onMounted, ref, watch } from 'vue';
import { Notify } from 'quasar'
import { useRifaStore } from '@/services/store/rifas.store';

  const rifaStore = useRifaStore()
  const props = defineProps({
    dialog: Boolean,
    rifa: Object,
  })

  const emit = defineEmits(['updateList', 'closeModal'])
  const step = ref(1)
  const loading = ref(false);
  const dialog = ref(props.dialog);

  const rifa   = ref(props.rifa)
  const actualStatus = ref(null)
  const options = [
    {
      title:'Activa',
      value:1
    },
    {
      title:'Finalizada',
      value:0
    }
  ]
  const loadingShow = (state) => {
    loading.value = state;
  }

  const hideModal = () => {
    cleanForm()
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

  const cleanForm = () => {
    rifa.value = [];
    step.value = 1;
  }

  const updateRifaStatus = () => {
    loadingShow(true)
    const data = {
      status: actualStatus.value.value
    }
    rifaStore.updateStatus(rifa.value.id, data)
    .then((response) => {
      
      loadingShow(false)
      showNotify('positive', 'Estado de rifa actualizado')

      updateList();
    })
    .catch((response) => {
      loadingShow(false)
      showNotify('negative', response)
    })
  }

  const formatRifa = () => {
    rifa.value = props.rifa
    actualStatus.value = {
      title:props.rifa.status_label,
      value:props.rifa.status
    }
  }
  

  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
    formatRifa()
  });

  onMounted(() => {
    formatRifa()
  })
  
  

</script>
<template>
   <q-dialog v-model="dialog" class="updateRifaStatusDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document" style="border-radius:1rem">
        <div class="close__button">
          <q-btn round color="primary" icon="close" @click="hideModal()" />
        </div>
        <q-card-section>
          <div class="text-h5 text-center text-bold">
            Cambiar Estado
          </div>
        </q-card-section>
        <section class="content__modalSection">
          <div class="flex justify-center q-pb-sm">

            <q-chip :color="rifa.status == 1 ? 'positive' : 'negative'" text-color="white"  class="">
              <div class="px-3">
                {{rifa.status_label}}
              </div>
            </q-chip>
          </div>
          <q-card-section class="q-pt-none q-px-sm">
           <q-form
              class="md:px-5"
              style="overflow: hidden; "
              @submit="updateRifaStatus()"
            >
              <div class="px-2">
     
                <div class="row my-3">
                  <div class="col-md-6 col-12 md:pr-2 mb-3 md:mb-0">
                    <q-select
                      outlined
                      option-value="value"
                      option-label="title"
                      behavior="menu"
                      color="primary"
                      :options="options"
                      v-model="actualStatus"
                      label="Estado de la rifa"
                      class=" updateRifaStatusForm__input"
                    />
                  </div>
                </div>
              </div>
              <div class="flex justify-end mt-5">
                <q-btn label="Cerrar" color="negative"  class="q-mx-sm" @click="hideModal()" />
                <q-btn label="Guardar"  color="black" type="submit" :loading="loading"/>
              </div>
            </q-form>
          </q-card-section>
        </section>

      </q-card>
    </q-dialog>
</template>
<style>

</style>
<style lang="scss">

.q-item--active{
  & .q-item__label{

    color: goldenrod;
  }
}
.updateRifaStatusForm__input {
  & .q-field__control {
    border-radius: 10px!important;

  }
  & .q-field__label{
    transform: translateY(11%)
  }
  &.q-field--auto-height.q-field--labeled .q-field__control-container{
    padding-top: 0px;
  }
  &.q-field--focused .q-field__label, &.q-field--float .q-field__label{
    z-index: 100;
    background: white!important;
    font-weight: 600;
    max-width: 133%;
    padding: 0px 10px;
    transform: translateY(-125%) translateX(0%) scale(0.75)!important;
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