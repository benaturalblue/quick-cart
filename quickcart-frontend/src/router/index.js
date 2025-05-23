import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/HomeView.vue';
import ItemDetail from '../views/ItemDetail.vue';

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/item/:id', name: 'ItemDetail', component: ItemDetail },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
