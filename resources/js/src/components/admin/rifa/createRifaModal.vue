<script setup>
import { onMounted, ref, watch } from 'vue';
import { Notify } from 'quasar'
import moment from 'moment';
  const props = defineProps({
    dialog: Boolean,
  })
  const emit = defineEmits(['updateList', 'closeModal'])
  const step = ref(1)
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const formInputs = ref({
    title:'',
    description:'',
    due_date:moment().format('YYYY/MM/DD'),
    quantity_tickets:10000,
    price:'',
    minimus_buy:1,
    auto_select:false,
    banner_img:'',
    all_image:'',
  })
  const optionsFn = (date) => {
    return date >= moment().format('YYYY/MM/DD')
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
      position:'top',
      timeout:2000
    })
  }
  const createRifa = () => {
    showNotify('positive', 'Bien!!!')
  }
  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });
  onMounted(() => {
    
  })
  

</script>
<template>
   <q-dialog v-model="dialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document" style="border-radius:1rem">
        <q-card-section>
          <div class="text-h5 text-center text-bold">
            {{ step == 1 ? 'Crear Rifa' : 'Configuracion de Ticket' }}
          </div>
        </q-card-section>

        <q-card-section class="q-pt-none">
         <q-form
            class="md:px-5"
            style="overflow: hidden; "
            @submit="step == 1 ? step = 2 : createRifa()"
          >
            <transition name="fade">
              <template v-if="step==1">
                <div>
                  <div class="row mb-5 justify-center">
                    <div style="height: 9rem; width: 9rem; background: #111; border-radius: 1rem;" class="flex justify-center items-center">
                        <q-icon name="add" color="white" size="5rem"/>
                    </div>
                  </div>
                  <div class="row my-3">
                    <div class="col-md-6 col-12 q-pr-md-sm q-pb-xs q-pb-md-none">
                      <q-input
                        outlined
                        v-model="formInputs.title"
                        label="Nombre de la rifa"
                        class=" createRifaForm__input"
                        :rules="[ val => val && val.length > 0 || 'Este campo es obligatorio']"
                      />
                    </div>
                    <div class="col-md-6 col-12 q-pl-md-sm q-mt-sm q-mt-md-none">
                      <q-input hint="Formato YYYY/MM/DD" label="Fecha de premiación" outlined class=" createRifaForm__input" v-model="formInputs.due_date" mask="date" :rules="['date']">
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                              <q-date v-model="formInputs.due_date" :options="optionsFn">
                                <div class="row items-center justify-end">
                                  <q-btn v-close-popup label="Close" color="primary" flat />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-12 my-3">
                      <q-input
                        outlined
                        v-model="formInputs.description"
                        label="Descripción de la rifa"
                        type="textarea"
                        class=" createRifaForm__input"
                        :rules="[ val => val && val.length > 0 || 'Este campo es obligatorio']"
                      />
                    </div>
                  </div>
                </div>
              </template>
            </transition>  
            <transition name="fade">
              <template v-if="step==2">
                <div>
                  <div class="row my-3">
                    <div class="col-md-6 col-12  q-mb-xs q-mb-md-none">
                      <q-input
                        outlined
                        v-model="formInputs.quantity_tickets"
                        type="number"
                        label="Cantidad de tickets"
                        class=" createRifaForm__input"
                        :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                      />
                    </div>
                    <div class="col-md-6 col-12  q-mt-xs q-mt-md-none">
                      <q-input
                        outlined
                        v-model="formInputs.price"
                        type="number"
                        label="Valor del ticket en Bs"
                        class=" createRifaForm__input"
                        :rules="[ val => val && val.length > 0 || 'Campo obligatorio']"
                      />
                    </div>
                  </div>
                  <div class="row my-3">
                    <div class="col-md-6 col-12 q-mb-xs q-mb-md-none">
                      <q-input
                        outlined
                        v-model="formInputs.minimus_buy"
                        type="number"
                        label="Compra minima"
                        class=" createRifaForm__input"
                        :rules="[ val => val && val > 0 || 'El campo es obligatorio']"
                      />
                    </div>
                    <div class="col-12 q-mt-xs q-mt-md-none">
                      <q-checkbox  v-model="formInputs.auto_select" label="Selección aleatoria de tickets" color="teal" />
                    </div>
                  </div>
                </div>
              </template>
            </transition>
            
            <div class="flex justify-end mt-5">
              <q-btn :label="step == 1 ? 'Cerrar' : 'Volver' " color="negative"  class="q-mr-sm" @click="step == 1 ? hideModal() : step = 1" />
              <q-btn :label="step == 1 ? 'Siguiente' : 'Enviar' "  color="black" type="submit"/>
            </div>
          </q-form>
        </q-card-section>

      </q-card>
    </q-dialog>
</template>
<style lang="scss">
.dialog_document {
  margin-left: 10%;
  min-width: 600px!important;
  max-width: 800px!important; 
}

.createRifaForm__input {
  & .q-field__control {
    border-radius: 10px!important;

  }
  & .q-field__label{
    transform: translateY(11%)
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
@media (max-width: 768px){
  .dialog_document {
    margin-left: 0%;
    min-width: 70%!important;
    max-width: 800px!important; 
  }
}
</style>