<script setup>
import { onMounted, ref, watch, useTemplateRef} from 'vue';
import { Notify } from 'quasar'
import numberUtils from '@/utils/numberUtils.js';
import { useOrderStore } from '@/services/store/order.store';
import { useRouter } from 'vue-router';
  const numberFormat = numberUtils.numberFormat 
  const router = useRouter()
  const props = defineProps({
    dialog: Boolean,
    rifa: Object
  })

  const emit = defineEmits(['orderSuccessful', 'closeModal'])
  const step = ref(1)
  const orderStore = useOrderStore()
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const rifa = props.rifa 
  const dataPaySelect = ref([])
  const optionsMethodPay = [
    {
      title:'Selecciona un método de pago',
      value:0
    },
    {
      title:'Pago movil',
      value:1
    },
    {
      title:'Transferencia',
      value:2
    },
    {
      title:'Zelle',
      value:3
    },
    {
      title:'Binance',
      value:4
    }
  ]
  const dataPay = [
    [],
    [
      {
        title:'Banco',
        value:'Banplus (0174)'
      },
      {
        title:'Teléfono',
        value:'04121028697'
      },
      {
        title:'Cedula',
        value:'V25401625'
      }
    ],
    [
      {
        title:'Banco',
        value:'Banplus (0174)'
      },
      {
        title:'Número de cuenta',
        value:'0000 1111 2222 3333 4444 5555'
      },
      {
        title:'Cedula',
        value:'V25401625'
      }
    ],
    [
      {
        title:'Correo Electrónico',
        value:'-----ve@gmail.com'
      }
    ],
      
    [
      {
        title:'Correo Electrónico',
        value:'------.ve@gmail.com'
      }
    ],
  ]
  const formInputs = ref({
    quantity: rifa.configuration.minimus_buy,
    clientName: '',
    clientCi: '',
    clientEmail: '',
    clientPhone: '',
    method_pay: {
      title:'Selcciona un método de pago',
      value:0
    },
    payReference:'',
    payPhoto: null,

  })

  const onFileChange = () => {
    const file = document.getElementById('vaucher').files[0]
    formInputs.value.payPhoto = file

    console.log(formInputs.value.payPhoto)
  }
  const loadingShow = (state) => {
    loading.value = state;
  }

  const hideModal = () => {
    // cleanForm()
    cleanForm()
    emit('closeModal')
  }

  const showNotify = (type,text) => {
    Notify.create({
      color:type,
      message: text,
      timeout:2000
    })
  }
  const cleanForm = () => {
    formInputs.value = {
      quantity: rifa.configuration.minimus_buy,
      clientName: '',
      clientCi: '',
      clientEmail: '',
      clientPhone: '',
      method_pay: {
        title:'Selcciona un método de pago',
        value:0
      },
      payReference:'',
      payPhoto: null,

    }
    step.value = 1;
  }
  const createOrder = () => {
    loadingShow(true)
    const formData = new FormData()
    formData.append('amount', (formInputs.value.quantity * rifa.configuration.price));
    formData.append('quantity', formInputs.value.quantity)
    formData.append('reference', formInputs.value.payReference)
    formData.append('vaucher', formInputs.value.payPhoto)
    formData.append('status', 1)

    formData.append('client_name',  formInputs.value.clientName)
    formData.append('client_ci',    formInputs.value.clientCi)
    formData.append('client_phone', formInputs.value.clientPhone)
    formData.append('client_email', formInputs.value.clientEmail)

    formData.append('rifa_id', rifa.id)
    formData.append('method_id', formInputs.value.method_pay.value)

    orderStore.createOrder(formData)
    .then((response) => {
      console.log(response)

      if(response.code !== 200 ) throw response
      showNotify('positive', 'Tu orden de compra fue exitosa, serás redirigido en breve...')

      loadingShow(false)
      console.log(response)
      setTimeout(() => {
        router.push('/order/finish/'+response.data.id)
      }, 2000);
    })
    .catch((response) => {
      console.log(response)
      loadingShow(false)

      showNotify('negative', response)
    })
  }
  const removeCount = () => {
    if(formInputs.value.quantity > rifa.configuration.minimus_buy) formInputs.value.quantity--
  }
  const addCount = () => {
    if(formInputs.value.quantity < rifa.configuration.quantity_tickets) formInputs.value.quantity++
  }

  const changeMethodData = () => {
    let id =  optionsMethodPay.find( (item) => item.value == formInputs.value.method_pay.value).value
    let contentInfo = document.getElementById('dataToPay')
    contentInfo.classList.remove('activeInfo')


    if(id==0)  {
      contentInfo.classList.add('nonActive')
      return
    }
    if(Object.values(contentInfo.classList).includes('nonActive')) {
      contentInfo.classList.remove('nonActive')
      contentInfo.classList.add('activeInfo')
      dataPaySelect.value = dataPay[id]
    }
    else {
      contentInfo.classList.add('nonActive')
      setTimeout(() =>{
        contentInfo.classList.remove('nonActive')        
        contentInfo.classList.add('activeInfo')
        dataPaySelect.value = dataPay[id]
      } , 900)
      
    }
  }
  
  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });

  onMounted(() => {

  })
  

