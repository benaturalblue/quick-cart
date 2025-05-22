<template>
  <div class="container py-4">
    <div class="row g-4">
      <div
        class="col-12 col-sm-6 col-md-4"
        v-for="item in items"
        :key="item.id"
      >
        <div class="card h-100 text-center shadow-sm mb-4">
          <img :src="item.image_url" class="card-img-top" :alt="item.name" />
          <div class="card-body text-center">
            <h5 class="card-title">{{ item.name }}</h5>
            <p class="card-text text-muted">¥{{ item.price.toLocaleString() }}</p>
            <form @submit.prevent="addToCart(item.id)" class="mb-2">
              <button type="submit" class="btn btn-outline-light btn-purple">カートに入れる</button>
            </form>
            <router-link :to="`/items/${item.id}`" class="btn btn-outline-light btn-purple">
              詳細を見る
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const items = ref([])

onMounted(async () => {
  const res = await axios.get('/api/items')
  items.value = res.data
})

function addToCart(itemId) {
  axios.post('/cart/add', {
    item_id: itemId,
  }).then(() => {
    alert('カートに追加しました！')
  })
}
</script>

<style scoped>
.btn-purple {
  background-color: #7e57c2;
  color: white;
}
.btn-purple:hover {
  background-color: #6a4fb3;
}
</style>
