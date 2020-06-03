<template>
      <div>

        <WishList :products="products" @loadMoreProducts="loadMoreProducts"/>
      </div>
</template>

<script>
import WishList from "./WishList";
export default {
          components: {
            WishList
          },
          props: {
            wish_list: {
              type: Object,
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

    loadMoreProducts() {
    //   this.pageNumber++;
    //   axios
    //     .get("/orders/more", {
    //       params: {
    //         page: this.pageNumber

    //       }
    //     })
    //     .then(response => {
    //       this.orders.data = this.orders.data.concat(response.data.data);
    //       this.orders.current_page = response.data.current_page;
    //       this.orders.last_page = response.data.last_page;
    //     });
    }
  },

  created() {
    this.products = this.wish_list;

  }
};
</script>
