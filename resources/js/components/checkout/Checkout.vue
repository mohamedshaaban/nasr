<template>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="Step1">
        <form  @submit.prevent="submit"  >
        <div class="row">
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.email_address_req')}}</label>
                <input type="email" v-model="fields.email" required style="width: 90%; margin-right: 2%;" class="form-control d-inline-block" placeholder="" name="email">
                <a href="#" class="d-inline-block text-right" style="width: 7%;" data-toggle="tooltip" data-placement="top" title="Help will be here">
                    <img src="images/help.png">
                </a>
                <small>{{$t('website.You_Can_create_an_Account_after_Checkout')}}</small>
            </div>
        </div>
        <hr>
        <div class="row">

            <div class="col-sm-9">
                <label>{{$t('website.first_name_label')}}*</label>
                <input type="text" v-model="fields.first_name" required class="form-control" placeholder="" name="first_name">
                <div v-if="errors && errors.first_name"  class="text-danger">{{ errors.first_name[0] }}</div>
            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.last_name_label')}}*</label>
                <input type="text" required v-model="fields.last_name" class="form-control" placeholder="" name="last_name">
                <div v-if="errors && errors.last_name" class="text-danger">{{ errors.last_name[0] }}</div>
            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.mobile_label')}}*</label>
                <input type="text" required v-model="fields.mobile" class="form-control" placeholder="" name="mobile">
                <div v-if="errors && errors.mobile" class="text-danger">{{ errors.mobile[0] }}</div>
            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.country_label')}} * </label>
                 <select class="form-control" required  @change="onChangeCountry($event)"  v-model="fields.country" id="country" name="country">
                     <option value="">{{$t('website.Please_Select')}}</option>
                    <option v-for="country in countries" :key="country.key" :value= "country.id" >{{ country.name_en }}</option>

                </select>
            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.Governorate_label')}}</label>
                <select class="form-control"  @change="onChangeGovernorate($event)" required  v-model="fields.governate_id" id="governate_id" name="governate_id">
                    <option value="">{{$t('website.Please_Select')}}</option>
                    <option v-for="governate in governates_arr" :key="governate.key" :value= "governate.id" >{{ governate.name_en }}</option>
                </select>
            </div><!--/col-sm-9-->

            <div class="col-sm-9 mt-20">
                <label>{{$t('website.area_label')}}</label>
                <select class="form-control" required @change="onChangeArea($event)"  v-model="fields.area_id" id="area_id" name="governate_id">
                    <option value="">{{$t('website.Please_Select')}}</option>
                    <option v-for="area in areas_arr" :key="area.key" :value= "area.id" >{{ area.name_en }}</option>
                </select>
            </div><!--/col-sm-9-->

            <div class="col-sm-9 mt-20">
                <label>{{$t('website.city_label')}}*</label>
                <input type="text" required class="form-control"  v-model="fields.city" placeholder="" name="city">
                <div v-if="errors && errors.city" class="text-danger">{{ errors.city[0] }}</div>
            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.block_label')}}</label>
                <input type="text" required class="form-control"  v-model="fields.block" placeholder="" name="block">
                <div v-if="errors && errors.block" class="text-danger">{{ errors.block[0] }}</div>

            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.street_label')}}</label>
                <input type="text" required class="form-control" placeholder=""  v-model="fields.street" name="street">
                <div v-if="errors && errors.street" class="text-danger">{{ errors.street[0] }}</div>

            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.Avenue_label')}}</label>
                <input type="text" class="form-control" placeholder=""  v-model="fields.avenue" name="avenue">
            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.floor_label')}}</label>
                <input type="text" class="form-control" placeholder="" v-model="fields.floor" name="floor">
            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.flat_label')}}</label>
                <input type="text" class="form-control" placeholder="" v-model="fields.flat" name="flat">
            </div><!--/col-sm-9-->

            <div class="col-sm-9 mt-20">
                <label>{{$t('website.building')}}</label>
                <input type="text" class="form-control" placeholder="" v-model="fields.fax" name="fax">
            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-20">
                <label>{{$t('website.extra_direction_label')}}</label>
                <input type="text" class="form-control" placeholder="" v-model="fields.extra_direction" name="extra_direction">
            </div><!--/col-sm-9-->
            <div class="col-sm-9 mt-30">
                <button id="next-rev-pay" style="display: none;">
                <a href="#Step2" style="display: none;" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="true">
                    {{$t('website.next_label')}}
                </a>
                </button>
                <button type="submit" id="btn_sv_address" disabled class="btn-lg btn-success">
                    {{$t('website.next_label')}}
                </button>
            </div>

        </div>
        </form>
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
                        <div v-if="this.$store.state.cartTotal> 0 ">
            <h4 class="mt-30">{{$t('website.choose_payment_label')}}</h4>
            <div class="row payment-mthd">
                <div class="col-xs-6 col-sm-6 col-md-3 mt-20"  v-for="method in payment_methods"  :key="method.key">
                    <label class="radio-btn"><img :src="`/uploads/`+method.image">
                        <input type="radio"  required v-model="orderfields.orderpayment" :value=" method.id" name="orderpayment" >
                        <span class="checkmark"></span>
                    </label>
                </div>

            </div>
            </div>
            <div v-else >
                <input type="checkbox"  checked="checked" style="display:none"  v-model="orderfields.orderpayment" :value="1" name="orderpayment" >
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
                <div class="clearfix" style="display: none">
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
            submit() {
                this.errors = {};
                axios.post('/checkout/addCheckoutAddress', this.fields).then(response => {

                    $('#next-rev-pay').click();
                    $('#addressid').val(response.data.id);
                    $('#user_id').val(response.data.user_id);
                    $('#username').html(response.data.first_name+' '+response.data.second_name);
                    $('#areaname').html(response.data.governorate.name_en);
                    $('#cityname').html(response.data.city);
                    $('#extraname').html(response.data.avenue);
                    $('#floorname').html(response.data.floor);
                    $('#flatname').html(response.data.flat);
                    $('#countryname').html(response.data.countries.name_en);
                    $('#plc_order').prop('disabled', false);
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
            placeOrder()
            {
               var address_id = $('#addressid').val();
               var user_id = $('#user_id').val();
               var discount_code = $('#discount_code').val();
 var orderpayment =  1;
                if(this.orderfields.orderpayment)
                {
                        orderpayment = this.orderfields.orderpayment;
                }
                axios.post('/checkout/placeorder', {'address_id': address_id, 'user_id': user_id ,
                    'orderpayment': orderpayment ,
                    'discount_code' : discount_code} ).then(response => {

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
            onChangeCountry(event)
            {
                axios.post('/country/getgovernate', {'country_id': event.target.value} ).then(response => {
                        this.governates_arr = response.data;

                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
            onChangeGovernorate(event)
            {
                axios.post('/country/getareas', {'governorate_id': event.target.value} ).then(response => {
                    this.areas_arr = response.data;

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
                        $('#total_delivery_charges').html(parseFloat(this.$store.state.cartTotal).toFixed(3));
                        $('#ordertotal').html(parseFloat(this.$store.state.cartTotal).toFixed(3));
                        this.showAlert(response.data.product);
                        $('#plc_order').prop('disabled', true);
                        $('#btn_sv_address').prop('disabled', true);

                    }
                    else
                    {
                        this.$store.state.cartTotalDelivery = response.data.totalDelivery;
                        $('#delivery_charges').html( parseFloat(response.data.totalDelivery).toFixed(3));
                        $('#total_delivery_charges').html(parseFloat(response.data.total).toFixed(3));
                        $('#ordertotal').html(parseFloat(response.data.total).toFixed(3));

                        $('#btn_sv_address').prop('disabled', false);
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
