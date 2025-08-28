<script setup>
import { ref, watch} from 'vue';
import mainIcons from '@/assets/icons/rifaIcons'
import moment from 'moment';
import 'moment/locale/es'
  moment.updateLocale('es', {
      relativeTime : {
          future: "en %s",
          past:   "Hace %s",
          s  : 'algunos segundos',
          ss : '%d segundos',
          m:  "un minuto",
          mm: "%d minutos",
          h:  "una hora",
          hh: "%d horas",
          d:  " un día",
          dd: "%d días",
          w:  "una semana",
          ww: "%d semanas",
          M:  "un mes",
          MM: "%d meses",
          y:  "un año",
          yy: "%d años"
      }
  });
  moment.locale('es');
  const props = defineProps({
    dialog: Boolean,
    notifications: Object
  })

  const emit = defineEmits([ 'closeModal'])

  const dialog = ref(props.dialog);
  const notifications = ref(props.notifications);
  const hideModal = () => {
    emit('closeModal')
  }
  
  watch(() => props.dialog, (newValue) => {
    dialog.value = newValue
  });

  watch(() => props.notifications, (newValue) => {
    notifications.value = newValue
  });

  


</script>
<template>
   <q-dialog v-model="dialog" class="viewNotificationDialog" persistent backdrop-filter="blur(2px)">
      <q-card class="dialog_document" style="border-radius:1rem">
        <div
          class=" pb-5 client__cont"
          style="height: 100%; "
        >
          <div style="height: 92%;" class="">
            <q-card-section class="">
              <div class="text-h6 text-center text-black pt-2 text-bold">
                Notificaciones
              </div>
            </q-card-section>
            <section class="content__modalSectionRifa  mt-0 pb-8" v-if="notifications.length > 0" >
              <div 
                :class="{'notRead': notification.is_read == 0}" 
                class="row px-2 md:px-5 md:py-3 pt-5 pb-3 relative items-center" 
                style="cursor: pointer; border-top: 1px solid lightgray; flex-wrap: nowrap;" 
                v-for="notification in notifications" 
                :key="notification.id"
              >
                <div>
                  <div style="" class="flex flex-center iconback ">
                   <div v-html="mainIcons.notificationI" />
                  </div>
                </div>
                <div class="md:px-4 px-2">
                  <div class="text-bold text-subtitle1x text-black">{{ notification.title }}</div>
                  <div class="text-grey-7 md:mt-1  text-subtitle2x" style="font-weight: 500;">{{ notification.content }}</div>
                </div>
                <div class="date_display">
                  {{ moment(notification.created_at).fromNow() }}
                </div> 
              </div>
            </section>
            <section class="content__modalSectionRifa  mt-0 pb-8 text-center text-black text-h6 pt-4" v-else >
              No hay notificaciones
            </section>
          </div>
          <section>
            <div class="flex justify-center mt-0">
              <q-btn label="Cerrar"  color="black"  class="q-mx-sm " style="width: 80%; border-radius: 0.8rem; padding: 0.7rem 0px;" @click="hideModal()" />
            </div>
          </section>
        </div>
      </q-card>
    </q-dialog>
</template>
<style lang="scss">
.notRead{
  background:rgba(248, 220, 96, 0.11); 
  &::before{
    content: '';
    height: 80%;
    width: 0.35rem;
    top: 0.5rem;
    border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
    left: 0.1rem;
    background: rgb(248, 220, 96);
    position: absolute;
  }
}
.iconback{
  height: 3.5rem; width: 3.5rem; border-radius: 3.5rem; background: #ffe18b;
}
.date_display{
  position: absolute;
  right: 1rem;
  top: 0.5rem;
  font-size: 0.82rem;
  font-weight: 500;
  color: rgb(184, 184, 184);
}
.statusInput.q-field--auto-height.q-field--labeled .q-field__control-container{
  padding-top: 10px;
}
.viewNotificationDialog{
  margin-left: 0%;
  min-width: 10%!important;

  overflow: visible!important;
  position: relative;
  & .dialog_document{
    border-radius: 1rem !important;
    height: 95%;
    max-height: 100%!important;
    
  }
  & .q-dialog__inner--minimized{
    padding: 0px;
  }
}
.client__cont{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.content__modalSectionRifa{
    overflow: auto;
    max-height: 90%;

}
.text-subtitle1x{
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.75rem;
  letter-spacing: 0.00937em;
}
.text-subtitle2x{
  font-size: 0.875rem;
  font-weight: 500;
  line-height: 1.375rem;
  letter-spacing: 0.00714em;
}
@media (max-width: 768px){

.viewNotificationDialog{
  margin-left: 0%;
  min-width: 10%!important;

  overflow: visible!important;
  position: relative;
  & .dialog_document{
    border-radius: 1rem !important;
    height: 95%;
    max-height: 100vh!important;
    min-width: 95%!important;
    width: 95%!important;

  }
  & .q-dialog__inner--minimized{
    padding: 0px;
  }

}

.text-subtitle1x{
  font-size: 0.9rem;
}
.text-subtitle2x{
  font-size: 0.75rem;
}
.iconback{
  height: 2.88rem; width: 2.88rem; border-radius: 2.88rem; background: #ffe18b;
  & svg{
     height: 25px; width: 25px; 
  }
}

.notRead{

  &::before{
    content: '';
    height: 90%;
    width: 0.25rem;
    top: 0.25rem;
    left: 0rem;

  }
}
.date_display{
  right: 0.8rem;
  top: 0.4rem;
  font-size: 0.7rem;

}
}



</style>