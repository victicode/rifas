<script setup>
import { onMounted, ref, watch, } from 'vue';
import { Notify } from 'quasar'
import moment from 'moment';
import { useRifaStore } from '@/services/store/rifas.store';

  const rifaStore = useRifaStore()
  const props = defineProps({
    dialog: Boolean,
    rifa: Object
  })

  const emit = defineEmits(['updateList', 'closeModal'])
  const step = ref(1)
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const previewImg = ref(null)
  const rewards = ref([
    {
      title:'',
      position:'',
      reward_time:'',
    }
  ])
  const formInputs = ref({
    title:'',
    description:'',
    due_date:moment().format('YYYY/MM/DD'),
    quantity_tickets:'10000',
    price:'',
    minimus_buy:2,
    auto_select:false,
    all_image:'',
  })
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
    formInputs.value = {
      title:'',
      description:'',
      due_date:moment().format('YYYY/MM/DD'),
      quantity_tickets:'10000',
      price:'',
      minimus_buy:2,
      auto_select:false,
      all_image:'',
    }
    rewards.value = [
      {
        title:'',
        position:'',
        reward_time:'',
      }
    ]
    previewImg.value = null
    step.value = 1;
  }
  const createRifa = () => {
    loadingShow(true)
    const file = document.getElementById('rifa_img')
 
    const formData = new FormData()
    formData.append('title', formInputs.value.title);
    formData.append('description', formInputs.value.description)
    formData.append('due_date', formInputs.value.due_date)
    formData.append('quantity_tickets', parseInt(formInputs.value.quantity_tickets.replace(/\./g, '')))
    formData.append('price', parseInt(formInputs.value.price.replace(/\./g, '')))
    formData.append('minimus_buy', formInputs.value.minimus_buy)
    formData.append('auto_select', formInputs.value.auto_select)
    formData.append('banner_img', file.files[0])
    formData.append('rewards', JSON.stringify(rewards.value))

    rifaStore.createRifa(formData)
    .then((response) => {
      
      loadingShow(false)
      showNotify('positive', 'Rifa creada con exito')

      updateList();
    })
    .catch((response) => {
      console.log(response)
      loadingShow(false)

      showNotify('negative', response)
    })
  }
  const deleteReward = (index) => {
    rewards.value.splice(index, 1)
  }
  const onFileChange = (e) => {
    const file = e.target.files[0];
    return previewImg.value= URL.createObjectURL(file)
  }
  const addReward = () => {
    rewards.value.push({
      title:'',
      position:'',
      reward_time:'',
    })
  }
  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });
  onMounted(() => {
    
  })
  

</script>
<template>
   <q-dialog v-model="dialog" class="createRifaDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document" style="border-radius:1rem">
        <div class="close__button">
          <q-btn round color="primary" icon="close" @click="hideModal()" />
        </div>
        <q-card-section>
          <div class="text-h5 text-center text-bold">
            {{ step == 1 ? 'Crear Rifa' : step == 2 ? 'Configuracion de Ticket' : 'Premios' }}
          </div>
        </q-card-section>
        <section class="content__modalSection">
          
          <q-card-section class="q-pt-none q-px-sm">
           <q-form
              class="md:px-5"
              style="overflow: hidden; "
              @submit="step == 3 ? createRifa() : step++"
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
                          v-model="formInputs.title"
                          label="Nombre de la rifa"
                          class=" createRifaForm__input"
                          :rules="[ val => val && val.length > 0 || 'Este campo es obligatorio']"
                        />
                      </div>
                      <div class="col-md-6 col-12 md:pl-2 mt-1 md:mt-0">
                        <q-input hint="Formato YYYY/MM/DD" label="Fecha de premiación" outlined class=" createRifaForm__input" v-model="formInputs.due_date" mask="date" :rules="['date']">
                          <template v-slot:append>
                            <q-icon name="event" class="cursor-pointer">
                              <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                <q-date v-model="formInputs.due_date" :options="optionsFn" :locale="localeTime">
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
                  <div class="px-2">
                    <div class="row my-3 ">
                      <div class="col-md-6 col-12 md:pr-2 mb-1 md:mb-0">
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
                      <div class="col-md-6 col-12  md:pl-2 mt-1 md:mt-0">
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
              <transition name="fade">
                <template v-if="step==3">
                  <div class="px-2">
                    <div class="row my-3" v-for="(item, index) in rewards" :key="index">
                      <div class="col-md-6 col-12 md:pr-2  pr-1 md:mb-0" >
                        <q-input
                          outlined
                          v-model="item.title"
                          :label="'Premio n° '+(index+1)"
                          class=" createRifaForm__input"
                          :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                        />
                      </div>
                      <div class="col-md-5 col-10 md:px-2 md:mb-0" >
                        <q-input
                          outlined
                          v-model="item.reward_time"
                          label="Hora de premiación"
                          class=" createRifaForm__input"
                          :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                        >
                        <template v-slot:append>
                            <q-icon name="access_time" class="cursor-pointer">
                              <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                <q-time v-model="item.reward_time">
                                  <div class="row items-center justify-end">
                                    <q-btn v-close-popup label="Guardar" color="primary" flat />
                                  </div>
                                </q-time>
                              </q-popup-proxy>
                            </q-icon>
                          </template>
                        </q-input>
                      </div>
                      <div class="col-md-1 col-2 md:pl-2 pl-1 pt-2" v-if="index > 0" >
                        <q-btn  color="negative" icon="delete" round v-if="step==3" @click="deleteReward(index)" />
                      </div>
                    </div>
                  </div>
                </template>
              </transition>
              <input type="file"  id="rifa_img" ref="rifa_img" style="display: none;" @change="onFileChange" >
              
              <div class="flex justify-end mt-5">
                <q-btn label=" Premio" color="black" class="" icon="add" v-if="step==3" @click="addReward()" />
                
                <q-btn :label="step == 1 ? 'Cerrar' : 'Volver' " color="negative"  class="q-mx-sm" @click="step == 1 ? hideModal() : step--" />
                <q-btn :label="step !== 3 ? 'Siguiente' : 'Enviar' "  color="black" type="submit" :loading="loading"/>
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

</style>