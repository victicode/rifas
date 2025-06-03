<script setup>
import { useAuthStore } from '@/services/store/auth.services';
import { storeToRefs } from 'pinia'

const { user } = storeToRefs(useAuthStore());
</script>
<template>
  <div>
    <h2 class="text-black">Bienvenido</h2>
  </div>
</template>