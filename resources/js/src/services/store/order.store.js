import { defineStore } from 'pinia'
import ApiService from '@/services/axios'
import storage from '@/services/storage'
export const useOrderStore = defineStore('Order', {
  actions: {
    async createOrder(data) {
      return await new Promise((resolve, reject) => {
        ApiService.post('/api/public/order', data)
        .then((data) => {
          if(data.status !=200) throw data;
          
          resolve(data);
        }).catch(( response ) => {
          console.log(response)
          reject(response.response.data.error);
        });
        
      })

    },

  },
})