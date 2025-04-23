<template>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Products List</h5>
                <button class="btn btn-primary" @click="showCreateModal">Create Product</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="productTable" class="table table-striped table-bordered" style="width: 100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <product-modal
        v-if="isProductModalVisible"
        :is-edit-mode="isEditMode"
        :product-data="selectedProduct"
        @close="isProductModalVisible = false"
        @refresh="refreshData"
    />
    <delete-product
        v-if="isDeleteModalVisible"
        :product-data="selectedProduct"
        @close="isDeleteModalVisible = false"
        @confirm="deleteProduct"
    />
</template>

<script>
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs5';
import productModal from "../components/ProductManagement/productModal.vue";
import deleteProduct from "../components/ProductManagement/deleteProduct.vue";

export default {
    components: {
        productModal,
        deleteProduct
    },
    data() {
        return {
            isProductModalVisible: false,
            isDeleteModalVisible: false,
            isEditMode: false,
            selectedProduct: null,
        };
    },
    mounted() {
        this.initializeDataTable();
    },
    methods: {
        initializeDataTable() {
            const vm = this;

            $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/products',
                    type: 'GET',
                },
                columns: [
                    { data: 'product_id' },
                    { data: 'product_name' },
                    { data: 'product_brand' },
                    { data: 'product_price' },
                    { data: 'product_stock_1' },
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
            $('#productTable').DataTable().ajax.reload();
        },
        showCreateModal() {
            this.isEditMode = false;
            this.selectedProduct = null;
            this.isProductModalVisible = true;
        },
        showEditModal(product) {
            this.isEditMode = true;
            this.selectedProduct = product;
            this.isProductModalVisible = true;
        },
        showDeleteModal(product) {
            this.selectedProduct = product;
            this.isDeleteModalVisible = true;
        },
        async deleteProduct() {
            try {
                await axios.delete(`/api/products/${this.selectedProduct.product_id}`);
                this.refreshData();
                this.isDeleteModalVisible = false;
            } catch (error) {
                console.error("Error deleting product:", error);
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
