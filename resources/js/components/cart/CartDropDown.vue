<template>
  <div class="cart_contents full-width">
    <h2>{{ $t('website.recently_Added_label')}}</h2>

    <h1 class="ttl-crt">{{ cart.cartCount }} {{ $t('website.items_label')}}</h1>

    <!-- /.border_dashed -->
    <div class="border_dashed full-width" v-for="cart_product in cart.cart" :key="cart_product.key">
      <img :src="cart_product.image" alt="cart" class="mCS_img_loaded">
      <h1>{{cart_product.product.name}}</h1>

      <h3 style="display: none">{{$t('website.kd_label')}} {{ parseFloat(cart_product.total).toFixed(3) }}</h3>
      <h1 class="crt-price">{{$t('website.quantity_label')}} : {{ cart_product.quantity}}</h1>
      <h3 class="cart-btnx crt-mt-10" style="margin: 0 4px 0 4px;">
          <a :href="`/product/`+cart_product.product.slug_name">
              <img src="/images/edit.png" alt="">
          </a>
        <a class="delete-4-cart" @click.prevent="removeFromCart(cart_product)">
          <img src="/images/dlt.png" alt>
        </a>
      </h3>


    </div>



    <!-- /.border_dashed -->
    <h1 class="crt-total" style="display: none">{{$t('website.total_label')}} </h1>
    <h1 class="crt-price-ttl" style="display: none">{{$t('website.kd_label')}} {{ parseFloat(cart.cartTotal).toFixed(3) }}</h1>
  </div>
</template>

<script>

export default {

  data() {
    return {
      cart: Object,
    };
  },
  created() {
    // this.$store.commit('getServerData');

      this.cart = this.$store.state;


  },
  beforeCreate() {

    this.$store.dispatch("getServerData");
  },
  methods: {
    removeFromCart(product) {
      this.$store.commit("removeFromCart", product);
    },
    removeCardFromCart(product) {
      this.$store.commit("removeCardFromCart", product);
    }
  }
};
</script>
