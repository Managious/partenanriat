<template>
    <div class="custom-modal">
        <div class="modal-overlay" @click="handleOutsideClick">
            <div class="modal-dialog" @click.stop>
                <form @submit.prevent="submitForm" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ isEditMode ? 'Edit Role' : 'Create Role' }}</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" v-model="formData.name" class="form-control" required autofocus/>
                            <div v-if="errors.name" class="text-danger">
                                <small>{{ errors.name[0] }}</small>
                            </div>
                        </div>                          
                    </div>
                    <div class="modal-footer justify-content-between align-items-center flex-wrap gap-2">
                        <transition name="fade">
                            <div v-if="message" class="alert alert-success mb-0 p-2" style="font-size: 0.85rem;">
                            {{ message }}
                            </div>
                        </transition>
                        <transition name="fade">
                            <div v-if="errorMessage" class="alert alert-danger mb-0 p-2" style="font-size: 0.85rem;">
                            {{ errorMessage }}
                            </div>
                        </transition>
                        <div class="ms-auto d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ isEditMode ? 'Update' : 'Create' }}</button>
                            <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import api from '@/axios';
export default {
    props: {
        isEditMode: Boolean,
        roleData: Object,
    },
    data() {
        return {
            formData: {
                name: '',
            },
            errors: {},
            message: '',      
            errorMessage: '',
        };
    },
    watch: {
        roleData: {
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
            const url = this.isEditMode ? `/roles/${this.roleData.id}` : '/roles';
            const method = this.isEditMode ? 'put' : 'post';
            this.message = '';
            this.errorMessage = '';
            this.errors = {};

            try {
                const response = await api[method](url, this.formData);
                this.message = response.data.message || 'Success';
                setTimeout(() => {
                    this.message = '';
                    this.$emit('refresh');
                    this.closeModal();
                }, 1500);
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    this.errorMessage = error.response?.data?.message || 'Failed to save role.';
                    console.error('Error saving role:', error);
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

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

.modal-dialog {
    background-color: #f1f5f9;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

.modal-header {
    padding: 1rem 1.5rem;
    background-color: #334155;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #f1f5f9;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
}

.close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #f1f5f9;
}

.modal-body {
    padding: 1.5rem;
    max-height: 70vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 1rem;
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1rem;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
}

.btn-primary {
    background-color: #4ade80;
    border: none;
    color: #f1f5f9;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #22c55e;
}

.btn-secondary {
    background-color: #64748b;
    border: none;
    color: #f1f5f9;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    cursor: pointer;
}

.btn-secondary:hover {
    background-color: #4b5563;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
