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

      $('#clientTable').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: '/api/clients',
            type: 'GET',
            xhrFields: {
              withCredentials: true
            }
          },
          columns: [
            { data: 'id'},
            { data: 'name' },
            { data: 'city' },
            { data: 'zone' },
            { data: 'type' },
            { data: 'address' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'discount' },
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
