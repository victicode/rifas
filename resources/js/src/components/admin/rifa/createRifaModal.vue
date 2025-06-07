<script setup>
import { onMounted, ref, watch, useTemplateRef } from 'vue';
import { Notify } from 'quasar'
import moment from 'moment';
  const props = defineProps({
    dialog: Boolean,
  })
  const emit = defineEmits(['updateList', 'closeModal'])
  const step = ref(1)
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const previewImg = ref(null)
  const formInputs = ref({
    title:'',
    description:'',
    due_date:moment().format('YYYY/MM/DD'),
    quantity_tickets:'10000',
    price:'',
    minimus_buy:1,
    auto_select:false,
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
    const file = document.getElementById('rifa_img')
    // const form = new FormData()
    // form.append('title', formInputs.value.title);
    // form.append('description', formInputs.value.description)
    // form.append('due_date', formInputs.value.due_date)
    // form.append('quantity_tickets', formInputs.value.quantity_tickets)
    // form.append('price', formInputs.value.price)
    // form.append('minimus_buy', formInputs.value.minimus_buy)
    // form.append('auto_select', formInputs.value.auto_select)
    // form.append('banner_img')
    
    console.log(file)



    showNotify('positive', 'Bien!!!')
  }
  const onFileChange = (e) => {
    const file = e.target.files[0];
    return previewImg.value= URL.createObjectURL(file)
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
                  <div class="row mb-8 justify-center column items-center">
                    <label for="rifa_img" class="label_img">
                      <div v-if="!previewImg" style="" class="flex justify-center items-center slot__img">
                          <q-icon name="add" color="white" size="5rem"/>
                      </div>
                      <div class="img__container" v-else>
                        <img  :src="previewImg" class="slot__img" alt="">
                        <div class="flex justify-center items-center overflow__img" style="">
                          <q-icon name="add" color="white" size="5rem"/>
                        </div>
                      </div>
                    </label>
                    <div class="text-center text-gray-700" style="font-size:1rem">
                      Agrega Imagen
                    </div>
                  </div>
                  <div class="row my-3">
                    <div class="col-md-6 col-12 md:pr-2 mb-4 md:mb-0">
                      <q-input
                        outlined
                        v-model="formInputs.title"
                        label="Nombre de la rifa"
                        class=" createRifaForm__input"
                        :rules="[ val => val && val.length > 0 || 'Este campo es obligatorio']"
                      />
                    </div>
                    <div class="col-md-6 col-12 md:pl-2 mt-4 md:mt-0">
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
                    <div class="col-md-6 col-12 md:pr-2 mb-4 md:mb-0">
                      <q-input
                        outlined
                        v-model="formInputs.quantity_tickets"
                        label="Cantidad de tickets"
                        class=" createRifaForm__input"
                        mask="###.###.###"
                        reverse-fill-mask
                        :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                      />
                    </div>
                    <div class="col-md-6 col-12  md:pl-2 mt-4 md:mt-0">
                      <q-input
                        outlined
                        v-model="formInputs.price"
                        mask="###.###.###"
                        reverse-fill-mask
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
            <input type="file"  id="rifa_img" ref="rifa_img" style="display: none;" @change="onFileChange" >
            
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
.img__container{
  position: relative;
   transition: all 0.5 ease;
  &:hover .overflow__img{
    opacity: 1;
    transition: all 0.5 ease;
  }
}
.label_img{
  cursor: pointer;
}
.slot__img{
  height: 14rem; width: 11rem; background: #111; border-radius: 1rem;
  &:hover{
    background: #484848;
  }
}
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
.overflow__img{
  opacity: 0;
  position: absolute; 
  top:0; bottom:0; left:0; right:0; 
  background:rgba(34, 34, 34, 0.539);
  border-radius: 1rem;
  transition: all 0.5 ease;
}
@media (max-width: 768px){
  .dialog_document {
    margin-left: 0%;
    min-width: 70%!important;
    max-width: 800px!important; 
  }
}
</style>