<template>
  <div class="container">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Clients List</h5>
        <button class="btn btn-primary" @click="showCreateModal">Add Client</button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="clientTable" class="table table-bordered" style="width: 100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>City</th>
                <th>Zone</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    <client-modal
      v-if="showModal"
      :is-edit-mode="isEditMode"
      :client-data="selectedClient"
      @close="closeModal"
      @refresh="reloadTable"
    />

    <delete-client
      v-if="showDeleteModal"
      :client-data="selectedClient"
      @close="closeDeleteModal"
      @confirm="deleteClient"
    />
  </div>
</template>

<script>
import $ from 'jquery';
import 'datatables.net-bs5';
import axios from 'axios';
import ClientModal from '../components/ClientManagement/clientModal.vue';
import DeleteClient from '../components/ClientManagement/deleteClient.vue';

export default {
  components: { ClientModal, DeleteClient },
  data() {
    return {
      showModal: false,
      showDeleteModal: false,
      isEditMode: false,
      selectedClient: null,
      table: null,
    };
  },
  mounted() {
    this.initializeDataTable();
  },
  methods: {
    initializeDataTable() {
      const vm = this;
      this.table = $('#clientTable').DataTable({
        ajax: { url: '/api/clients', type: 'GET', dataSrc: 'data' },
        columns: [
          { data: 'client_id' },
          { data: 'client_name' },
          { data: 'client_city' },
          { data: 'client_zone' },
          {
            data: null,
            render(data) {
              return `
                <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                <button class="btn btn-sm btn-danger delete-btn">Delete</button>
              `;
            },
            orderable: false,
            searchable: false,
          },
        ],
        createdRow(row, data) {
          $(row).find('.edit-btn').on('click', () => vm.editClient(data));
          $(row).find('.delete-btn').on('click', () => vm.confirmDelete(data));
        },
      });
    },
    reloadTable() {
      this.table.ajax.reload();
    },
    showCreateModal() {
      this.selectedClient = null;
      this.isEditMode = false;
      this.showModal = true;
    },
    editClient(client) {
      this.selectedClient = client;
      this.isEditMode = true;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
    },
    confirmDelete(client) {
      this.selectedClient = client;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
    },
    async deleteClient() {
      await axios.delete(`/api/clients/${this.selectedClient.client_id}`);
      this.reloadTable();
      this.closeDeleteModal();
    }
  }
};
</script>
