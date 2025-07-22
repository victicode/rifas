<script setup>
import { onMounted, ref, watch} from 'vue';
import { Notify } from 'quasar'
import numberUtils from '@/utils/numberUtils.js';
import { useRouter } from 'vue-router';
import { useMethodPayStore } from '@/services/store/methodPay.store';

  const props = defineProps({
    dialog: Boolean,
  })
  const numberFormat = numberUtils.numberFormat 
  const router = useRouter()

  const emit = defineEmits(['updateList', 'closeModal'])
  const step = ref(1)
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const payMethod = ref({
    name:'Selecciona un método de pago',
    id:0
  })
  const payMethodStore = useMethodPayStore()
  const optionsMethodPay = ref([])

  const getPayMethods = () => {
    payMethodStore.getPayMethods()
    .then((response) => {
      console.log(response)
      optionsMethodPay.value = [
        {
          name:'Selecciona un método de pago',
          id:0
        },
        ...response.data
      ]
    })
    .catch((response) => {
      console.log(response)
    })
  }
  const formInputs = ref({})


  const loadingShow = (state) => {
    loading.value = state;
  }
  
  const backButton = () => {
    step.value == 1 ? hideModal() : step.value--
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
    formInputs.value = {}
    payMethod.value = {
      name:'Selecciona un método de pago',
      id:0
    }
    step.value = 1;
  }
  const createDataPay = () => {

    loadingShow(true)
    const formData = new FormData()
    formData.append('data_pay', JSON.stringify(formInputs.value));
    formData.append('method_id', payMethod.value.id)
    payMethodStore.createMethodData(formData)
    .then((response) => {
      if(response.code !==200) throw response

      showNotify('positive', 'Datos de pagos creados con exito')

      setTimeout(() => {
        updateList()
        loading.value = false
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

  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });

  onMounted(() => {
    getPayMethods()
  })

</script>
<template>
   <q-dialog v-model="dialog" class="createPayMethodDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 order__form"
          style="height: 100%; "
          @submit="step == 2 ? createDataPay() : step++"
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                {{ step == 1 ? 'Selecciona el método de pago' : 'Completa el formulario' }}
              </div>
              
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-5 mt-0">
              <q-card-section class="q-pt-none q-px-sm ">
                <transition name="fade">
                  <template v-if="step==1">
                    <div class="px-2 ">
                      <div class="row mt-1 ">
                        <div class="col-12  md:pl-2 mb-3 md:mt-0">
                          <q-select
                            v-model="payMethod"
                            label="Selecciona el metodo de pago"
                            option-value="id"
                            option-label="name"
                            behavior="menu"
                            color="primary"
                            :options="optionsMethodPay"
                            class="createPayMethodForm__input"
                            :rules="[ (val, rules) => val.id != 0 || 'Debes seleccionar una rifa activa' ]"
                            @update:model-value="changeMethodData()"
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </transition> 
                <transition name="fade">
                  <template v-if="step==2">
                    <div class="px-2">
                      <div class="text-subtitle2  text-center text-stone-500">
                        Debes ingresar los datos que se solicitan
                      </div>
                      <div class="row mt-5 ">
                        <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0" v-for="(item, key) in formInputs" :key="key">
                          <q-input
                            v-model="item.value"
                            :label="item.title"
                            class=" createPayMethodForm__input"
                            :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                          />
                        </div>  
                      </div>
                    </div>
                  </template>
                </transition>
              </q-card-section>
            </section>
          </div>
          <section>
            <div class="flex justify-evenly mt-5">
              <q-btn :label="step == 1 ? 'Cerrar' : 'Volver' "  color="black"  class="q-mx-sm " style="width: 35%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="backButton()" />
              <q-btn :label="step !== 3 ? 'Siguiente' : 'Comprar' "   color="blur" type="submit" style="width: 50%; border-radius: 0.8rem; padding: 0.7rem 0px;" :loading="loading"/>
            </div>
          </section>
        </q-form>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">

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