<template>
    <div>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="/">{{$t('website.home_label')}}</a></li>
            <li class="active">{{$t('website.special_offers_label')}}</li>
        </ul>
    </div>
  <div class="container">
    <div class="row">


      <div class="col-sm-12 product-listing">
        <ProductsList
          :products="products"
          @loadMoreProducts="loadMoreProducts"
          @fetchProducts="fetchFilterDataOffers"
        />
      </div>
    </div>
  </div>
    </div>
</template>
<script>
import ProductsList from "./ProductsList";

export default {
  components: {
    ProductsList
  },
  props: {
    products_list: {
      required: true
    },

  },
  data() {
    return {
      products: Object,
      filterQuery: {},
      pageNumber: 1
    };
  },
  methods: {
      fetchFilterDataOffers(filterQuery) {
          this.pageNumber = 1;

      let queryObj = {};
          queryObj['sorting'] = filterQuery;


      axios
        .get("/special-offers/filter", {
          params: queryObj
        })
        .then(response => {
          this.products = response.data;
        });
    },

    loadMoreProducts() {
      this.pageNumber++;
      let queryObj = {};
      queryObj["page"] = this.pageNumber;

      for (var property in this.filterQuery) {
        if (this.filterQuery.hasOwnProperty(property)) {
          queryObj[property] = this.filterQuery[property];
        }
      }

      axios
        .get("/products/filter", {
          params: queryObj
        })
        .then(response => {
          this.products.data = this.products.data.concat(response.data.data);
          this.products.current_page = response.data.current_page;
          this.products.last_page = response.data.last_page;
        });
    }
  },
  created() {
    this.products = this.products_list;
      this.filterQuery = this.selected_categories;

  }
};
</script>
