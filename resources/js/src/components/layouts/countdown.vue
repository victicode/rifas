<template>
  <div class="countdown-container">
    <div class=" text-white px-5 py-2 my-1 tex t-center actualRifa" >
        Nuestra proxima rifa se juega en 🎉:
    </div>
    <div class="countdown-display">
      <div class="time-unit">
        <div class="value">{{ days }}</div>
        <div class="label">DÍAS</div>
      </div>
      <div class="separator">:</div>
      <div class="time-unit">
        <div class="value">{{ hours }}</div>
        <div class="label">HORAS</div>
      </div>
      <div class="separator">:</div>
      <div class="time-unit">
        <div class="value">{{ minutes }}</div>
        <div class="label">MIN</div>
      </div>
      <div class="separator">:</div>
      <div class="time-unit">
        <div class="value">{{ seconds }}</div>
        <div class="label">SEG</div>
      </div>
    </div>
    <div v-if="finished" class="message">¡Hoy!</div>
  </div>
</template>

<script>
export default {
  props: {
    targetDate: {
      type: Date,
      required: true
    },
    showTitle: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      now: Math.floor(Date.now() / 1000),
      interval: null
    }
  },
  computed: {
    finished() {
      return this.targetTimestamp <= this.now
    },
    targetTimestamp() {
      return Math.floor(this.targetDate.getTime() / 1000)
    },
    seconds() {
      return this.formatUnit(this.difference % 60)
    },
    minutes() {
      return this.formatUnit(Math.floor(this.difference / 60) % 60)
    },
    hours() {
      return this.formatUnit(Math.floor(this.difference / 3600) % 24)
    },
    days() {
      return this.formatUnit(Math.floor(this.difference / 86400))
    },
    difference() {
      return Math.max(this.targetTimestamp - this.now, 0)
    }
  },
  mounted() {
    this.interval = setInterval(() => {
      this.now = Math.floor(Date.now() / 1000)
    }, 1000)
  },
  beforeDestroy() {
    clearInterval(this.interval)
  },
  methods: {
    formatUnit(value) {
      return value < 10 ? `0${value}` : value.toString()
    }
  }
}
</script>



<style scoped>
.countdown-container {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  text-align: center;
  max-width: 800px;
  margin: 0 auto;
  padding: 0px 0.5rem;
  border-radius: 16px;
  
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}


.countdown-display {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
  gap: 0.8rem;
}

.time-unit {
  background: rgb(8, 8, 8);
  border-radius: 12px;
  padding: 1.2rem 2rem;
  min-width: 80px;
  border: 2px solid white;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease;
}

.time-unit:hover {
  transform: translateY(-5px);
}

.value {
  font-size: 2.5rem;
  font-weight: 700;
  color: #ffffff;
  margin-bottom: 0.3rem;
}

.label {
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #d0d0d0;
  font-weight: 500;
}

.separator {
  font-size: 2rem;
  font-weight: bold;
  color: #bdc3c7;
  margin: 0 0.2rem;
  align-self: flex-end;
  padding-bottom: 0.8rem;
}

.message {
  margin-top: 1.5rem;
  font-size: 1.5rem;
  color: #e74c3c;
  font-weight: 600;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% { opacity: 0.8; }
  50% { opacity: 1; }
  100% { opacity: 0.8; }
}

@media (max-width: 600px) {
  .countdown-display {
    gap: 0.2rem;
  }
  
  .time-unit {
    min-width: 75px;
    padding: 0.8rem 0.4rem;
  }
  
  .value {
    font-size: 1.8rem;
  }
  
  .separator {
    font-size: 1.5rem;
    padding-bottom: 0.6rem;
  }
}
</style>