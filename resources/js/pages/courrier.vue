<template>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Courriers List</h5>
                <button class="btn btn-primary" @click="showCreateModal">Create Courrier</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="courrierTable" class="table table-striped table-bordered" style="width: 100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <courrier-modal
        v-if="isModalVisible"
        :is-edit-mode="isEditMode"
        :courrier-data="selectedCourrier"
        @close="isModalVisible = false"
        @refresh="refreshData"
    />
    <delete-courrier
        v-if="isDeleteModalVisible"
        :courrier-data="selectedCourrier"
        @close="isDeleteModalVisible = false"
        @confirm="deleteCourrier"
    />
</template>

<script>
import axios from 'axios';
import $ from 'jquery';
import 'datatables.net-bs5';
import courrierModal from "../components/CourrierManagement/courrierModal.vue";
import deleteCourrier from "../components/CourrierManagement/deleteCourrier.vue";

export default {
    components: {
        courrierModal,
        deleteCourrier
    },
    data() {
        return {
            isModalVisible: false,
            isDeleteModalVisible: false,
            isEditMode: false,
            selectedCourrier: null,
        };
    },
    mounted() {
        this.initializeDataTable();
    },
    methods: {
        initializeDataTable() {
    const vm = this;

    $('#courrierTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/courriers',
            type: 'GET',
            xhrFields: {
                withCredentials: true
            }
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'courrier_email' },
            { data: 'courrier_phone' },
            {
                data: null,  // Use null since we're generating the content
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary edit-btn">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                    `;
                }
            },
        ],
        createdRow(row, data) {
            $(row).find('.edit-btn').on('click', function() {
                vm.showEditModal(data);
            });
            $(row).find('.delete-btn').on('click', function() {
                vm.showDeleteModal(data);
            });
        },
    });
},
        refreshData() {
            $('#courrierTable').DataTable().ajax.reload();
        },
        showCreateModal() {
            this.isEditMode = false;
            this.selectedCourrier = null;
            this.isModalVisible = true;
        },
        showEditModal(courrier) {
            this.isEditMode = true;
            this.selectedCourrier = courrier;
            this.isModalVisible = true;
        },
        showDeleteModal(courrier) {
            this.selectedCourrier = courrier;
            this.isDeleteModalVisible = true;
        },
        async deleteCourrier() {
            try {
                await axios.delete(`/api/courriers/${this.selectedCourrier.id}`);
                this.refreshData();
                this.isDeleteModalVisible = false;
            } catch (error) {
                console.error("Error deleting courrier:", error);
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
