<template>
    <div class="d-flex">
      <Sidebar :is-collapsed="isSidebarCollapsed" @toggle="toggleSidebar" />
  
      <div :class="['main-wrapper', { collapsed: isSidebarCollapsed }]">
        <Header @toggleSidebar="toggleSidebar" />
        <div class="main-content">
          <router-view />
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import Sidebar from '@/components/Layout/Sidebar.vue';
  import Header from '@/components/Layout/Header.vue';
  import { ref } from 'vue';
  
  const isSidebarCollapsed = ref(false);
  
  function toggleSidebar() {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
  }
  </script>
  
  <style scoped>
  .main-wrapper {
    width: 100%;
    margin-left: 250px;
    transition: margin-left 0.3s;
  }
  
  .main-wrapper.collapsed {
    margin-left: 80px;
  }
  
  .main-content {
    padding: 20px;
    background: #f8f9fa;
    min-height: calc(100vh - 56px); /* header height */
  }
  </style>
  