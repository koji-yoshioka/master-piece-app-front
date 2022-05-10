import { createRouter, createWebHistory, NavigationGuardNext, RouteLocationNormalized } from 'vue-router';

import Top from '@/pages/Top.vue'
import Contact from '@/pages/Contact.vue'

const routes = [
  {
    path: '/',
    name: 'top',
    component: Top
  },
  {
    path: '/contact',
    name: 'contact',
    component: Contact
  },
]

export const router = createRouter({
  history: createWebHistory(),
  routes,
})
router.afterEach((to, from) => {
  window.scrollTo(0, 0)
})
