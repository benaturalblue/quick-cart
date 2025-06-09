<template>
    <AppHeader />
  <div class="p-4">
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
  <AppFooter />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { loadStripe } from '@stripe/stripe-js'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'


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

async function submitOrder() {
  if (!stripe || !cardElement || !user.value) return

  const { error, paymentMethod } = await stripe.createPaymentMethod({
    type: 'card',
    card: cardElement,
    billing_details: {
      name: user.value.name,
      email: user.value.email,
    },
  })

  if (error) {
    document.getElementById('card-error').textContent = error.message
    return
  }

  try {
    await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
    const token = getCookie('XSRF-TOKEN')

    await axios.post(
      '/api/order/submit',
      {
        payment_method_id: paymentMethod.id,
        total: totalAmount.value,
      },
      {
        headers: {
          'X-XSRF-TOKEN': decodeURIComponent(token),
        },
        withCredentials: true,
      }
    )

    alert('注文が完了しました！')
  } catch (err) {
    console.error('決済エラー:', err)
    alert('決済に失敗しました')
  }
}
</script>
