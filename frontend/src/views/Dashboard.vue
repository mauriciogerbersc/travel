<template>
  <div class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800">📋 Pedidos de Viagem</h2>
      <LogoutButton />
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-4">
      <div>
        <label class="text-gray-700 font-medium mr-2">Filtrar por status:</label>
        <select
          v-model="filterStatus"
          @change="fetchPedidos"
          class="border border-gray-300 rounded-md px-3 py-1 focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="">Todos</option>
          <option value="solicitado">Solicitados</option>
          <option value="aprovado">Aprovados</option>
          <option value="cancelado">Cancelados</option>
        </select>
      </div>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
      <LoadingSpinner v-if="isLoading" />
      <table v-if="!isLoading" class="min-w-full table-auto">
        <thead class="bg-gray-100 text-left text-sm text-gray-700">
          <tr>
            <th class="px-4 py-3">Solicitante</th>
            <th class="px-4 py-3">Destino</th>
            <th class="px-4 py-3">Data Ida</th>
            <th class="px-4 py-3">Data Volta</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Detalhes</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="pedido in pedidos"
            :key="pedido.id"
            class="hover:bg-gray-50 border-b"
          >
            <td class="px-4 py-3 text-sm text-gray-800">{{ pedido.requester_name }}</td>
            <td class="px-4 py-3 text-sm text-gray-800">{{ pedido.destination }}</td>
            <td class="px-4 py-3 text-sm text-gray-800">{{ formatDate(pedido.departure_date) }}</td>
            <td class="px-4 py-3 text-sm text-gray-800">{{ formatDate(pedido.return_date) }}</td>
            <td class="px-4 py-3">
              <span
                :class="statusClass(pedido.status)"
                class="px-2 py-1 text-xs font-semibold rounded-full"
              >
                {{ pedido.status }}
              </span>
            </td>
            <td class="px-4 py-3">
              <router-link
                :to="`/pedido/${pedido.id}`"
                class="text-blue-600 hover:underline text-sm"
              >
                Ver detalhes
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="pedidos.length === 0" class="text-center py-6 text-gray-500">
        Nenhum pedido encontrado.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../api/axios'
import LogoutButton from '../components/LogoutButton.vue'
import LoadingSpinner from '../components/Loading.vue'

const pedidos = ref([])
const filterStatus = ref('')

const isLoading = ref(false)

async function fetchPedidos() {
  isLoading.value = true
  try {
    const params = {}
    if (filterStatus.value) params.status = filterStatus.value
    const response = await api.get('/travel-requests', { params })
    pedidos.value = response.data
  } catch (error) {
    console.error(error)
  } finally {
    isLoading.value = false
  }
}


onMounted(fetchPedidos)

function formatDate(dateStr) {
  const options = { year: 'numeric', month: 'short', day: 'numeric' }
  return new Date(dateStr).toLocaleDateString('pt-BR', options)
}

function statusClass(status) {
  switch (status) {
    case 'aprovado':
      return 'bg-green-100 text-green-800'
    case 'cancelado':
      return 'bg-red-100 text-red-800'
    case 'solicitado':
      return 'bg-yellow-100 text-yellow-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}
</script>
