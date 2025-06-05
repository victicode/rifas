<template>
  <main  class="h-screen md:py-0">
    <RouterView class="appMobile h-full"/>
  </main>
</template>
<style lang="scss">
.appMobile{
  //width: 60%;
  overflow: hidden;
  margin: auto;
  //border-radius: 20px;
}
@media (max-width: 780px) {
  .appMobile{
    width: 100%;
    border-radius: 0px;

  }
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  position: absolute;
}
</style>