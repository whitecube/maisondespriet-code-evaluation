<template>
    <div class="cart">
        <div class="cart__products">
            <product v-for="product, index in products"
                :key="index"
                :product="product"
                @increment="increment(product)"
                @decrement="decrement(product)" />
        </div>

        <div class="cart__side">
            <p class="title">Panier</p>
            <div class="cart__items">
                <p v-if="! cart.length">Le panier est vide</p>
                <cart-item v-for="item in cart"
                    :key="item.id"
                    :item="item"
                    @increment="increment(item)"
                    @decrement="decrement(item)"
                    @remove="remove(item)" />
            </div>

            <div class="cart__receipt">
                <p class="title">Total : {{ total }} €</p>
            </div>
        </div>
    </div>
</template>

<script>
import Product from '../components/product.vue';
import CartItem from '../components/cart-item.vue';

export default {
    props: ['products'],
    components: { Product, CartItem },

    data() {
        return {
            cart: [],
            total: 0
        }
    },

    methods: {
        increment(product) {
            let item = this.cart.find(item => item.id === product.id);

            if (! item) {
                return this.cart.push({
                    ...product,
                    quantity: 1
                });
            }

            item.quantity++;
        },

        decrement(product) {
            let item = this.cart.find(item => item.id === product.id);

            if (! item) {
                return;
            }

            item.quantity--;

            if (item.quantity < 1) {
                this.cart.splice(this.cart.indexOf(item), 1);
            }
        },

        remove(product) {
            let item = this.cart.find(item => item.id === product.id);

            if (! item) {
                return;
            }

            this.cart.splice(this.cart.indexOf(item), 1);
        }
    },

    watch: {
        cart: {
            handler: function () {
                // Temporary
                this.total = this.cart.reduce((carry, item) => carry += item.quantity, 0);

                // Get totals from back-end
                // window.axios.post()
            },
            deep: true
        }
    }
}
</script>

<style scoped>
.cart {
    display: flex;
    align-items: flex-start;
    gap: 32px;
    margin: 60px 0;
}

.cart__products {
    width: 65%;
    display: grid;
    gap: 12px;
    grid-template-columns: repeat(3, 1fr);
}

.cart__side {
    flex-grow: 1;
    padding: 24px;
    border-radius: 6px;
    border: 1px solid #E9E9E9;
}

.cart__receipt {
    margin-top: 30px;
}

@media screen and (max-width: 1024px) {
    .cart {
        flex-direction: column;
    }

    .cart__products,
    .cart__side {
        width: 100%;
    }
}
</style>
