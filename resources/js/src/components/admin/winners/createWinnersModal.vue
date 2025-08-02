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
  })

  const emit = defineEmits(['updateList', 'closeModal'])
  const winnerStore = useWinnerStore()
  const loading = ref(false);
  const dialog = ref(props.dialog);

  const rifasOption = ref([])
  const rewardsOption = ref([])
  const previewImg = ref(null)


  const formInputs = ref({
    rifa: {
      title:'Selecciona la rifa',
      id:0
    },
    reward: {
      title:'Selecciona el premio',
      id:0
    },
    winnerNumber: null,
    photoWinner: null,
  })

  const onFileChange = () => {
    const file = document.getElementById('winner_photo').files[0]
    formInputs.value.photoWinner = file
    
    return previewImg.value= URL.createObjectURL(file)
  }

  const loadingShow = (state) => {
    loading.value = state;
  }
  const hideModal = () => {
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
        rifa: {
        title:'Selecciona la rifa',
        id:0
      },
      reward: {
        title:'Selecciona el premio',
        id:0
      },
      winnerNumber: null,
      photoWinner: null,

    }
  }
  const setRewardsOption = (rifa) => {
    formInputs.value.reward = {
        title:'Selecciona el premio',
        id:0
    }
    formInputs.value.winnerNumber = null
    formInputs.value.photoWinner = null,
    rewardsOption.value = rifa.rewards
  }

  const updateList = () => {
    hideModal()
    emit('updateList')
  }
  const createWinner = () => {
    loadingShow(true)
    const formData = new FormData()
    formData.append('rifa', (formInputs.value.rifa.id));
    formData.append('reward', formInputs.value.reward.id)
    formData.append('ticket', formInputs.value.winnerNumber.join(''))
    formData.append('photo', formInputs.value.photoWinner)


    winnerStore.createWinner(formData)
    .then((response) => {
      if(response.code !== 200 ) throw response
      showNotify('positive', 'Ganador agregado con exito')
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
  });
    
  onMounted(() => {
    getRifas()
  })
  

</script>
<template>
   <q-dialog v-model="dialog" class="createWinnerDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 order__form"
          style="height: 100%; "
          @submit="createWinner()"
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
                  <div class="row mt-1 ">
                    
                    <div class="col-12  md:pl-2 mb-3 md:mt-0">
                      <q-select
                        v-model="formInputs.rifa"
                        label="Selecciona la rifa"
                        option-value="id"
                        option-label="title"
                        behavior="menu"
                        color="primary"
                        class="createOrderForm__input"
                        @update:model-value="setRewardsOption"
                        :options="rifasOption"
                        :rules="[ (val, rules) => val.id != 0 || 'Debes seleccionar una rifa' ]"
                      />
                    </div>
                    <div class="col-12  md:pl-2 mb-3 md:mt-0" v-if=" formInputs.rifa.id > 0">
                      <q-select
                        v-model="formInputs.reward"
                        label="Selecciona el premio"
                        option-value="id"
                        option-label="title"
                        behavior="menu"
                        color="primary"
                        class="createOrderForm__input"
                        :options="rewardsOption"
                        :rules="[ (val, rules) => val.id != 0 || 'Debes seleccionar una rifa' ]"
                      >
                        <template v-slot:option="scope">
                          <q-item v-bind="scope.itemProps">
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
                    <div class="col-12  md:pl-2 mb-3 md:mt-0 " v-if=" formInputs.rifa.id > 0 && formInputs.reward.id > 0">
                      <div class="pb-5 text-center text-subtitle1">
                        Ingresa el número del ticket ganador
                      </div>
                      <div class="flex flex-center w-full ">
                        <div style="border:1px solid black; border-radius: 1rem" class=" p-4">
                          <n-input-otp
                            v-model:value="formInputs.winnerNumber"
                            length="4"
                            placeholder="0"
                            size="large"
                            gap="1rem"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-12 flex justify-center"  v-if=" formInputs.rifa.id > 0 && formInputs.reward.id > 0"> 
                      <div class="py-2">
                        <label for="winner_photo" class="label_img">
                          <div v-if="!previewImg" style="" class="flex justify-center items-center slotWinner__img">
                              <q-icon name="add" color="white" size="5rem"/>
                          </div>
                          <div class="img__container" v-else>
                            <img  :src="previewImg" class="slotWinner__img" alt="">
                            <div class="flex justify-center items-center overflowWinner__img" style="">
                              <q-icon name="add" color="white" size="5rem"/>
                            </div>
                          </div>
                        </label>
                        <div class="text-subtitle1">
                          Agrega foto de la entrega
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </q-card-section>
            </section>
          </div>
          <section>
            <div class="flex justify-evenly mt-5">
              <input type="file"  id="winner_photo" ref="winner_photo" accept="image/*"  style="display: none;" @change="onFileChange" >
              <q-btn label="Volver"  color="black"  class="q-mx-sm " style="width: 35%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="hideModal()" />
              <q-btn label="Completar" color="blur" type="submit" style="width: 50%; border-radius: 0.8rem; padding: 0.7rem 0px;" :loading="loading"/>
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
.createWinnerDialog{
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

.slotWinner__img{
  height: 14rem; width: 11rem; background: #111; border-radius: 1rem;
  &:hover{
    background: #484848;
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