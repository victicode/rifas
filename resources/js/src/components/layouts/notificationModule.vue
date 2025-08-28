<script setup>

import { ref, onMounted} from 'vue';
import { useNotificationStore } from '@/services/store/notification.store';
import notificationSound from '@/assets/audio/alert4.mp3'
import notificationModal from '@/components/layouts/notificationModal.vue';

const page = ref(1)
const notifications = ref([]) 
const countNotView = ref(0)
const sound = new Audio(notificationSound)

const getNotifications = () => {
  const query = {
    page: page.value
  }
  
  useNotificationStore().getNotification(query)
  .then(({data}) => {
    // console.log(data)
    notifications.value = data.notifications.data
    countNotView.value = data.countNotView
  })
}
const viewAll = () => {

  
  countNotView.value = 0
  useNotificationStore().viewAllnotification()
  .then(({data}) => {
    getNotifications()
  })
}
const closeModal = () => {
  viewAll()
  dialog.value=false
}
const dialog = ref(false)
onMounted(() => {
  getNotifications()
  window.Echo
  .channel('orderStatusUpdated')
  .listen('OrderStatusUpdated', async () => {
    getNotifications()
    sound.play()
  })
})

</script>

<template>
  <div>

    <div class="relative flex flex-center ">
      <q-btn flat round color="white" class="mx-1" text-color="white" icon="notifications" @click="dialog=true"  />
      <div class="notificationBadge flex flex-center" v-if="countNotView > 0">{{ countNotView }}</div>
    </div>

    <notificationModal :dialog="dialog" :notifications="notifications"  @closeModal=" closeModal()" />

  </div>
</template>
<style lang="scss">
.notificationBadge{
  background: red;
  height: 1.2rem;
  width: 1.2rem;
  border-radius: 0.2rem;
  top: 0rem;
  right: 0.4rem;
  position: absolute;
  color: white;
  font-weight: 900!important;
  
}
</style>