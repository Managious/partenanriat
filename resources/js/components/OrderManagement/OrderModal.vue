<template>
  <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,0.3)">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form @submit.prevent="submitOrder">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditMode ? 'Edit Order' : 'Create Order' }}</h5>
            <button type="button" class="btn-close" @click="$emit('close')"></button>
          </div>
          <div class="modal-body">
            <div v-if="error" class="alert alert-danger">{{ error }}</div>

            <!-- Invoice Nature -->
            <div class="mb-3">
              <label class="form-label">Invoice Nature</label>
              <input v-model="order.invoice_nature" type="text" class="form-control" required />
            </div>
            <!-- Invoice Date -->
            <div class="mb-3">
              <label class="form-label">Invoice Date</label>
              <input v-model="order.invoice_date" type="date" class="form-control" />
            </div>
            <!-- Sale Point -->
            <div class="mb-3">
              <label class="form-label">Sale Point</label>
              <input v-model="order.invoice_sale_point" type="text" class="form-control" />
            </div>
            <!-- Client -->
            <div class="mb-3">
              <label class="form-label">Client</label>
              <select v-model="order.client_id" class="form-select" required>
                <option disabled value="">Please select</option>
                <option v-if="clientsError" disabled value="">{{ clientsError }}</option>
                <option v-else-if="clients.length === 0" disabled value="">Loading clients...</option>
                <option v-for="client in clients" :key="client.id" :value="client.id">
                  {{ client.client_name }}
                </option>
              </select>
            </div>
            <!-- Product -->
            <div class="mb-3">
              <label class="form-label">Product</label>
              <select v-model="order.product_id" class="form-select" required>
                <option disabled value="">Please select</option>
                <option v-if="productsError" disabled value="">{{ productsError }}</option>
                <option v-else-if="products.length === 0" disabled value="">Loading products...</option>
                <option v-for="prod in products" :key="prod.id" :value="products.id">
                  {{ products.product_name }} ({{ products.product_brand || 'No Brand' }})
                </option>
              </select>
            </div>
            <!-- Sale Price -->
            <div class="mb-3">
              <label class="form-label">Sale Price</label>
              <input v-model.number="order.product_sale_price" type="number" class="form-control" required />
            </div>
            <!-- Discount -->
            <div class="mb-3">
              <label class="form-label">Discount</label>
              <input v-model.number="order.product_discount_1" type="number" class="form-control" />
            </div>
            <!-- Quantity -->
            <div class="mb-3">
              <label class="form-label">Quantity</label>
              <input v-model.number="order.quantity" type="number" min="1" class="form-control" required />
            </div>

            <!-- Optional fields -->
            <div class="mb-3">
              <label class="form-label">Ordered By</label>
              <input v-model="order.ordered_by" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Order Date</label>
              <input v-model="order.ordered_on" type="date" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Invoiced By</label>
              <input v-model="order.invoiced_by" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Invoiced On</label>
              <input v-model="order.invoiced_on" type="date" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Delivered By</label>
              <input v-model="order.delivered_by" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Delivered On</label>
              <input v-model="order.delivered_on" type="date" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Payment Type</label>
              <input v-model="order.payment_type" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Payment Amount</label>
              <input v-model.number="order.payment_amount" type="number" class="form-control" />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancel</button>
            <button type="submit" class="btn btn-primary">
              {{ isEditMode ? 'Save' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import api from '@/axios'

const props = defineProps({
  isEditMode: Boolean,
  orderData: Object
})
const emit = defineEmits(['close', 'refresh'])

const order = ref({ ...props.orderData })
const clients = ref([])
const clientsError = ref('')
const products = ref([])
const productsError = ref('')
const error = ref('')

watch(() => props.orderData, (val) => {
  order.value = { ...val }
})

onMounted(async () => {
  try {
    const { data: clData } = await api.get('/clients/all')
    clients.value = clData
  } catch (e) {
    clientsError.value = 'Failed to load clients'
  }

  try {
    const { data: prData } = await api.get('/products/all')
    products.value = prData
  } catch (e) {
    productsError.value = 'Failed to load products'
  }
})

const submitOrder = async () => {
  error.value = ''
  try {
    if (props.isEditMode) {
      await api.put(`/orders/${order.value.id}`, order.value)
    } else {
      await api.post('/orders', order.value)
    }
    emit('refresh')
    emit('close')
  } catch (e) {
    error.value = e.response?.data?.message || 'Error while saving.'
  }
}
</script>
