import { InjectionKey } from 'vue'
import { createStore, useStore as baseUseStore, Store } from 'vuex'

type State = {
  loginUser: {
    id: number
    name: string
  } | null
}

export const key: InjectionKey<Store<State>> = Symbol()

export const auth = createStore<State>({
  state: {
    loginUser: null,
  },
  getters: {
    loginUser: (state) => {
      return state.loginUser ? state.loginUser : null
    },
  },
  mutations: {
    setUser(state, loginUser) {
      state.loginUser = loginUser
    }
  },
  actions: {
    async setUser({ commit }, data) {
      commit('setUser', data)
    },
  }
})

export const useStore = () => {
  return baseUseStore(key)
}
