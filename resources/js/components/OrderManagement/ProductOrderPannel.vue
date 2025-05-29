<template>
  
  <div class="container my-4">
    <h4 class="mb-3"> Order Products</h4>
   <!--  Cart Dropdown -->
<div class="cart-wrapper position-relative ms-auto" style="width: fit-content">
  <button class="btn btn-outline-dark" @click="cartOpen = !cartOpen">
    <i class="fas fa-shopping-cart"></i>
     <span class="text"></span>
  </button>
   <!--  cart panel -->
  <transition name="fade-slide">
    <div
      v-if="cartOpen"
      class="cart-dropdown shadow"
          style="min-width: 700px; max-width: 600px;"

    >
      <h5 class="mb-3">Cart Summary</h5>
      <ul class="list-group mb-3">
        <li
          class="list-group-item d-flex justify-content-between align-items-center"
          v-for="(item, index) in cart"
          :key="index"
        >
          <span>
          
            {{ item.product_name }} × {{ item.quantity }} =
            <strong>{{ (item.product_sale_price * item.quantity).toFixed(2) }} MAD</strong>
          </span>
        
          <button class="btn btn-sm btn-outline-danger" @click="removeFromCart(index)"><i class="fas fa-trash-alt"></i></button>
        </li>

      
        <div v-if="user && user.role === 'partner'">

         <ClientSelectPartner  @clientSelected="setClient"/>
        </div>
        
      </ul>
      <p class="text-end"><strong>Total:</strong> {{ cartTotal.toFixed(2) }} MAD</p>
      <button class="btn btn-success w-100" @click="confirmCart">Confirm Order</button>
    </div>
  </transition>
</div>

    <!--  Search -->
    <div class="mb-4">
      <input
        type="text"
        v-model="searchQuery"
        class="form-control"
        placeholder="Search by name or brand..."
      />
    </div>

    <!-- Products -->
<div class="row">
  <template v-if="filteredProducts.length">
    <div
      class="col-md-6 mb-4"
      v-for="product in filteredProducts"
      :key="product.id"
    >
      <div class="card h-100 shadow-sm border-0">
        <div class="card-body d-flex flex-column">

          <!--  Product Image Section -->
          <div class="mb-3 text-center">
            <img
              v-if="product.product_image_link"
              :src="product.product_image_link"
              alt="Product Image"
              class="img-fluid rounded"
              style="max-height: 160px; object-fit: contain;"
            />
            <div v-else class="text-muted">No image</div>
          </div>

          <!-- Product Info -->
          <h5 class="card-title text-center">{{ product.product_name || "Unnamed" }}</h5>
          <small class="d-block text-center text-muted">
            {{ product.product_brand || "Unknown brand" }}
          </small>

          <p class="mt-2 mb-1">
            <strong> Price:</strong> <strong>{{ product.product_price }} MAD</strong>
          </p>
          <p class="mb-1 text-muted">
             Stock: {{ product.product_stock_1 ?? "N/A" }}
          </p>
          <p class="mb-1 text-muted">
             Discount: {{ product.product_discount_1 ?? "No discount" }}
          </p>

          <!-- Quantity Input -->
          <input
            type="number"
            v-model.number="quantities[product.id]"
            :max="product.product_stock_1"
            class="form-control mb-2"
            min="1"
            placeholder="Quantity"
          />

          <!-- Total Price -->
          <p>
            Total:
            <strong>{{ getTotal(product).toFixed(2) }} MAD</strong>
          </p>

          <!-- Add to Cart Button -->
          <button
            class="btn btn-primary mt-auto"
            :disabled="!canAdd(product)"
            @click="addToCart(product)"
          >
            Add to Cart
          </button>
        </div>
      </div>
    </div>
              <!-- order confirmation (new) -->
  <div v-if="showConfirmation" class="alert alert-success d-flex align-items-center mt-3">
    <i class="fas fa-check-circle me-2"></i>
    <div>Order confirmed successfully!</div>
  </div>





  </template>
  
  <template v-else>
    <ProductTemplateCard />
    

  </template>

  

</div>
</div>
</template>

<script>
import axios from "axios";
import api from "@/axios";

import ProductTemplateCard from './ProductTemplateCard.vue';
import ClientSelectPartner from './ClientSelectPartner.vue';

import { computed } from 'vue';
import { useAuthStore } from '@/stores/authStore';

