<template>
  <div class="min-h-screen flex flex-col">
    <AppHeader />

    <main class="flex-grow bg-gray-50 py-8 px-4">
      <h1 class="text-2xl font-bold text-center mb-6">カート一覧</h1>

      <div v-if="cartItems.length" class="max-w-3xl mx-auto space-y-6">
        <div
          v-for="item in cartItems"
          :key="item.id"
          class="bg-white shadow-md rounded-lg p-4 flex items-start space-x-4"
        >
          <img
            :src="`/images/${item.item.image}`"
            :alt="item.item.name"
            class="w-28 h-28 object-cover rounded"
          />
          <div class="flex-1">
            <p class="text-lg font-semibold mb-1">{{ item.item.name }}</p>
            <p class="text-gray-700 mb-1">価格: ¥{{ item.item.price }}</p>
            <p class="text-gray-700">数量: {{ item.quantity }}</p>
          </div>
        </div>

        <div class="text-right pr-2">
          <p class="text-xl font-bold">合計金額: ¥{{ totalAmount }}</p>
        </div>

        <div class="flex justify-center gap-4 mt-6">
          <button
            @click="goToOrderConfirm"
            class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded"
          >
            注文確認に進む
          </button>
          <button
            @click="goHome"
            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-black font-semibold rounded"
          >
            ホームに戻る
          </button>
        </div>
      </div>

      <p v-else class="text-center text-gray-600 mt-10">カートに商品はありません。</p>
    </main>

    <AppFooter />
  </div>
</template>


<script setup>
import { onMounted, ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true
const cartItems = ref([])
const user = ref(null)
const router = useRouter()
const userStore = useUserStore()


function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
  return null;
}

function goToOrderConfirm() {
  router.push('/order/confirm')
}


onMounted(async () => {

  try {
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true });

    const token = getCookie('XSRF-TOKEN')

    const res = await axios.get('/api/cart/show', {
      headers: {
        'X-XSRF-TOKEN': decodeURIComponent(token),
      },
      withCredentials: true
    })



    cartItems.value = res.data

    const userRes = await axios.get('/api/user', {
      headers: {
        'X-XSRF-TOKEN': decodeURIComponent(token),
      },
      withCredentials: true
    })
    userStore.setUser(userRes.data)

  } catch (error) {
    console.error('カート取得エラー:', error)
    if (error.response) {
      console.log('サーバーからのエラーレスポンス:', error.response.data)
    }
  }
})

const totalAmount = computed(() => {
  return cartItems.value.reduce((sum, item) => {
    return sum + item.item.price * item.quantity
  }, 0)
})

function goHome() {
  router.push('/')
}

</script>
