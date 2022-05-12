import { createRouter, createWebHistory, NavigationGuardNext, RouteLocationNormalized } from 'vue-router';

import Top from '@/pages/Top.vue'
import Contact from '@/pages/Contact.vue'
import SignUp from '@/pages/SignUp.vue'
import Login from '@/pages/Login.vue'
import Search from '@/pages/Search.vue'
import Reserve from '@/pages/Reserve.vue'

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
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/sign-up',
    name: 'sign-up',
    component: SignUp,
  },
  {
    path: '/search',
    name: 'search',
    component: Search,
  },
  {
    path: '/reserve',
    name: 'reserve',
    component: Reserve,
  },
]

export const router = createRouter({
  history: createWebHistory(),
  routes,
})
router.afterEach((to, from) => {
  window.scrollTo(0, 0)
})
