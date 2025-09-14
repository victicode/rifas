<script setup>
import { useWinnerStore } from '@/services/store/winner.store';
import { onMounted, ref } from 'vue';
import numberUtils from '@/utils/numberUtils.js'
import instagram from '@/assets/icons/instagram3.svg';
import tiktok from '@/assets/icons/tiktok3.svg';
import moment from 'moment';
import { useRouter } from 'vue-router';
import confeti from '@/components/layouts/confeti.vue';
const router = useRouter()
const ready = ref(false)
const winnerStore = useWinnerStore()
const winners = ref([]) 
const showModal = ref('')
const lastPage = ref(0);
const actualPage = ref(1)

const viewConfeti = ref(false)
const getWinner = () => {
  ready.value = false

  const query = {
    page: actualPage.value
  }
  winnerStore.getWinnersPublic(query)
  .then((response) =>{
    lastPage.value = response.data.last_page
    // winners.value = response.data.data;

    setTimeout(() => {
      ready.value = true
      viewConfeti.value = true
    }, 1000);
  })
}
const ticketFormat = (ticket) => {
  let max = '0000'
  let ticketFormat = ticket+''
  return max.substring(0, (4 - ticketFormat.length))+ticketFormat

}
const closeModal = () => {
  showModal.value = ''
}
onMounted(() =>{
  getWinner()
})
</script>
<template>
  <div class="py-10" >
    <div class="text-center text-h5 mt-0 text-black text-bold">
      Nuestros ganadores 🤩
    </div>
    <div class="text-stone-600 text-center text-subtitle1 text-bold my-4">
      Gracias por confiar en nosotros❤
    </div>
    <div v-if="ready">
      <template v-if="winners.length > 0">
        <div class="row">
          <!-- Primer premio -->
          <div class="col-md-3 col-12 flex flex-center my-2" v-for="winner in winners" :key="winner.id"> 
    
            <div class="winner-card premio-1 pt-10 ">
              <div class="medal ">{{winner.reward.pole}}°</div>
              <div class="premio mt-2">Ganador del {{winner.rifa.title}}</div>
              <div class="numero-ganador">{{ticketFormat(winner.ticket.number) }}</div>
              <div class="descripcion " style="height: 60px;">¡Felicidades! Ganador de: <br>{{ winner.reward.title }}.</div>
              <div class="ganador-info">
                <div class="nombre-ganador">{{winner.ticket.order.client.name}}</div>
                <div class="fecha">Sorteado el {{moment(winner.rifa.due_date +' '+ winner.reward.reward_time).format('DD/MM/YYYY h A')}}</div>
              </div>
              <div class="flex flex-center mt-4  md:mt-3">
                <a href="https://www.instagram.com/rifaderave?igsh=MTg2bGpwcTRzOHdjbA==" target="_blank" rel="noopener noreferrer">
                  <img :src="instagram" alt="" class="iconSocialWinner  mx-4">
                </a>
                <img :src="tiktok" alt="" class="iconSocialWinner  mx-4">
              </div>
            </div>
          </div>
        </div>
      </template>
      <div v-else>
        <div class="text-center text-black text-h6 py-24 my-20 text-bold mx-5">
          Se nuestro primer ganador, <br>juega ahora!
        </div>
      </div>
    </div>
    <template v-else>
      <div class="flex flex-center mt-24">
        <q-spinner-tail
          color="primary"
          size="5em"
        />
      </div>
    </template>
    <confeti  :duration="5000" 
      :particleCount="200"
      :wind="true" 
      v-if="viewConfeti"
    />
  </div>
</template>
<style lang="scss" >

.iconSocialWinner{
  height: 2rem;
  filter: contrast(0) brightness(2);
  transition: all 0.5s ease;
  &:hover{
    filter: contrast(0) brightness(1);
  }
}

@media (max-width: 780px) {
  .iconSocialWinner{
    height: 2rem;
    filter: contrast(0) brightness(2);
  }
  .socialTitle{
    font-size: 1.11rem;
  }
}
</style>
<style>


    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap');
    
    :root {
        --premio-1: #ffb84d;
        --premio-2: #ffb84d;;
        --premio-3: #ffb84d;
        --dark-bg: #1a1a2e;
        --card-bg: #000000;
    }
    .winner-card {
        background: var(--card-bg);
        border-radius: 20px;
        width: 300px;
        padding: 30px;
        height: 31rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: transform 0.4s ease;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .winner-card:hover {
        transform: translateY(-10px) scale(1.03);
    }
    
    .winner-card::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, 
                        var(--card-color) 0%, 
                        rgba(255, 255, 255, 0.403) 50%, 
                        var(--card-color) 100%);
        z-index: -1;
        border-radius: 22px;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .winner-card:hover::after {
        opacity: 1;
        animation: neonGlow 2s linear infinite;
    }
    
    @keyframes neonGlow {
        0% { filter: blur(5px); opacity: 0.7; }
        50% { filter: blur(7px); opacity: 1; }
        100% { filter: blur(5px); opacity: 0.7; }
    }
    
    .winner-card.premio-1 {
        --card-color: var(--premio-1);
    }
    
    .winner-card.premio-2 {
        --card-color: var(--premio-2);
    }
    
    .winner-card.premio-3 {
        --card-color: var(--premio-3);
    }
    
    .medal {
        width: 80px;
        height: 80px;
        margin: 0px auto;
        background: radial-gradient(circle at center, 
                      var(--card-color) 0%, 
                      rgba(0,0,0,0) 70%);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 30px;
        font-weight: 800;
        color: white;
        box-shadow: 0 0 20px var(--card-color);
        position: relative;
        z-index: 1;
    }
    
    .medal::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border: 2px dashed var(--card-color);
        border-radius: 50%;
        animation: rotate 10s linear infinite;
        opacity: 0.5;
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .premio {
        font-size: 18px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .numero-ganador {
        font-size: 52px;
        font-weight: 800;
        color: white;
        margin: 0px 0 15px;
        text-shadow: 0 0 10px var(--card-color);
        position: relative;
        display: inline-block;
    }
    
    .numero-ganador::after {
        content: '';
        position: absolute;
        bottom: -0px;
        left: 50%;
        transform: translateX(-50%);
        width: 50%;
        height: 3px;
        background: var(--card-color);
        border-radius: 3px;
    }
    
    .descripcion {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.768);
        margin-bottom: 20px;
        line-height: 1.6;
    }
    
    .ganador-info {
        background: rgba(255, 255, 255, 0.05);
        padding: 15px;
        border-radius: 12px;
        margin-top: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
    }
    
    .nombre-ganador {
        font-weight: 600;
        color: white;
        margin-bottom: 5px;
        font-size: 18px;
    }
    
    .fecha {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.5);
        letter-spacing: 1px;
    }
    
    .particles {
        position: absolute;
        width: 4px;
        height: 4px;
        background-color: var(--card-color);
        border-radius: 50%;
        opacity: 0;
    }
    
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            align-items: center;
        }
        
        .winner-card {
            width: 100%;
            max-width: 350px;
        }
    }
  </style>
