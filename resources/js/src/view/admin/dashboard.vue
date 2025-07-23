<script setup>
import { useAuthStore } from '@/services/store/auth.services';
import { storeToRefs } from 'pinia'
import dashboardRifasActive from '@/components/admin/dashboard/dashboardRifasActive.vue';
const { user } = storeToRefs(useAuthStore());
</script>
<template>
  <div>
    <div class="flex items-center">
        <div class="icon_badgeDash q-pa-sm flex items-center justify-center">
          <q-icon name="person_4" color="white" size="1.5rem"/>
        </div>
        <h5 class="text-black font-bold ml-2" >
          Bienvenido {{user.name}}
        </h5>
      </div>
    <div class="row pt-6">
      <dashboardRifasActive />
    </div>
  </div>
</template>

<style lang="scss">
.card__dash{
  background: white;
  border-radius: 1rem;
  overflow:hidden;
  box-shadow: 5px 0px 26px 0px #dfdfdf;
  border: 1px solid black;

}
.icon_badgeDash{
  height: 2.5rem;
  width: 2.5rem;
  border-radius: 0.3rem;
  background: black;
  border: 1px solid #7f7f7f;
}
</style>