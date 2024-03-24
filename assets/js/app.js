import { createApp } from 'vue';
import { createPinia } from "pinia";
import ToastPlugin from 'vue-toast-notification';
import { useProductStore } from './stores/productStore.js'
import { createMemoryHistory, createRouter } from 'vue-router'
import 'vue-toast-notification/dist/theme-bootstrap.css';

import App from './components/App.vue'
import ProductList from './components/ProductList.vue'
import ProductEdit from './components/ProductEdit.vue'
import ProductNew from './components/ProductNew.vue'

const routes = [
    { path: '/products', component: ProductList,
        beforeEnter: () => {
            useProductStore().fetchProducts()
            return true;
        },
    },
    { path: '/edit/:id', component: ProductEdit,
        beforeEnter: (to) => {
            useProductStore().setProductOrFetch(to.params.id)
            return true;
        },
    },
    { path: '/new', component: ProductNew }
]

export const router = createRouter({
    history: createMemoryHistory(),
    routes,
})

const app = createApp(App);
const pinia = createPinia()

app.component('app', App);
app.use(router)
app.use(pinia)
app.use(ToastPlugin)
app.mount('#app');
                                                        