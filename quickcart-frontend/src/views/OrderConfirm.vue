<template>
  <div class="min-h-screen flex flex-col">
    <AppHeader />
    <main class="flex-grow bg-gray-50 py-8 px-4">
      <h1 class="text-2xl font-bold text-center mb-6">注文確認</h1>

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
            <p class="text-lg font-semibold mb-1">商品名: {{ item.item.name }}</p>
            <p class="text-gray-700 mb-1">価格: ¥{{ item.item.price }}</p>
            <p class="text-gray-700">数量: {{ item.quantity }}</p>
          </div>
        </div>
        <div class="text-right pr-2">
          <p class="text-xl font-bold">合計金額: ¥{{ totalAmount }}</p>
        </div>
        <div v-if="user" class="bg-white p-4 rounded shadow-md">
          <h2 class="text-lg font-semibold mb-2 border-b pb-1">お届け先情報</h2>
          <p class="mb-1">お名前: {{ user.name }}</p>
          <p class="mb-1">メール: {{ user.email }}</p>
          <p class="mb-1">住所: {{ user.address }}</p>
          <p>電話番号: {{ user.number }}</p>
        </div>
        <div v-if="user" class="mt-6">
        <h2 class="text-lg font-semibold mb-2">カード情報</h2>
        <div class="bg-white p-4 rounded shadow-md">
            <div id="card-element" class="p-2 border rounded bg-white"></div>
            <p id="card-error" class="text-red-500 text-sm mt-2"></p>
        </div>
        </div>
        <div class="text-center mt-6">
          <button
            @click="submitOrder"
            class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded"
          >
            注文を確定する
          </button>
        </div>
      </div>
      <p v-else class="text-center text-gray-600 mt-10">カートに商品はありません。</p>
    </main>
    <AppFooter />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { loadStripe } from '@stripe/stripe-js'
import { useUserStore } from '@/stores/user'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'


const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_KEY)

let stripe = null
let cardElement = null

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true

const cartItems = ref([])
const user = ref(null)
const userStore = useUserStore()

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
    userStore.setUser(userRes.data)

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
