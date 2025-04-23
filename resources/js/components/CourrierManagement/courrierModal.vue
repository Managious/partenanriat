<template>
    <div class="modal-overlay" @click="handleOutsideClick">
        <div class="modal-dialog" @click.stop>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ isEditMode ? 'Edit Courrier' : 'Create Courrier' }}</h5>
                    <button type="button" class="close" @click="closeModal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <form @submit.prevent="submitForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input v-model="formData.name" type="text" id="name" class="form-control" required />
                            <div v-if="errors.name" class="text-danger"><small>{{ errors.name[0] }}</small></div>
                        </div>

                        <div class="form-group">
                            <label for="courrier_email">Email</label>
                            <input v-model="formData.courrier_email" type="email" id="courrier_email" class="form-control" required />
                            <div v-if="errors.courrier_email" class="text-danger"><small>{{ errors.courrier_email[0] }}</small></div>
                        </div>

                        <div class="form-group">
                            <label for="courrier_phone">Phone</label>
                            <input v-model="formData.courrier_phone" type="text" id="courrier_phone" class="form-control" required />
                            <div v-if="errors.courrier_phone" class="text-danger"><small>{{ errors.courrier_phone[0] }}</small></div>
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
        courrierData: Object,
    },
    data() {
        return {
            formData: {
                name: '',
                courrier_email: '',
                courrier_phone: '',
            },
            errors: {},
        };
    },
    watch: {
        courrierData: {
            immediate: true,
            handler(newVal) {
                if (this.isEditMode && newVal) {
                    this.formData = { ...newVal };
                }
            },
        },
    },
    methods: {
        async submitForm() {
            const url = this.isEditMode ? `/api/courriers/${this.courrierData.id}` : '/api/courriers';
            const method = this.isEditMode ? 'put' : 'post';
            try {
                await axios[method](url, this.formData);
                this.$emit('refresh');
                this.closeModal();
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Error saving courrier.');
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
        }
    }
}
</script>

<style scoped>
/* même style que les autres modaux pour uniformité */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex; align-items: center; justify-content: center;
    z-index: 1050;
}
.modal-dialog {
    background: #f1f5f9;
    border-radius: 8px;
    width: 90%; max-width: 500px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.modal-header, .modal-footer {
    padding: 1rem 1.5rem;
}
.modal-header {
    background: #334155;
    color: white;
    display: flex; justify-content: space-between;
}
.close { background: none; border: none; font-size: 1.5rem; color: white; cursor: pointer; }
</style>
