import { createRouter, createWebHistory, RouterScrollBehavior } from 'vue-router'
import { store } from './store/store'
import Top from '@/pages/Top.vue'
import Contact from '@/pages/Contact.vue'
import SignUp from '@/pages/SignUp.vue'
import Login from '@/pages/Login.vue'
import Search from '@/pages/Search.vue'
import MenuList from '@/pages/MenuList.vue'
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
    path: '/menu-list',
    name: 'menu-list',
    component: MenuList,
    meta: { requiresAuth: true }
  },
  {
    path: '/reserve',
    name: 'reserve',
    component: Reserve,
    meta: { requiresAuth: true }
  },
  // {
  //   path: '*',
  //   component: Top, // TODO:最終的にエラーページを出すようにする
  // },
]

const scrollBehavior: RouterScrollBehavior = (to, from, savedPosition) => {
  return { left: 0, top: 0 }
}

export const router = createRouter({
  history: createWebHistory(),
  scrollBehavior,
  routes,
})

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (store.getters.isLoggingIn) {
      next()
    } else {
      next({ path: '/login' })
    }
  } else {
    next()
  }
})
