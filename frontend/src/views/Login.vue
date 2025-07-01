<template>
  <div class="login">
    <h2>Login</h2>
    <form @submit.prevent="handleLogin">
      <input v-model="email" placeholder="Email" type="email" required />
      <input v-model="password" placeholder="Senha" type="password" required />
      <button type="submit">Entrar</button>
    </form>
    <p v-if="error" style="color:red">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../api/axios';
import { onMounted } from 'vue';
import { isLoggedIn } from '../utils/auth';

const email = ref('');
const password = ref('');
const error = ref('');

async function handleLogin() {
  error.value = '';
  try {
    const response = await api.post('/login', {
      email: email.value,
      password: password.value,
    });

    localStorage.setItem('token', response.data.token);
    localStorage.setItem('user', JSON.stringify(response.data.user));
   
    window.location.href = '/dashboard';
  } catch (e) {
    error.value = 'Credenciais inválidas.';
  }
}

onMounted(() => {
  if (isLoggedIn()) {
    window.location.href = '/dashboard';
  }
});
</script>
