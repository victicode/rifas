<template>
  <aside class="h-full w-1/6 aside__menu q-px-md">
    <div>
      <div class="text-white text-center text-h6 py-6 title__app-aside">
        RIFAS APP
      </div>
      <div class="q-px-md " style="border-top: 1px solid darkgrey;">
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/dashboard">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Inicio
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/rifas">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Rifas
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/pagos">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Pagos
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/pay_methods">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Metodos de pago
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/clients">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Clientes
            </div>
          </RouterLink>
        </div>
        <div class=" py-3 q-px-md text-subtitle1 item__menu-aside mt-6">
          <RouterLink activeClass="active_link" to="/admin/configuration">
            <div class="text-center font-bold" style="transition:all ease 0.5s">
              Sistema
            </div>
          </RouterLink>
        </div>
      </div>
    </div>
  </aside>
</template>
<style lang="scss">
.aside__menu{
  box-shadow: 5px 0px 26px 0px #9a9a9a;
  background: rgb(17, 17, 17);

}

.item__menu-aside{
  
  background: #f9fafb;
  border-radius: 0.8rem;
  cursor: pointer;
  transition: all  ease 0.5s;
  color: black;
  &:hover{
    background: #414141;
    & .text-center {
      color: white
    }
  }
  &:has(.active_link){
    background: #414141;
    & .text-center {
      color: white
    }

  }

}

</style>