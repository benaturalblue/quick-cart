<template>
  <div class="p-4 max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">お問い合わせ</h2>

    <div class="border p-4 h-80 overflow-y-scroll mb-4 bg-gray-50">
      <div v-for="msg in messages" :key="msg.id" class="mb-2">
        <div class="bg-white p-2 rounded shadow">{{ msg.message }}</div>
      </div>
    </div>

    <form @submit.prevent="sendMessage">
      <input
        v-model="newMessage"
        placeholder="メッセージを入力"
        class="border p-2 w-full mb-2"
      />
      <button class="bg-blue-500 text-white px-4 py-2 rounded">送信</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const newMessage = ref('')
const messages = ref([])

function getCookie(name) {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift())
}

// Axiosの初期設定（1回だけ実行）
axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.headers.common['X-XSRF-TOKEN'] = getCookie('XSRF-TOKEN')

async function loadMessages() {
  try {
    await axios.get('/sanctum/csrf-cookie') // CSRFトークン事前取得
    const res = await axios.get('/api/contact/messages')
    messages.value = res.data
  } catch (err) {
    console.error('メッセージ取得エラー:', err)
  }
}

async function sendMessage() {
  if (!newMessage.value.trim()) return

  try {
    await axios.get('/sanctum/csrf-cookie') // CSRFトークン事前取得（重要！）

    const res = await axios.post('/api/contact/message', {
      message: newMessage.value
    })

    messages.value.push(res.data)
    newMessage.value = ''
  } catch (err) {
    console.error('送信エラー:', err)
  }
}

onMounted(() => {
  loadMessages()
})
</script>
