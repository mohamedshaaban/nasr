<template>
  <label class="cart-count badge">{{cart.cartCount}}</label>
</template>

<script>
export default {
  data() {
    return {
      cart: Object
    };
  },
  created() {
    this.cart = this.$store.state;
  }
};
</script>
