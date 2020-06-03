<template>
    <div ref="container innr-cont-area">
        <div class="row">
            <div class="col-sm-12 filtering">
                <div class="pull-left name">
                    {{$t('website.special_offers_label')}}
                </div>

                <div class="pull-right">
                    <span> {{$t('website.sort_by_label')}} : </span>
                    <select @change="setFilterQuery('sorting',$event)">
                        <option value="alpaasc">{{$t('website.alphnewest_label')}}</option>
                        <option value="alpadesc">{{$t('website.alphlatest_label')}}</option>
                        <option value="asc">{{$t('website.sorting_price_asc_label')}}</option>
                        <option value="desc">{{$t('website.sorting_price_desc_label')}}</option>
                    </select>
                </div>
            </div><!--/.filtering-->
        </div><!--/.row-->

        <div class="row">
            <div v-for="(product) in products.data" :key="product.key"  class="col-sm-3">
                <a :href="`/product/`+ product.slug_name" class="col-md-12 prodct-list-item">
                <div class="prod-bx">
                    <div class="img">
                        <img :src="'/uploads/'+product.main_image">

                        <span class="disc" v-if="product.offer.is_fixed==1">{{parseInt((((product.offer.fixed))*100)/product.price) }}%</span>
                        <span class="disc" v-else>{{ product.offer.percentage }}%
                        </span>
                    </div>
                    <h5>{{ product.categories.name }}</h5>
                    <h3>{{ product.name}}</h3>
                    <p></p>
                    <div class="price">
                        <div class="old">{{$t('website.kd_label')}} {{ parseFloat(product.price).toFixed(2) }}</div>
                        <div class="new" v-if="product.offer.is_fixed==1">{{$t('website.kd_label')}} {{ parseFloat(product.price-product.offer.fixed).toFixed(3) }}</div>
                        <div class="new" v-else>{{$t('website.kd_label')}} {{ product.price -((product.price*product.offer.percentage)/100) }}</div>
                    </div>
                </div><!--/.prod-bx-->
                </a>
            </div>
            <!--<div v-for="(product) in products.data" :key="product.key" class="col-md-4">-->
                <!--<a :href="`/product/`+ product.slug_name" class="col-md-12 prodct-list-item">-->
                    <!--<div class="item-img">-->
                        <!--<img :src="product.main_image_path" class="img-responsive" :alt="product.name">-->
                    <!--</div>-->
                    <!--<p-->
                        <!--class="new-labl off"-->
                        <!--v-if="product.offer"-->
                    <!--&gt;{{product.offer.percentage}}{{$t('website.off_percent_label')}}</p>-->
                    <!--<p-->
                        <!--class="new-labl out"-->
                        <!--v-if="!product.in_stock && !product.pre_order"-->
                    <!--&gt;{{ $t('website.out_of_stock_label')}}</p>-->
                    <!--<p-->
                        <!--class="new-labl pre"-->
                        <!--v-if="!product.in_stock && product.pre_order"-->
                    <!--&gt;{{ $t('website.pre_order_label')}}</p>-->
                    <!--<p class="new-labl" v-if="product.is_new">{{$t('website.new_label')}}</p>-->
                    <!--<div class="btn-fav-cart">-->
                        <!--<button @click.prevent="addToWishlist(product)" class="fav-crt">-->
                            <!--<img src="/img/wish.png" alt>-->
                        <!--</button>-->
                        <!--<button @click.prevent="addToCart(product)" class="fav-crt">-->
                            <!--<img src="/img/crt.png" alt>-->
                        <!--</button>-->
                    <!--</div>-->
                    <!--<p class="item-desc">{{product.name}}</p>-->
                    <!--<div v-if="product.offer">-->
                        <!--<p-->
                            <!--class="old-item-price"-->
                        <!--&gt;{{ product.currency_name}} {{ product.price * product.currency_exchange_rate }}</p>-->
                        <!--<p-->
                            <!--class="item-price"-->
                        <!--&gt;{{ product.currency_name}} {{ (product.price - product.offer.fixed) * product.currency_exchange_rate}}</p>-->
                    <!--</div>-->
                    <!--<div v-else>-->
                        <!--<p-->
                            <!--class="item-price"-->
                        <!--&gt;{{ product.currency_name}} {{product.price * product.currency_exchange_rate}}</p>-->
                    <!--</div>-->
                <!--</a>-->
            <!--</div>-->
            <!--product item-->
        </div>
        <!--row-->
        <div class="col-xs-12 text-center" v-if="products.current_page < products.last_page">
            <a
                @click.prevent="loadMoreProducts()"
                href="#"
                class="view-more-tbl"
            >{{$t('website.view_more_label')}}</a>
        </div>

    </div>
</template>

<script>

export default {

  data() {
    return {
      selectedProduct: {},
        filterQuery: []
    };
  },
  props: {
    products: {
      type: Object,
      required: true
    }
  },
  methods: {
    sorting(event) {
      this.$emit("fetchProducts", { sorting: event.target.value });
    },
    loadMoreProducts: function() {
      this.$emit("loadMoreProducts");
    },
    addToCart(product) {
      // this.ProductModaltKey += 1;
      this.selectedProduct = product;
      // this.appendProductModal = true;
      // this.openProductModal();
    },
    addToWishlist(product) {
      if (this.$store.getters.isLoggedIn) {
        this.$store.commit("addToWishlist", product.id);
        this.showAlert(this.$t("website.added_to_wishlist_message"));
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
    },
      setFilterQuery: function(name, element) {

          this.getFilterData(element.target.value);
      },
      getFilterData(value) {
          this.$emit("fetchProducts" , value );
      },
  },

};
</script>
