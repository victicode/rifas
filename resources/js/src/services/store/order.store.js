import { defineStore } from 'pinia'
import ApiService from '@/services/axios'
import storage from '@/services/storage'
export const useOrderStore = defineStore('Order', {
  actions: {
    async createOrder(data) {
      return await new Promise((resolve, reject) => {
        ApiService.post('/api/public/order', data)
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response}) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },
    
    async getOrderById(id) {
      return await new Promise((resolve, reject) => {
        ApiService.get('/api/public/order/byId/'+id)
        .then(({data}) => {
          if(data.code !=200) throw data;
  
          resolve(data);
        }).catch(( {response}) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },
    async getPaginationOrders(data) {
      return await new Promise((resolve, reject) => {
        ApiService.get('/api/orders?page='+data.page+'&'+'search='+data.search+'&searchType='+data.searchType+'&')
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response}) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })
    },
    async updateStatus(data) {
      return await new Promise((resolve, reject) => {
        ApiService.post('/api/orders/changeStatus/'+data.id, data)
        .then(({data}) => {
          if(data.code !=200) throw data;
          
          resolve(data);
        }).catch(( {response}) => {
          console.log(response)
          reject(response.data.error);
        });
        
      })

    },
  },
})