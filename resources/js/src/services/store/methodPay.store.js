import { defineStore } from 'pinia'
import ApiService from '@/services/axios'
import storage from '@/services/storage'
export const useMethodPayStore = defineStore('MethodPay', {
  actions: {
    async getMethodPays() {
      return await new Promise((resolve, reject) => {
        ApiService.get('/api/public/method_pays')
        .then(({data}) => {
          console.log(data)
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