import { defineStore } from 'pinia'
import ApiService from '@/services/axios'
import storage from '@/services/storage'
export const useRifaStore = defineStore('rifa', {
  state: () => ({ user: {} }),
  actions: {

    async getRifas(query) {
      return await new Promise((resolve, reject) => {
        
        if (!ApiService.getToken()) throw ''

        ApiService.setHeader();
        ApiService.get('/api/rifas?page='+query.page+'&')
          .then((data) => {
            if(data.status !=200) throw data;
            
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
    async createRifa(data) {
      return await new Promise((resolve, reject) => {
        
        if (!ApiService.getToken()) throw ''
        ApiService.setHeader();
        ApiService.post('/api/rifas/create', data)
        .then((data) => {
          if(data.status !=200) throw data;
          
          resolve(data);
        }).catch(( response ) => {
          console.log(response)
          reject(response.response.data.error);
        });
        
      })

    },
    async updateRewards(data) {
      return await new Promise((resolve, reject) => {
        if(!ApiService.getToken()) throw ''
        ApiService.setHeader();
        ApiService.post('/api/rifas/rewards/u/'+data.id, data.form)
        .then((data) => {
            if(data.status !=200) throw data;
            resolve(data);
        })
        .catch((response) => {
          console.log(response)
          reject('Error al editar premios');
        })
      })

    }
  },
})