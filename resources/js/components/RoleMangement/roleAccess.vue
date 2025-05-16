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

          <!-- Search -->
          <div class="search-box">
            <input
              type="text"
              class="form-control"
              placeholder="Search permissions..."
              v-model="search"
            />
          </div>

          <!-- Body -->
          <div class="modal-body">
            <!-- Assigned -->
            <div class="permissions-group">
              <h6 class="group-title">✅ Assigned Permissions</h6>
              <div v-for="(permissions, group) in filteredGroupedAssigned" :key="'assigned-' + group" class="permission-section">
                <div class="section-title">{{ group.toUpperCase() }}</div>
                <div class="permissions-list">
                  <div
                    v-for="permission in permissions"
                    :key="permission.id"
                    class="form-check"
                  >
                    <input
                      class="form-check-input"
                      type="checkbox"
                      :id="'perm-' + permission.id"
                      :value="permission.id"
                      v-model="selectedPermissions"
                    />
                    <label class="form-check-label" :for="'perm-' + permission.id">
                      {{ permission.name }}
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <hr />

            <!-- Unassigned -->
            <div class="permissions-group">
              <h6 class="group-title">❌ Unassigned Permissions</h6>
              <div v-for="(permissions, group) in filteredGroupedUnassigned" :key="'unassigned-' + group" class="permission-section">
                <div class="section-title">{{ group.toUpperCase() }}</div>
                <div class="permissions-list">
                  <div
                    v-for="permission in permissions"
                    :key="permission.id"
                    class="form-check"
                  >
                    <input
                      class="form-check-input"
                      type="checkbox"
                      :id="'perm-' + permission.id"
                      :value="permission.id"
                      v-model="selectedPermissions"
                    />
                    <label class="form-check-label" :for="'perm-' + permission.id">
                      {{ permission.name }}
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="closeModal">Cancel</button>
            <button class="btn btn-primary" @click="savePermissions">Save</button>
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
      search: ''
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
      api.post(`/roles/${this.roleData.id}/permissions`, {
        permission_ids: this.selectedPermissions
      })
      .then(() => {
        this.$emit('updated');
        this.closeModal();
      })
      .catch(error => {
        console.error('Error saving permissions:', error);
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
}

.modal-dialog {
  background: #fff;
  border-radius: 8px;
  width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.modal-content {
  display: flex;
  flex-direction: column;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  padding: 1rem;
  border-bottom: 1px solid #ddd;
}

.close-btn {
  font-size: 1.5rem;
  border: none;
  background: transparent;
  cursor: pointer;
}

.search-box {
  padding: 0.75rem 1rem 0;
}

.modal-body {
  padding: 1rem;
}

.modal-footer {
  padding: 1rem;
  border-top: 1px solid #ddd;
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}

.permissions-group {
  margin-bottom: 1.5rem;
}

.permission-section {
  margin-bottom: 1rem;
}

.section-title {
  font-weight: bold;
  background: #f5f5f5;
  padding: 0.4rem 0.75rem;
  margin-bottom: 0.5rem;
  border-left: 4px solid #0d6efd;
}

.permissions-list {
  padding-left: 1rem;
}

.form-check {
  margin-bottom: 0.25rem;
}
</style>
