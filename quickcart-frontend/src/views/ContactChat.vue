<template>
  <div class="min-h-screen bg-gradient-to-br py-8">
    <div class="p-4 max-w-xl mx-auto bg-white rounded-xl shadow-lg">
      <h1 class="text-2xl font-bold text-center mb-6">お問い合わせ</h1>

      <div class="border p-4 h-96 overflow-y-auto mb-4 bg-gray-50 rounded-lg shadow-inner space-y-3">
        <div
          v-for="msg in messages"
          :key="msg.id"
          :class="msg.user_id === userId ? 'text-right' : 'text-left'"
        >
          <div
            :class="[
              'inline-block px-4 py-2 rounded-lg',
              msg.user_id === userId
                ? 'bg-gray-500 text-white rounded-br-none'
                : 'bg-gray-200 text-gray-800 rounded-bl-none'
            ]"
          >
            {{ msg.message }}
          </div>
          <div class="text-xs text-gray-500 mt-1" :class="msg.user_id === userId ? 'pr-1' : 'pl-1'">
            {{ formatTimestamp(msg.created_at) }}
          </div>
        </div>
      </div>

      <form @submit.prevent="sendMessage" class="flex gap-2">
        <input
          v-model="newMessage"
          placeholder="メッセージを入力"
          class="flex-grow border rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400"
        />
        <button
          type="submit"
          class="bg-red-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
        >
          送信
        </button>
      </form>
    </div>
  </div>
</template>



<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const newMessage = ref('')
const messages = ref([])
const userId = ref(null)

function getCookie(name) {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift())
}

function formatTimestamp(timestamp) {
  const date = new Date(timestamp)
  const yyyy = date.getFullYear()
  const mm = String(date.getMonth() + 1).padStart(2, '0')
  const dd = String(date.getDate()).padStart(2, '0')
  const hh = String(date.getHours()).padStart(2, '0')
  const mi = String(date.getMinutes()).padStart(2, '0')
  return `${yyyy}/${mm}/${dd} ${hh}:${mi}`
}

axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.headers.common['X-XSRF-TOKEN'] = getCookie('XSRF-TOKEN')

async function loadMessages() {
  const [msgRes, userRes] = await Promise.all([
    axios.get('/api/contact/messages', { withCredentials: true }),
    axios.get('/api/user', { withCredentials: true })
  ])
  messages.value = msgRes.data
  userId.value = userRes.data.id
}

async function sendMessage() {
  if (!newMessage.value.trim()) return

  try {
    await axios.get('/sanctum/csrf-cookie')

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
