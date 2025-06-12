<template>
  <header class="flex justify-between items-center px-6 py-5 shadow-md">
    <router-link to="/">
    <img
      src="/images/quickcart_logo.jpg"
      alt="QuickCart"
      class="h-16 object-contain"
    />
    </router-link>
    <div class="space-x-4">
      <template v-if="user">
        <span class="text-gray-700">{{ user.nickname }} 様</span>
        <router-link to="/cart" class="btn-purple">カートを見る</router-link>
        <router-link to="/mypage" class="btn-purple">マイページ</router-link>
        <button @click="logout" class="btn-purple">ログアウト</button>
      </template>
      <template v-else>
        <router-link to="/login" class="btn-purple">ログイン</router-link>
        <router-link to="/register" class="btn-purple">新規登録</router-link>
      </template>
    </div>
  </header>
</template>

<script setup>
import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const userStore = useUserStore()
const { user } = storeToRefs(userStore)

axios.defaults.withCredentials = true

async function logout() {
  try {
    await axios.get('/sanctum/csrf-cookie')

    function getCookie(name) {
      const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'))
      if (match) return decodeURIComponent(match[2])
      return null
    }

    const token = getCookie('XSRF-TOKEN')

    await axios.post('/logout', {}, {
      headers: {
        'X-XSRF-TOKEN': token || '',
      }
    })

    userStore.clearUser()
    router.push('/')
  } catch (error) {
    console.error('ログアウト失敗:', error)
    alert('ログアウトに失敗しました')
  }
}

</script>
