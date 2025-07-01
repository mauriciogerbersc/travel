<template>
  <button @click="handleLogout">Sair</button>
</template>

<script setup>
import { logout } from '../utils/auth';

function handleLogout() {
  logout();
}
</script>
