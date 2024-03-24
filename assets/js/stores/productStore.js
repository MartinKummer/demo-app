import { defineStore } from "pinia";
import axios from "axios";
import { router} from "../app.js"
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

export const useProductStore = defineStore("products", {
    state: () => {
        return {
            products: [],
            product: null,
            freshProduct: [],
        };
    },
    actions: {
        fetchProducts() {
            axios.get('/api/products')
                .then(response => {
                    this.products = response.data;
                })
                .catch(error => {
                    useToast().error('Error fetching products : ' + error);
                });
        },

        setProductOrFetch(id) {
            const p = Array.from(this.products)
            const product = p.find(p => p.id === id)
            if (product) {
                return this.product = product
            }
            return this.fetchProduct(id)
        },

        fetchProduct(id) {
            axios.get('/api/products/' + id)
                .then(response => {
                    this.product = response.data;
                })
                .catch(error => {
                    useToast().error('Error fetching product : ' + error);
                });
        },

        fetchSearch(id = '', name = '', description = '') {
            axios.get('/api/search', {
                params: {
                    id: id,
                    name: name,
                    description: description
                }
            })
                .then(response => {
                    this.products = response.data;
                })
                .catch(error => {
                    useToast().error('Error fetching search result :' + error);
                });
        },

        updateProduct() {
            axios.put('/api/products/' + this.product.id,
                {
                    name: this.product.name,
                    description: this.product.description,
                    price: this.product.price
                })
                .then(response => {
                    router.push({ path: '/'});
                    useToast().success('Product saved : ID = ' + this.product.id);
                    this.product = null;
                })
                .catch(error => {
                    useToast().error('Error saving product : ' + error);
                });
        },
        deleteProduct(id) {
            axios.delete(`/api/products/${id}`)
                .then(response => {
                    confirm('are you sure you want to delete this item?')
                    this.products = this.products.filter(product => product.id !== id);
                    useToast().warning('Product deleted : ID = ' + id)
                })
                .catch(error => {
                    useToast().error('Error deleting product : ' + error);
                });
        },
        newProduct() {
            axios.post(`/api/products`,
                {
                    name: this.freshProduct.name,
                    description: this.freshProduct.description,
                    price: this.freshProduct.price
                })
                .then(response => {
                    this.freshProduct = []
                    router.push({ path: '/products' });
                    useToast().success('Product created');
                })
                .catch(error => {
                    useToast().error('Error saving product : ' + error);
                });
        }
    },
});
