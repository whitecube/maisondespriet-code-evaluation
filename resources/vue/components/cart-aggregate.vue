<template>
    <div class="cart-aggregate">
        <p class="cart-aggregate__label" v-html="item.label"></p>
        <p class="cart-aggregate__price" v-html="item.price"></p>
    </div>
</template>

<script>
export default {
    props: ['item']
}
</script>

<style scoped>
.cart-aggregate {
    display: flex;
    gap: 12px;
    align-items: center;
}

.cart-aggregate__label {
    flex-grow: 1;
}
</style>