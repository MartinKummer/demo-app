<template>
  <h1 class="d-flex justify-content-center m-5">This is my demo App</h1>
  <nav class="mb-5 d-flex justify-content-center">
    <RouterLink class="btn btn-primary m-2" to="/products">Product List</RouterLink>
    <RouterLink class="btn btn-secondary m-2" to="/new">New Product</RouterLink>
  </nav>
  <main>
    <RouterView/>
  </main>
</template>
