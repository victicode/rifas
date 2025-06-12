<script setup>
import { onMounted, ref, watch} from 'vue';

  const dialog = ref(false)
   
  const reject = () => {
    dialog.value = false

    setTimeout(() => {
      dialog.value = true
    }, 100);
  }
  const conditions = [
    'La compra mínima para poder participar son 2 números y el monto se fija en bs y no varía dentro del lapso de tiempo que se vendan los tickets.',
    'La Lotería se llama Super Gana avalada por la Lotería del Tachira 4 dígitos 10.000 números.',
    'Participa cualquier personal natural mayor de edad venezolano o extranjero que resida o no en el país siempre y cuando tenga un familiar o allegado que pueda hacer presencia dentro de un lapso máximo de 60 dias.',
    'Los ganadores deberán aceptar que se difunda fotos y videos de ellos y del premio en nuestras redes sociales para corroborar la fidelidad de la misma.',
  ]
</script>
<template>
   <q-dialog v-model="dialog" class="createRifaDialog" persistent backdrop-filter="blur(5px)">
      <q-card class="dialog_documentCard" style="border-radius:1rem">
        <q-card-section class="q-pb-sm">
          <div class="text-h5 text-black text-bold">
           Términos y condiciones
          </div>
        </q-card-section>
        <q-card-section class="content__modalSection  modal-temrs" style="">
          <div class="row md:px-2" >
            <div class="col-12 my-2 text-black" style="font-size:1rem" v-for="(condition, index) in conditions" :key="index">
              {{ (index+1) }}) {{ condition }}
            </div>
          </div>
        </q-card-section>
        <q-card-section class="q-pt-sm">
          <div class="flex justify-between mt-2 md:px-2" >
            <q-btn style="border-radius: 0.5rem;" size="" color="primary"  class="" label="Rechazar" @click="reject()" />

            <q-btn style="border-radius: 0.5rem;" size="" color="primary"  icon="fact_check" class="" label="Aceptar" @click="dialog = false"  />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">
.modal-temrs{
  border-top: 1px solid darkgray;
  border-bottom: 1px solid darkgray;

}
.dialog_documentCard {
  margin-left: 10%;
  min-width: 600px!important;
  max-width: 800px!important; 
  overflow: visible!important;
  position: relative;
}
@media (max-width: 768px){
  
  .close__button{
    position: absolute;
    right: -1rem;
    top: -1rem;
    z-index: 2;
  }
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