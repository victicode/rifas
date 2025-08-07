<script setup>
import { onMounted, ref, watch} from 'vue';
import { Notify } from 'quasar'
import { useRifaStore } from '@/services/store/rifas.store';
import moment from 'moment';
import {
  NInputOtp,
} from 'naive-ui'
import { useWinnerStore } from '@/services/store/winner.store';

  const rifaStore = useRifaStore()
  const props = defineProps({
    dialog: Boolean,
    winner: Object
  })

  const emit = defineEmits(['updateList', 'closeModal'])
  const winnerStore = useWinnerStore()
  const loading = ref(false);
  const dialog = ref(props.dialog);

  const rifasOption = ref([])
  const rewardsOption = ref([])

  const step = ref(0) 
  const winner = ref({})
  const previewImg = ref(null)
  const onFileChange = () => {
    const file = document.getElementById('winner_photo').files[0]
    winner.value.winner_photo = file
    return previewImg.value= URL.createObjectURL(file)
 
  }

  const loadingShow = (state) => {
    loading.value = state;
  }
  const hideModal = () => {
    if(step.value == 1 ) {
      step.value--
      return
    }
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
    winner.value = {
        rifa: {
        title:'Selecciona la rifa',
        id:0
      },
      reward: {
        title:'Selecciona el premio',
        id:0
      },
      winnerNumber: null,
      winner_photo: null,
      linkIg: '',
      linkTt: '',

    }
  }
  const setRewardsOption = (rifa) => {
    winner.value.reward = {
        title:'Selecciona el premio',
        id:0
    }
    winner.value.winnerNumber = null
    winner.value.winner_photo = null,
    rewardsOption.value = rifa.rewards
  }

  const formatWinner = () => {
    Object.entries(props.winner).forEach(([key, value]) => {
      winner.value[key] = value;
    }); 
    winner.value.rifa_id = {
      id: winner.value.rifa_id,
      title: winner.value.rifa.title
    }
    winner.value.reward_id = {
      id: winner.value.reward_id,
      title: winner.value.reward.title
    }
    winner.value.number = ticketFormat(winner.value.ticket.number).split('')
    previewImg.value = winner.value.winner_photo

    getRifas()
  }

  const ticketFormat = (ticket) => {
    let max = '0000'
    let ticketFormat = ticket+''
    return max.substring(0, (4 - ticketFormat.length))+ticketFormat

  }

  const updateList = () => {
    step.value = 0
    cleanForm()
    emit('closeModal')
    emit('updateList')
  }

  const validateForm = () => {
    if(winner.value.number == null ){
      showNotify('negative', 'Debes ingresar el número ganador')
      return false
    }
    if(winner.value.number.join('').length < 4){
      showNotify('negative', 'Número no valido')
      return false
    }
    // if(winner.value.winner_photo == null ){
    //   showNotify('negative', 'Debes subir la foto de la entrega')
    //   return false
    // }
    
    return true
  }
  const updateWinner = () => {
    if(!validateForm()) return 
    if(step.value == 0) {
      
      step.value++
      return
    }  
    loadingShow(true)
    const formData = new FormData()
    formData.append('rifa', winner.value.rifa_id.id);
    formData.append('reward', winner.value.reward_id.id)
    formData.append('ticket', winner.value.number.join(''))
    formData.append('photo', winner.value.winner_photo)
    formData.append('link_ig', winner.value.link_ig)
    formData.append('link_tt', winner.value.link_tt)

    const data = {
      id: winner.value.id,
      data: formData
    }
    winnerStore.updateWinner(data)
    .then((response) => {
      if(response.code !== 200 ) throw response
      showNotify('positive', 'Ganador editado con exito')
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

  const getRifas  = () => {
    rifaStore.getAllRifas()
    .then((response) => {
      rifasOption.value = [
        {
        title:'Selecciona la rifa',
        id:0
        },
        ...response.data
      ]
    })
  }

  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
    
    formatWinner()

  });
    
  onMounted(() => {
    formatWinner()
  })
  

</script>
<template>
   <q-dialog v-model="dialog" class="updateWinnerDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 order__form"
          style="height: 100%; "
          @submit="updateWinner()"
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                Completa el formulario
              </div>
              
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-5 mt-0">
              <q-card-section class="q-pt-none q-px-sm ">
                <div class="px-2 ">
                  <transition name="fade">
                    <template v-if="step==0">
                      <div class="row mt-1 ">
                        
                        <div class="col-12  md:pl-2 mb-3 md:mt-0">
                          <q-select
                            v-model="winner.rifa_id"
                            label="Selecciona la rifa"
                            option-value="id"
                            option-label="title"
                            behavior="menu"
                            color="primary"
                            class="updateWinnerForm__input"
                            @update:model-value="setRewardsOption"
                            :options="rifasOption"
                            :rules="[ (val, rules) => val.id != 0 || 'Debes seleccionar una rifa' ]"
                          />
                        </div>
                        <div class="col-12  md:pl-2 mb-3 md:mt-0" v-if=" winner.rifa_id.id > 0">
                          <q-select
                            v-model="winner.reward_id"
                            label="Selecciona el premio"
                            option-value="id"
                            option-label="title"
                            behavior="menu"
                            color="primary"
                            class="updateWinnerForm__input"
                            :options="rewardsOption"
                            :rules="[ (val, rules) => val.id != 0 || 'Debes seleccionar una rifa' ]"
                          >
                            <template v-slot:option="scope">
                              <q-item v-bind="scope.itemProps" v-if="scope.opt.winner == null">
                                <q-item-section>
                                  <q-item-label>{{ scope.opt.title }}</q-item-label>
                                  <q-item-label caption v-if="scope.opt.id!=0" class="py-2 ">
                                    <div class="text-gray-500">
                                      Hora: {{ moment( (moment().format("YYYY-MM-DD") +' '+ scope.opt.reward_time)).format("hh:mm:ss a") }}
                                    </div>
                                  </q-item-label>
                                </q-item-section>
                              </q-item>
                            </template>
                          </q-select>
                        </div>
                        <div class="col-12  md:pl-2 mb-3 md:mt-0 " v-if=" winner.rifa_id.id > 0 && winner.reward_id.id > 0">
                          <div class="pb-5 text-center text-subtitle1">
                            Ingresa el número del ticket ganador
                          </div>
                          <div class="flex flex-center w-full ">
                            <div style="border:1px solid black; border-radius: 1rem" class=" p-4">
                              <n-input-otp
                                v-model:value="winner.number"
                                length="4"
                                placeholder="0"
                                size="large"
                                gap="1rem"
                              />
                            </div>
                          </div>
                        </div>
                      </div>

                    </template>
                  </transition>
                  <transition name="fade">
                    <template v-if="step==1">
                      <div class="row mt-1 ">
                        <div class="col-12 flex justify-center"> 
                          <label for="winner_photo"  >
                            <div class="py-2" style="text-decoration: underline; cursor:pointer;" :class="{'text-positive': winner.winner_photo}">
                              <img :src="previewImg" alt="" style="height: 15rem;  margin: auto; border-radius: 1rem; object-fit: cover;" v-if="previewImg">
                            </div>
                            <div class="pb-5 pt-2 text-subtitle1 text-center" style="text-decoration: underline; cursor:pointer;" :class="{'text-positive': previewImg}">
                              {{ !previewImg ? 'Agregar foto de la entrega' :'Foto de la entrega ✅' }} 
                            </div>
                          </label>
                        </div>
                        <div class="col-12  md:pl-2 mb-3 md:mt-2">
                          <q-input
                            v-model="winner.link_ig"
                            label="Link de publicación de Instagram"
                            class=" updateWinnerForm__input"
                          />
                        </div>
                        <div class="col-12  md:pl-2 mb-3 md:mt-2" v-if=" winner.rifa.id > 0">
                           
                          <q-input
                            v-model="winner.link_tt"
                            label="Link de publicación de Telegram"
                            class=" updateWinnerForm__input"
                          />
                        </div>

                      </div>

                    </template>
                  </transition>
                </div>
              </q-card-section>
            </section>
          </div>
          <section>
            <div class="flex justify-evenly mt-5">
              <input type="file"  id="winner_photo" accept="image/*"  style="display: none;" @change="onFileChange" >
              <q-btn label="Volver"  color="black"  class="q-mx-sm " style="width: 35%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="hideModal()" />
              <q-btn :label="step== 0 ? 'Siguente' : 'Completar'" color="blur" type="submit" style="width: 50%; border-radius: 0.8rem; padding: 0.7rem 0px;" :loading="loading"/>
            </div>
          </section>
        </q-form>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">
.n-input--resizable{
  height: 4rem!important;
  width: 3rem!important;
  & .n-input__placeholder{
    font-size: 1.8rem;
  }
  & .n-input__border{
    border-color: black;
  }
  & .n-input__input{
    height: 100%;
    & .n-input__input-el{
      height: 100%;
      font-size: 1.8rem;

    }

  }
}
.updateWinnerDialog{
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
.updateWinnerForm__input {
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

.slotWinner__img{
  height: 14rem; width: 11rem; background: #111; border-radius: 1rem;
  &:hover{
    background: #484848;
  }
}
.updateWinnerForm__input {
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
.overflowWinner__img{
  opacity: 0;
  position: absolute; 
  top:0; bottom:0; left:0; right:0; 
  background:rgba(34, 34, 34, 0.539);
  border-radius: 1rem;
  transition: all 0.5 ease;
}

</style>