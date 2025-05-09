<template>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Roles List</h5>
                <button class="btn btn-primary" @click="showCreateModal">Create Role</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="roleTable" class="table table-striped table-bordered" style="width: 100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <role-modal
        v-if="isRoleModalVisible"
        :is-edit-mode="isEditMode"
        :role-data="selectedRole"
        @close="isRoleModalVisible = false"
        @refresh="refreshData"
    />
    <delete-role
        v-if="isDeleteModalVisible"
        :role-data="selectedRole"
        @close="isDeleteModalVisible = false"
        @confirm="deleteRole"
    />

    <role-access 
        :role="selectedRole"
        :visible="showPermissionModal"
        @close="showPermissionModal = false"
        @updated="fetchRoles"
    />
</template>

<script>
import api from '@/axios';
import $ from 'jquery';
import 'datatables.net-bs5';
import roleModal from "../components/RoleMangement/roleModal.vue";
import deleteRole from "../components/RoleMangement/deleteRole.vue";
import roleAccess from '../components/RoleMangement/roleAccess.vue';

export default {
    components: {
        roleModal,
        deleteRole,
        roleAccess
    },
    data() {
        return {
            isRoleModalVisible: false,
            isDeleteModalVisible: false,
            isEditMode: false,
            selectedRole: null,
            showPermissionModal: false,
        };
    },
    mounted() {
        this.initializeDataTable();
    },
    methods: {
        initializeDataTable() {
            const vm = this;

            $('#roleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'api/roles',
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
                    $(row).find('.permission-btn').on('click', function () {
                        vm.showPermissionModalForRole(data);
                    });
                },
            });
        },
        refreshData() {
            $('#roleTable').DataTable().ajax.reload();
        },
        showCreateModal() {
            this.isEditMode = false;
            this.selectedRole = null;
            this.isRoleModalVisible = true;
        },
        showEditModal(role) {
            this.isEditMode = true;
            this.selectedRole = role;
            this.isRoleModalVisible = true;
        },
        showDeleteModal(role) {
            this.selectedRole = role;
            this.isDeleteModalVisible = true;
        },
        showPermissionModalForRole(role) {
            this.selectedRole = role;
            this.showPermissionModal = true;
        },
        async deleteRole() {
            try {
                await api.delete(`/roles/${this.selectedRole.id}`);
                this.refreshData();
                this.isDeleteModalVisible = false;
            } catch (error) {
                console.error("Error deleting role:", error);
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
