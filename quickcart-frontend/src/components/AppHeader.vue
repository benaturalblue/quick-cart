<template>
  <header class="flex justify-between items-center px-6 py-5 shadow-md">
    <router-link to="/">
    <img
      src="/images/quickcart_logo.jpg"
      alt="QuickCart"
      class="h-16 object-contain"
    />
    </router-link>
    <div class="space-x-4">
      <template v-if="user">
        <span class="text-gray-700">{{ user.nickname }} 様</span>
        <router-link to="/cart" class="btn-purple">カートを見る</router-link>
        <router-link to="/mypage" class="btn-purple">マイページ</router-link>
      </template>
      <template v-else>
        <router-link to="/login" class="btn-purple">ログイン</router-link>
        <router-link to="/register" class="btn-purple">新規登録</router-link>
      </template>
    </div>
  </header>
</template>

<script setup>
import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'
const userStore = useUserStore()
const { user } = storeToRefs(userStore)
</script>
