<!-- src/views/CartView.vue -->
<template>
  <div>
    <header class="flex justify-between items-center px-6 py-5 bg-white shadow-md">
      <img
        src="/images/quickcart_logo.jpg"
        alt="QuickCart"
        class="h-12 object-contain"
      />
      <div class="space-x-4">
    <template v-if="user">
      <span class="text-gray-700">{{ user.nickname }} 様</span>
      <router-link to="/cart" class="btn-purple">カートを見る</router-link>
    </template>
    <template v-else>
      <router-link to="/login" class="btn-purple">ログイン</router-link>
      <router-link to="/register" class="btn-purple">新規登録</router-link>
    </template>
  </div>
    </header>
    <h1>カート一覧</h1>
    <div v-if="cartItems.length">
      <ul>
        <li v-for="item in cartItems" :key="item.id" class="mb-4">
          <p>商品名: {{ item.item.name }}</p>
          <img
            :src="`/images/${item.item.image}`"
            :alt="item.name"
          />
          <p>価格: ¥{{ item.item.price }}</p>
          <p>数量: {{ item.quantity }}</p>
        </li>
      </ul>
      <div class="mt-4">
        <p class="font-semibold">合計金額: ¥{{ totalAmount }}</p>
      </div>
      <button @click="goToOrderConfirm" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">
        注文確認
      </button>
       <button @click="goHome" class="px-4 py-2 bg-gray-300 text-black rounded">
        ホームに戻る
       </button>
    </div>
    <p v-else>カートに商品はありません。</p>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true
const cartItems = ref([])
const user = ref(null)
const router = useRouter()


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
    user.value = userRes.data

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
