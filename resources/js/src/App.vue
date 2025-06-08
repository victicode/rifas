<template>
  <main  class="h-screen md:py-0">
    <RouterView class="appMobile h-full"/>
  </main>
</template>
<style lang="scss">
.dialog_document {
  margin-left: 10%;
  min-width: 600px!important;
  max-width: 800px!important; 
  overflow: visible!important;
  position: relative;
}
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
  transition: all 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  position: absolute!important;
  top: 0;
  // transform: scale(0);
}
.content__modalSection{
  overflow: auto; max-height: 34rem; 
}
@media (max-width: 768px){
  .content__modalSection{
    max-height: 36rem;
  }
  .createRifaDialog .q-dialog__inner--minimized{
    padding: 24px 1rem;
  }
  .dialog_document {
    margin-left: 0%;
    min-width: 100%!important;
    max-width: 800px!important; 
  }
}
</style>