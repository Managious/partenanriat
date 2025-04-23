<template>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Suppliers List</h5>
                <button class="btn btn-primary" @click="showCreateModal">Create Supplier</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="supplierTable" class="table table-striped table-bordered" style="width: 100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>City</th>
                            <th>Zone</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <supplier-modal
        v-if="isModalVisible"
        :is-edit-mode="isEditMode"
        :supplier-data="selectedSupplier"
        @close="isModalVisible = false"
        @refresh="refreshData"
    />
    <delete-supplier
        v-if="isDeleteModalVisible"
        :supplier-data="selectedSupplier"
        @close="isDeleteModalVisible = false"
        @confirm="deleteSupplier"
    />
</template>

<script>
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs5';
import supplierModal from "../components/SupplierManagement/supplierModal.vue";
import deleteSupplier from "../components/SupplierManagement/deleteSupplier.vue";

export default {
    components: {
        supplierModal,
        deleteSupplier
    },
    data() {
        return {
            isModalVisible: false,
            isDeleteModalVisible: false,
            isEditMode: false,
            selectedSupplier: null,
        };
    },
    mounted() {
        this.initializeDataTable();
    },
    methods: {
        initializeDataTable() {
            const vm = this;

            $('#supplierTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/suppliers',
                    type: 'GET',
                },
                columns: [
                    { data: 'supplier_id' },
                    { data: 'supplier_name' },
                    { data: 'supplier_city' },
                    { data: 'supplier_zone' },
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
            $('#supplierTable').DataTable().ajax.reload();
        },
        showCreateModal() {
            this.isEditMode = false;
            this.selectedSupplier = null;
            this.isModalVisible = true;
        },
        showEditModal(supplier) {
            this.isEditMode = true;
            this.selectedSupplier = supplier;
            this.isModalVisible = true;
        },
        showDeleteModal(supplier) {
            this.selectedSupplier = supplier;
            this.isDeleteModalVisible = true;
        },
        async deleteSupplier() {
            try {
                await axios.delete(`/api/suppliers/${this.selectedSupplier.supplier_id}`);
                this.refreshData();
                this.isDeleteModalVisible = false;
            } catch (error) {
                console.error("Error deleting supplier:", error);
            }
        }
    }
};
</script>

<style scoped>
.table-responsive {
    overflow-x: auto;
}
.table {
    width: 100%;
    margin-top: 15px;
    border-collapse: collapse;
}
.table th,
.table td {
    padding: 0.75rem;
    text-align: left;
    border: 1px solid #ddd;
}
.table th {
    background-color: #f8f9fa;
    font-weight: bold;
}
.btn-primary {
    margin-bottom: 1rem;
}
</style>