export default {
  components: {
    ProductTemplateCard,
    ClientSelectPartner
  },

  setup() {
    const authStore = useAuthStore();
    const user = computed(() => authStore.user);
    return { user };
  },

  data() {
    return {
      cartOpen: false,
      showConfirmation: false,
      products: [],
      quantities: {},
      cart: [],
      searchQuery: "",
      clientId: null, // holds the selected client ID
    };
  },

  computed: {
    filteredProducts() {
      const q = this.searchQuery.toLowerCase();
      if (!q) return [];
      return this.products.filter(p =>
        p.product_name?.toLowerCase().includes(q) ||
        p.product_brand?.toLowerCase().includes(q)
      );
    },
    cartTotal() {
      return this.cart.reduce((sum, i) =>
        sum + i.quantity * i.product_sale_price, 0
      );
    }
  },

  methods: {
    // receives client ID from <ClientSelectPartner>
    setClient(id) {
      this.clientId = id;
    },

    getTotal(product) {
      const qty = this.quantities[product.id] || 0;
      const price = parseFloat(product.product_price) || 0;
      return qty * price;
    },

    canAdd(product) {
      const qty = this.quantities[product.id];
      return qty > 0 && qty <= product.product_stock_1;
    },

    addToCart(product) {
      const quantity = this.quantities[product.id];
      if (!quantity || quantity <= 0 || quantity > product.product_stock_1) {
        alert("Invalid quantity for " + product.product_name);
        return;
      }
      this.cart.push({
        id: product.id,
        product_name: product.product_name,
        product_sale_price: product.product_price,
        product_discount_1: product.product_discount_1 || 0,
        quantity
      });
      this.quantities[product.id] = 0;
    },

    removeFromCart(index) {
      this.cart.splice(index, 1);
    },

    async confirmCart() {
      if (!this.cart.length) return;

      // require client for partners
 if (this.user.role?.name === 'Partner' && !this.clientId) {
        return alert("Veuillez sélectionner un client avant de confirmer.");
      }

      const payload = {
        products: this.cart,
        invoice_nature: "Standard",
        invoice_sale_point: "Web Store",
        invoiced_by: this.user.name,
        ordered_by: this.user.name,
      
        ...(this.user.role === 'partner' && { client_id: this.clientId })
      };

      try {
        await axios.post("/api/orders/store-cart", payload);
        this.showConfirmation = true;
        this.cart = [];
      } catch (err) {
        alert("Failed to confirm order");
        console.error(err.response?.data || err);
      }
    },

    async fetchProducts() {
      try {
        const res = await api.get("/products/all");
        console.log("API Response:", res.data);
        this.products = res.data;
      } catch (err) {
        console.error("Failed to fetch products:", err);
      }
    }
  },

  mounted() {
    this.fetchProducts();
  }
};
</script>


<style  >
/* animation for fade */
.fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* dropdown panel */
.cart-dropdown {
  position: absolute;
  top: 60px;
  right: 0;
  background-color: #f8f9fa; /* Bootstrap light */
  border: 1px solid #dee2e6;
  border-radius: 0.75rem;
  padding: 1.25rem;
  width: 360px;
  z-index: 999;
  transition: all 0.3s ease;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* cards */
.card {
  background-color: rgba(250, 248, 249, 0.6); /* similar to #faf8f998 */
  color: #111827;
  border-radius: 0.75rem;
  border: 1px solid #dee2e6;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
}

.card input {
  background-color: white;
  border: 1px solid #ced4da;
  color: #111827;
}

/* emerald-styled button */
.card .btn {
  background-color: #097d56;
  border: none;
  color: white;
  font-weight: 500;
  border-radius: 0.5rem;
}

.card .btn:hover {
  background-color: #086b49;
}

.card strong {
  color: #097d56;
}

.card p {
  font-size: 0.95rem;
  color: #374151;
}

.card small {
  color: #6b7280;
}

/* Cart Summary Panel (already handled via Bootstrap, minimal needed) */
.cart-summary {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 0.75rem;
  padding: 1.25rem;
  margin-top: 2rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

/* Transition for Vue */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.3s ease;
}
.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}
.fade-slide-enter-to {
  opacity: 1;
  transform: translateY(0);
}
.fade-slide-leave-from {
  opacity: 1;
  transform: translateY(0);
}
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

</style>