import { defineStore } from 'pinia'
import ApiService from '@/services/axios'

export const useNotificationStore = defineStore('Notification', {
  actions: {
    async getNotification(query) {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) throw ''

        ApiService.setHeader();
        ApiService.get('/api/notifications?page='+query.page+'&')
          .then(({data}) => {
            if(data.code !=200) throw data;
            
            resolve(data);
          }).catch(( response ) => {
            console.log(response)
            reject('Error al obtener rifas');
          });
              
      })
      .catch(( response ) => {
        console.log(response)
        return 'Error al obtener rifas';
      });

    },
    async viewAllnotification() {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) {
          throw '';
        }
        ApiService.setHeader();
        ApiService.get('/api/notifications/view-all')
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response} ) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },



  },
})