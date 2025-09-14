<script setup>
import { ref, watch} from 'vue';
import { Notify } from 'quasar'
import { useRouter } from 'vue-router';
import { useUserStore } from '@/services/store/user.store';

  const props = defineProps({
    dialog: Boolean,
  })

  const isPwd =  ref(true)

  const emit = defineEmits(['updateList', 'closeModal'])
  const loading = ref(false);
  const dialog = ref(props.dialog);
  const userStore = useUserStore()

  const formInputs = ref({
    name:'',
    email:'',
    password:''
  })


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
      name:'',
      email:'',
      password:''
    }
  }
  const createUser = () => {

    loadingShow(true)
    userStore.createUser(formInputs.value)
    .then((response) => {
      if(response.code !==200) throw response

      showNotify('positive', 'Usuario creado con exito')

      setTimeout(() => {
        updateList()
        loading.value = false
      },1000)
    })
    .catch((response) => {
      console.log(response)
      loadingShow(false)
      showNotify('negative', response)
    })
  }

  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });


</script>
<template>
   <q-dialog v-model="dialog" class="createPayMethodDialog" persistent backdrop-filter="blur(8px)">
      <q-card class="dialog_document public" style="border-radius:1rem">
        <q-form
          class="md:px-5 pb-5 order__form"
          style="height: 100%; "
          @submit="createUser()"
        >
          <div>
            <q-card-section class="">
              <div class="text-h6 text-center text-black">
                Completa el formulario
              </div>
            </q-card-section>
            <section class="content__modalSectionRifa md:mt-5 mt-0">
              <q-card-section class="q-pt-none q-px-sm ">
                <transition name="fade">
                  <div class="px-2 ">
                    <div class="row mt-1 ">
                      <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0" >
                        <q-input
                          v-model="formInputs.name"
                          label="Nombre y apellidos"
                          class=" createUserForm__input"
                          :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                        />
                      </div>  
                      <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0" >
                        <q-input
                          v-model="formInputs.email"
                          label="Correo electronico"
                          class=" createUserForm__input"
                          :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                        />
                      </div>  
                      <div class="col-md-12 col-12 md:pr-2 mb-1 md:mb-0" >
                        <q-input
                          v-model="formInputs.password"
                          label="Contraseña"
                          class=" createUserForm__input"
                          :type="isPwd ? 'password' : 'text'"
                          :rules="[ val => val && val.length > 0 || 'El campo es obligatorio']"
                        >
                          <template v-slot:append>
                            <q-icon
                              :name="isPwd ? 'eva-eye-off-outline' : 'eva-eye-outline'"
                              class="cursor-pointer"
                              @click="isPwd = !isPwd"
                            />
                          </template>
                        </q-input>
                      </div>  
                    </div>
                  </div>
                </transition> 
              </q-card-section>
            </section>
          </div>
          <section>
            <div class="flex justify-evenly mt-5">
              <q-btn label="Cerrar"  color="black"  class="q-mx-sm " style="width: 35%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="hideModal()" />
              <q-btn label="Crear"   color="blur" type="submit" style="width: 50%; border-radius: 0.8rem; padding: 0.7rem 0px;" :loading="loading"/>
            </div>
          </section>
        </q-form>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">

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
.createUserForm__input {
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