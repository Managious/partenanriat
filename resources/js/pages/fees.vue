<template>
  <div class="container">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Fees List</h5>
        <button class="btn btn-primary" @click="showCreateModal">Add Fee</button>
      </div>
      <div class="card-body">
        <table id="feeTable" class="table table-bordered table-striped" style="width:100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Type</th>
              <th>Default Value</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

    <fees-modal
      v-if="isModalVisible"
      :is-edit-mode="isEditMode"
      :fee-data="selectedFee"
      @close="isModalVisible = false"
      @refresh="refreshTable"
    />

    <delete-fees
      v-if="isDeleteModalVisible"
      :fee-data="selectedFee"
      @close="isDeleteModalVisible = false"
      @confirm="deleteFee"
    />
  </div>
</template>

<script>
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs5';
import FeesModal from '../components/FeesManagement/feesModal.vue';
import DeleteFees from '../components/FeesManagement/deleteFees.vue';

export default {
  components: { FeesModal, DeleteFees },
  data() {
    return {
      isModalVisible: false,
      isDeleteModalVisible: false,
      isEditMode: false,
      selectedFee: null,
      table: null,
    };
  },
  mounted() {
    this.initTable();
  },
  methods: {
    initTable() {
      const vm = this;
      this.table = $('#feeTable').DataTable({
        ajax: {
          url: '/api/fees',
          dataSrc: 'data'
        },
        columns: [
          { data: 'id' },
          { data: 'name' },
          { data: 'type' },
          { data: 'default_value' },
          {
            data: null,
            render(data) {
              return `
                <div class="d-flex gap-1">
                  <button class="btn btn-sm btn-warning edit-btn" title="Edit">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-sm btn-danger delete-btn" title="Delete">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>`;
            }
          }
        ],
        createdRow(row, data) {
          $(row).find('.edit-btn').on('click', () => vm.editFee(data));
          $(row).find('.delete-btn').on('click', () => vm.confirmDelete(data));
        }
      });
    },
    refreshTable() {
      this.table.ajax.reload();
    },
    showCreateModal() {
      this.isEditMode = false;
      this.selectedFee = null;
      this.isModalVisible = true;
    },
    editFee(fee) {
      this.isEditMode = true;
      this.selectedFee = fee;
      this.isModalVisible = true;
    },
    confirmDelete(fee) {
      this.selectedFee = fee;
      this.isDeleteModalVisible = true;
    },
    async deleteFee() {
      try {
        await axios.delete(`/api/fees/${this.selectedFee.id}`);
        this.refreshTable();
        this.isDeleteModalVisible = false;
      } catch (error) {
        console.error("Delete error:", error);
      }
    }
  }
};
</script>
