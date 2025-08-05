import { defineStore } from 'pinia'
import ApiService from '@/services/axios'

export const useWinnerStore = defineStore('Winners', {
  actions: {
    async getWinners(query) {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) throw ''

        ApiService.setHeader();
        ApiService.get('/api/winners?page='+query.page+'&')
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
    async getWinnersPublic(query){
      return await new Promise((resolve, reject) => {
  
        ApiService.get('/api/public/winners?page='+query.page+'&')
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
    async createWinner(data) {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) {
          throw '';
        }
        ApiService.setHeader();
        ApiService.post('/api/winners', data)
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