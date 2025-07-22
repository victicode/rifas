<script setup>
import { onMounted, ref, watch} from 'vue';
import { Notify } from 'quasar'
import numberUtils from '@/utils/numberUtils.js';
import { useRouter } from 'vue-router';
import { useMethodPayStore } from '@/services/store/methodPay.store';

  const props = defineProps({
    dialog: Boolean,
    payMethod: Object
  })

  const emit = defineEmits(['updateList', 'closeModal'])

  const payMethod = ref([])
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const payMethodStore = useMethodPayStore()
  const statusOption = [
    {title:'Inactivo', value: 0 },
    {title:'Activo', value: 1 },

  ]
  const coinOptions = [
    {
      name:'Selecciona la moneda asociada',
      id:0
    },
    {
      name:'Bolivares',
      id:1
    },
    {
      name:'Dolares',
      id:2
    },
  ]
  const updateList = () => {
    emit('updateList')
    hideModal()
  }
  const loadingShow = (state) => {
    loading.value = state;
  }
  
  const hideModal = () => {
    emit('closeModal')
  }

  const showNotify = (type,text) => {
    Notify.create({
      color:type,
      message: text,
      timeout:2000
    })
  }
  const updatePayMethod = () => {

    loadingShow(true)
    const formData = new FormData()
    formData.append('name', payMethod.value.name);
    formData.append('status', payMethod.value.statusInput.value);
    formData.append('min_buy', payMethod.value.min_buy);

    const data = {
      data: formData,
      id: payMethod.value.id
    }

    payMethodStore.updatePayMethod(data)
    .then((response) => {
      if(response.code !==200) throw response

      showNotify('positive', 'Método de pago actualizado con exito')
      setTimeout(() => {
        loading.value = false
        updateList()
      },1000)
    })
    .catch((response) => {
      console.log(response)
      loadingShow(false)
      showNotify('negative', response)
    })
  }



  const formatPayMethod = () => {
    Object.entries(props.payMethod).forEach(([key, value]) => {
        payMethod.value[key] = value;
    
    }); 
    payMethod.value['statusInput'] = {
      title: payMethod.value.status_label,
      value: payMethod.value.status
    }
  }

  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
    formatPayMethod()
  });

  onMounted(() => {
    formatPayMethod()
  })

</script>
<template>
   <q-dialog v-model="dialog" class="createPayMethodDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 order__form"
          style="height: 100%; "
          @submit="updatePayMethod()"
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                Completa el formulario
              </div>
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-0 mt-0">
              <q-card-section class="q-pt-none q-px-sm ">
                <div class="px-2 ">
                    <div class="row mt-1 ">
                      <div class="col-12   mb-3 md:mt-0">
                        <q-input
                          v-model="payMethod.name"
                          label="Nombre del metodo de pago"
                          class=" createPayMethodForm__input"
                          :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                        />
                      </div>
                      <div class="col-12   mb-3 md:mt-0">
                        <q-select
                          v-model="payMethod.statusInput"
                          label="Estado"
                          option-value="value"
                          option-label="title"
                          behavior="menu"
                          color="primary"
                          :options="statusOption"
                          class="createPayMethodForm__input statusInput"
                        />
                      </div>
                    </div>
                  </div>
              </q-card-section>
            </section>
          </div>
          <section>
            <div class="flex justify-evenly mt-5">
              <q-btn label="Cerrar"  color="black"  class="q-mx-sm " style="width: 35%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="hideModal()" />
              <q-btn label="Actualizar"   color="blue-10" type="submit" style="width: 45%; border-radius: 0.8rem; padding: 0.7rem 0px;" :loading="loading"/>
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