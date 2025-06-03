<template>
  <section class="h-full ">
    <div>
      
    </div>
    <div>
      <div>
  
      </div>
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