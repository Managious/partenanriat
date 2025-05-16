<template>
  <div class="custom-modal">
    <div class="modal-overlay" @click="handleOutsideClick">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Header -->
          <div class="modal-header">
            <h5 class="modal-title">Manage Permissions for {{ roleData.name }}</h5>
            <button type="button" class="close-btn" @click="closeModal">&times;</button>
          </div>

          <div class="search-box">
            <input type="text" class="form-control" placeholder="Search permissions..." v-model="search" />
          </div>

          <div class="modal-body scrollable-body">
            <div class="permissions-group">
              <h6 class="group-title">Assigned Permissions</h6>
              <div v-for="(permissions, group) in filteredGroupedAssigned" :key="'assigned-' + group" class="permission-section">
                <div class="section-title">{{ group.toUpperCase() }}</div>
                <div class="permissions-list">
                  <div v-for="permission in permissions" :key="permission.id" class="form-check" >
                    <input class="form-check-input" type="checkbox" :id="'perm-' + permission.id" :value="permission.id" v-model="selectedPermissions" />
                    <label class="form-check-label" :for="'perm-' + permission.id">
                      {{ permission.name }}
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <hr />

            <div class="permissions-group">
              <h6 class="group-title">Unassigned Permissions</h6>
              <div v-for="(permissions, group) in filteredGroupedUnassigned" :key="'unassigned-' + group" class="permission-section">
                <div class="section-title">{{ group.toUpperCase() }}</div>
                <div class="permissions-list">
                  <div v-for="permission in permissions" :key="permission.id" class="form-check">
                    <input class="form-check-input" type="checkbox" :id="'perm-' + permission.id" :value="permission.id" v-model="selectedPermissions" />
                    <label class="form-check-label" :for="'perm-' + permission.id">
                      {{ permission.name }}
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer flex-column align-items-start">
              <transition name="fade">
                <div v-if="message" class="alert alert-success w-100 mb-2 p-2" style="font-size: 0.9rem;">
                  {{ message }}
                </div>
              </transition>

              <transition name="fade">
                <div v-if="errorMessage" class="alert alert-danger w-100 mb-2 p-2" style="font-size: 0.9rem;">
                  {{ errorMessage }}
                </div>
              </transition>

              <div class="w-100 d-flex justify-content-end gap-2">
                <button class="btn btn-secondary" @click="closeModal">Cancel</button>
                <button class="btn btn-primary" @click="savePermissions">Save</button>
              </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/axios';

export default {
  props: {
    roleData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      allPermissions: [],
      selectedPermissions: [],
      search: '',
      message: '',
      errorMessage: ''  
    };
  },
  computed: {
    groupedAssigned() {
      return this.groupByType(this.allPermissions.filter(p => this.selectedPermissions.includes(p.id)));
    },
    groupedUnassigned() {
      return this.groupByType(this.allPermissions.filter(p => !this.selectedPermissions.includes(p.id)));
    },
    filteredGroupedAssigned() {
      return this.filterGroupedPermissions(this.groupedAssigned);
    },
    filteredGroupedUnassigned() {
      return this.filterGroupedPermissions(this.groupedUnassigned);
    }
  },
  mounted() {
    this.fetchPermissions();
    document.addEventListener('keydown', this.handleEscClose);
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleEscClose);
  },
  methods: {
    async fetchPermissions() {
      try {
        const [all, assigned] = await Promise.all([
          api.get('/permissions/list'),
          api.get(`/roles/${this.roleData.id}/permissions`)
        ]);

        this.allPermissions = all.data;
        this.selectedPermissions = assigned.data.map(p => p.id);
      } catch (error) {
        console.error('Error fetching permissions:', error);
      }
    },
    savePermissions() {
      this.message = '';
      this.errorMessage = '';

      api.post(`/roles/${this.roleData.id}/permissions`, {
        permission_ids: this.selectedPermissions
      })
      .then((response) => {
        this.message = response.data.message || 'Permissions updated successfully.';
        setTimeout(() => {
            this.message = '';
            this.$emit('updated');
            this.closeModal();
        }, 1500);
      })
      .catch(error => {
        console.error('Error saving permissions:', error);
        this.errorMessage = error.response?.data?.message || 'Failed to save permissions.';
      });
    },
    closeModal() {
      this.$emit('close');
    },
    handleOutsideClick(event) {
      if (!this.$el.querySelector('.modal-dialog').contains(event.target)) {
        this.closeModal();
      }
    },
    handleEscClose(event) {
      if (event.key === 'Escape') {
        this.closeModal();
      }
    },
    groupByType(permissions) {
      const groups = {};
      permissions.forEach(p => {
        const parts = p.name.split('_');
        const type = parts[parts.length - 1]; // group by suffix (e.g. user, role)
        if (!groups[type]) groups[type] = [];
        groups[type].push(p);
      });
      return groups;
    },
    filterGroupedPermissions(grouped) {
      const lowerSearch = this.search.toLowerCase();
      const filtered = {};
      for (const group in grouped) {
        const perms = grouped[group].filter(p => p.name.toLowerCase().includes(lowerSearch));
        if (perms.length) filtered[group] = perms;
      }
      return filtered;
    }
  }
};
</script>

<style scoped>
.custom-modal {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
  overflow: auto;
}

.modal-dialog {
  width: 600px;
  max-width: 95%;
  background: white;
  border-radius: 8px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
}

.modal-content {
   display: flex;
  flex-direction: column;
  height: 100%;
  overflow: hidden;
}

.modal-header,
.modal-footer {
  background-color: #fff;
  padding: 1rem;
  z-index: 1;
}

.modal-header {
  border-bottom: 1px solid #ddd;
}

.modal-footer {
  border-top: 1px solid #ddd;
}

.close-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  font-size: 1.5rem;
  border: none;
  background: transparent;
  color: #333;
  cursor: pointer;
}

.search-box {
  background: #fff;
  padding: 0.5rem 1rem 0.5rem;
  border-bottom: 1px solid #eee;
}

.scrollable-body {
  overflow-y: auto;
  flex: 1 1 auto;
  padding: 1rem;
}

.alert {
  font-size: 0.9rem;
  padding: 0.5rem 0.75rem;
  margin-bottom: 0.75rem;
}

.alert-success {
  background-color: #d1e7dd;
  color: #0f5132;
  border: 1px solid #badbcc;
}

.alert-danger {
  background-color: #f8d7da;
  color: #842029;
  border: 1px solid #f5c2c7;
}

.permissions-group {
  margin-bottom: 1.5rem;
}

.permission-section {
  margin-bottom: 1.25rem;
}

.section-title {
  font-weight: bold;
  background: #f5f5f5;
  padding: 0.4rem 0.75rem;
  margin-bottom: 0.5rem;
  border-left: 4px solid #0d6efd;
  text-transform: uppercase;
  font-size: 0.85rem;
  color: #333;
}

.permissions-list {
  padding-left: 1rem;
}

.form-check {
  margin-bottom: 0.4rem;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>

