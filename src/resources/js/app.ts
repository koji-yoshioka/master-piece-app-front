import './bootstrap'
import { createApp } from 'vue'
import { router } from './router'
import { modules } from './store/index'
import { store } from './store/store'
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

// FlashMessage
declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $flashMessage: FlashMessagePlugin
  }
}

library.add(far, fas)

const appInitialize = async () => {
  await store.dispatch('currentUser')
  const app = createApp(App)
  modules.forEach(({ modelName, key }) => {
    app.use(modelName, key)
  })
  app.use(router)
  app.component('font-awesome-icon', FontAwesomeIcon)
  app.component('vue-element-loading', VueElementLoading)
  app.component('v-pagination', VPagination)
  app.use(FlashMessage)
  // app.component("star-rating", vue3StarRatings)
  app.mount("#app")
}

appInitialize()
