<script setup>
import { useProductStore } from '../stores/productStore.js'

defineProps([
  'product'
])

const productStore = useProductStore()
</script>

<template v-if="product">
  <tbody>
  <tr>
    <td class="align-middle text-center">{{ product.id }}</td>
    <td class="align-middle text-center">{{ product.name }}</td>
    <td class="align-middle text-center">{{ product.description }}</td>
    <td class="align-middle text-center">{{ product.price }}</td>
    <td>
      <RouterLink class="btn btn-success btn-sm w-100 h-50" :to="`/edit/${product.id}`">Edit</RouterLink>
      <button class="btn btn-danger btn-sm w-100 h-50" title="Delete" @click="productStore.deleteProduct(product.id)">
        Delete
      </button>
    </td>
  </tr>
  </tbody>
</template>
