<template>
  <div class="container">
    <div class="row">


      <div class="col-sm-12 product-listing">
        <ProductsList
            :categoryname="categoryname"
          :products="products"
          @loadMoreProducts="loadMoreProducts"
          @fetchProducts="fetchFilterData"
        />
      </div>
    </div>
  </div>
</template>
<script>
import ProductsFilter from "./ProductsFilter";
import ProductsList from "./ProductsList";

export default {
  components: {
    ProductsFilter,
    ProductsList
  },
  props: {
    products_list: {
      required: true
    },

    categories: {
      type: Array,
      required: true
    },
      vendors: {
          type: Array,
          required: true
      },
      selected_categories: {
          type: Array,
      },
        categoryname:{},
  },
  data() {
    return {
      products: Object,
      filterQuery: {},
      pageNumber: 1,
    };
  },
  methods: {
    fetchFilterData(filterQuery) {
       console.log(filterQuery);
      this.filterQuery = filterQuery;
      this.pageNumber = 1;

      let queryObj = {};
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
    },

  },
  created() {
    this.products = this.products_list;
    this.selected_categories = this.selected_categories;
    this.filterQuery = this.selected_categories;
  }
};
</script>
