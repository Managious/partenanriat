<template>
  <div class="container py-4">
    <div class="row g-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3" v-for="card in cards" :key="card.title">
        <DashboardCard
          :title="card.title"
          :value="card.value"
          :icon="card.icon"
          :iconColor="card.iconColor"
          :bgColor="card.bgColor"
          :valueColor="card.valueColor"
          :subtitle="card.subtitle"
        />
      </div>
    </div>
    <!-- Charts row -->
    <div class="row g-4">
      <div class="col-md-3">
        <CircleChart
          title="Répartition Clients/Partenaires"
          :labels="['Clients', 'Partenaires']"
          :values="[30, 9]"
          :colors="['#3498db', '#2ecc71']"
        />
      </div>
      <div class="col-md-3">
        <CircleChart
          title="Répartition Clients par Ville"
          :labels="['Casablanca', 'Rabat', 'Fès', 'Marrakech']"
          :values="[12, 7, 6, 5]"
          :colors="['#1466ec', '#28a745', '#ffc107', '#b800d8']"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import DashboardCard from '../components/Dashboard/DashboardCard.vue'
import CircleChart from '../components/Dashboard/CircleChart.vue'

const cards = [
  {
    title: 'Courriers',
    value: 18,
    icon: 'fas fa-envelope',
    iconColor: '#1466ec',
    bgColor: '#e6f0fb',
    valueColor: '#1466ec',
    subtitle: "Total"
  },
  {
    title: 'Partenaires',
    value: 9,
    icon: 'fas fa-user-friends',
    iconColor: '#28a745',
    bgColor: '#e8f5e9',
    valueColor: '#28a745',
    subtitle: "Actifs"
  },
  {
    title: "Commandes (Auj.)",
    value: 5,
    icon: 'fas fa-shopping-cart',
    iconColor: '#ffc107',
    bgColor: '#fff9e6',
    valueColor: '#ffc107',
    subtitle: "Aujourd'hui"
  },
  {
    title: 'Commandes (Semaine)',
    value: 22,
    icon: 'fas fa-calendar-week',
    iconColor: '#b800d8',
    bgColor: '#f4e6fb',
    valueColor: '#b800d8',
    subtitle: "Cette semaine"
  },
  {
    title: 'Produits',
    value: 50,
    icon: 'fas fa-box-open',
    iconColor: '#17a2b8',
    bgColor: '#e3f7fa',
    valueColor: '#17a2b8',
    subtitle: "En stock"
  },
  
  {
    title: 'Fournisseurs',
    value: 6, 
    icon: 'fas fa-truck',
    iconColor: '#fd7e14',
    bgColor: '#fff3e6',
    valueColor: '#fd7e14',
    subtitle: "Actifs"
  }
]
</script>
