<template>
    <div class="modal-overlay" @click="handleOutsideClick">
        <div class="modal-dialog" @click.stop>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Delete Supplier</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p v-if="supplierData">
                        Are you sure you want to delete the supplier
                        <strong>{{ supplierData.supplier_name }}</strong>?
                    </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" @click="$emit('confirm')">Delete</button>
                    <button class="btn btn-secondary" @click="closeModal">Cancel</button>
                </div>

            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';

export default {
    props: {
        supplierData: {
            type: Object,
            required: true
        }
    },
    methods: {
        async deleteSupplier() {
            try {
                // Changed from supplier_id to id
                await axios.delete(`/api/suppliers/${this.supplierData.id}`);
                this.$emit('refresh');
                this.$emit('close');
                // Optional: Add a success notification
                this.$notify({
                    title: 'Success',
                    message: 'Supplier deleted successfully',
                    type: 'success'
                });
            } catch (error) {
                console.error('Delete error:', error);
                this.$notify({
                    title: 'Error',
                    message: 'Failed to delete supplier',
                    type: 'error'
                });
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
    }
};
</script>

<style scoped>
/* Your existing styles remain the same */
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
    max-width: 400px;
    width: 90%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.modal-header {
    padding: 1rem 1.5rem;
    background-color: #dc2626;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #f1f5f9;
}

.modal-body {
    padding: 1.5rem;
    color: #1e293b;
}

.modal-footer {
    padding: 1rem;
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-danger {
    background-color: #ef4444;
    color: #fff;
    border: none;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    cursor: pointer;
}

.btn-danger:hover {
    background-color: #dc2626;
}

.btn-secondary {
    background-color: #64748b;
    color: #fff;
    border: none;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    cursor: pointer;
}

.btn-secondary:hover {
    background-color: #4b5563;
}
</style>