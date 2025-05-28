<template>
  <div class="modal-creative-backdrop" @click.self="$emit('close')">
    <transition name="fade-scale">
      <div class="modal-creative-card">
        <div class="modal-creative-header">
          <div class="header-left">
            <i class="fas fa-shipping-fast fa-lg header-icon"></i>
            <h2>Order Progress</h2>
          </div>
          <button class="modal-creative-close" @click="$emit('close')">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-creative-body">
          <div class="progress-timeline">
            <div
              v-for="(step, idx) in steps"
              :key="step.key"
              class="timeline-step"
              :class="{ active: order[step.key], last: idx === steps.length - 1 }"
            >
              <div class="circle">
                <i v-if="order[step.key]" :class="step.faDone"></i>
                <i v-else :class="step.fa"></i>
              </div>
              <div class="label-group">
                <span class="step-label">{{ step.label }}</span>
                <span v-if="order[step.key]" class="step-date">
                  {{ formatDate(order[step.key]) }}
                </span>
              </div>
              <div
                v-if="idx < steps.length - 1"
                class="timeline-bar"
                :class="{ done: order[steps[idx + 1].key] }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
const props = defineProps({
  order: { type: Object, required: true }
});

const steps = [
  {
    key: 'ordered_on',
    label: 'Commande effectuée',
    fa: 'fas fa-receipt',
    faDone: 'fas fa-check-circle'
  },
  {
    key: 'invoiced_on',
    label: 'Facturée',
    fa: 'fas fa-file-invoice-dollar',
    faDone: 'fas fa-check-circle'
  },
  {
    key: 'shipped_on',
    label: 'Expédiée',
    fa: 'fas fa-truck-moving',
    faDone: 'fas fa-check-circle'
  },
  {
    key: 'delivered_on',
    label: 'Livrée',
    fa: 'fas fa-box-open',
    faDone: 'fas fa-check-circle'
  }
];

function formatDate(date) {
  if (!date) return '';
  return new Date(date).toLocaleString();
}
</script>

<style scoped>
/* Backdrop with glassmorphism */
.modal-creative-backdrop {
  position: fixed;
  z-index: 1050;
  inset: 0;
  background: rgba(30, 34, 45, 0.45);
  backdrop-filter: blur(6px);
  display: flex;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.4s;
}

.modal-creative-card {
  background: rgba(255, 255, 255, 0.90);
  border-radius: 1.4rem;
  box-shadow: 0 10px 40px rgba(0,0,0,0.15), 0 2px 10px rgba(0,0,0,0.09);
  padding: 2.5rem 2rem 2rem 2rem;
  min-width: 520px;
  max-width: 600px;
  width: 100%;
  animation: popUp 0.35s cubic-bezier(.5,1.5,.5,1);
  position: relative;
}

.modal-creative-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.6rem;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 0.7rem;
}

.header-icon {
  color: #2196f3;
  font-size: 1.5rem;
  margin-top: 0.07rem;
}

.modal-creative-header h2 {
  font-size: 1.55rem;
  font-weight: 700;
  color: #22223b;
  letter-spacing: 1px;
  margin: 0;
}

/* Close button */
.modal-creative-close {
  background: transparent;
  border: none;
  font-size: 1.6rem;
  color: #4f4f4f;
  cursor: pointer;
  transition: color 0.15s;
  padding: 0.3rem 0.5rem;
  border-radius: 50%;
}

.modal-creative-close:hover {
  background: #ffe5ec;
  color: #c9184a;
}

/* Modal body */
.modal-creative-body {
  padding-top: 1rem;
}

.progress-timeline {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.8rem;
}

.timeline-step {
  display: flex;
  align-items: center;
  position: relative;
}

.timeline-step.last .timeline-bar {
  display: none;
}

.circle {
  width: 38px;
  height: 38px;
  background: #eee;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
  margin-right: 0.9rem;
  box-shadow: 0 2px 6px #0001;
  border: 2px solid #adb5bd;
  color: #adb5bd;
  transition: all 0.25s;
}

.timeline-step .circle .fa-check-circle {
  color: #41c462;
  font-size: 1.7rem;
  animation: popUp 0.3s;
}

.timeline-step.active .circle {
  background: linear-gradient(135deg, #a3cef1, #2196f3 85%);
  color: #fff;
  border-color: #2196f3;
  transform: scale(1.08);
}

.label-group {
  display: flex;
  flex-direction: column;
  gap: 0.1rem;
}

.step-label {
  font-weight: 600;
  color: #22223b;
  font-size: 1.07rem;
}
.step-date {
  font-size: 0.97rem;
  color: #2196f3;
  margin-top: 0.10rem;
}

.timeline-bar {
  width: 4px;
  height: 36px;
  background: #adb5bd;
  border-radius: 2px;
  margin-left: 17px;
  margin-right: 0.8rem;
  position: absolute;
  left: 18px;
  top: 38px;
  z-index: 1;
  transition: background 0.3s;
}
.timeline-bar.done {
  background: linear-gradient(180deg, #a3cef1, #2196f3 80%);
}

@keyframes popUp {
  0% { transform: scale(0.8) translateY(40px); opacity: 0; }
  100% { transform: scale(1) translateY(0); opacity: 1; }
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
.fade-scale-enter-active, .fade-scale-leave-active {
  transition: all 0.35s cubic-bezier(.5,1.5,.5,1);
}
.fade-scale-enter-from, .fade-scale-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

@media (max-width: 540px) {
  .modal-creative-card {
    min-width: 90vw;
    padding: 1.2rem 0.6rem 1rem 0.8rem;
  }
  .modal-creative-header h2 {
    font-size: 1.08rem;
  }
}
</style>
