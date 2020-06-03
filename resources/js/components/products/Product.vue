<template>
    <div>

    <div class="row quantity">
        <div class="col-sm-4">
            {{$t('website.Qty')}}
            <input type="number" class="form-control rounded-0"  value="1"
                   id="quantity"
                   name="quantity"
                   v-model="productQuantity"
                   :min="0"
                   :max=" product.quantity " >
            </div>

        <div v-if="product.product_type==1 || product.product_type==3||product.product_type==4||product.product_type==5||product.product_type==6||product.product_type==7" class="col-sm-6">

            <span v-if="product.product_type==1">{{$t('website.Box')}}</span>
            <span v-if="product.product_type==3">{{$t('website.Felline')}}</span>
            <span v-if="product.product_type==4">{{$t('website.Carton')}}</span>
            <span v-if="product.product_type==5">{{$t('website.Sack')}}</span>
            <span v-if="product.product_type==6">{{$t('website.Bale')}}</span>
            <span v-if="product.product_type==7">{{$t('website.Alshida')}}</span>
            <select class="form-control" name="sizedesc" required id="sizedesc" @change="onChangeSizes($event,product.id)">
                <option value="0">{{$t('website.Please_Select')}}</option>
                <option v-for="option in productsizes" :key="option.key"  :value="option.id " >{{ option.name }}</option>

            </select>
            <small id="smlSizeText"></small>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">
            <button class="btn-lg btn-success full-width" @click.prevent="addToCart(product)">{{$t('website.add_to_cart_label')}}</button>
        </div>

    </div>


    </div>
</template>
<script>


let added;
export default {
    props: {
        product: {
            required: true,
            type: Object
        },
        productsizes: {
            required: true,
            type: Array
        },
    },
    methods: {

        addToCart(product) {


           var extraoption = $("#sizedesc option:selected").text();
           var extraoptionvalue = $("#sizedesc option:selected").val();

            // handel errors
            this.handleErrors();
            if (this.errorCount > 0) {
                return;
            }
            if(product.in_stock != 1 || product.quantity < 1)
            {
                this.showFailureAlert(this.$t("website.product_out_of_stock"));
                return ;
            }
if(this.productQuantity>product.quantity || this.productQuantity<1)
{
this.showFailureAlert(this.$t("website.unavaliable_quantity"));
return ;
}
            let data = {
                product: product,
                price: this.productPrice,
                quantity: this.productQuantity,
                extraoption: extraoption,
                extraoptionvalue: extraoptionvalue
            };

                this.$store.commit("addToCart", data);
                   this.showAlert(this.$t("website.added_to_cart_message"));

        },
        addToWishlist(product) {

            if (this.$store.getters.isLoggedIn) {

                this.$store.commit("addToWishlist", product.id);
                this.showAlert(this.$t("website.added_to_wishlist_message"));
            } else {

                this.showWarningAlert(this.$t("website.you_must_logged"));
            }
        },
        handleErrors() {
            this.errorCount = 0;
            this.error = false;
            this.errorsMessages = [];

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
        showFailureAlert(message) {
            this.$swal({
                type: "warning",
                title: message,
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000
            });
        },
        onChangeSizes(event,product_id)
        {
            $('#smlSizeText').html('');
            axios.get('/getSizeInfo/'+event.target.value+'/'+product_id ).then(response => {

                $('#smlSizeText').html(response.data.productsize.description);
                $('#prodPrice').html(response.data.price);
                $('#prodoldprice').html(response.data.productsize.price);
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
    },
    data() {
        return {

            productQuantity: 1,
            errorCount: 0,
            error: false,
            errorsMessages: [],
            currentUrl:'',


        };
    },
    created: function(){
        this.cart = this.$store.state;
        this.currentUrl = window.location.origin +window.location.pathname;

    }

};

</script>
