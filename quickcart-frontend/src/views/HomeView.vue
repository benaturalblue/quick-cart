<template>
  <div class="min-h-screen bg-gray-100">
    <!-- ロゴ -->
    <div class="py-5">
      <img
        src="/images/quickcart_logo.jpg"
        alt="QuickCart"
        class="h-12 object-contain"
      />
    </div>

    <div class="px-6 pb-10">
      <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        <div
          v-for="item in items"
          :key="item.id"
          class="bg-white shadow rounded-lg overflow-hidden"
        >
          <img
            :src="`/images/${item.image}`"
            :alt="item.name"
            class="w-full h-50 object-cover"
          />
          <div class="p-4 flex flex-col justify-between h-[150px]">
            <div>
              <h5 class="text-base font-bold truncate">{{ item.name }}</h5>
              <p class="text-sm text-gray-500 mt-1">¥{{ item.price.toLocaleString() }}</p>
            </div>
            <div class="mt-3 justify-center">
              <button
                @click="addToCart(item.id)"
                class="btn-purple"
              >
                カート
              </button>
              <router-link
                :to="{ name: 'ItemDetail', params: { id: item.id } }"
                class="btn-purple"
              >
                詳細
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>




<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const items = ref([])

onMounted(async () => {
    try {
        const response = await axios.get('/api/items')
        items.value = response.data
    } catch (error) {
        console.error('商品一覧取得エラー:', error)
    }
})

const addToCart = async (itemId) => {
    try {
        await axios.post('/api/cart/add', { item_id: itemId })
        alert('カートに追加しました')
    } catch (error) {
        console.error('カート追加エラー:', error)
    }
}
</script>

<style scoped>
.btn-purple {
    @apply border border-purple-600 text-purple-600 hover:bg-purple-50 rounded px-4 py-2;
}
</style>
