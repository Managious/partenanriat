<template>
    <div class="modal-overlay" @click="handleOutsideClick">
      <div class="modal-dialog" @click.stop>
        <div class="modal-content">
  
          <div class="modal-header">
            <h5 class="modal-title">Delete Client</h5>
            <button type="button" class="close" @click="closeModal">
              <span>&times;</span>
            </button>
          </div>
  
          <div class="modal-body">
            <p>Are you sure you want to delete the client "<strong>{{ clientData.client_name }}</strong>"?</p>
            <p>This action cannot be undone.</p>
          </div>
  
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" @click="confirmDelete">Delete</button>
            <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      clientData: {
        type: Object,
        required: true,
      },
    },
    methods: {
      confirmDelete() {
        this.$emit('confirm');
        this.closeModal();
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
  };
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
    background-color: #fff;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: relative;
  }
  .modal-header {
    padding: 1rem 1.5rem;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
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
  .btn-danger {
    background-color: #dc3545;
    border: none;
    color: #fff;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    cursor: pointer;
  }
  .btn-danger:hover {
    background-color: #c82333;
  }
  .btn-secondary {
    background-color: #6c757d;
    border: none;
    color: #fff;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    cursor: pointer;
  }
  .btn-secondary:hover {
    background-color: #5a6268;
  }
  </style>