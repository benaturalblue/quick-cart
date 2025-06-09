<template>
    <AppHeader />
  <div>
    <h1>商品詳細ページ</h1>
  </div>
  <AppFooter />
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'

export default {
  name: 'ItemDetail',
}
</script>
