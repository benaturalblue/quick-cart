<template>
  <div class="min-h-screen bg-gray-100">
    <!-- ヘッダー -->
    <header class="flex justify-between items-center px-6 py-5 bg-white shadow-md">
      <img
        src="/images/quickcart_logo.jpg"
        alt="QuickCart"
        class="h-12 object-contain"
      />
      <div class="space-x-4">
    <template v-if="user">
      <span class="text-gray-700">{{ user.nickname }} 様</span>
    </template>
    <template v-else>
      <router-link to="/login" class="btn-purple">ログイン</router-link>
      <router-link to="/register" class="btn-purple">新規登録</router-link>
    </template>
  </div>
    </header>

    <!-- 商品一覧 -->
    <main class="px-6 py-10 max-w-screen-xl mx-auto">
      <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        <div
          v-for="item in items"
          :key="item.id"
          class="bg-white shadow rounded-lg overflow-hidden flex flex-col"
        >
          <img
            :src="`/images/${item.image}`"
            :alt="item.name"
            class="w-full h-40 object-cover"
          />
          <div class="p-4 flex flex-col justify-between flex-grow min-h-[180px]">
            <div>
              <h5 class="text-base font-bold truncate">{{ item.name }}</h5>
              <p class="text-sm text-gray-500 mt-1">¥{{ item.price.toLocaleString() }}</p>
            </div>
            <div class="mt-3 flex flex-col gap-2">
              <button
                @click="addToCart(item.id)"
                class="btn-purple w-full"
              >
                カートに入れる
              </button>
              <router-link
                :to="{ name: 'ItemDetail', params: { id: item.id } }"
                class="btn-purple w-full text-center"
              >
                商品詳細を見る
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>






<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true


const items = ref([])
const user = ref(null)

onMounted(async () => {
    try {
        const response = await axios.get('/api/items')
        items.value = response.data
    } catch (error) {
        console.error('商品一覧取得エラー:', error)
    }
})

const fetchUser = async () => {
  try {
    const res = await axios.get('/user', { withCredentials: true })
    user.value = res.data
  } catch (e) {
    user.value = null
  }
}

onMounted(fetchUser)

const getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

const addToCart = async (itemId) => {
    try {
        // CSRFクッキーの取得（これで XSRF-TOKEN がセットされる）
        await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });

        // CSRFトークンをクッキーから取得してヘッダーに追加
        const xsrfToken = getCookie('XSRF-TOKEN');

        await axios.post('/api/cart/add',
            { item_id: itemId, quantity: 1 },
            {
                withCredentials: true,
                headers: {
                    'X-XSRF-TOKEN': decodeURIComponent(xsrfToken),
                }
            }
        );

        alert('カートに追加しました');
    } catch (error) {
        console.error('カート追加エラー:', error);
    }
}

</script>

<style scoped>
.btn-purple {
    @apply border border-purple-600 text-purple-600 hover:bg-purple-50 rounded px-4 py-2;
}
</style>
