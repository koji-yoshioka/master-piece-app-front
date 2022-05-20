<script setup lang="ts">
import { computed } from 'vue'
import { useStore } from "@/store/store"
import Header from "@/layouts/Header.vue"
import Main from "@/layouts/Main.vue"
import Footer from "@/layouts/Footer.vue"
import ErrorDialog from '@/components/ErrorDialog.vue'

const store = useStore()
const hasError = computed(() => store.getters.hasError)
const errorInfo = computed(() => store.getters.errorInfo)

const close = () => {
  store.dispatch('clearError')
}
</script>

<template>
  <Header></Header>
  <Main></Main>
  <Footer></Footer>
  <ErrorDialog :show="hasError" @proceed="close">
    <p v-for="(message, index) in errorInfo.messages">
      {{ message }}
    </p>
  </ErrorDialog>
</template>
