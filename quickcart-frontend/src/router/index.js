import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/HomeView.vue';
import ItemDetail from '../views/ItemDetail.vue';
import LoginView from '@/views/LoginView.vue';
import RegisterView from '@/views/RegisterView.vue';
import CartView from '../views/CartView.vue';
import OrderConfirm from '@/views/OrderConfirm.vue';


const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/cart', name: 'Cart', component: CartView,},
  { path: '/item/:id', name: 'ItemDetail', component: ItemDetail },
  { path: '/login', name: 'Login', component: LoginView },
  { path: '/order/confirm', name: 'OrderConfirm', component: OrderConfirm },
  { path: '/register', name: 'Register', component: RegisterView }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
