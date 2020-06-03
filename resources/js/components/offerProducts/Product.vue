<template>
    <div>

    <div class="row quantity">
        <div class="col-sm-4">
            {{$t('website.Qty')}}
            <input type="number" class="form-control rounded-0"  value="1"
                   id="quantity"
                   name="quantity"
                   v-model="productQuantity"
                   :max=" product.quantity " >
            </div>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <button class="btn-lg btn-success full-width" @click.prevent="addToCart(product)">{{$t('website.add_to_cart_label')}}</button>
        </div>

    </div>

        <a class="wishlist"  @click.prevent="addToWishlist(product)" href="#">{{$t('website.add_to_wish_label')}}</a><a :href="`mailto:?subject=I wanted you to see this product&amp;body=I wanted you to see this product  `+this.currentUrl "
                                                                                                    title="Share by Email">
        {{$t('website.send_email_label')}}</a>
    </div>
</template>
<script>



export default {
    props: {
        product: {
            required: true,
            type: Object
        },

    },
    methods: {
        addToCart(product) {

            if(product.in_stock != 1)
            {
                this.showFailureAlert(this.$t("website.product_out_of_stock"));
                return ;
            }
            if(this.productQuantity>product.quantity || this.productQuantity<1)
            {
                this.showFailureAlert(this.$t("website.unavaliable_quantity"));
                return ;
            }
            // handel errors
            this.handleErrors();
            if (this.errorCount > 0) {
                return;
            }

            let data = {
                product: product,
                price: this.productPrice,
                quantity: this.productQuantity
            };
console.log(data);
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

    },
    data() {
        return {

            productQuantity: 1,
            errorCount: 0,
            error: false,
            errorsMessages: [],

        };
    },


};

</script>
