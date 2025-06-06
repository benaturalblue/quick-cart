<template>
  <div>
    <h2 class="text-xl font-bold mb-4">購入履歴</h2>
    <div v-if="orders.length === 0">
      購入履歴はありません。
    </div>
    <div v-else>
      <div v-for="order in orders" :key="order.id" class="mb-6 border p-4 rounded-lg shadow">
        <p>注文日: {{ formatDate(order.created_at) }}</p>
        <p>合計金額: ¥{{ order.total_price }}</p>
        <ul class="mt-2">
          <li
            v-for="item in order.order_items"
            :key="item.id"
            class="border-t py-2"
          >
            {{ item.item.name }} × {{ item.quantity }}（¥{{ item.price }}）
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const orders = ref([])

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
