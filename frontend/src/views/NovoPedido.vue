<template>
  <div class="max-w-md mx-auto p-6 bg-gray-50 rounded-md shadow-md">
    <!-- Breadcrumb simples e discreto -->
    <nav class="flex items-center text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
      <router-link
        to="/"
        class="hover:text-blue-600 transition-colors"
      >
        Dashboard
      </router-link>
      <span class="mx-2 select-none">/</span>
      <span class="font-semibold text-gray-800">Criar Pedido de Viagem</span>
    </nav>

    <h2 class="text-2xl font-semibold text-gray-800 mb-5">Criar Pedido de Viagem</h2>

    <form @submit.prevent="criarPedido" class="space-y-5">
      <input
        v-model="requester_name"
        type="text"
        placeholder="Nome do solicitante"
        required
        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
      />

      <input
        v-model="destination"
        type="text"
        placeholder="Destino"
        required
        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
      />

      <input
        v-model="departure_date"
        type="date"
        required
        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
      />

      <input
        v-model="return_date"
        type="date"
        required
        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
      />

      <button
        type="submit"
        :disabled="loading"
        class="w-full flex justify-center items-center bg-blue-600 text-white font-semibold py-2 rounded-md disabled:bg-blue-300 disabled:cursor-not-allowed transition-colors"
      >
        <svg
          v-if="loading"
          class="animate-spin mr-2 h-4 w-4 text-white"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
          />
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
          />
        </svg>
        <span>{{ loading ? 'Enviando...' : 'Enviar' }}</span>
      </button>
    </form>

    <p v-if="error" class="mt-4 text-red-600 font-medium">{{ error }}</p>

    <p
      v-if="success"
      class="mt-4 bg-green-100 text-green-800 font-semibold px-4 py-3 rounded-md"
    >
      Pedido criado com sucesso! Agora você pode acompanhar no dashboard.
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../api/axios';

const requester_name = ref('');
const destination = ref('');
const departure_date = ref('');
const return_date = ref('');
const loading = ref(false);
const error = ref('');
const success = ref('');

async function criarPedido() {
  error.value = '';
  success.value = '';
  loading.value = true;

  try {
    await api.post('/travel-requests', {
      requester_name: requester_name.value,
      destination: destination.value,
      departure_date: departure_date.value,
      return_date: return_date.value,
    });
    success.value = 'Pedido criado com sucesso! Agora você pode acompanhar no dashboard.';
    requester_name.value = '';
    destination.value = '';
    departure_date.value = '';
    return_date.value = '';
  } catch (e) {
    error.value = 'Erro ao criar pedido. Tente novamente mais tarde.';
  } finally {
    loading.value = false;
  }
}
</script>
