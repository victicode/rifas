<script setup>
import headerClient from '@/components/layouts/headerClient.vue';
import sideBarClient from '@/components/layouts/sideBarClient.vue';
import whatsappFloatButton from '@/components/client/whatsappFloatButton.vue';
import footerClient from '@/components/layouts/footerClient.vue';

</script>
<template>
  <section class="h-full ">
    <sideBarClient  />

    <headerClient class="header-height"/>
    <div class="body-height" style="overflow: auto;">
      <router-view v-slot="{ Component }">
        <transition name="fade">
          <component :is="Component" />
        </transition>
      </router-view>
      <whatsappFloatButton />
      <footerClient/>
    </div>
    
  </section>
</template>
<style lang="scss">
.header-height{
  height: 11vh;
  overflow: hidden;
}
.body-height{
  height: 89vh;
  overflow: auto;
  position: relative;
  &::-webkit-scrollbar {
    width: 0.5rem;
    border-radius: 2rem;
    background: transparent;
  }
  &::-webkit-scrollbar-track {
    display: none;
  }
  &::-webkit-scrollbar-thumb {
    background: #050505;
    border-radius: 2rem;

  }

}
@media (max-width: 768px){
.header-height{
  height: 13vh;
}
.body-height{
  height: 86vh;
}
}
</style>
