<script setup>
import {useProductStore} from '../stores/productStore.js'

const productStore = useProductStore()
</script>

<template>
  <div class="m-2">
    <h2 class="border-bottom">New Product</h2>
    <form @submit.prevent="productStore.newProduct()">
      <div>
        <label style="width: 10%" for="name">Name:</label>
        <input class="w-25" type="text" id="name" v-model="productStore.freshProduct.name" required maxlength="255"/>
      </div>
      <div class="d-flex">
        <label style="width: 10%" for="description">Description:</label>
        <textarea class="w-50" id="description" v-model="productStore.freshProduct.description" required
                  maxlength="255"></textarea>
      </div>
      <div>
        <label style="width: 10%" for="price">Price:</label>
        <input class="w-25" type="number" id="price" v-model="productStore.freshProduct.price" required min="0"
               step="1"/>
      </div>
      <button class="btn btn-success m-5" type="submit">Save</button>
    </form>
  </div>
</template>
