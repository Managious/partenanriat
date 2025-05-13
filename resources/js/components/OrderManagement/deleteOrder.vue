<template>
  <div class="modal-overlay" @click="handleOutsideClick">
    <div class="modal-dialog" @click.stop>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Order</h5>
          <button type="button" class="close" @click="closeModal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete order <strong>#{{ orderData.invoice_number }}</strong>?</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-danger" @click="confirmDelete">Delete</button>
          <button class="btn btn-secondary" @click="closeModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: { orderData: Object },
  methods: {
    confirmDelete() {
      this.$emit('confirm');
      this.closeModal();
    },
    closeModal() {
      this.$emit('close');
    },
    handleOutsideClick(e) {
      if (e.target === this.$el) {
        this.closeModal();
      }
    },
  },
};
</script>