<template>
  <div>
    <div class="slid-new-arriwal">
      <a
        :href="`/product/` + product.slug_name"
        v-for="product in products"
        :key="product.key"
        class="col-md-3 prodct-new-arr"
      >
        <div class="pic">
          <img :src="product.main_image_path" :alt="product.name">
        </div>
         <p class="new-labl off" v-if="product.offer">{{product.offer.percentage}}{{$t('website.off_percent_label')}}</p>
          <p class="new-labl out" v-if="!product.in_stock && !product.pre_order">{{ $t('website.out_of_stock_label')}}</p>
          <p class="new-labl pre" v-if="!product.in_stock && product.pre_order">{{ $t('website.pre_order_label')}}</p>
          <p class="new-labl" v-if="product.is_new">{{$('website.new_label')}}</p>

        <div class="btnx-func">
          <button @click="addToWishlist(product)" class="crt">
            <img src="/img/wish.png" alt>
          </button>
          <button @click="addToCart(product)" class="crt">
            <img src="/img/crt.png" alt>
          </button>
        </div>
        <p class="small-des">{{ product.name}}</p>
        <div v-if="product.offer">
          <p
            class="old-item-price"
          >{{ product.currency_name }} {{ product.price * product.currency_exchange_rate}}</p>
          <p
            class="price"
          >{{ product.currency_name }} {{ (product.price - product.offer.fixed) * product.currency_exchange_rate }}</p>
        </div>
        <div v-else>
          <p
            class="price"
          >{{ product.currency_name }} {{ product.price * product.currency_exchange_rate }}</p>
        </div>
      </a>
    </div>

  </div>
</template>

<script>
// import ProductModal from "./ProductModal";

export default {
  components: {
    // ProductModal
  },
  data() {
    return {
      selectedProduct: {},
      // appendProductModal: false,
      // ProductModaltKey: 0
    };
  },
  props: {
    products: {
      required: false
    }
  },

  methods: {
    addToCart(product) {
      // this.ProductModaltKey += 1;
      this.selectedProduct = product;
      // this.appendProductModal = true;
      // this.openProductModal();
    },
    addToWishlist(product) {
      if (this.$store.getters.isLoggedIn) {
        this.$store.commit("addToWishlist", product.id);
        this.showAlert("Product successfully added to wishlist");
      } else {
        $("#login-reg").modal("show");
      }
    },

    showAlert(message) {
      this.$swal({
        type: "success",
        title: message,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000
      });
    }
  }
};
</script>
