<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <AppHeader />

    <main class="flex-grow px-4 py-8">
      <h2 class="text-2xl font-bold text-center mb-6">購入履歴</h2>

      <div class="max-w-3xl mx-auto">
        <div v-if="orders.length === 0" class="text-center text-gray-600">
          購入履歴はありません。
        </div>

        <div v-else class="space-y-6">
          <div
            v-for="order in orders"
            :key="order.id"
            class="bg-white p-6 rounded-lg shadow-md"
          >
            <div class="mb-2">
              <p class="text-sm text-gray-500">注文日: {{ formatDate(order.created_at) }}</p>
              <p class="text-lg font-semibold text-gray-800">合計金額: ¥{{ order.total_price }}</p>
            </div>

            <ul class="mt-4 space-y-2 border-t pt-4">
              <li
                v-for="item in order.order_items"
                :key="item.id"
                class="text-gray-700"
              >
                {{ item.item.name }} × {{ item.quantity }}（¥{{ item.price }}）
              </li>
            </ul>
          </div>
        </div>
      </div>
    </main>
    <AppFooter />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useUserStore } from '@/stores/user'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'

const orders = ref([])
const userStore = useUserStore()


function getCookie(name) {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift())
  return null
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString()
}

onMounted(async () => {
  try {
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true })
    const token = getCookie('XSRF-TOKEN')

    const userRes = await axios.get('/api/user', {
    headers: { 'X-XSRF-TOKEN': decodeURIComponent(token) },
    withCredentials: true,
    })
    userStore.setUser(userRes.data)

    const res = await axios.get('/api/orders/history', {
      headers: { 'X-XSRF-TOKEN': decodeURIComponent(token) },
      withCredentials: true,
    })

    orders.value = res.data
  } catch (err) {
    console.error('購入履歴取得エラー:', err)
  }
})
</script>
