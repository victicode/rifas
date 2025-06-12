<script setup>
import headerClient from '@/components/layouts/headerClient.vue';
</script>
<template>
  <section class="h-full ">
    <headerClient/>
    <div class="h-5/6" style="overflow: auto;">
      <router-view v-slot="{ Component }">
        <transition name="fade">
          <component :is="Component" />
        </transition>
      </router-view>
    </div>
  </section>
</template>
<style>

</style>