<template>
    <AppHeader />
  <div class="min-h-screen bg-gray-100">
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
                class="w-full"
              >
                カートに入れる
              </button>
              <router-link
                :to="{ name: 'ItemDetail', params: { id: item.id } }"
                class="w-full text-center"
              >
                商品詳細を見る
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <AppFooter />
</template>






<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'


axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true

const router = useRouter()
const items = ref([])
const user = ref(null)
const userStore = useUserStore()

onMounted(async () => {
    userStore.fetchUser()
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
        await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });

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
