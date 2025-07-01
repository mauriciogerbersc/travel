import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import NovoPedido from '../views/NovoPedido.vue';
import { isLoggedIn } from '../utils/auth';
import PedidoDetalhe from '@/views/PedidoDetalhe.vue';

const routes = [
  { path: '/', component: Login, meta: { public: true } },
  { path: '/dashboard', component: Dashboard },
  { path: '/novo-pedido', component: NovoPedido },
  { path: '/pedido/:id', component: PedidoDetalhe },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (!to.meta.public && !isLoggedIn()) {
    return next('/'); // se não estiver logado e não for rota pública, manda pro login
  }
  next();
});

export default router;
