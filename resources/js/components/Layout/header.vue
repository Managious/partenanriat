<template>
    <header class="navbar navbar-light bg-light shadow-sm sticky-top px-3 header-bar">
      <div class="d-flex justify-content-between w-100 align-items-center">
        <button class="btn d-md-none" @click="$emit('toggleSidebar')">
          <i class="fas fa-bars"></i>
        </button>
        <div>
          <span class="fw-bold">Welcome</span>
        </div>
      </div>
    </header>
  </template>
  
  <style scoped>
  .header-bar {
    height: 56px;
    width: 100%;
  }
  </style>
    