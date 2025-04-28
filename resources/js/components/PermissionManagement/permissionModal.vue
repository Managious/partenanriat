<template>
    <div class="modal-overlay" @click="handleOutsideClick">
        <div class="modal-dialog" @click.stop>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ isEditMode ? 'Edit Permission' : 'Create Permission' }}</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" v-model="formData.name" class="form-control" required />
                            <div v-if="errors.name" class="text-danger">
                                <small>{{ errors.name[0] }}</small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ isEditMode ? 'Update' : 'Create' }}</button>
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '@/axios';
export default {
    props: {
        isEditMode: Boolean,
        permissionData: Object,
    },
    data() {
        return {
            formData: {
                name: '',
            },
            errors: {},
        };
    },
    watch: {
        permissionData: {
            immediate: true,
            handler(newValue) {
                if (this.isEditMode && newValue) {
                    this.formData = { ...newValue };
                }
            },
        },
    },
    methods: {
        async submitForm() {
            const url = this.isEditMode ? `/api/permissions/${this.permissionData.id}` : '/api/permissions';
            const method = this.isEditMode ? 'put' : 'post';
            try {
                await api[method](url, this.formData);
                this.$emit('refresh');
                this.closeModal();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Error saving permission:', error);
                }
            }
        },
        closeModal() {
            this.$emit('close');
        },
        handleOutsideClick(event) {
            if (event.target === this.$el) {
                this.closeModal();
            }
        },
    },
}
</script>