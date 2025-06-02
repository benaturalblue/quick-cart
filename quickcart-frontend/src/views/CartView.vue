<!-- src/views/CartView.vue -->
<template>
  <div>
    <h1>カート一覧</h1>
    <div v-if="cartItems.length">
      <ul>
        <li v-for="item in cartItems" :key="item.id" class="mb-4">
          <p>商品名: {{ item.item.name }}</p>
          <p>価格: ¥{{ item.item.price }}</p>
          <p>数量: {{ item.quantity }}</p>
        </li>
      </ul>
    </div>
    <p v-else>カートに商品はありません。</p>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
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

onMounted(async () => {
     try {
    const userRes = await axios.get('/api/user')
    console.log('ログインユーザー情報:', userRes.data)
  } catch (err) {
    console.error('ログインされていません:', err)
  }

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
  } catch (error) {
    console.error('カート取得エラー:', error)
    if (error.response) {
      console.log('サーバーからのエラーレスポンス:', error.response.data)
    }
  }
})
</script>
