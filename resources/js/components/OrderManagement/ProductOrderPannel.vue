<template>
  <div class="container my-4">
    <h4 class="mb-3"> Order Products</h4>
   <!--  Cart Dropdown -->
<div class="cart-wrapper position-relative ms-auto" style="width: fit-content">
  <button class="btn btn-outline-dark" @click="cartOpen = !cartOpen">
     <span class="material-icons">store</span>  
     <span class="text"></span>
  </button>
   <!--  cart panel -->
  <transition name="fade-slide">
    <div
      v-if="cartOpen"
      class="cart-dropdown shadow"
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
          <button class="btn btn-sm btn-outline-danger" @click="removeFromCart(index)">🗑</button>
        </li>
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
      :key="product.product_id"
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
            v-model.number="quantities[product.product_id]"
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
  </template>
  <template v-else>
    <ProductTemplateCard />
  </template>

  

</div>
</div>
</template>

<script>
import axios from "axios";
import ProductTemplateCard from './ProductTemplateCard.vue'; 


export default {
  components: { ProductTemplateCard },

  data() {
    return {
      cartOpen: false,

      products: [],
      quantities: {},
      cart: [],
      searchQuery: "",
    };
  },
  computed: {
    filteredProducts() {
      const query = this.searchQuery.toLowerCase();
      if (!query) return [];
      return this.products.filter((p) => {
        return (
          p.product_name?.toLowerCase().includes(query) ||
          p.product_brand?.toLowerCase().includes(query)
        );
      });
    },
    cartTotal() {
      return this.cart.reduce((sum, item) => sum + item.quantity * item.product_sale_price, 0);
    },
  },
  methods: {
    async fetchProducts() {
      try {
        const res = await axios.get("/api/products/all");
        this.products = res.data;
      } catch (err) {
        console.error("Failed to fetch products:", err);
      }
    },
    getTotal(product) {
      const qty = this.quantities[product.product_id] || 0;
      const price = parseFloat(product.product_price) || 0;
      return qty * price;
    },
    canAdd(product) {
      const qty = this.quantities[product.product_id];
      return qty > 0 && qty <= product.product_stock_1;
    },
    addToCart(product) {
    const quantity = this.quantities[product.product_id];

   if (!quantity || quantity <= 0 || quantity > product.product_stock_1) {
    alert(" Invalid quantity for " + product.product_name);
    return;
  }

  const item = {
    product_id: product.product_id,
    product_name: product.product_name,
    product_sale_price: product.product_price,
    product_discount_1: 0,
    quantity: quantity,
  };

  this.cart.push(item);
  this.quantities[product.product_id] = 0;
    },
    removeFromCart(index) {
      this.cart.splice(index, 1);
    },
    async confirmCart() {
      if (!this.cart.length) return;

      const payload = {
        products: this.cart,
        invoice_nature: "Standard",
        invoice_sale_point: "Web Store",
        invoiced_by: "Client",
      };

      try {
        await axios.post("/api/orders/store-cart", payload);
        alert(" Order confirmed!");
        this.cart = [];
      } catch (err) {
        alert(" Failed to confirm order");
        console.error(err.response?.data || err);
      }
    },
  },
  mounted() {
    this.fetchProducts();
  },
};
</script>

