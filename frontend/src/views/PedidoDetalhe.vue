<template>
  <div class="max-w-lg mx-auto p-6 bg-gray-50 rounded-md shadow-md">
    <!-- Breadcrumb -->
    <nav class="flex items-center text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
      <router-link to="/dashboard" class="hover:text-blue-600 transition-colors">Dashboard</router-link>
      <span class="mx-2 select-none">/</span>
      <span class="font-semibold text-gray-800">Pedido #{{ pedido.id || '...' }}</span>
    </nav>

    <h2 class="text-2xl font-semibold text-gray-800 mb-5">Pedido #{{ pedido.id || '...' }}</h2>

    <div v-if="loading" class="flex justify-center py-10">
      <svg
        class="animate-spin h-8 w-8 text-blue-600"
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
    </div>

    <div v-else>
      <p><strong>Solicitante:</strong> {{ pedido.user?.name || '-' }}</p>
      <p><strong>Destino:</strong> {{ pedido.destination || '-' }}</p>
      <p><strong>Data de Ida:</strong> {{ pedido.departure_date || '-' }}</p>
      <p><strong>Data de Volta:</strong> {{ pedido.return_date || '-' }}</p>
      <p><strong>Status:</strong> {{ pedido.status || '-' }}</p>

      <div v-if="isAdmin" class="mt-6">
        <label for="status" class="block mb-1 font-semibold">Atualizar status:</label>
        <select
          v-model="novoStatus"
          id="status"
          class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
        >
          <option value="aprovado">Aprovado</option>
          <option value="cancelado">Cancelado</option>
        </select>
        <button
          @click="atualizarStatus"
          :disabled="loadingStatus || novoStatus === pedido.status"
          class="ml-3 px-4 py-2 bg-blue-600 text-white rounded-md disabled:bg-blue-300 disabled:cursor-not-allowed transition"
        >
          <template v-if="loadingStatus">
            <svg
              class="inline animate-spin mr-2 h-4 w-4 text-white"
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
            Atualizando...
          </template>
          <template v-else>Salvar</template>
        </button>
      </div>

      <p v-if="error" class="mt-4 text-red-600 font-medium">{{ error }}</p>
      <p v-if="success" class="mt-4 bg-green-100 text-green-800 font-semibold px-4 py-3 rounded-md">
        {{ success }}
      </p>

      <router-link to="/dashboard" class="inline-block mt-6 text-blue-600 hover:underline">
        ← Voltar
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api/axios';

const route = useRoute();
const router = useRouter();

const pedido = ref({});
const novoStatus = ref('');
const loading = ref(true);
const loadingStatus = ref(false);
const isAdmin = ref(false);
const error = ref('');
const success = ref('');

onMounted(async () => {
  try {
    const response = await api.get(`/travel-requests/${route.params.id}`);
    pedido.value = response.data;
    novoStatus.value = pedido.value.status;

    // Ajuste conforme seu sistema de autenticação
    const user = JSON.parse(localStorage.getItem('user'));
    isAdmin.value = user?.role === 'admin';
  } catch (err) {
    error.value = 'Erro ao carregar o pedido.';
  } finally {
    loading.value = false;
  }
});

const atualizarStatus = async () => {
  error.value = '';
  success.value = '';
  loadingStatus.value = true;

  try {
    await api.put(`/travel-requests/${pedido.value.id}/status`, {
      status: novoStatus.value,
    });
    success.value = 'Status atualizado com sucesso.';
    pedido.value.status = novoStatus.value;
  } catch (err) {
    error.value = 'Erro ao atualizar o status.';
  } finally {
    loadingStatus.value = false;
  }
};
</script>

<style scoped>
/* você pode adicionar estilos extras aqui, se quiser */
</style>
