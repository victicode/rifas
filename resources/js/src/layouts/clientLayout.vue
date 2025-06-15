<script setup>
import headerClient from '@/components/layouts/headerClient.vue';
</script>
<template>
  <section class="h-full ">
    <headerClient class="header-height"/>
    <div class="body-height" style="overflow: auto;">
      <router-view v-slot="{ Component }">
        <transition name="fade">
          <component :is="Component" />
        </transition>
      </router-view>
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
  &::-webkit-scrollbar {
    width: 0.5rem;
    border-radius: 2rem;
    background: transparent;
  }
  &::-webkit-scrollbar-track {
    display: none;
  }
  &::-webkit-scrollbar-thumb {
    background: #aa13a4;
    border-radius: 2rem;

  }

}
@media (max-width: 768px){
.header-height{
  height: 14vh;
}
.body-height{
  height: 86vh;
}
}
</style>
