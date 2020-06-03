<template>
    <div ref="container">
        <div class="row">
            <div class="col-sm-12 filtering">
                <div class="pull-left name">
                    {{ categoryname }} ({{ products.total}})
                </div>


                <div class="pull-right">
                    <span> {{$t('website.sort_by_label')}} : </span>
                    <select @change="setFilterQuery('sorting',$event)">
                        <option value="newest">{{$t('website.newest_label')}}</option>
                        <option value="latest">{{$t('website.latest_label')}}</option>
                        <option value="alpaasc">{{$t('website.alphnewest_label')}}</option>
                        <option value="alpadesc">{{$t('website.alphlatest_label')}}</option>
                        <option value="asc">{{$t('website.sorting_price_asc_label')}}</option>
                        <option value="desc">{{$t('website.sorting_price_desc_label')}}</option>
                    </select>

                </div>
            </div><!--/.filtering-->
        </div><!--/.row-->

        <div class="row">
            <div v-for="(product) in products.data" :key="product.key"  class="col-sm-4">
                <a :href="`/product/`+ product.slug_name" class="col-md-12 prodct-list-item">
                <div class="prod-bx">
                    <div class="img">
                        <img :src="'/uploads/'+product.main_image">
                        <div class="price" v-if="product.offer">
                        <span class="disc" v-if="product.offer.is_fixed==1">{{ parseInt((((product.offer.fixed))*100)/product.price) }}%</span>
                        <span class="disc" v-else>{{ product.offer.percentage }}%</span>
                        </div>
                    </div>

                    <h5><span v-for="category in product.categories " :key="category.key">{{ category.name }}  </span></h5>
                    <h3>{{ product.name}}</h3>
                    
                    <p  v-if="product.vendor"></p>
                   
                </div><!--/.prod-bx-->
                </a>
            </div>

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

    };
  },
  props: {
    products: {
      type: Object,
      required: true
    },
      categoryname:{},
  },
  methods: {
    sorting(event) {

      this.$emit("fetchProducts",   event.target.value );
    },
    loadMoreProducts: function() {
      this.$emit("loadMoreProducts");
    },
    addToCart(product) {

      this.selectedProduct = product;

    },
    addToWishlist(product) {
      if (this.$store.getters.isLoggedIn) {
        this.$store.commit("addToWishlist", product.id);
        this.showAlert(this.$t("website.added_to_wishlist_message"));
      } else {
          this.showWarningAlert(this.$t("website.you_must_logged"));
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
      showWarningAlert(message) {
      this.$swal({
        type: "warning",
        title: message,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000
      });
    },
      setFilterQuery: function(name, element) {

          if (this.$store.filterQuery[name]) {

              if (this.$store.filterQuery[name].includes(element.target.value)  ==true ) {

                  this.$store.filterQuery[name].splice(this.$store.filterQuery[name].indexOf(element.target.value),1);
              } else {

                  this.$store.filterQuery[name]=((element.target.value));
              }
          } else {

              this.$store.filterQuery[name]=((element.target.value));

          }

          this.getFilterData();
      },
      getFilterData() {
          this.$emit("fetchProducts", this.$store.filterQuery);
      },
  }
};
</script>
