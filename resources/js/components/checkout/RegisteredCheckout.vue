<template>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="Step1">
        <h4>{{$t('website.Shipping_Address')}}</h4>
        <div class="row">

            <div class="col-sm-5" v-for="address in useraddress"  :key="address.key">
                <div class="box">
                    <label class="radio-btn select-box">
                        <input type="radio" id="radio" :value="address.id " name="radio" @change="onChangeAddress($event)"  >
                        <span class="checkmark"></span>
                    </label>
                    <span class="data">
                      {{ address.first_name }} {{ address.second_name }}<br>
                        <span v-if="address.countries">{{ address.countries.name_en }}</span><br>
                        <span v-if="address.governorate">{{ address.governorate.name_en }}</span><br>
                        <span v-if="address.area"> {{ address.area.name_en }}</span><br>
                      {{ address.city }}<br>
                      {{ address.block }}<br>
                      {{ address.street }}<br>
                      {{ address.avenue }}<br>
                      {{ address.floor }}<br>

                    </span>
                    <br>
                    <div class="text-center"><button class="btn btn-success" :value="address.id "  @click="onChangeAddress($event)">Ship Here</button></div>
                </div>
            </div><!--/.col-sm-6-->


            <div class="col-sm-12">
               <a href="/address-book" style="display: none"> <button class="btn-lg btn-success mt-30">{{$t('website.add_address')}}</button></a>
                <button id="next-rev-pay" disabled class="btn-lg btn-success mt-30"  >
                    <a href="#Step2"   aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true">
                        {{$t('website.next_label')}}
                    </a>
                </button>
            </div>


        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="Step2">

        <h4>{{$t('website.delivery_address_label')}}</h4>
        <div class="row">
            <div class="col-sm-6">
                    <span class="data">
                      <span id="username"></span><br>
                      <span id="areaname"></span><br>
                      {{$t('website.city_label')}} <span id="cityname"></span><br>
                      {{$t('website.Avenue_label')}} <span id="extraname"></span><br>
                      {{$t('website.floor_label')}} <span id="floorname"></span><br>
                      {{$t('website.flat_label')}} <span id="flatname"></span><br>
                      <span id="countryname"></span><br>
                    </span>
            </div>
        </div>
        <form  @submit.prevent="placeOrder"  >
            <input type="hidden" id="addressid" name="addressid" value="" required />
            <input type="hidden" id="user_id" name="user_id" value="" required />
            <div style="display: none">
            <h4 class="mt-30">{{$t('website.choose_payment_label')}}</h4>
            <div class="row payment-mthd">
                <div class="col-xs-6 col-sm-6 col-md-3 mt-20"  v-for="method in payment_methods"  :key="method.key">
                    <label class="radio-btn"><img :src="`/uploads/`+method.image">
                        <input type="radio" checked v-model="orderfields.orderpayment" :value=" method.id" name="orderpayment" >
                        <span class="checkmark"></span>
                    </label>
                </div>

            </div>
            </div>
            <br><br>
            <button type="submit" class="btn-lg btn-success" id="plc_order" disabled>{{$t('website.Place_Order')}}</button>
            <hr>
            <div class="disc-code" style="display: none">
                <a class="" data-toggle="collapse" data-parent="#stacked-menu" href="#disc-code" aria-expanded="true">{{$t('website.apply_discount')}}</a>
                <div class="collapse clearfix mt-15" id="disc-code" aria-expanded="true" style="">
                    <input type="text" class="form-control" style="max-width: 300px;"  :placeholder="$t('website.enter_code_label')"
                           :value="coupon"
                           :v-model="orderfields.discount_code"
                           name="discount_code"
                           id="discount_code"
                    >
                    <div class="clearfix">
                        <a class="update-cart apply-discount-cart" href="#" @click.prevent="updateCart(cart)">{{$t('website.apply_discount')}}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</template>
