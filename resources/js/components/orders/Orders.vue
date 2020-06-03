<template>
      <div>
        <OrdersList :orders="orders" @loadMoreOrders="loadMoreOrders"/>
      </div>
</template>

<script>
import OrdersList from "./OrdersList";
export default {
          components: {
            OrdersList
          },
          props: {
            orders_list: {
              type: Object,
              required: true
            }
  },
  data() {
    return {
      orders: Object,
      filterQuery: {},
      pageNumber: 1
    };
  },
  methods: {

    loadMoreOrders() {
      this.pageNumber++;
      axios
        .get("/orders/more", {
          params: {
            page: this.pageNumber

          }
        })
        .then(response => {
          this.orders.data = this.orders.data.concat(response.data.data);
          this.orders.current_page = response.data.current_page;
          this.orders.last_page = response.data.last_page;
        });
    }
  },
  created() {
    this.orders = this.orders_list;
  }
};
</script>
