<template>
  <div class="flex flex-col min-h-screen bg-gray-100">
    <AppHeader />
    <main class="flex-grow flex items-center justify-center px-4">
      <div class="max-w-md w-full bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">ログイン</h2>
        <form @submit.prevent="login">
          <input v-model="email" type="email" placeholder="メールアドレス" class="input mb-3 w-full" />
          <input v-model="password" type="password" placeholder="パスワード" class="input mb-3 w-full" />
          <button class="btn-purple w-full">ログイン</button>
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


axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true

const email = ref('')
const password = ref('')
const router = useRouter()


function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
  return null;
}


const login = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true });

    const xsrfToken = getCookie('XSRF-TOKEN');

    await axios.post(
      '/login',
      {
        email: email.value,
        password: password.value
      },
      {
        withCredentials: true,
        headers: {
          'X-XSRF-TOKEN': xsrfToken
        }
      }
    );


    alert('ログイン成功');
    router.push('/')

  } catch (error) {
    console.error('ログイン失敗', error);
    alert('ログインに失敗しました');
    if (error.response) {
    console.error('レスポンスデータ:', error.response.data);
  }
  }
};

</script>

