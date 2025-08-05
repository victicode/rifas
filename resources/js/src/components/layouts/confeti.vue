<template>
  <div class="confeti-container" ref="container"></div>
</template>

<script>
import { onMounted, onUnmounted, ref } from 'vue';

export default {
  name: 'ConfetiFinal',
  props: {
    duration: {
      type: Number,
      default: 3000
    },
    colors: {
      type: Array,
      default: () => ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4CAF50', '#8BC34A', '#CDDC39', '#FFEB3B', '#FFC107', '#FF9800', '#FF5722']
    },
    particleCount: {
      type: Number,
      default: 150
    },
    wind: {
      type: Boolean,
      default: false
    }
  },
  setup(props) {
    const container = ref(null);
    let animationFrame;
    let particles = [];
    let startTime;
    let activeParticles = 0;

    class Particle {
      constructor(width, height, color) {
        this.x = Math.random() * width;
        this.y = -10 - Math.random() * 100; // Comienzan arriba del viewport
        this.color = color;
        this.angle = props.wind ? (Math.random() - 0.5) * 0.2 : 0;
        this.velocity = 1 + Math.random() * 3;
        this.rotation = Math.random() * Math.PI * 2;
        this.rotationSpeed = (Math.random() - 0.5) * 0.2;
        this.size = 5 + Math.random() * 8;
        this.shape = Math.random() > 0.5 ? 'rect' : 'circle';
        this.active = true;
      }

      update(width, height) {
        if (!this.active) return;

        // Movimiento hacia abajo
        this.x += Math.sin(this.angle) * 0.5;
        this.y += this.velocity;
        this.rotation += this.rotationSpeed;
        
        // Desactivar si llega al fondo
        if (this.y > height - 10) {
          this.active = false;
          activeParticles--;
        }
      }

      draw(ctx) {
        if (!this.active) return;

        ctx.save();
        ctx.translate(this.x, this.y);
        ctx.rotate(this.rotation);
        ctx.fillStyle = this.color;
        
        if (this.shape === 'rect') {
          ctx.fillRect(-this.size/2, -this.size/2, this.size, this.size);
        } else {
          ctx.beginPath();
          ctx.arc(0, 0, this.size/2, 0, Math.PI * 2);
          ctx.fill();
        }
        
        ctx.restore();
      }
    }

    const createParticles = () => {
      if (!container.value) return;
      
      const rect = container.value.getBoundingClientRect();
      particles = [];
      activeParticles = props.particleCount;
      
      for (let i = 0; i < props.particleCount; i++) {
        const color = props.colors[Math.floor(Math.random() * props.colors.length)];
        particles.push(new Particle(
          rect.width,
          rect.height,
          color
        ));
      }
    };

    const animate = (timestamp) => {
      if (!startTime) startTime = timestamp;
      const elapsed = timestamp - startTime;
      
      // Detener cuando se acaba el tiempo o no hay partículas activas
      if (elapsed > props.duration || activeParticles <= 0) {
        if (container.value) {
          container.value.innerHTML = ''; // Limpiar completamente
        }
        return;
      }
      
      const canvas = container.value.querySelector('canvas');
      if (!canvas) return;
      
      const ctx = canvas.getContext('2d');
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      
      particles.forEach(particle => {
        particle.update(canvas.width, canvas.height);
        particle.draw(ctx);
      });
      
      animationFrame = requestAnimationFrame(animate);
    };

    const initCanvas = () => {
      if (!container.value) return;
      
      const canvas = document.createElement('canvas');
      const rect = container.value.getBoundingClientRect();
      
      canvas.width = rect.width;
      canvas.height = rect.height;
      canvas.style.position = 'absolute';
      canvas.style.top = '0';
      canvas.style.left = '0';
      canvas.style.pointerEvents = 'none';
      
      // Limpiar cualquier canvas existente
      container.value.innerHTML = '';
      container.value.appendChild(canvas);
    };

    const startConfetti = () => {
      initCanvas();
      createParticles();
      startTime = null;
      animationFrame = requestAnimationFrame(animate);
    };

    onMounted(() => {
      startConfetti();
    });

    onUnmounted(() => {
      if (animationFrame) {
        cancelAnimationFrame(animationFrame);
      }
    });

    return {
      container
    };
  }
};
</script>

<style>
.confeti-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 9999;
  overflow: hidden;
}
</style>