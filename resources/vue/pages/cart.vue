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
                <p v-if="! lines.length">Le panier est vide</p>
                <cart-item v-for="item in lines"
                    :key="item.id"
                    :item="item"
                    @remove="remove(item)" />
            </div>

            <div class="cart__receipt">
                <p class="title">Total</p>
                <p v-html="total"></p>
            </div>
        </div>
    </div>
</template>

<script>
import Product from '../components/product.vue';
import CartItem from '../components/cart-item.vue';

export default {
    props: ['products', 'receipt'],
    components: { Product, CartItem },

    data() {
        return {
            lines: [],
            total: 0,
            url: null
        }
    },

    mounted() {
        this.lines = this.receipt.lines;
        this.total = this.receipt.total;
        this.url = this.receipt.route;
    },

    methods: {
        increment(product) {
            let item = this.lines.find(item => item.product === product.id);

            if (! item) {
                return this.update({id: product.id, quantity: 1});
            }

            this.update({id: product.id, line: item.line, quantity: (item.quantity + 1)});
        },

        decrement(product) {
            let item = this.lines.find(item => item.product === product.id);

            if (! item) {
                return;
            }

            this.update({id: product.id, line: item.line, quantity: Math.max(0, item.quantity - 1)});
        },

        remove(item) {
            if (! item) {
                return;
            }

            this.update({id: item.product, line: item.line, quantity: 0});
        },

        update(data) {
            window.axios.post(this.url, data).then(response => {
                this.lines = response.data.lines;
                this.total = response.data.total;
                this.url = response.data.route;
            });
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
    display: flex;
    justify-content: space-between;
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
