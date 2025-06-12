<template>
  <div class="min-h-screen flex flex-col">
    <AppHeader />
    <main class="flex-grow flex items-center justify-center px-4">
    <div class="flex-grow max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
      <h2 class="text-xl font-bold mb-4">新規登録</h2>
      <form class="h-adr" @submit.prevent="register">
        <input v-model="nickname" type="text" placeholder="ニックネーム" class="input mb-3 w-full" />
        <input v-model="name" type="text" placeholder="名前" class="input mb-3 w-full" />
        <span class="p-country-name" style="display:none;">Japan</span>
        <input v-model="postalCode" type="text" class="input mb-3 w-full p-postal-code" placeholder="郵便番号（例：1000001）" />
        <input v-model="address" ref="addressInput" type="text" class="input mb-3 w-full p-region p-locality p-street-address" placeholder="住所" />
        <input v-model="number" type="text" placeholder="電話番号" class="input mb-3 w-full" />
        <input v-model="email" type="email" placeholder="メールアドレス" class="input mb-3 w-full" />
        <input v-model="password" type="password" placeholder="パスワード" class="input mb-3 w-full" />
        <input v-model="passwordConfirmation" type="password" placeholder="パスワード確認" class="input mb-3 w-full" />
        <button class="btn-purple w-full">登録</button>
      </form>
    </div>
    </main>
    <AppFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'

const nickname = ref('')
const name = ref('')
const address = ref('')
const number = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const router = useRouter()


function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
  return null;
}

const register = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true });
    const xsrfToken = getCookie('XSRF-TOKEN');
    await axios.post('/register',
    {
      nickname: nickname.value,
      name: name.value,
      address: address.value,
      number: number.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
      },
    {
      withCredentials: true,
      headers: {
      'X-XSRF-TOKEN': xsrfToken
       }
    })
    alert('登録成功！')
    router.push('/')
  } catch (error) {
    alert('登録に失敗しました')
    console.error(error)
  }
}
</script>
