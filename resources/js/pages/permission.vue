<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Permissions List</h5>
                <button class="btn btn-primary" @click="showCreateModal">Create Permission</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="permissionTable" class="table table-striped table-bordered" style="width: 100%">
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

    <permission-modal 
        v-if="isPermissionModalVisible"
        :is-edit-mode="isEditMode"
        :permission-data="selectedPermission"
        @close="isPermissionModalVisible = false"
        @refresh="refreshData"
    />

    <delete-permission 
    v-if="isDeleteModalVisible"
        :permission-data="selectedPermission"
        @close="isDeleteModalVisible = false"
        @confirm="deletePermission" 
    />
</template>

<script>
import api from '@/axios';
import $ from 'jquery';
import 'datatables.net-bs5';
import permissionModal from "../components/PermissionManagement/permissionModal.vue";
import deletePermission from "../components/PermissionManagement/deletePermission.vue";

export default {
    components: {
        permissionModal,
        deletePermission
    },
    data() {
        return {
            isPermissionModalVisible: false,
            isDeleteModalVisible: false,
            isEditMode: false,
            selectedPermission: null,
        };
    },
    mounted() {
        this.initializeDataTable();
    },
    methods: {
        initializeDataTable() {
            const vm = this;

            $('#permissionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/permissions',
                    type: 'GET',
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
                },
            });
        },
        refreshData() {
            $('#permissionTable').DataTable().ajax.reload();
        },
        showCreateModal() {
            this.isEditMode = false;
            this.selectedPermission = null;
            this.isPermissionModalVisible = true;
        },
        showEditModal(permission) {
            this.isEditMode = true;
            this.selectedPermission = permission;
            this.isPermissionModalVisible = true;
        },
        showDeleteModal(role) {
            this.selectedPermission = permission;
            this.isDeleteModalVisible = true;
        },
        async deleteRole() {
            try {
                await api.delete(`/api/permissions/${this.selectedPermission.id}`);
                this.refreshData();
                this.isDeleteModalVisible = false;
            } catch (error) {
                console.error("Error deleting role:", error);
            }
        }
    }
}
</script>