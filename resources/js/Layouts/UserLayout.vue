<script setup>
import Sidebar from '@/Components/Sidebar.vue';
</script>

<template>
  <div class="min-h-screen bg-slate-50/50">
    <Sidebar />

    <main class="md:ml-64 pt-20 md:pt-0 min-h-screen transition-all duration-300">
      <slot />
    </main>
  </div>
</template>