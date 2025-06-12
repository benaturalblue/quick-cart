<template>
  <div>
    <AppHeader />
    <main class="max-w-screen-md mx-auto px-4 py-10">
      <div v-if="item" class="bg-white shadow rounded p-6 text-center">
        <img
          :src="`/images/${item.image}`"
          :alt="item.name"
          class="w-full h-64 object-contain mb-4"
        />
        <h1 class="text-2xl font-bold mb-2">{{ item.name }}</h1>
        <p class="text-gray-700 mb-4">{{ item.description }}</p>
        <p class="text-xl font-semibold mb-4">¥{{ item.price.toLocaleString() }}</p>
        <button class="mr-4 btn-outline-purple px-6 py-2 rounded border-2 border-purple-600 text-purple-600 hover:bg-purple-100 transition" @click="addToCart(item.id)">カートに追加</button>
        <button
            class="btn-outline-gray px-6 py-2 rounded border-2 border-gray-400 text-gray-700 hover:bg-gray-100 transition"
            @click="goBack"
          >
            戻る
        </button>
      </div>
      <div v-else class="text-center text-gray-500">読み込み中...</div>
    </main>
    <AppFooter />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'
import { useRouter, useRoute } from 'vue-router'
import { useUserStore } from '@/stores/user'


axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true

const route = useRoute()
const router = useRouter()
const item = ref(null)
const userStore = useUserStore()

onMounted(async () => {
  try {
    const res = await axios.get(`/api/items/${route.params.id}`)
    item.value = res.data
  } catch (error) {
    console.error('商品詳細取得エラー:', error)
  }
})

const fetchUser = async () => {
  try {
    const res = await axios.get('/user', { withCredentials: true })
    userStore.setUser(res.data)
  } catch (e) {
    userStore.clearUser()
  }
}


onMounted(fetchUser)

const getCookie = (name) => {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

const addToCart = async (itemId) => {
  if (!userStore.user) {
    alert('ログインしてください')
    router.push('/login')
    return
  }

  try {
    await axios.get('/sanctum/csrf-cookie')

    const xsrfToken = getCookie('XSRF-TOKEN')

    await axios.post('/api/cart/add',
      { item_id: itemId, quantity: 1 },
      {
        headers: {
          'X-XSRF-TOKEN': decodeURIComponent(xsrfToken),
        }
      }
    )

    alert('カートに追加しました')
  } catch (error) {
    console.error('カート追加エラー:', error)
  }
}

function goBack() {
  router.back()
}
</script>
