import App from '@/App.vue'
import { createApp } from 'vue';
import quasarIconSet from 'quasar/icon-set/eva-icons'
import { createPinia } from 'pinia'
import {
  Quasar,
  Notify
} from 'quasar'
import router  from '@/routes'
import '@quasar/extras/material-icons/material-icons.css'
import '@quasar/extras/ionicons-v4/ionicons-v4.css'
import '@quasar/extras/eva-icons/eva-icons.css'

import 'quasar/src/css/index.sass'
import { useAuthStore } from '@/services/store/auth.services';
const pinia = createPinia()

const myApp = createApp(App)

myApp.use(Quasar, {
  plugins: {
    Notify
  },
  iconSet: quasarIconSet,
})

myApp.use(pinia)
myApp.use(router)

useAuthStore().currentUser().then(() => {
  myApp.mount('#app')
})
