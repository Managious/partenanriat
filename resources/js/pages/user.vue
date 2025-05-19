<template>
  <div class="container">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Management</h5>
        <button class="btn btn-primary" @click="showCreateModal">Create User</button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="userTable" class="table table-striped table-bordered" style="width: 100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Active</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <user-modal
    v-if="isModalVisible"
    :is-edit-mode="isEditMode"
    :user-data="selectedUser"
    @close="isModalVisible = false"
    @refresh="refreshData"
  />
  <delete-user
    v-if="isDeleteModalVisible"
    :user-data="selectedUser"
    @close="isDeleteModalVisible = false"
    @confirm="deleteUser"
  />
</template>

<script>
import api from '@/axios';
import $ from 'jquery';
import 'datatables.net-bs5';
import userModal from "../components/UserManagement/userModal.vue";
import deleteUser from "../components/UserManagement/deleteUser.vue";

export default {
  components: { userModal, deleteUser },
  data() {
    return {
      isModalVisible: false,
      isDeleteModalVisible: false,
      isEditMode: false,
      selectedUser: null,
    };
  },
  mounted() {
    this.initializeDataTable();
  },
  methods: {
    initializeDataTable() {
      const vm = this;
      $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/users',
            type: 'GET',
            xhrFields: {
              withCredentials: true
            }
        },
        columns: [
          { data: 'id' },
          { data: 'name' },
          { data: 'username' },
          { data: 'email' },
          { data: 'role_name' },
          { 
            data: 'is_active', 
            render: data => data ? 'Yes' : 'No' 
          },
          {
            data: 'action',
            orderable: false,
            searchable: false,
          },
        ],
        createdRow(row, data) {
          $(row).find('.edit-btn').on('click', function () {
            vm.showEditModal(data);
          });
          $(row).find('.delete-btn').on('click', function () {
            vm.showDeleteModal(data);
          });
        },
      });
    },
    refreshData() {
      $('#userTable').DataTable().ajax.reload();
    },
    showCreateModal() {
      this.isEditMode = false;
      this.selectedUser = null;
      this.isModalVisible = true;
    },
    showEditModal(user) {
      this.isEditMode = true;
      this.selectedUser = user;
      this.isModalVisible = true;
    },
    showDeleteModal(user) {
      this.selectedUser = user;
      this.isDeleteModalVisible = true;
    },
    async deleteUser() {
      try {
        await api.delete(`/users/${this.selectedUser.id}`);
        this.refreshData();
        this.isDeleteModalVisible = false;
      } catch (error) {
        console.error("Delete error:", error);
      }
    }
  }
};
</script>

<style scoped>
.table-responsive {
  overflow-x: auto;
}
.table th, .table td {
  padding: 0.75rem;
  text-align: left;
  border: 1px solid #ddd;
}
.table th {
  background-color: #f8f9fa;
  font-weight: bold;
}
</style>
