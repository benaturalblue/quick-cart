import { defineStore } from 'pinia'
import axios from 'axios'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
  }),
  actions: {
    async fetchUser() {
      try {
        const res = await axios.get('/user', { withCredentials: true })
        this.user = res.data
      } catch (e) {
        this.user = null
      }
    },
    setUser(userData) {
      this.user = userData
    },
    clearUser() {
      this.user = null
    },
  },
})
