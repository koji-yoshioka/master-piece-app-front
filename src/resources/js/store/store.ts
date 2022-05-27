import { InjectionKey } from 'vue'
import { createStore, useStore as baseUseStore, Store } from 'vuex'
import axios from 'axios'
import { OK, CREATED, UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR } from '@/util'

type State = {
  errorInfo: {
    messages: string[]
  } | null
  loginUser: {
    id: number
    name: string
  } | null
}

export const key: InjectionKey<Store<State>> = Symbol()

export const store = createStore<State>({
  state: {
    errorInfo: null,
    loginUser: null,
  },
  getters: {
    errorInfo: (state) => {
      return state.errorInfo
    },
    loginUser: (state) => {
      return state.loginUser ? state.loginUser : null
    },
    isLoggingIn: (state) => !!state.loginUser,
    hasError: (state) => !!state.errorInfo,
  },
  mutations: {
    setErrorInfo(state, errorInfo) {
      state.errorInfo = errorInfo
    },
    setUser(state, loginUser) {
      state.loginUser = loginUser
    }
  },
  actions: {
    // 会員登録
    async signUp({ commit }, data) {
      const response = await axios.post('/api/sign-up', data)
        .catch(e => e.response || e)
      if (response.status === OK || response.status === CREATED) {
        commit('setUser', response.data)
        return
      } else if (response.status === UNPROCESSABLE_ENTITY) {
        // 入力値不正
        const messages: string[] = []
        for (const [key, value] of Object.entries(response.data.errors)) {
          if (Array.isArray(value)) {
            value.forEach(v => messages.push(v))
          }
        }
        commit('setErrorInfo', { messages })
      } else {
        // 500エラー
        commit('setErrorInfo', { messages: ['システムエラーが発生しました'] })
      }
    },
    // ログイン
    async login({ commit }, data) {
      const response = await axios.post('/api/login', data)
        .catch(e => e.response || e)
      console.log('res', response)
      if (response.status === OK) {
        commit('setUser', response.data)
        return
      }
      // エラーの場合
      if (response.status === UNPROCESSABLE_ENTITY) {
        // 入力値不正
        const messages: string[] = []
        for (const [key, value] of Object.entries(response.data.errors)) {
          if (Array.isArray(value)) {
            value.forEach(v => messages.push(v))
          }
        }
        commit('setErrorInfo', { messages })
      } else {
        // 500エラー
        commit('setErrorInfo', { messages: ['システムエラーが発生しました'] })
      }
      commit('setUser', null)
    },
    // ログアウト
    async logout({ commit }) {
      const response = await axios.post('/api/logout')
        .catch(e => e.response || e)
      if (response.status === OK) {
        commit('setUser', null)
        return
      }
      // 500エラー
      commit('setErrorInfo', { messages: ['システムエラーが発生しました'] })

    },
    // ログイン中のユーザ情報取得
    async currentUser({ commit }) {
      const response = await axios.get('/api/user')
        .catch(e => e.response || e)
      if (response.status === OK) {
        if (response.data) {
          // ここまで
          commit('setUser', response.data)
        } else {
          // commit('setApiStatus', null)
          commit('setUser', null)
        }
        return
      }
      // 500エラー
      commit('setErrorInfo', { messages: ['システムエラーが発生しました'] })
      commit('setUser', null)
    },
    // エラー設定
    setError({ commit }, errorResponse) {
      if (errorResponse.status === UNPROCESSABLE_ENTITY) {
        // 入力値不正
        const messages: string[] = []
        for (const [key, value] of Object.entries(errorResponse.data.errors)) {
          if (Array.isArray(value)) {
            value.forEach(v => messages.push(v))
          }
        }
        commit('setErrorInfo', { messages })
      } else {
        // 500エラー
        commit('setErrorInfo', { messages: ['システムエラーが発生しました'] })
      }
    },
    // エラークリア
    clearError({ commit }) {
      commit('setErrorInfo', null)
    },
  }
})

export const useStore = () => {
  return baseUseStore(key)
}
