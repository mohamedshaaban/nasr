<template>
  <div ref="container">


    <div class="col-md-9 wishlist-page">
<p class="item-counter-veiwer">{{$t('website.wish_list_label')}}&ThickSpace;<span>&lpar;{{$t('website.items_label')}} {{  products.to  }} {{$t('website.of_label')}} {{ products.total }} {{$t('website.total_label')}}&rpar;</span></p>
      <div v-for="(product,index) in products.data" :key="product.key">

          <a :href="`/product/`+ product.slug_name" class="col-md-12 prodct-list-item">
          <section class="row item-row"  >
              <div class="col-md-4">
                  <img
                      :src="`/uploads/`+ product.main_image"
                      class="img-responsive"
                      :alt="product.name_en"
                  >
              </div>
              <div class="col-md-5">
                  <h3>{{ product.name_en }}</h3>
                  {{$t('website.by_label')}}  {{$t('website.code_label')}}:{{ product.code }}
                  <div class="price" v-if="product.offer">


                      <div class="new" v-if="product.offer.is_fixed==1">{{$t('website.kd_label')}} {{ parseFloat(product.price-product.offer.fixed).toFixed(3) }}</div>
                      <div class="new" v-else>{{$t('website.kd_label')}} {{ parseFloat(product.price -((product.price*product.offer.percentage)/100)).toFixed(3)}}</div>
                  </div>

                  <div class="price" v-else>
                      <div class="new"  >{{$t('website.kd_label')}} {{ parseFloat(product.price).toFixed(3)  }}</div>

                  </div>
              </div>
              <div class="col-md-3">
                 <button class="btn btn-success mt-10 " @click.prevent="addToCart(product)">{{$t('website.add to bag_label_label')}}</button>
                  <button class="btn btn-success mt-10" @click.prevent="removeFromWishList(product , index)">{{$t('website.remove_item_label')}}</button>
              </div>
          </section><!--/section-->
          </a>
      </div>


      </div>

    <div class="col-xs-12 text-center" v-if="products.current_page < products.last_page">
      <a @click.prevent="loadMoreProducts()" href="#" class="view-more-tbl">{{$t('website.view_more_label')}}</a>
    </div>
  </div>
</template>
<script>
export default {
  components: {

  },
  data() {
    return {
      selectedProduct: {},

    };
  },
  props: {
    products: {
      type: Object,
      required: true
    }
  },
  methods: {
    loadMoreProducts: function() {
      this.$emit("loadMoreProducts");
    },
    addToCart(product) {

        let data = {
            product: product,
            price: product.productPrice,
            quantity: 1
        };

        this.$store.commit("addToCart", data);


        this.showAlert(this.$t("website.added_to_cart_message"));

    },

    removeFromWishList(product , index)
    {

        this.products.data.splice(index, 1);
        this.$store.commit("removeFromWishList", product.id);

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
