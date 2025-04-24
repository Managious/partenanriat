<template>
    <div class="container">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Clients List</h5>
          <button class="btn btn-primary" @click="showCreateModal">Add Client</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="clientTable" class="table table-striped table-bordered" style="width: 100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>City</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  
    <!-- Modal for Add/Edit -->
    <client-modal
      :visible="isClientModalVisible"
      :form="formData"
      :is-editing="isEditMode"
      @close="isClientModalVisible = false"
      @submit="submitClient"
    />
  
    <!-- Modal for Delete -->
    <delete-client-modal
      v-if="isDeleteModalVisible"
      :client-data="formData"
      @close="isDeleteModalVisible = false"
      @confirm="deleteClient"
    />



  </template>
  
  <script>
  import axios from 'axios';
  import $ from 'jquery';
  import 'datatables.net-bs5';
  import deleteClientModal from '../components/deleteModal.vue';
  import clientModal from '../components/ClientModal.vue';
  
  export default {
    components: {
      clientModal,
      deleteClientModal,
    },
    data() {
      return {
        isClientModalVisible: false,
        isDeleteModalVisible: false,
        isEditMode: false,
        formData: {
          client_name: '',
          client_email: '',
          client_city: '',
          client_zone: '',
          client_type: '',
          client_address: '',
          client_phone: '',
        },
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
          serverSide: false, 
          ajax: {
            url: '/api/clients',
            type: 'GET',
            dataSrc: '',
          },
          columns: [
            { data: 'id' },
            { data: 'client_name' },
            { data: 'client_email' },
            { data: 'client_city' },
            {
              data: null,
              render: function(data, type, row) {
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
        this.resetFormData();
        this.isClientModalVisible = true;
      },
      showEditModal(client) {
        this.isEditMode = true;
        this.formData = { ...client };
        this.isClientModalVisible = true;
      },
      showDeleteModal(client) {
        this.formData = { ...client };
        this.isDeleteModalVisible = true;
      },
      resetFormData() {
        this.formData = {
          client_name: '',
          client_email: '',
          client_city: '',
          client_zone: '',
          client_type: '',
          client_address: '',
          client_phone: '',
        };
      },
      async submitClient() {
  const url = this.isEditMode ? `/api/clients/${this.formData.id}` : '/api/clients';
  const method = this.isEditMode ? 'put' : 'post';
  
  try {
    await axios[method](url, this.formData);
    this.refreshData();
    this.isClientModalVisible = false;
  } catch (error) {
    if (error.response) {
      // Server responded with error status
      console.error('Full error response:', error.response);
      
      if (error.response.status === 500) {
        // Show server error message if available
        const serverMessage = error.response.data?.message || 'Server error occurred';
        alert(`Server Error: ${serverMessage}`);
      }
    } else {
      console.error('Network/request setup error:', error);
      alert('Network error occurred');
    }
  }
},
      async deleteClient() {
        try {
          await axios.delete(`/api/clients/${this.formData.id}`);
          this.refreshData();
          this.isDeleteModalVisible = false;
        } catch (error) {
          console.error('Error deleting client:', error);
        }
      },
    },
  };




  </script>
  
  