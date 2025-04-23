<template>
    <div class="modal-overlay" @click="handleOutsideClick">
        <div class="modal-dialog" @click.stop>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ isEditMode ? 'Edit Supplier' : 'Create Supplier' }}</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="submitForm">
                        <div class="form-group">
                            <label for="supplier_id">Supplier ID</label>
                            <input type="text" id="supplier_id" v-model="formData.supplier_id" class="form-control" required />
                            <div v-if="errors.supplier_id" class="text-danger">
                                <small>{{ errors.supplier_id[0] }}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">Supplier Name</label>
                            <input type="text" id="name" v-model="formData.supplier_name" class="form-control" required />
                            <div v-if="errors.supplier_name" class="text-danger">
                                <small>{{ errors.supplier_name[0] }}</small>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" v-model="formData.supplier_city" class="form-control" />
                            <div v-if="errors.supplier_city" class="text-danger">
                                <small>{{ errors.supplier_city[0] }}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="zone">Zone</label>
                            <input type="text" id="zone" v-model="formData.supplier_zone" class="form-control" />
                            <div v-if="errors.supplier_zone" class="text-danger">
                                <small>{{ errors.supplier_zone[0] }}</small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{ isEditMode ? 'Update' : 'Create' }}</button>
                            <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        isEditMode: Boolean,
        supplierData: Object,
    },
    data() {
        return {
            formData: {
                supplier_name: '',
                supplier_city: '',
                supplier_zone: '',
            },
            errors: {},
        };
    },
    watch: {
        supplierData: {
            immediate: true,
            handler(newVal) {
                if (this.isEditMode && newVal) {
                    this.formData = { ...newVal };
                }
            }
        }
    },
    methods: {
        async submitForm() {
            const url = this.isEditMode ? `/api/suppliers/${this.supplierData.supplier_id}` : '/api/suppliers';
            const method = this.isEditMode ? 'put' : 'post';

            try {
                await axios[method](url, this.formData);
                this.$emit('refresh');
                this.closeModal();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Error:', error);
                    alert('An error occurred while saving the supplier.');
                }
            }
        },
        closeModal() {
            this.$emit('close');
        },
        handleOutsideClick(e) {
            if (e.target === this.$el) {
                this.closeModal();
            }
        },
    }
};
</script>

<style scoped>
/* même style que pour roleModal/productModal */
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
.modal-body {
    padding: 1.5rem;
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
.btn-primary {
    background-color: #4ade80;
    border: none;
    color: #f1f5f9;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
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
}
.btn-secondary:hover {
    background-color: #4b5563;
}
</style>
