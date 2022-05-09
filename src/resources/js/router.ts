import { createRouter, createWebHistory, NavigationGuardNext, RouteLocationNormalized } from 'vue-router';

import Home from '@/pages/Home.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
]

export const router = createRouter({
  history: createWebHistory(),
  routes,
})
