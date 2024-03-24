<script setup>
import {ref} from 'vue';
import {useProductStore} from '../stores/productStore.js'
import ProductDetail from "./ProductDetail.vue"

const productStore = useProductStore();
const searchId = ref()
const searchName = ref()
const searchDescription = ref()

function clearFilter() {
  searchId.value = ''
  searchName.value = ''
  searchDescription.value = ''
}

</script>

<template>
  <table class="table table-responsive table-bordered table-striped table-hover">
    <thead>
    <tr class="table-dark">

      <th>
        <input class="form-control form-control-sm search" v-model="searchId"
               type="text" placeholder="id"/>
      </th>
      <th>
        <input class="form-control form-control-sm search" v-model="searchName"
               type="text" placeholder="name"/>
      </th>
      <th class="w-75">
        <input class="form-control form-control-sm search" v-model="searchDescription"
               type="text" placeholder="description"/>
      </th>
      <th>
        <button type="button" name="search" id="search" class="btn btn-secondary btn-sm w-100"
                @click="productStore.fetchSearch(searchId, searchName, searchDescription)">
          Search
        </button>
      </th>
      <th>
        <button type="button" name="clearFilter" id="clearFilter" class="btn btn-warning btn-sm w-100"
                @click="clearFilter()">Clear
        </button>
      </th>
    </tr>
    <tr class="text-center table-dark">
      <th>#id</th>
      <th>Name</th>
      <th>Description</th>
      <th>Price</th>
      <th>Buttons</th>
    </tr>
    </thead>
    <ProductDetail
        v-for="product in productStore.products" :key="product.id"
        :product="product"
    />
  </table>
</template>
