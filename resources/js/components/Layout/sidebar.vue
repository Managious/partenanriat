<template>
  <nav :class="['sidebar', { collapsed: isCollapsed }]">
    <div class="sidebar-header d-flex justify-content-between align-items-center p-3">
      <span class="fw-bold" v-if="!isCollapsed">Partenariat</span>
      <button class="btn btn-sm" @click="$emit('toggle')">
        <i class="fas fa-bars"></i>
      </button>
    </div>

    <ul class="list-unstyled px-2">
      <li v-for="item in filteredLinks" :key="item.title">
        <router-link :to="item.path" class="d-flex align-items-center px-3 py-2 sidebar-link">
          <i :class="['me-2', item.icon]"></i>
          <span v-if="!isCollapsed">{{ item.title }}</span>
        </router-link>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';
import { usePermissions } from '@/composables/usePermission';

const props = defineProps({
  isCollapsed: Boolean
});

const emit = defineEmits(['toggle']);

const { hasPermission } = usePermissions();

const links = [
  { title: 'Dashboard', path: '/', icon: 'fas fa-tachometer-alt', permission: null },
  { title: 'Roles', path: '/roles', icon: 'fas fa-user-shield', permission: null },
  { title: 'Permissions', path: '/permissions', icon: 'fas fa-lock', permission: null },
  { title: 'Products', path: '/products', icon: 'fas fa-box', permission: null },
  { title: 'Suppliers', path: '/suppliers', icon: 'fas fa-truck-loading', permission: null },
  { title: 'Courriers', path: '/courriers', icon: 'fas fa-motorcycle', permission: null },
  { title: 'Clients', path: '/clients', icon: 'fas fa-users', permission: null },
];

const filteredLinks = computed(() =>
  links.filter(link => !link.permission || hasPermission(link.permission))
);
</script>

<style scoped>
.sidebar {
  width: 250px;
  background: #f8f9fa;
  min-height: 100vh;
  position: fixed;
  transition: all 0.3s;
  z-index: 1030;
}

.sidebar.collapsed {
  width: 80px;
}

.sidebar-link {
  color: #333;
  text-decoration: none;
}

.sidebar-link:hover {
  background: #e9ecef;
  border-radius: 4px;
}
</style>
