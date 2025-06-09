<script setup>
import { onMounted, ref, watch } from 'vue';
import { Notify } from 'quasar'
import moment from 'moment';
import { useRifaStore } from '@/services/store/rifas.store';
import numberUtils from '@/utils/numberUtils.js';

  const rifaStore = useRifaStore()
  const props = defineProps({
    dialog: Boolean,
    rifa: Object,
  })

  const emit = defineEmits(['updateList', 'closeModal'])
  const step = ref(1)
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const previewImg = ref(props.rifa.configuration.banner_img)
  const rifa   = ref([])
  const optionsFn = (date) => {
    return date >= moment().format('YYYY/MM/DD')
  }
  const localeTime = {
    days:         ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
    daysShort:    ['Dom','Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
    months:       ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    monthsShort:  ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
  }
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
    step.value = 1;
  }
  const updateRifa = () => {
    loadingShow(true)
    const file = document.getElementById('rifa_img')
 
    const formData = new FormData()
    formData.append('title', rifa.value.title);
    formData.append('description', rifa.value.description)
    formData.append('due_date', rifa.value.due_date)
    formData.append('quantity_tickets', parseInt(rifa.value.configuration.quantity_tickets.replace(/\./g, '')))
    formData.append('price', parseInt(rifa.value.configuration.price.replace(/\./g, '')))
    formData.append('minimus_buy', rifa.value.configuration.minimus_buy)
    formData.append('auto_select', rifa.value.configuration.auto_select)
    formData.append('banner_img', file.files[0])

    rifaStore.updateRifa(rifa.value.id, formData)
    .then((response) => {
      loadingShow(false)
      showNotify('positive', 'Rifa modificada con exito')
      updateList();
    })
    .catch((response) => {
      loadingShow(false)
      showNotify('negative', response)
    })
  }

  const onFileChange = (e) => {
    const file = e.target.files[0];
    return previewImg.value= URL.createObjectURL(file)
  }
  const formatRifa = () => {
    Object.entries(props.rifa).forEach(([key, value]) => {
      if(key !=='configuration'){
        rifa.value[key] = key== 'due_date' ? value.replace(/\-/g, '/') : value;
      } else {
        rifa.value['configuration'] = []
        Object.entries(value).forEach(([key2, value2]) => {
          if(['auto_select', 'quantity_tickets', 'price', 'minimus_buy'].includes(key2)) rifa.value['configuration'][key2] =numberUtils.numberFormat(value2);
        })
      }
    }); 
    previewImg.value = props.rifa.configuration.banner_img
    rifa.value.configuration.auto_select = rifa.value.configuration.auto_select == 1;
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
   <q-dialog v-model="dialog" class="updateRifaDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document" style="border-radius:1rem">
        <div class="close__button">
          <q-btn round color="primary" icon="close" @click="hideModal()" />
        </div>
        <q-card-section>
          <div class="text-h5 text-center text-bold">
            {{ step == 1 ? 'Modificar Rifa' : 'Configuracion de Ticket' }}
          </div>
        </q-card-section>
        <section class="content__modalSection">
          
          <q-card-section class="q-pt-none q-px-sm">
           <q-form
              class="md:px-5"
              style="overflow: hidden; "
              @submit="step == 2 ? updateRifa() : step++"
            >
              <transition name="fade">
                <template v-if="step==1">
                  <div class="px-2">
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
                      <div class="col-md-6 col-12 md:pr-2 mb-3 md:mb-0">
                        <q-input
                          outlined
                          v-model="rifa.title"
                          label="Nombre de la rifa"
                          class=" updateRifaForm__input"
                          :rules="[ val => val && val.length > 0 || 'Este campo es obligatorio']"
                        />
                      </div>
                      <div class="col-md-6 col-12 md:pl-2 mt-1 md:mt-0">
                        <q-input hint="Formato YYYY/MM/DD" label="Fecha de premiación" outlined class=" updateRifaForm__input" v-model="rifa.due_date" mask="date">
                          <template v-slot:append>
                            <q-icon name="event" class="cursor-pointer">
                              <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                <q-date v-model="rifa.due_date" :options="optionsFn" :locale="localeTime">
                                  <div class="row items-center justify-end">
                                    <q-btn v-close-popup label="Guardar" color="primary" flat />
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
                          v-model="rifa.description"
                          label="Descripción de la rifa"
                          type="textarea"
                          class=" updateRifaForm__input"
                          :rules="[ val => val && val.length > 0 || 'Este campo es obligatorio']"
                        />
                      </div>
                    </div>
                  </div>
                </template>
              </transition>  
              <transition name="fade">
                <template v-if="step==2">
                  <div class="px-2">
                    <div class="row my-3 ">
                      <div class="col-md-6 col-12 md:pr-2 mb-1 md:mb-0">
                        <q-input
                          outlined
                          v-model="rifa.configuration.quantity_tickets"
                          label="Cantidad de tickets"
                          class=" updateRifaForm__input"
                          mask="###.###.###"
                          reverse-fill-mask
                          :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                        />
                      </div>
                      <div class="col-md-6 col-12  md:pl-2 mt-1 md:mt-0">
                        <q-input
                          outlined
                          v-model="rifa.configuration.price"
                          mask="###.###.###"
                          reverse-fill-mask
                          label="Valor del ticket en Bs"
                          class=" updateRifaForm__input"
                          :rules="[ val => val && val.length > 0 || 'Campo obligatorio']"
                        />
                      </div>
                    </div>
                    <div class="row my-3">
                      <div class="col-md-6 col-12 q-mb-xs q-mb-md-none">
                        <q-input
                          outlined
                          v-model="rifa.configuration.minimus_buy"
                          type="number"
                          label="Compra minima"
                          class=" updateRifaForm__input"
                          :rules="[ val => val && val > 0 || 'El campo es obligatorio']"
                        />
                      </div>
                      <div class="col-12 q-mt-xs q-mt-md-none">
                        <q-checkbox  v-model="rifa.configuration.auto_select" label="Selección aleatoria de tickets" color="teal" />
                      </div>
                    </div>
                  </div>
                </template>
              </transition>
              <input type="file"  id="rifa_img" ref="rifa_img" style="display: none;" @change="onFileChange" >
              
              <div class="flex justify-end mt-5">

                
                <q-btn :label="step == 1 ? 'Cerrar' : 'Volver' " color="negative"  class="q-mx-sm" @click="step == 1 ? hideModal() : step--" />
                <q-btn :label="step !== 2 ? 'Siguiente' : 'Guardar' "  color="black" type="submit" :loading="loading"/>
              </div>
            </q-form>
          </q-card-section>
        </section>

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

.updateRifaForm__input {
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

</style>