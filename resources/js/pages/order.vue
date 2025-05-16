<template>
  <div class="container mt-4">

    <!--  Product Panel -->
    <div class="mb-4">
      <ProductOrderPannel />
    </div>

    <!-- Orders DataTable -->
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Orders List</h5>
        <button class="btn btn-primary" @click="showCreateModal">Create Order</button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="orderTable" class="table table-striped table-bordered" style="width: 100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Invoice #</th>
                <th>Product</th>
                <th>Client</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    <order-modal
      v-if="isOrderModalVisible"
      :is-edit-mode="isEditMode"
      :order-data="selectedOrder"
      @close="isOrderModalVisible = false"
      @refresh="refreshData"
    />

    <delete-order
      v-if="isDeleteModalVisible"
      :order-data="selectedOrder"
      @close="isDeleteModalVisible = false"
      @confirm="deleteOrder"
    />
  </div>
</template>


<script>
import $ from 'jquery';
import 'datatables.net-bs5';
import deleteOrder from '../components/OrderManagement/deleteOrder.vue';
import ProductOrderPannel from '../components/OrderManagement/ProductOrderPannel.vue';

export default {
  components: {  deleteOrder,ProductOrderPannel},
  data() {
    return {
      isOrderModalVisible: false,
      isDeleteModalVisible: false,
      isEditMode: false,
      selectedOrder: null,
    };
  },
  mounted() {
    this.initializeDataTable();
  },
  methods: {
    initializeDataTable() {
      const vm = this;

      $('#orderTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '/api/orders',
          type: 'GET',
        },
        columns: [
          { data: 'id' },
          { data: 'invoice_number' },
          { data: 'product_name' },
          { data: 'client_name' },
          { data: 'product_sale_price' },
          { data: 'product_discount_1' },
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
      $('#orderTable').DataTable().ajax.reload();
    },
    showCreateModal() {
      this.isEditMode = false;
      this.selectedOrder = null;
      this.isOrderModalVisible = true;
    },
    showEditModal(order) {
      this.isEditMode = true;
      this.selectedOrder = order;
      this.isOrderModalVisible = true;
    },
    showDeleteModal(order) {
      this.selectedOrder = order;
      this.isDeleteModalVisible = true;
    },
    async deleteOrder() {
      try {
        await axios.delete(`/api/orders/${this.selectedOrder.id}`);
        this.refreshData();
        this.isDeleteModalVisible = false;
      } catch (error) {
        alert('Failed to delete order.');
      }
    },
  },
};
</script>