</script>
<template>
   <q-dialog v-model="dialog" class="createOrderDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 order__form"
          style="height: 100%; "
          @submit="step == 3 ? createOrder() : step++"
        >
        <!-- <div class="close__button">
          <q-btn round color="primary" icon="close" @click="hideModal()" />
        </div> -->
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                {{ step == 1 ? 'Selecciona tu compra' : step == 2 ? 'Ingresa tus datos' : 'Realiza tu pago' }}
              </div>
              
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-5 mt-0">
              
              <q-card-section class="q-pt-none q-px-sm ">
                <transition name="fade">
                  <template v-if="step==1">
                    <div class="px-2 ">
                      <div class="text-subtitle1 text-center text-black">
                        Verifica el monto antes de continuar
                      </div>
                      <div class="row py-3  my-3" style="border-bottom: 1px solid #dedede;">
                        <div class=" col-6  text-black " >
                          Precio de boleto
                        </div>
                        <div class=" col-6  text-black text-end " >
                          Bs. {{ numberFormat(rifa.configuration.price) }},00
                        </div>
                      </div>
                      <div class="row py-3 my-3 " style="border-bottom: 1px solid #dedede;">
                        <div class=" col-6  text-black ">
                          Cantidad de boletos
                        </div>
                        <div class=" col-6  text-black text-end text-subtitle2">
                        x {{ numberFormat( formInputs.quantity) }}
                        </div>
                      </div>
                      <div class="row py-3 my-3 " style="border-bottom: 1px solid #dedede;">
                        <div class=" col-6  text-black ">
                          Total a pagar
                        </div>
                        <div class=" col-6  text-black text-end text-subtitle2">
                          Bs. {{ numberFormat((rifa.configuration.price * formInputs.quantity)) }},00
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 col-12 mt-5">
                          <q-input
                            type="number"
                            v-model="formInputs.quantity"
                            label="Cantidad de tickects"
                            class=" createOrderForm__input quantity"
                            readonly
                          />
                        </div>
                        <div class="col-md-12 col-12 mt-5 flex justify-between  px-12">
                          <q-btn rounded outline  color="primary" icon="remove" size="1.2rem" class="mx-2" @click="removeCount()"/>
                          <q-btn rounded outline  color="primary" icon="add" size="1.2rem"  class="mx-2" @click="addCount()" />
                        </div>
                      </div>
                    </div>
                  </template>
                </transition>  
                <transition name="fade">
                  <template v-if="step==2">
                    <div class="px-2">
                      <div class="text-subtitle2  text-center text-stone-500">
                        Debes ingresar los datos del titular de la compra y medios de contacto
                      </div>
                      <div class="row mt-5 ">
                        
                        <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0">
                          <q-input
                            v-model="formInputs.clientName"
                            label="Nombre del comprador"
                            class=" createOrderForm__input"
                            :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                          />
                        </div>
                        <div class="col-md-12 col-12  mb-1 md:mt-0">
                          <q-input
                            v-model="formInputs.clientCi"
                            label="Cedula de identidad"
                            mask="###.###.###"
                            maxlength="10"
                            reverse-fill-mask
                            class=" createOrderForm__input"
                            :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                          />
                        </div>
                        <div class="col-md-12 col-12  mb-1 md:mt-0">
                          <q-input
                            v-model="formInputs.clientPhone"
                            label="Teléfono"
                            maxlength="12"
                            mask="####-#######"
                            reverse-fill-mask
                            class=" createOrderForm__input"
                            :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                          />
                        </div>
                        <div class="col-md-12 col-12  mb-1 md:mt-0">
                          <q-input
                            v-model="formInputs.clientEmail"
                            label="Correo Electrónico"
                            class=" createOrderForm__input"
                            :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                          />
                        </div>
                        
                      </div>
                    </div>
                  </template>
                </transition>
                <transition name="fade">
                  <template v-if="step==3">
                    <div class="px-2">      
                      <div class="row mt-1 ">
                        <div class="col-12  md:pl-2 mb-3 md:mt-0">
                          <q-select
                            v-model="formInputs.method_pay"
                            label="Selecciona tu metodo de pago"
                            option-value="value"
                            option-label="title"
                            behavior="menu"
                            color="primary"
                            :options="optionsMethodPay"
                            class="createOrderForm__input"
                            @update:model-value="changeMethodData()"
                          />
                        </div>
                      </div>
                      <div class="mt-4 nonActive" id="dataToPay">
                        <div style="border:2px solid black; border-radius:0.8rem" class="py-1 px-3">
                          <div class="flex   items-center text-subtitle2  text-stone-900">
                            <div class="w-5/6">
                              Procede a realizar el pago con los datos suministrados aqui
                            </div>
                            <div class="w-1/6 flex items-center justify-end">
                              <q-icon name="info" size="2rem" color="primary" class="pb-1"/>
                            </div>
                          </div>
                        </div>
                        <div class="row mt-5 ">
                          <div class=" col-12 py-2 flex justify-between " v-for="(item, key) in dataPaySelect" :key="key" >
                              <div class="text-subtitle2 text-black">
                                {{item.title}}:
                              </div>
                              <div class="text-subtitle2 text-black">
                                {{item.value}}
                              </div>
                          </div>
                          <div class=" col-12 py-2 flex justify-between " style="border-bottom: 1px solid darkgray;" >
                              <div class="text-subtitle2 text-black">
                                Monto
                              </div>
                              <div class="text-subtitle2 text-black">
                               Bs. {{ numberFormat((rifa.configuration.price * formInputs.quantity)) }},00
                              </div>
                          </div>
                          <div class=" col-12 pt-5 flex justify-between ">
                            <q-btn outline label="Copiar datos" color="black"  class="q-mx-sm " 
                            style="width: 100%; border-radius: 0.5rem;"  />

                          </div>
                        </div>
                        <div class="row mt-6 md:mt-10">
                          <div class="col-12 mb-5 md:mb-6">
                            <q-input
                              v-model="formInputs.payReference"
                              label="Ingresa el número de referencia"
                              class=" createOrderForm__input"
                              hint="*Asegurate de copiar correctamente la referencia"
                              :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                            />
                          </div>
                          <div class="col-12 mb-0">
                            <label for="vaucher">
                              <div ref="dropzone" id="dropzoneFile" class="dropzone" :class="{'load': !(formInputs.payPhoto == null)}">
                                <div class="dz-message" v-if="formInputs.payPhoto == null">
                                 Haz click para cargar tu capture <q-icon name="image" size="sm" color="blur" />
                                </div>
                                <div v-else class="text-bold text-black">
                                  Archivo subido con exito <q-icon name="check_circle" size="sm" color="blur" />
                                </div>
                              </div>
                            </label>
                          </div>
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
              <input type="file"  id="vaucher" ref="vaucher" accept="image/*"  style="display: none;" @change="onFileChange" >

              <q-btn :label="step == 1 ? 'Cerrar' : 'Volver' "  color="black"  class="q-mx-sm " style="width: 35%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="step == 1 ? hideModal() : step--" />
              <q-btn :label="step !== 3 ? 'Siguiente' : 'Comprar' "   color="blur" type="submit" style="width: 50%; border-radius: 0.8rem; padding: 0.7rem 0px;" :loading="loading"/>
            </div>
          </section>
        </q-form>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">
.createOrderDialog{
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
#dataToPay{
  transition: all 0.9s ease-in;
  
}
.nonActive{
  height: 0px;
  overflow: hidden;
  border-bottom: 5px solid ;
}
.activeInfo{
  height: 31rem;
  overflow: hidden;
}
.q-item__label{

  color: black!important;
}
.q-item--active{
  & .q-item__label{

    color: goldenrod!important;
  }
}


.createOrderForm__input {
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

.dropzone {
  border: 2px dashed #ccc;
  border-radius: 5px;
  padding: 20px;
  text-align: center;
  cursor: pointer;
  font-size: 16px;
  &.load{
  border: 2px solid #aa13a4!important;

  }
}

.dropzone .dz-message {
  
  color: #aaa;
}

.dropzone.dz-started .dz-message {
  display: none;
}

.dropzone .dz-preview.dz-image-preview {
  width: 150px;
  height: 150px;
  margin: 10px;
}

.dropzone .dz-preview .dz-image {
  width: 150px;
  height: 150px;
}

.dropzone .dz-preview .dz-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>