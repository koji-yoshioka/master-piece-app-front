import './bootstrap'
import { createApp } from 'vue'
import { router } from './router'
import App from '@/App.vue'

// Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Loading
import VueElementLoading from "vue-element-loading"

// Pagination
import VPagination from "@hennge/vue3-pagination"
import "@hennge/vue3-pagination/dist/vue3-pagination.css"

library.add(fas)

const app = createApp(App)
app.use(router)
app.component('font-awesome-icon', FontAwesomeIcon)
app.component('vue-element-loading', VueElementLoading)
app.component('v-pagination', VPagination)
app.mount("#app")
