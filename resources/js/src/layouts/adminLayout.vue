<script setup>
import sideBar from '@/components/layouts/sideBar.vue';
import headerAdmin from '@/components/layouts/headerAdmin.vue';
</script>
<template>
  <section class="h-full flex">
    <sideBar />
    <div class="w-5/6 h-screen container__page">
      <headerAdmin />
      <section class=" py-5 px-8 h-5/6" >
        <router-view v-slot="{ Component }">
          <transition name="fade">
            <component :is="Component" />
          </transition>
        </router-view>
      </section>
    </div>
  </section>
</template>
<style lang="scss">
.container__page{
  overflow: auto;
}

</style>