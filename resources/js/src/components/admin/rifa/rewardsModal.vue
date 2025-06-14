<script setup>
import { onMounted, ref, watch} from 'vue';
import { Notify } from 'quasar'
import moment from 'moment';
import { useRifaStore } from '@/services/store/rifas.store';
  const rifaStore = useRifaStore()
  const emit = defineEmits(['closeModal', 'updateReward'])
  const props = defineProps({
    dialog: Boolean,
    rifa: Object,
  })

  const dialog    = ref(props.dialog); 
  const rewards   = ref([])
  const editView  = ref(false)
  const loading   = ref(false)
  const hideModal = () => {
    editView.value = false
    
    emit('closeModal')
  }
  const showNotify = (type,text) => {
    Notify.create({
      color:type,
      message: text,
      timeout:2000
    })
  }
  const updateReward = () => {
    loading.value  = true
    const formData = new FormData()
    formData.append('rewards', JSON.stringify(rewards.value))
    rifaStore.updateRewards({id: props.rifa.id, form: formData})
    .then((response) => {
      loading.value  = false
      showNotify('positive', 'Premios actualizados con exito')
      
      rewards.value = formatReward(response.data.data)
      emit('updateReward', { rewards:rewards.value, id: props.rifa.id })
    })
  }
  const addReward = () => {
    rewards.value.push({
      title:'',
      position:'',
      time:'',
    })
  }
  const deleteReward = (index) => {
    rewards.value.splice(index, 1)
  }
  const formatReward = (rewards) => {
    let value = []
    rewards.forEach((reward) => {
      value.push({
        title: reward.title,
        reward_time: reward.reward_time,
      })
    });         
    return value
  }
  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue

    rewards.value = formatReward(props.rifa.rewards)
  });

  onMounted(() => {
   rewards.value =  formatReward(props.rifa.rewards)
  })
  
</script>
<template>
   <q-dialog v-model="dialog" class="createRifaDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_documentCard" style="border-radius:1rem">
        <div class="close__button">
          <q-btn round color="primary" icon="close" @click="hideModal()" />
        </div>
        <q-card-section>
          <div class="text-h5 text-center text-bold">
           Premios de {{ rifa.title }}
          </div>
        </q-card-section>
        <section class="content__modalSection">
          <transition name="fade">
            <template v-if="editView">
              <div class="q-px-md">
                <div class="row my-3" v-for="(reward, index) in rewards" :key="index">
                  <div class="col-md-6 col-12 md:pr-2  pr-1 md:mb-0" >
                    <q-input
                      outlined
                      v-model="reward.title"
                      :label="'Premio n° '+(index+1)"
                      class=" updateRewardForm__input"
                      :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                    />
                  </div>
                  <div class="col-md-5 col-10 md:px-2 px-1 md:mb-0" >
                    <q-input
                      outlined
                      v-model="reward.reward_time"
                      label="Hora de premiación"
                      class=" updateRewardForm__input"
                      :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                    >
                    <template v-slot:append>
                        <q-icon name="access_time" class="cursor-pointer">
                          <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                            <q-time v-model="reward.reward_time">
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
                    <q-btn  color="negative" icon="delete" round  @click="deleteReward(index)" />
                  </div>
                </div>
              </div>
            </template>
          </transition>
          <transition name="fade">
            <template v-if="!editView">
              <q-card-section class="q-pt-none q-px-sm">
                
                <div v-for="(reward, index) in rifa.rewards" :key="reward.id" class="q-mb-sm q-pt-sm q-pb-sm  mx-2" style="border-bottom: 1px solid darkgray;">
                  <div class="flex justify-between items-center">
                    <div class="text-h6 text-bold text-black">
                      Premio n° {{ index+1 }}
                    </div>
                    <q-chip color="black" text-color="white"  class="">
                      <div class="text-caption">
                        Se juega a las {{moment(rifa.due_date +' '+reward.reward_time).format('hh:mm A')}}
                      </div>
                    </q-chip>
                  </div>
                  <div class="py-2 text-subtitle1 text-bold text-black">
                    {{ reward.title }}
                  </div>
                </div>
              </q-card-section>
            </template>
          </transition>
        </section>
        <q-card-section class="q-pt-none">
          <div class="flex justify-end mt-0" >
            <template v-if="editView" >
              <q-btn label="Premio" color="black" class="" icon="add"  @click="addReward()" />
              <q-btn style="border-radius: 0.5rem;" size="0.8rem" color="negative" class="mx-1" label="volver" @click="editView = false; rewards = formatReward(props.rifa.rewards)" />
            </template>
            <q-btn style="border-radius: 0.5rem;" size="0.8rem" color="primary"  class="" label="Editar" :loading="loading" @click="editView ? updateReward() : editView = true" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">

.updateRewardForm__input {
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
.dialog_documentCard {
  margin-left: 10%;
  min-width: 600px!important;
  max-width: 800px!important; 
  overflow: visible!important;
  position: relative;
}
@media (max-width: 768px){
  

  .createRifaDialog .q-dialog__inner--minimized{
    padding: 24px 1rem;
  }
  .dialog_documentCard {
    margin-left: 0%;
    min-width: 100%!important;
    max-width: 800px!important; 
  }
}
</style>