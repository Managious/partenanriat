<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="orderTable" class="table table-striped table-bordered" style="width: 100%">
           <thead>
             <tr>
                <th><i class="fas fa-hashtag"></i> ID</th>
                <th><i class="fas fa-file-invoice"></i> Invoice #</th>
                 <th><i class="fas fa-cube"></i> Product</th>
               <th><i class="fas fa-user"></i> Client</th>
                 <th><i class="fas fa-shipping-fast"></i> Progress</th>
                 </tr>
              </thead>

            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    <OrderProgressModal
      v-if="isOrderModalVisible"
      :order="selectedOrder"
      @close="isOrderModalVisible = false"
    />
  </div>
</template>

<script>
import $ from 'jquery';
import 'datatables.net-bs5';
import OrderProgressModal from './OrderProgressModal.vue';
import { useAuthStore } from '@/stores/authStore';

export default {
  components: { OrderProgressModal },
  data() {
    return {
      isOrderModalVisible: false,
      selectedOrder: null,
      dataTableInstance: null,
    };
  },
  mounted() {
    this.initializeDataTable();
    const vm = this;
    $(document).on('click', '.show-progress', function (event) {
      const id = $(event.currentTarget).data('id');
      const order = vm.dataTableInstance
        .rows()
        .data()
        .toArray()
        .find(o => o.id == id);
      vm.selectedOrder = order;
      vm.isOrderModalVisible = true;
    });
  },
  methods: {
    initializeDataTable() {
      const vm = this;
      // Get user from Pinia
      const authStore = useAuthStore();
      const user = authStore.user;
      const userId = user?.id;
      const role = user?.role?.toLowerCase();

      if (this.dataTableInstance) {
        this.dataTableInstance.destroy();
      }

      this.dataTableInstance = $('#orderTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '/api/orders',
          type: 'GET',
          dataSrc: function (json) {
            
            let data = json.data || json; 

            if (!role) return data;

            if (role === 'partner') {
              
              return data.filter(order =>
                order.client && order.client.partner_id === user.id
              );
            } else if (role === 'client') {
              return data.filter(order =>
               (order.client && order.client.client_name === user.username) ||
                order.client_name === user.username
              );
            }
         
            return data;
          }
        },
        columns: [
          { data: 'id' },
          { data: 'invoice_number' },
          { data: 'product_name' },
          { data: 'client_name' },
          {
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              return `<button class="btn btn-info btn-sm show-progress" data-id="${row.id}">Shipping status</button>`;
            }
          },
        ]
      });
    },
    refreshData() {
      if (this.dataTableInstance) {
        this.dataTableInstance.ajax.reload();
      }
    },
  },
};
</script>

<style scoped>
/* Glassmorphism for the card */
.card {
  background: rgba(255,255,255,0.82);
  box-shadow: 0 10px 30px rgba(0,0,0,0.11), 0 1.5px 4px rgba(0,0,0,0.09);
  border-radius: 1.2rem;
  backdrop-filter: blur(7px);
  border: none;
}

/* Table styles */
.stylish-datatable, .table {
  border-radius: 1rem;
  overflow: hidden;
  background: rgba(255,255,255,0.85);
  box-shadow: 0 1.5px 6px #2196f320;
}

.stylish-datatable thead th, .table thead th {
  background: linear-gradient(90deg, #a3cef1 0, #87b5dc 100%);
  color: #fff !important;
  font-weight: 700;
  border: none;
  font-size: 1.07rem;
  letter-spacing: 0.7px;
  padding: 0.95rem 1rem;
  vertical-align: middle;
  text-shadow: 0 1px 3px #0001;
}

.stylish-datatable thead th i, .table thead th i {
  margin-right: 0.4em;
  opacity: 0.88;
  font-size: 1.09em;
  vertical-align: middle;
}

.stylish-datatable tbody tr, .table tbody tr {
  transition: background 0.18s;
  cursor: pointer;
}

.stylish-datatable tbody tr:hover, .table tbody tr:hover {
  background: rgba(163, 206, 241, 0.21) !important;
  box-shadow: 0 2px 10px #2196f325;
}

.stylish-datatable td, .table td {
  border: none;
  vertical-align: middle;
  padding: 0.85rem 1rem;
  font-size: 1.01rem;
  color: #292929;
}

.btn-info.btn-sm.show-progress {
  background: linear-gradient(92deg, #a3cef1, #2196f3 90%);
  color: #fff;
  border: none;
  border-radius: 1.4em;
  font-weight: 600;
  letter-spacing: 0.5px;
  transition: box-shadow 0.15s, background 0.15s;
  box-shadow: 0 1.5px 4px #2196f326;
}

.btn-info.btn-sm.show-progress:hover {
  background: linear-gradient(92deg, #2196f3 10%, #a3cef1 100%);
  color: #fff;
  box-shadow: 0 2.5px 8px #2196f340;
}

/* Responsive tweaks */
@media (max-width: 600px) {
  .card {
    padding: 0.5rem;
    border-radius: 1rem;
  }
  .stylish-datatable thead th, .table thead th {
    font-size: 0.93rem;
    padding: 0.55rem 0.35rem;
  }
  .stylish-datatable td, .table td {
    font-size: 0.95rem;
    padding: 0.45rem 0.2rem;
  }
}
</style>

