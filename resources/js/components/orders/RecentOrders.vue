<template>
    <table class="table">
        <thead>
        <tr>
            <th width="15%" scope="col">{{$t('website.order')}} #</th>
            <th width="15%" scope="col">{{$t('website.Date')}}</th>
            <th width="15%" scope="col">{{$t('website.ship_to')}}</th>
            <th width="15%" scope="col">{{$t('website.Order_Total')}}</th>
            <th width="15%" scope="col">&nbsp</th>
            <th width="25%" scope="col">&nbsp</th>
        </tr>
        </thead>
        <tbody>

        <tr v-for="order in orders" :key="order.key">
            <td>{{ order.unique_id }}</td>
            <td>{{ order.order_date }}</td>
            <td>{{ order.useraddress.area.name_en }}</td>
            <td>{{$t('website.kd_label')}} {{ parseFloat(order.total).toFixed(3) }}</td>
            <td>&nbsp</td>
            <td><a class="btn" :href="`/order/`+order.unique_id">{{$t('website.View Order')}}</a> <a class="btn"  @click.prevent="reorderOrder(order.id)">{{$t('website.Reorder')}}</a></td>
        </tr>

        </tbody>
    </table>
</template>

<script>

    export default {

        props: {
            orders: {
                type: Array,
                required: true
            }
        },
        methods: {
            loadMoreOrders: function() {
                this.$emit("loadMoreOrders");
            },
            reorderOrder(orderId)
            {
                this.$store.commit("reOrder", orderId);
                this.showAlert(this.$t("website.products_added_to_cart_message"));
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
        }
    };
</script>
