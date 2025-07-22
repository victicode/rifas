<script setup>
import { ref, watch} from 'vue';
import bankLabels from '@/utils/bankLabelUtils';
import numberUtils from '@/utils/numberUtils.js';

const props = defineProps({
  dialog: Boolean,
  payMethod: Object
})

const emit = defineEmits([ 'closeModal'])


const dialog = ref(props.dialog);


const hideModal = () => {
  emit('closeModal')
}

const formatMethodData = (data) => {
  data.data_pay.forEach(element => {
      element.data = JSON.parse(element.data) 
  });
  console.log(data)
  return data

}

const payMethod = ref(formatMethodData(props.payMethod))

const bankLabelAssign = (code) => {
  return bankLabels.find((bank) => bank.code == code)? bankLabels.find((bank) => bank.code == code).name  : 'S/B'
}
watch(() => props.dialog, (newValue) => {
  dialog.value = newValue

});
watch(() => props.payMethod, (newValue) => {
  payMethod.value = formatMethodData(newValue)

});



</script>
<template>
  <q-dialog v-model="dialog" class="createPayMethodDialog" persistent backdrop-filter="blur(8px)">
    <q-card class="dialog_document public" style="border-radius:1rem">
      <div>
        <q-card-section class="">
          <div class="text-h6 text-center text-black">
            Datos de pagos asociados
          </div>
        </q-card-section>
        <section class="content__modalSectionRifa md:mt-0 mt-0">
          <q-card-section class="q-pt-none q-px-sm ">
            <div class="px-2">
              <div class="text-center mt-2">
                <q-chip color="black" text-color="white"  >
                  <div class="px-2 md:px-1">
                    {{ payMethod.name }}
                  </div>
                </q-chip>
              </div>
              <div class="px-5">
                <div class="row mt-4 pb-2 " v-for="(item, index) in payMethod.data_pay" :key="index" style="border-bottom: 1px solid darkgrey">
                  <template v-for="(i, k) in item.data" :key="k" >
                    <div class="col-6 pb-1 text-black text-subtitle2">
                      {{ i.title }}:  
                    </div>
                    <div class="col-6 text-end pb-1 text-black text-subtitle2">
                      <span v-if="i.title=='Banco'" class="mr-1"> 
                          {{ 
                           bankLabelAssign(i.value)
                          }}
                        </span>
                        <span v-if="i.title=='Banco'">(</span>{{ i.title == 'Documento' ? numberUtils.numberFormat(i.value) : i.value }}<span v-if="i.title=='Banco'">)</span>
                      
                    </div>
                    
                  </template>
  
                </div>
              </div>
            </div>
          </q-card-section>
        </section>
      </div>
      <section>
        <div class="flex justify-evenly mt-5">
          <q-btn label="Cerrar"  color="black"  class="q-mx-sm " style="width: 35%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="hideModal()" />
        </div>
      </section>
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