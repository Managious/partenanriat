<template>
    <div class="modal fade show d-block" tabindex="-1" role="dialog" v-if="visible">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Assign Permissions to {{ role.name }}</h5>
            <button type="button" class="btn-close" @click="close"></button>
          </div>
          <div class="modal-body">
            <div v-if="loading">Loading permissions...</div>
            <div v-else>
              <div v-for="permission in allPermissions" :key="permission.id" class="form-check">
                <input class="form-check-input"
                       type="checkbox"
                       :id="'perm-' + permission.id"
                       :value="permission.id"
                       v-model="selectedPermissions" />
                <label class="form-check-label" :for="'perm-' + permission.id">
                  {{ permission.name }}
                </label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="close">Cancel</button>
            <button class="btn btn-primary" @click="save">Save</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, onMounted } from 'vue';
  import api from '@/axios';
  
  const props = defineProps({
    role: Object,
    visible: Boolean,
  });
  
  const emits = defineEmits(['close', 'updated']);
  
  const allPermissions = ref([]);
  const selectedPermissions = ref([]);
  const loading = ref(false);
  
  const fetchPermissions = async () => {
    loading.value = true;
  
    const all = await api.get('/permissions/all');
    allPermissions.value = all.data;
  
    /*const rolePerms = await api.get(`/roles/${props.role.id}/permissions`);
    selectedPermissions.value = rolePerms.data;*/
  
    loading.value = false;
  };
  
  const save = async () => {
    await api.post(`/roles/${props.role.id}/permissions`, {
      permissions: selectedPermissions.value,
    });
    emits('updated');
    close();
  };
  
  const close = () => {
    emits('close');
  };
  
  watch(() => props.visible, (val) => {
    if (val) fetchPermissions();
  });
  </script>
  
  <style scoped>
  .modal {
    background: rgba(0, 0, 0, 0.4);
  }
  </style>
  