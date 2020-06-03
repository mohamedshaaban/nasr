<template>
    <div class="col-sm-9 my-order mt-30">
        <div class="heading">{{$t('website.order_history_label')}}</div>
        <div class="table-cvr">
            <table class="table">
      <tbody>
      <tr class="headk">
          <td>{{$t('website.Date')}}</td>
          <td>{{$t('website.order')}} #</td>
          <td>{{$t('website.Status_label')}}</td>
          <td>{{$t('website.Ammount_label')}}</td>
          <td>&nbsp;</td>
        </tr>
      </tbody>
        <tr v-for="order in orders.data" :key="order.key">
            <td><span>{{ order.order_date }}</span></td>
            <td><span>{{ order.unique_id }}</span></td>
            <td><span v-if="order.status">{{ order.status.title_en  }}</span></td>
            <td><span>{{$t('website.kd_label')}} {{ parseFloat(order.total).toFixed(3) }}</span></td>
            <td>
            <a :href="`order/`+ order.unique_id  "><img src="/images/view-icon.png" alt="dhl" class="mCS_img_loaded"></a>  &nbsp;&nbsp;&nbsp;&nbsp;

            </td>
        </tr>
    </table>
  </div>
    <div class="col-xs-12 text-center" v-if="orders.current_page < orders.last_page">
      <a @click.prevent="loadMoreOrders()" href="#" class="view-more-tbl">{{$t('website.view_more_label')}}</a>
    </div>
  </div>
</template>
<script>

export default {

  props: {
    orders: {
      type: Object,
      required: true
    }
  },
  methods: {
    loadMoreOrders: function() {
      this.$emit("loadMoreOrders");
    },
  }
};
</script>
