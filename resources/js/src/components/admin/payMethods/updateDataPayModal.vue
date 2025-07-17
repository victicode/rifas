<script setup>
import { onMounted, ref, watch} from 'vue';
import { Notify } from 'quasar'
import numberUtils from '@/utils/numberUtils.js';
import { useRouter } from 'vue-router';
import { useMethodPayStore } from '@/services/store/methodPay.store';

  const props = defineProps({
    dialog: Boolean,
    dataPay: Object
  })

  const emit = defineEmits(['updateList', 'closeModal'])

  const dataPay = ref([])
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const payMethodStore = useMethodPayStore()

  const statusOption = [
    {title:'Inactivo', value: 0 },
    {title:'Activo', value: 1 },

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
  const updateDataPay = () => {

    loadingShow(true)
    const formData = new FormData()
    formData.append('data_pay', JSON.stringify(dataPay.value.data));
    formData.append('status', dataPay.value.statusInput.value);

    const data = {
      data: formData,
      id: dataPay.value.id
    }

    payMethodStore.updateMethodData(data)
    .then((response) => {
      if(response.code !==200) throw response

      showNotify('positive', 'Datos de pagos actualizados con exito')
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

  const changeMethodData = () => {
    const structureMethodPay = [
      '',
      [
        {
          title: 'Banco',
          value: ''
        },
        {
          title: 'Teléfono',
          value: ''
        },
        {
          title: 'Documento',
          value: ''
        }
      ],
      [
        {
          title: 'Banco',
          value: ''
        },
        {
          title: 'Número de cuenta',
          value: ''
        },
        {
          title: 'Documento',
          value: ''
        },
        {
          title: 'Titular de la cuenta',
          value: ''
        },
      ],
      [
        {
          title: 'Ubicación de entrega',
          value: ''
        },
      ],
      [
        {
          title: 'Correo electrónico',
          value: ''
        },
        {
          title: 'Titular de la cuenta',
          value: ''
        },
      ],
      [
        {
          title: 'Banco',
          value: ''
        },
        {
          title: 'Número de cuenta',
          value: ''
        },
        {
          title: 'Documento',
          value: ''
        },
        {
          title: 'Titular de la cuenta',
          value: ''
        },
      ],
    ]
   formInputs.value =  structureMethodPay[payMethod.value.id]
  }



  const formatDataPay = () => {
    Object.entries(props.dataPay).forEach(([key, value]) => {
        dataPay.value[key] = value;
      
      // if(key !=='configuration'){
      //   dataPay.value[key] = value;
      // } else {
      //   rifa.value['configuration'] = []
      //   Object.entries(value).forEach(([key2, value2]) => {
      //     if(['auto_select', 'quantity_tickets', 'price', 'minimus_buy'].includes(key2)) rifa.value['configuration'][key2] = numberUtils.numberFormat(value2);
      //   })
      // }
    }); 
    dataPay.value['statusInput'] = {
      title: dataPay.value.status_label,
      value: dataPay.value.status
    }
  }

  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
    formatDataPay()
  });

  onMounted(() => {
    formatDataPay()
  })

</script>
<template>
   <q-dialog v-model="dialog" class="createPayMethodDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 order__form"
          style="height: 100%; "
          @submit="updateDataPay()"
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                Completa el formulario
              </div>
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-0 mt-0">
              <q-card-section class="q-pt-none q-px-sm ">
                <div class="px-2">
                  <div class="text-subtitle1 q-mt-none q-pt-none text-center text-stone-500">
                    Debes ingresar los datos que se solicitan
                  </div>
                  <div class="text-center mt-2">
                    <q-chip color="black" text-color="white"  >
                      <div class="px-2 md:px-1">
                        {{ dataPay.method_type.name }}
                      </div>
                    </q-chip>
                  </div>
                  <div class="row mt-4 ">
                    
                    <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0" v-for="(item, key) in dataPay.data" :key="key">
                      <q-input
                        v-model="item.value"
                        :label="item.title"
                        class=" createPayMethodForm__input"
                        :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                      />
                    </div>  
                    <div class="col-12 mb-3 md:mt-0">
                      <q-select
                        v-model="dataPay.statusInput"
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