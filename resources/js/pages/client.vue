<template>
  <div class="container">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Client Management</h5>
        <button class="btn btn-primary" @click="showCreateModal">Create Client</button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="clientTable" class="table table-striped table-bordered" style="width: 100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>City</th>
                <th>Zone</th>
                <th>Type</th>
                <th>Partner</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <client-modal
    v-if="isModalVisible"
    :is-edit-mode="isEditMode"
    :client-data="selectedClient"
    @close="isModalVisible = false"
    @refresh="refreshData"
  />
  <delete-client
    v-if="isDeleteModalVisible"
    :client-data="selectedClient"
    @close="isDeleteModalVisible = false"
    @confirm="deleteClient"
  />
</template>

<script>
import $ from 'jquery';
import 'datatables.net-bs5';
import ClientModal from "../components/ClientManagement/clientModal.vue";
import DeleteClient from "../components/ClientManagement/deleteClient.vue";

export default {
  components: { ClientModal, DeleteClient },
  data() {
    return {
      isModalVisible: false,
      isDeleteModalVisible: false,
      isEditMode: false,
      selectedClient: null,
    };
  },
  mounted() {
    this.initializeDataTable();
  },
  methods: {
    initializeDataTable() {
      const vm = this;
      $('#clientTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/api/clients',
        columns: [
          { data: 'id' },
          { data: 'client_name' },
          { data: 'client_city' },
          { data: 'client_zone' },
          { data: 'client_type' },
          { data: 'partner_name' },
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
      $('#clientTable').DataTable().ajax.reload();
    },
    showCreateModal() {
      this.isEditMode = false;
      this.selectedClient = null;
      this.isModalVisible = true;
    },
    showEditModal(client) {
      this.isEditMode = true;
      this.selectedClient = client;
      this.isModalVisible = true;
    },
    showDeleteModal(client) {
      this.selectedClient = client;
      this.isDeleteModalVisible = true;
    },
    async deleteClient() {
      try {
        await axios.delete(`/api/clients/${this.selectedClient.id}`);
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