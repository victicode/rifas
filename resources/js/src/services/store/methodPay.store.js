import { defineStore } from 'pinia'
import ApiService from '@/services/axios'
import storage from '@/services/storage'
export const useMethodPayStore = defineStore('MethodPay', {
  actions: {
    

    async getMethodPaysActive() {
      return await new Promise((resolve, reject) => {
        ApiService.get('/api/public/method_pays')
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response} ) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },
    async getPayMethods() {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) {
          throw '';
        }
        ApiService.setHeader();
        ApiService.get('/api/methods_pay/methods')
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response} ) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },
    async updatePayMethod(data) {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) {
          throw '';
        }
        ApiService.setHeader();
        ApiService.post('/api/methods_pay/methods/u/'+data.id, data.data)
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response} ) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },
    async getMethodsData() {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) {
          throw '';
        }
        ApiService.setHeader();
        ApiService.get('/api/methods_pay')
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response} ) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },
    async createMethodData(data) {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) {
          throw '';
        }
        ApiService.setHeader();
        ApiService.post('/api/methods_pay/data', data)
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response} ) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },
    async updateMethodData(data) {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) {
          throw '';
        }
        ApiService.setHeader();
        ApiService.post('/api/methods_pay/data/u/'+data.id, data.data)
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response} ) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },
    async deleteMethodData(id) {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) {
          throw '';
        }
        ApiService.setHeader();
        ApiService.post('/api/methods_pay/data/d/'+id)
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