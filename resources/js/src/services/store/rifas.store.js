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
    async getAllRifas(query) {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) throw ''

        ApiService.setHeader();
        ApiService.get('/api/rifas/all')
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
    async getRifasActive() {
      return await new Promise((resolve, reject) => {
        ApiService.get('/api/public/rifas/active')
          .then(({ data }) => {
            if(data.code!=200) throw data;
            
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
    async getDataStadisticRifas() {
      return await new Promise((resolve, reject) => {
        ApiService.get('/api/public/rifas/stadistics')
          .then(({ data }) => {
            if(data.code!=200) throw data;
            
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
    async getRifaById(id) {
      return await new Promise((resolve, reject) => {
        ApiService.get('/api/public/rifas/byId/'+id)
          .then(({ data }) => {
            if(data.code!=200) throw data;
            
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
    async getTicketsByRifa(id) {
      return await new Promise((resolve, reject) => {
        ApiService.get('/api/rifas/tickets/'+id)
          .then(({ data }) => {
            if(data.code!=200) throw data;
            
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
        ApiService.post('/api/rifas', data)
        .then((data) => {
          if(data.status !=200) throw data;
          
          resolve(data);
        }).catch(( response ) => {
          console.log(response)
          reject(response.response.data.error);
        });
        
      })

    },
    async updateRifa(id, data){
      return await new Promise((resolve, reject) => {
        if(!ApiService.getToken()) throw ''

        ApiService.setHeader();
        ApiService.post('/api/rifas/u/'+id, data)
        .then((data) => {
            if(data.status !=200) throw data;
            resolve(data);
        })
        .catch((response) => {
          console.log(response)
          reject('Error al editar premios');
        })
      })
    },
    async deleteRifa(id){
      return await new Promise((resolve, reject) => {
        if(!ApiService.getToken()) throw ''

        ApiService.setHeader();
        ApiService.post('/api/rifas/d/'+id)
        .then((data) => {
            if(data.status !=200) throw data;
            resolve(data);
        })
        .catch((response) => {
          console.log(response)
          reject('Error al editar premios');
        })
      })
    },
    async updateStatus(id, data){
      return await new Promise((resolve, reject) => {
        if(!ApiService.getToken()) throw ''

        ApiService.setHeader();
        ApiService.post('/api/rifas/status/u/'+id, data)
        .then((data) => {
            if(data.status !=200) throw data;
            resolve(data);
        })
        .catch((response) => {
          console.log(response)
          reject('Error al editar premios');
        })
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

    },
    async getRifaWithReport(id){
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) throw ''

        ApiService.setHeader();
        ApiService.get('/api/rifas/with_report/'+id)
          .then(({data}) => {
            console.log(data)
            if(data.code !=200) throw data;
            
            resolve(data);
          }).catch(( response ) => {
            console.log(response)
            reject('Error al obtener rifas');
          });
        
      })
      .catch(( response ) => {
        console.log(response)
        return 'Error al obtener datos';
      });
    }
  },
})