<script>
    export default {
        props: {
            countries: {
                required: true,
                type: Array
            },
            governates: {
                required: true,
                type: Array
            },
            areas: {
                required: true,
                type: Array
            },
            useraddress: {
                required: true,
                type: Array
            },
            payment_methods:{
                required: true,
                type: Array
            },
            coupon:{

            },

        },
        data() {
            return {
                fields: {},
                orderfields: {},
                errors: {},
                governates_arr: Array,
                areas_arr: Array,
            }
        },
        methods: {
            updateCart(cart) {
                let discount_code = $('#discount_code').val();
                let productsQuantityArray = this.productsQuantity;
                let data = [];
                data['discount_code'] = discount_code;


                this.$store.commit("quantityUpdate", data );

            },
            placeOrder()
            {
               var address_id = $('#addressid').val();
               var user_id = $('#user_id').val();
                var discount_code = $('#discount_code').val();
                axios.post('/checkout/placeorder', {'address_id': address_id, 'user_id': user_id ,'orderpayment': this.orderfields.orderpayment , 'discount_code' : discount_code} ).then(response => {

                    if(response.data.status =='true')
                    {

                        if(response.data.knetpayment == 1)
                        {
                            window.location.href =   response.data.data.knetUrl + response.data.data.params;
                        }
                        else {
                            window.location.href = "/checkout/thankYou/"+response.data.order;
                        }

                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
            onChangeAddress(event)
            {
                $("input[name=radio][value="+event.target.value+"]").prop("checked",true);
                axios.post('/checkout/checkaddress', {'address_id': event.target.value} ).then(response => {

                        if(response.data.status==true)
                        {

                            $('#addressid').val(event.target.value);
                            $('#user_id').val(response.data.user_id);
                            $('#username').html(response.data.address.first_name+' '+response.data.address.second_name);
                            $('#areaname').html(response.data.address.governorate.name_en);
                            $('#cityname').html(response.data.address.city);
                            $('#extraname').html(response.data.address.avenue);
                            $('#floorname').html(response.data.address.floor);
                            $('#flatname').html(response.data.address.flat);
                            $('#countryname').html(response.data.address.countries.name_en);

                            this.$store.state.cartTotalDelivery = response.data.totalDelivery;
                            $('#delivery_charges').html( parseFloat(response.data.totalDelivery).toFixed(3));
                            $('#total_delivery_charges').html(parseFloat(response.data.total).toFixed(3));
                            $('#ordertotal').html(parseFloat(response.data.total).toFixed(3));
                            $('#next-rev-pay').prop('disabled', false);
                            $('#plc_order').prop('disabled', false);
                        }
                        else
                        {
                            $('#next-rev-pay').prop('disabled', true);
                            $('#plc_order').prop('disabled', true);
                            this.$store.state.cartTotalDelivery = 0;
                            $('#delivery_charges').html( '00');
                            $('#total_delivery_charges').html(parseFloat(this.$store.state.cartTotal).toFixed(3));
                            this.showAlert(response.data.product);
                        }

                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },

            onChangeArea(event)
            {
                axios.post('/checkout/checkvendorarea', {'area_id': event.target.value} ).then(response => {
                    // this.areas_arr = response.data;


                    if(response.data.status == false)
                    {
                        this.$store.state.cartTotalDelivery = 0;
                        $('#delivery_charges').html( '00');
                        $('#total_delivery_charges').html(parseFloat(this.$store.state.cartTotal));
                        this.showAlert(response.data.product);

                    }
                    else
                    {
                        this.$store.state.cartTotalDelivery = response.data.totalDelivery;
                        $('#delivery_charges').html( parseFloat(response.data.totalDelivery));
                        $('#total_delivery_charges').html(parseFloat(response.data.totalDelivery)+parseFloat(this.$store.state.cartTotal));

                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
            showAlert(product) {
                product = JSON.parse(product);

                this.$swal({
                    type: "error",
                    title: "Product "+product.name_en+" is not supported in your area",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000
                });
            },
        },
        created()
            {
                this.governates_arr = this.governates;
                this.areas_arr = this.areas;
            }
    }
</script>
