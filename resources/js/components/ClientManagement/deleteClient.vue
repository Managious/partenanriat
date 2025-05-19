<template>
  <div class="modal-overlay" @click="handleOutsideClick">
    <div class="modal-dialog" @click.stop>
      <div class="modal-content">
        <div class="modal-header">
          <h5>Delete Client</h5>
          <button class="close" @click="$emit('close')">&times;</button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete <strong>{{ clientData.client_name }}</strong>?
        </div>
        <div class="modal-footer">
          <button class="btn btn-danger" @click="$emit('confirm')">Delete</button>
          <button class="btn btn-secondary" @click="$emit('close')">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    clientData: Object
  },
  methods: {
    handleOutsideClick(event) {
      if (event.target === this.$el) {
        this.$emit('close');
      }
    }
  }
};
</script>
