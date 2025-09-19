<script setup>
import { useRifaStore } from '@/services/store/rifas.store';
import { onMounted, ref } from 'vue';
// import bgVideo from '@/assets/videos/horizontalVideo.mp4'
const rifaStore = useRifaStore()
const data = ref([])
const ready = ref(false)
const getDataRifa = () => {
  rifaStore.getDataStadisticRifas()
  .then((response) =>{
    data.value = response.data
    ready.value = true
  })
}




const countAnimation = () => {
  const counters = document.querySelectorAll('.count');
  const speed = 0.001;
  counters.forEach((counter) => {
    const updateCount = () => {
      const target = parseInt(counter.getAttribute('data-target'));

      const count = parseInt(counter.innerText);
      const increment = Math.trunc(target / speed);

      if (count < target) {
        counter.innerText = count + increment;
        setTimeout(updateCount, 5);
      } else {
        counter.innerText = target;
      }
    };
    updateCount();
  });
}
onMounted(() => {
  getDataRifa()
  setTimeout(() => {
    
    countAnimation()
  }, 1000);
})
</script>
<template>
  <div class=" relative " style="overflow:hidden" >
    <div class="absolute video__con">
      <div class="video-wrapper position-relative d-none d-992-block " style="height:-webkit-fill-available">
        <div class="video-container" style="height:100%">
            <video class="video-dt-lazy" id="hp_video" muted="" autoplay="" loop="" width="100%"  preload="metadata" playsinline="" style="cursor: pointer; object-fit: cover; ">
            <source src="https://ganaconlahijalinda.com/public/video/horizontalVideo.mp4" type="video/mp4"></video>
            <!-- <source :src="bgVideo" type="video/mp4"></video> -->
            
            
        </div>
      </div>
    </div>
    <div class="relative md:py-24" style="background:rgb(0 0 0 / 79%)">
      <div class=" py-16">
        <div class="">
          <div class="text-white text-center text-benefit-title">
            Nuestras bendiciones
          </div>
          <div class="text-white mt-2 text-center text-benefit-subtitle px-5">
            Gracias a ti por confiar y creer en nosotros, seguiremos dando mas bendiciones 
          </div>
        </div>
        <div v-if="ready" class="md:pl-4">
          <div class="row justify-center py-5">
            <div class="text-center col-md-3 col-6 px-3 md:px-14" >
              <div class="py-5 px-1  benefit__item">
                <div class="text-h3 mb-2 text-white count" :data-target="data.rifas ">
                  0
                </div>
                <div class="text-white text-bold" style="font-size:1.1rem">
                  Rifas completadas 🎟
                </div>
              </div>
            </div>
            <div class="text-center col-md-3 col-6 px-3 md:px-14" >
              <div class="py-5 px-1  benefit__item">
                <div class="text-h3 mb-2 text-white count" :data-target="data.premios">
                  0
                </div>
                <div class="text-white text-bold" style="font-size:1.1rem">
                  Premios entregados 🎁🏆
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss">
.benefit__item{
  border:5px solid rgb(255, 255, 255); 
  border-radius:0.8rem;
  box-shadow: 0px 2px 5px 1px #5454544a;
}
.text-benefit-title{
  font-size: 1.9rem;
  font-weight: 600;

}
.text-benefit-subtitle{
  font-size: 1.2rem;

}

.video__con{
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
}
.video-dt-lazy{
  height: auto;
}
@media (max-width: 780px) {
  .video-wrapper{
    height: 100%!important;
  }
  .video-dt-lazy{
    height: 100%!important;
  }
  .text-benefit-subtitle{
    font-size: 0.9rem;
  }
}
</style>