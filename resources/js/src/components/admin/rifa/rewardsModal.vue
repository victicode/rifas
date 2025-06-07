<script setup>
import { onMounted, ref, watch} from 'vue';
import { Notify } from 'quasar'
import moment from 'moment';
import { useRifaStore } from '@/services/store/rifas.store';

  const props = defineProps({
    dialog: Boolean,
    rifa: Object,
  })

  const loading = ref(false);
  const dialog = ref(props.dialog); 
  const rewards = ref(props.rifa)

  const hideModal = () => {
    emit('closeModal')
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
        <q-card-section>
          <div class="text-h5 text-center text-bold">
           Premios de {{ rifa.title }}
          </div>
        </q-card-section>

        <q-card-section class="q-pt-none q-px-sm">
          <div v-for="(reward, index) in rifa.rewards" :key="reward.id">
            <div class="flex justify-between items-center">
              <div class="text-subtitle1 text-bold">
                Premio n° {{ index+1 }}
              </div>
              <div>
                <q-chip color="black" text-color="white"  class="">
                  <div class="text-caption">
                    Se juega el {{moment(rifa.due_date).format('DD/MM/YYYY')}}
                  </div>
                </q-chip>
              </div>
            </div>
            <div>
              {{ reward.title }}
            </div>
          </div>
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
  .createRifaDialog .q-dialog__inner--minimized{
    padding: 24px 1rem;
  }
  .dialog_document {
    margin-left: 0%;
    min-width: 100%!important;
    max-width: 800px!important; 
  }
}
</style>