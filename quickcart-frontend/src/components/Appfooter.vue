<template>
  <footer class="text-center py-6 border-t text-sm text-gray-600">
    <p>&copy; 2025 QuickCart. All rights reserved.</p>
    <div class="mt-2 space-x-4">
      <router-link to="/privacy" class="hover:underline">プライバシーポリシー</router-link>
      <router-link to="/terms" class="hover:underline">利用規約</router-link>
      <router-link to="/contact" class="hover:underline">お問い合わせ</router-link>
    </div>
    <router-link to="/">
    <img
      src="/images/quickcart_logo.jpg"
      alt="QuickCart"
      class="h-20 mx-auto"
    />
    </router-link>
  </footer>
</template>

<script setup>
</script>
