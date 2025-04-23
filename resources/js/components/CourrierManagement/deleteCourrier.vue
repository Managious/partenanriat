<template>
    <div class="modal-overlay" @click="closeModal">
        <div class="modal-dialog" @click.stop>
            <div class="modal-content text-center">
                <div class="modal-header bg-danger text-white justify-content-center">
                    <h5 class="modal-title">Confirm Delete</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this courrier: <strong>{{ courrierData.name }}</strong>?
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-danger" @click="$emit('confirm')">Yes, Delete</button>
                    <button class="btn btn-secondary" @click="closeModal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        courrierData: Object,
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
    },
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

.modal-dialog {
    background-color: #fff;
    border-radius: 8px;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}
.modal-header {
    padding: 1rem;
    font-weight: bold;
}
.modal-body {
    padding: 1rem;
}
.modal-footer {
    padding: 1rem;
}
</style>
