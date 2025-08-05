<script setup>
import { onMounted, ref, watch } from 'vue';
import { Notify } from 'quasar'
import numberUtils from '@/utils/numberUtils.js';
import { useOrderStore } from '@/services/store/order.store';
import { useRouter } from 'vue-router';
import { useMethodPayStore } from '@/services/store/methodPay.store';
import bankLabels from '@/utils/bankLabelUtils';
  
  const numberFormat = numberUtils.numberFormat 
  const router = useRouter()
  const props = defineProps({
    dialog: Boolean,
    rifa: Object
  })

  const emit = defineEmits(['closeModal'])
  const step = ref(1)
  const orderStore = useOrderStore()
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const rifa = props.rifa 
  const payMethodStore = useMethodPayStore()
  const optionsMethodPay = ref([])
  const getPayMethods = () => {
    payMethodStore.getMethodPaysActive()
    .then((response) => {
      formInputs.value.method_pay = response.data.find(item => item.id == formInputs.value.method_pay.id)
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
  const formInputs = ref({
    quantity: rifa.configuration.minimus_buy,
    clientName: '',
    clientCi: '',
    clientEmail: '',
    clientPhone: '',
    method_pay: {
      name:'Pago Movil',
      coin_id:1,
      id:1
    },
    payReference:'',
    payPhoto: null,

  })
  const dataPays = ref([]);
  const onFileChange = () => {
    const file = document.getElementById('vaucher').files[0]
    formInputs.value.payPhoto = file
  }

  const loadingShow = (state) => {
    loading.value = state;
  }
  const bankLabelAssign = (code) => {
     return bankLabels.find((bank) => bank.code == code)? bankLabels.find((bank) => bank.code == code).name  : 'S/B'
  }
  const backButton = () => {


    step.value == 1 ? hideModal() : step.value--
  }
  const nextStep = () => {
    if(step.value == 4) {
      createOrder()
      return
    }
    if(step.value == 2) {
      changeMethodData()
    }
    step.value++
  }
  const hideModal = () => {
    // cleanForm()
    cleanForm()
    emit('closeModal')
  }

  const showNotify = (type,text, position="bottom") => {
    Notify.create({
      color:type,
      position,
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
        name:'Pago Movil',
        coin_id:1,
        id:1
      },
      payReference:'',
      payPhoto: null,
    }
    step.value = 1;
  }
  const createOrder = () => {
    if(!formInputs.value.payPhoto){
      showNotify('negative', 'Debes adjuntar tu capture de forma obligatoria')
      return
    }
    loadingShow(true)
    const amount = formInputs.value.method_pay.coin_id == 1 ? rifa.configuration.price : rifa.configuration.price_usd
    const formData = new FormData()
    formData.append('amount', (formInputs.value.quantity * amount));
    formData.append('quantity', formInputs.value.quantity)
    formData.append('reference', formInputs.value.payReference)
    formData.append('vaucher', formInputs.value.payPhoto)
    formData.append('status', 1)
    formData.append('client_name',  formInputs.value.clientName)
    formData.append('client_ci',    parseInt(formInputs.value.clientCi.replace(/\./g, '')))
    formData.append('client_phone', formInputs.value.clientPhone)
    formData.append('client_email', formInputs.value.clientEmail)
    formData.append('rifa_id', rifa.id)
    formData.append('method_id', formInputs.value.method_pay.id)

    orderStore.createOrder(formData)
    .then((response) => {
      console.log(response)
      if(response.code !== 200 ) throw response
      showNotify('positive', 'Tu orden de compra fue exitosa, serás redirigido en breve...')
     
      setTimeout(() => {
        router.push('/order/finish/'+response.data.order.id)
      }, 2000);
    })
    .catch((response) => {
      console.log(response)
      loadingShow(false)
      if(response.code == 403){

        showNotify('black', `Cantidad disponible: ${response.error.availableTickets}`, 'center')
        showNotify('black', response.error.msg, 'center')

        return
      }
        showNotify('negative', response)

    })
  }
  const copyDataPay = () => {
    const texto = formatDataPayText();
    const element = document.getElementById('pasteClipb')
    const textArea = document.createElement('textarea');
    textArea.value = texto;
    textArea.style.opacity = 0;
    element.appendChild(textArea);
    textArea.select();

    try {
      const success = document.execCommand('copy');
      showNotify('positive', 'Datos copiados con exito')
    } catch (err) {
      console.error(err.name, err.message);
    }
    element.removeChild(textArea);
  }

  const formatDataPayText = () => {
    let text = ''
    JSON.parse(formInputs.value.method_pay.data_pay[0].data).forEach((element) => {
      text += element.value+' '
    });
    
    return text
  }
  const removeCount = () => {
    let min = formInputs.value.method_pay.coin_id  == 2
    ? rifa.configuration.minimus_buy_usd
    : rifa.configuration.minimus_buy


    if(formInputs.value.quantity > min) formInputs.value.quantity--
  }
  const addCount = () => {
    if(formInputs.value.quantity < rifa.configuration.quantity_tickets) formInputs.value.quantity++
  }

  const changeMethodData = () => {
    let id =  formInputs.value.method_pay.id 
    

    try {
      setTimeout(() => {
        
        let contentInfo = document.getElementById('dataToPay')
        contentInfo.classList.remove('activeInfo')
        if(id==0)  {
          contentInfo.classList.add('nonActive')
          return
        }
        if(Object.values(contentInfo.classList).includes('nonActive')) {
          contentInfo.classList.remove('nonActive')
          contentInfo.classList.add('activeInfo')
          dataPays.value = formInputs.value.method_pay.data_pay
          formInputs.value.quantity = formInputs.value.method_pay.coin_id  == 2 && formInputs.value.quantity < rifa.configuration.minimus_buy_usd
          ? rifa.configuration.minimus_buy_usd
          : formInputs.value.quantity
        }
        else {
          contentInfo.classList.add('nonActive')
          setTimeout(() =>{
            dataPays.value = formInputs.value.method_pay.data_pay
            contentInfo.classList.remove('nonActive')        
            contentInfo.classList.add('activeInfo')
            formInputs.value.quantity = formInputs.value.method_pay.coin_id  == 2 && formInputs.value.quantity < rifa.configuration.minimus_buy_usd
            ? rifa.configuration.minimus_buy_usd
            : formInputs.value.quantity
          } , 900)
          
        }
      }, 100);
    } catch (error) {
        console.log(error)
    }
    
  }
  const formatTicket = (value) =>{
      // Filtra solo números (elimina todo lo que no sea dígito)
      
      formInputs.value.quantity = value.replace(/[^0-9]/g, '');
      formInputs.value.quantity = parseInt(formInputs.value.quantity)
      
      if(isNaN(formInputs.value.quantity)){
        formInputs.value.quantity = 0
        return
      }
      formInputs.value.quantity = parseInt(formInputs.value.quantity)

  }
  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });

  onMounted(() => {
    getPayMethods()
  })
  

</script>
<template>
   <q-dialog v-model="dialog" class="createOrderDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 order__form"
          style="height: 100%; "
          @submit="nextStep()"
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                {{ step == 1 ? 'Selecciona tu compra' : step == 2 ? 'Ingresa tus datos' : step == 3 ? 'Realiza tu pago'  : 'Confirma tu pago'  }}
              </div>

            </q-card-section>
            <section class="content__modalSectionRifaBuy md:mt-5 mt-0">
              
              <q-card-section class="q-py-none q-px-sm pb-0">
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
                        <div class=" col-6  text-black text-end  text-subtitle2" >
                          {{ 
                            formInputs.method_pay.coin_id == 1
                            ? `Bs. ${numberFormat(rifa.configuration.price)},00` 
                            : `$  ${(rifa.configuration.price_usd+'').replace('.', ',')}`
                          }}
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
                          {{ 
                            formInputs.method_pay.coin_id == 1
                            ? `Bs. ${numberFormat((rifa.configuration.price * formInputs.quantity))},00 ` 
                            : `$  ${((rifa.configuration.price_usd * formInputs.quantity).toFixed(2)+'').replace('.', ',')}`
                          }}
                          
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 col-12 mt-5">
                          <q-input
                            type="text"
                            v-model="formInputs.quantity"
                            label="Cantidad de tickets"
                            class=" createOrderForm__input quantity"
                           @update:model-value="formatTicket"
                          :rules="[ val => !!val  || 'El campo es obligatorio', val => val >= 2 || 'El minimo son 2 tickets', val => val <= 10000 || 'El maximo son 9999 tickets',]"
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
                            :rules="[ val => val && val.length > 0 || 'El campo es obligatorio', val => (/^\d+$/.test(val) == false ) || 'Formato no valido']"
                          />
                        </div>
                        <div class="col-md-12 col-12  mb-1 md:mt-0">
                          <q-input
                            v-model="formInputs.clientCi"
                            label="Cedula de identidad"
                            mask="###.###.###"
                            maxlength="10"
                            reverse-fill-mask
                            inputmode="numeric"
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
                            inputmode="numeric"
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
                            option-value="id"
                            option-label="name"
                            behavior="menu"
                            color="primary"
                            :options="optionsMethodPay"
                            class="createOrderForm__input methodSelectInput"
                            :hint="formInputs.method_pay.coin_id == 1 ? 'La compra minima es de 2 tickets': 'La compra minima es de '+rifa.configuration.minimus_buy_usd+' tickets'"
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
                          <div class="row mb-2 w-full"  v-for="(i, k) in dataPays" :key="k"  style="border-bottom:1px solid darkgray;"> 
                            <div class=" col-12 py-2 flex justify-between " v-for="(item, key) in JSON.parse(i.data)" :key="key"  >
                                <div class="text-subtitle2 text-black">
                                  {{item.title}}:
                                </div>
                                <div class="text-subtitle2 text-black">
                                  {{ 
                                    item.title == 'Banco' 

                                    ? bankLabelAssign(item.value)+' ('+item.value+')'
                                    : item.title == 'Teléfono' 
                                    ? item.value.slice(0, 4)+'-'+item.value.slice(4) 
                                    : item.title == 'Documento'
                                    ? 'V'+item.value 
                                    : item.value
                                  }}
                                </div>
                            </div>
                          </div>
                          <div class=" col-12 pb-1 flex justify-between my-2 "  >
                            <div class="text-subtitle2 text-black">
                              Tickets
                            </div>
                            <div class="text-subtitle2 text-black">
                              x {{ numberFormat( formInputs.quantity) }}
                            </div>
                          </div>
                          <div class=" col-12 pb-1 flex justify-between my-2 " style="border-bottom: 1px solid darkgray;" >
                            <div class="text-subtitle2 text-black">
                              Monto
                            </div>
                            <div class="text-subtitle2 text-black">
                              {{ 
                                formInputs.method_pay.coin_id == 1
                                ? `Bs. ${numberFormat((rifa.configuration.price * formInputs.quantity))},00 ` 
                                : `$  ${((rifa.configuration.price_usd * formInputs.quantity).toFixed(2)+'').replace('.', ',')}`
                              }}
                            </div>
                          </div>
                          <div class=" col-12 pt-3 flex justify-between ">
                            <q-btn @click="copyDataPay()"  outline label="Copiar datos" color="black"  class="q-mx-sm " 
                            style="width: 100%; border-radius: 0.5rem;"  />

                          </div>
                          <div id="pasteClipb"></div>
                        </div>

                      </div>
                    </div>
                  </template>
                </transition>
                <transition name="fade">
                  <template v-if="step==4">
                    <div class="px-2">      
                      <div class="mt-4">
                        <div style="border:2px solid black; border-radius:0.8rem" class="py-1 px-3">
                          <div class="flex   items-center text-subtitle2  text-stone-900">
                            <div class="w-5/6">
                              Confirma que los datos de tu compra sean los correctos
                            </div>
                            <div class="w-1/6 flex items-center justify-end">
                              <q-icon name="info" size="2rem" color="primary" class="pb-1"/>
                            </div>
                          </div>
                        </div>
                        <div class="row mt-5 ">
                          <div class=" col-12 pb-1 flex justify-between my-2 "  >
                            <div class="text-subtitle2 text-black">
                              Tickets
                            </div>
                            <div class="text-subtitle2 text-black">
                              x {{ numberFormat( formInputs.quantity) }}
                            </div>
                          </div>
                          <div class=" col-12 pb-1 flex justify-between my-2 " style="border-bottom: 1px solid darkgray;" >
                            <div class="text-subtitle2 text-black">
                              Monto cancelado
                            </div>
                            <div class="text-subtitle2 text-black">
                              {{ 
                                formInputs.method_pay.coin_id == 1
                                ? `Bs. ${numberFormat((rifa.configuration.price * formInputs.quantity))},00 ` 
                                : `$  ${((rifa.configuration.price_usd * formInputs.quantity).toFixed(2)+'').replace('.', ',')}`
                              }}
                            </div>
                          </div>
                        </div>
                        <div class="row mt-8 pt-8 md:pt-3 md:mt-6" style="border-top:6px dotted #252525">
                          <div class="col-12 mb-10 md:mb-6">
                            <q-input
                              v-model="formInputs.payReference"
                              outlined=""
                              inputmode="numeric"
                              :maxlength="8"
                              label="Ingresa el número de referencia"
                              class=" createOrderFormRef__input"
                              hint="*Asegurate de copiar correctamente la referencia"
                              :rules="[ val => val && val.length > 0 || 'El campo es obligatorio', val => (/^\d+$/.test(val) == true )  || 'Formato no valido']"
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
            <div class="flex justify-evenly md:mt-5 mt-1">
              <input type="file"  id="vaucher" ref="vaucher" accept="image/*"  style="display: none;" @change="onFileChange" >
              <q-btn :label="step == 1 ? 'Cerrar' : 'Volver' "  color="black"  class="q-mx-sm " style="width: 35%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="backButton()" />
              <q-btn :label="step !== 4 ? 'Siguiente' : 'Comprar' "   color="blur" type="submit" style="width: 50%; border-radius: 0.8rem; padding: 0.7rem 0px;" :loading="loading"/>
            </div>
          </section>
        </q-form>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">
.methodSelectInput .q-field__messages {
  color:rgb(93, 92, 92);
  font-size: 0.85rem;
}

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
.content__modalSectionRifaBuy{
    overflow: auto;
    max-height: 80vh;

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
  height: 28rem;
}
.q-item__label{

  color: black!important;
}
.q-item--active{
  & .q-item__label{

    color: goldenrod!important;
  }
}
.createOrderFormRef__input {

  & input{
    padding-bottom: 0px!important;
  }
  & .q-field__label{
    transform: translateY(11%);
    
  }
  &.q-field--outlined .q-field__control{
    border-radius: 0.5rem;
  }
  &.q-field--focused .q-field__label, &.q-field--float .q-field__label{
    z-index: 100;
    background: white!important;
    font-weight: 600;
    width: fit-content;


    padding: 0px 10px;
    font-size: 0.8rem;
    transform: translateY(-150%) translateX(-0.5rem) !important;
  }
  
  & .q-field__native{
    padding-top: 5px!important;
    font-weight: 600;
  }
  & .q-field__append{
    transform: translateY(5%)
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
  border: 2px solid #31ae1a!important;

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