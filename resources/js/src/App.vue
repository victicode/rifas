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

</style>