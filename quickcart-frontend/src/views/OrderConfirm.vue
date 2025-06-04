<template>
  <div class="p-4">
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
    <h1>注文確認</h1>
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
      <div v-if="user" class="mt-4 border-t pt-4">
        <h2 class="text-lg font-semibold">お届け先情報</h2>
        <p>お名前: {{ user.name }}</p>
        <p>メール: {{ user.email }}</p>
        <p>住所: {{ user.address }}</p>
        <p>電話番号: {{ user.number }}</p>
      </div>
    <div v-if="user" class="mt-6">
    <h2 class="text-lg font-semibold">カード情報</h2>
    <div id="card-element" class="border p-3 rounded bg-white"></div>
    <p id="card-error" class="text-red-500 text-sm mt-1"></p>
    </div>
      <button @click="submitOrder" class="mt-4 px-4 py-2 bg-green-500 text-white rounded">
        注文を確定する
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { loadStripe } from '@stripe/stripe-js'


const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_KEY)

let stripe = null
let cardElement = null

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true

const cartItems = ref([])
const user = ref(null)

function getCookie(name) {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift())
  return null
}

onMounted(async () => {
  try {
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true })

    const token = getCookie('XSRF-TOKEN')

    const [cartRes, userRes] = await Promise.all([
      axios.get('/api/cart/show', {
        headers: { 'X-XSRF-TOKEN': decodeURIComponent(token) },
      }),
      axios.get('/api/user', {
        headers: { 'X-XSRF-TOKEN': decodeURIComponent(token) },
      }),
    ])

    cartItems.value = cartRes.data
    user.value = userRes.data

    stripe = await stripePromise
    const elements = stripe.elements()
    cardElement = elements.create('card')
    cardElement.mount('#card-element')
  } catch (error) {
    console.error('データ取得エラー:', error)
  }
})


const totalAmount = computed(() => {
  return cartItems.value.reduce((sum, item) => {
    return sum + item.item.price * item.quantity
  }, 0)
})

function submitOrder() {
  alert('注文を確定します（実装はこれから）')
}
</script>
