<template>
    <div class="cart-item">
        <div class="cart-item__info">
            <p class="cart-item__name" v-html="item.label"></p>
        </div>
        <p class="cart-item__price" v-html="item.price"></p>
    </div>
</template>

<script>
export default {
    props: ['item']
}
</script>

<style scoped>
.cart-item {
    display: flex;
    gap: 12px;
    align-items: center;
    border-bottom: 1px solid #E9E9E9;
    padding: 12px 0;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item__info {
    flex-grow: 1;
}
</style>