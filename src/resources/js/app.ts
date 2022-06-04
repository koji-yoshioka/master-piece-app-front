import './bootstrap'
import { createApp } from 'vue'
import { router } from './router'
import { store } from './store/index'
import { auth } from './store/auth'
import FlashMessage, { FlashMessagePlugin } from '@smartweb/vue-flash-message'
import App from '@/App.vue'

// Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Loading
import VueElementLoading from "vue-element-loading"

// Pagination
import VPagination from '@hennge/vue3-pagination'
import '@hennge/vue3-pagination/dist/vue3-pagination.css'

// Http
import { httpService } from '@/services/httpService'

// FlashMessage
declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $flashMessage: FlashMessagePlugin
  }
}

library.add(far, fas)

const appInitialize = async () => {
  console.log('start')
  // ログイン済みの状態でページリロードした場合、ログイン情報をサーバから取得
  const loginUser = await httpService.getLoginUser()
  auth.dispatch('setUser', loginUser)

  const app = createApp(App)
  store.forEach(({ modelName, key }) => {
    app.use(modelName, key)
  })
  app.use(router)
  app.component('font-awesome-icon', FontAwesomeIcon)
  app.component('vue-element-loading', VueElementLoading)
  app.component('v-pagination', VPagination)
  app.use(FlashMessage)
  app.mount("#app")
}

appInitialize()
