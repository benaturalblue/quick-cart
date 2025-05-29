<!-- src/views/RegisterView.vue -->
<template>
  <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">新規登録</h2>
    <form @submit.prevent="register">
      <input v-model="name" type="text" placeholder="名前" class="input mb-3 w-full" />
      <input v-model="email" type="email" placeholder="メールアドレス" class="input mb-3 w-full" />
      <input v-model="password" type="password" placeholder="パスワード" class="input mb-3 w-full" />
      <input v-model="passwordConfirmation" type="password" placeholder="パスワード確認" class="input mb-3 w-full" />
      <button class="btn-purple w-full">登録</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')

const register = async () => {
  try {
    await axios.post('http://localhost/api/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    })
    alert('登録成功！')
  } catch (error) {
    alert('登録に失敗しました')
    console.error(error)
  }
}
</script>
