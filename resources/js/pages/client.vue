<template>
    <div class="container-fluid px-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Clients List</h5>
          <button class="btn btn-primary" @click="showCreateModal">Add Client</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="clientTable" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>City</th>
                  <th>Zone</th>
                  <th>Type</th>
                  <th>Address</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Discount</th>
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
        v-if="isClientModalVisible"
        :is-edit-mode="isEditMode"
        :client-data="selectedClient"
        @close="isClientModalVisible = false"
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
import api from '@/axios';
import $ from 'jquery';
import 'datatables.net-bs5';
import clientModal from '../components/ClientManagement/clientModal.vue';
import deleteClient from '../components/ClientManagement/deleteClient.vue';

export default {
  components: {
    clientModal,
    deleteClient
  },
  data() {
    return {
      isClientModalVisible: false,
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
          ajax: {
            url: 'api/clients',
            type: 'GET',
            beforeSend: function (xhr) {
              const token = localStorage.getItem('token');
              if (token) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + token);
              }
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
    refreshData() {
      $('#clientTable').DataTable().ajax.reload();
    },
    showCreateModal() {
      this.isEditMode = false;
      this.selectedClient = null;
      this.isClientModalVisible = true;
    },
    showEditModal(client) {
      this.isEditMode = true;
      this.selectedClient = client;
      this.isClientModalVisible = true;
    },
    showDeleteModal(client) {
      this.selectedClient = client;
      this.isDeleteModalVisible = true;
    },
    async deleteClient() {
      try {
            await api.delete(`/clients/${this.selectedClient.id}`);
            this.refreshData();
            this.isDeleteModalVisible = false;
      } catch (error) {
        console.error("Error deleting client:", error);
      }
    }
  }

}
</script>
  
  