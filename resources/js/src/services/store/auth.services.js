import { defineStore } from 'pinia'
import ApiService from '@/services/axios'
import storage from '@/services/storage'
export const useAuthStore = defineStore('auth', {
  state: () => ({ user: {} }),
  actions: {
    setAuth({data}){
      this.setUser(data)
      // this.setIsAdmin(user.data.user)
    },
    setUser(user){
      this.user = user;
    },
    setIsAdmin(user){
      // storage.setItem("is_admin",  user.rol_id !== '3' ? true : false);
      // storage.setItem("user_unique_id",user.id);
    },
    setRememberAccount({user, password, remember}){
      storage.setItem("rememberUser", user);
      storage.setItem("rememberPassword", password);
      storage.setItem("isRemember", remember);
    },
    clearRememberAccount(){
      storage.deleteItem("rememberUser");
      storage.deleteItem("rememberPassword");
      storage.deleteItem("isRemember");
    },
    preLogin(credentials){
      this.clearRememberAccount()
      if(credentials.remember == true) this.setRememberAccount(credentials)
    },
    saveToken(token){
      storage.setItem("access_token",token);

    },
    async login(credentials) {
      return await new Promise((resolve, reject) => {
        // ApiService.setHeader()
        ApiService.get('/sanctum/csrf-cookie')
        .then((response) => {
          ApiService.post('api/login', credentials)
          .then(({data}) => {
            if(!data.data.token){
              throw data;
            }
            this.saveToken(data.data.token)

            this.currentUser().then((res) => {
              resolve(res)
            })
            
          }).catch(({response}) =>{
  
            reject(response)
          })
        }).catch(({response}) =>{

          reject(response)
        })
      }) 
      .catch((response) => {
        return response
      })
    },
    async currentUser() {
      return await new Promise((resolve, reject) => {
        if (!ApiService.getToken()) {
          throw '';
        }
        ApiService.setHeader();
        ApiService.get("/api/user")
          .then((data) => {
            if(data.status !=200){
              throw data;
            }
            this.setAuth(data)
            resolve(data);
          }).catch(( response ) => {
            console.log(response)
            reject('Error al obtener usuario');
          });
        
      })
      .catch(( response ) => {
        console.log(response)
        return 'Error al obtener';
      });
    },
    async logout(){
      return await new Promise((resolve) => {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.get("api/auth/logout")
            .then(({ data }) => {
              if(data.code !== 200){
                throw data;
              }
              this.logoutAction()
              resolve(data)
            })
        }
      })
      .catch(( response ) => {
        console.log(response)
        resolve('Error al cerrar sesión');
      });
    },
  },
})