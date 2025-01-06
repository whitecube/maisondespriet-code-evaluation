<template>
    <div class="cart-item">
        <div class="cart-item__info">
            <p class="cart-item__name">{{ item.name }}</p>
            <p class="cart-item__quantity">Quantité : {{ item.quantity }}</p>
        </div>
        <button class="button" @click.prevent="$emit('increment')">➕</button>
        <button class="button" @click.prevent="$emit('decrement')">➖</button>
        <button class="button" @click.prevent="$emit('remove')">🗑️</button>
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