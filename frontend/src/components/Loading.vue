<template>
  <div class="flex justify-center py-6">
    <svg
      class="spinner"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      width="24"
      height="24"
      stroke="blue"
      stroke-width="4"
      stroke-linecap="round"
    >
      <circle cx="12" cy="12" r="10" stroke-opacity="0.25" />
      <path d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
    </svg>
  </div>
</template>

<style scoped>
.spinner {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
</style>
