import { createRouter, createWebHistory, RouterScrollBehavior } from 'vue-router'
import { store } from './store/store'
import Top from '@/pages/Top.vue'
import Contact from '@/pages/Contact.vue'
import SignUp from '@/pages/SignUp.vue'
import Login from '@/pages/Login.vue'
import Search from '@/pages/Search.vue'
import Company from '@/pages/Company.vue'
import MenuList from '@/pages/MenuList.vue'
import Reserve from '@/pages/Reserve.vue'
import MyPage from '@/pages/MyPage.vue'
import Profile from '@/pages/Profile.vue'
import PasswordChange from '@/pages/PasswordChange.vue'
import ReserveHistory from '@/pages/ReserveHistory.vue'
import LikeCompanyList from '@/pages/LikeCompanyList.vue'

import Error from '@/pages/Error.vue'

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
    path: '/company',
    name: 'company',
    component: Company,
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
  {
    path: '/my-page',
    name: 'my-page',
    component: MyPage,
    // TODO:ページ作ったらコメント外す
    // meta: { requiresAuth: true }
  },
  {
    path: '/profile',
    name: 'profile',
    component: Profile,
    // TODO:ページ作ったらコメント外す
    // meta: { requiresAuth: true }
  },
  {
    path: '/password-change',
    name: 'password-change',
    component: PasswordChange,
    // TODO:ページ作ったらコメント外す
    // meta: { requiresAuth: true }
  },
  {
    path: '/reserve-history',
    name: 'reserve-history',
    component: ReserveHistory,
    // TODO:ページ作ったらコメント外す
    // meta: { requiresAuth: true }
  },
  {
    path: '/like-company-list',
    name: 'like-company-list',
    component: LikeCompanyList,
    // TODO:ページ作ったらコメント外す
    // meta: { requiresAuth: true }
  },
  {
    path: '/error',
    name: 'error',
    component: Error,
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
