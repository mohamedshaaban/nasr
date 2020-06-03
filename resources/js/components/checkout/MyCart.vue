<template>
    <div class="row">

        <div class="col-sm-12 mt-20">
            <h3 class="innerpage-head">{{$t('website.shopping_cart_label')}}</h3>

        </div>
        <div class="col-sm-8 col-md-9" v-if="cart.cartCount > 0">

            <div  v-for="product_cart in cart.groupedCart" :key="product_cart.key">



            <div class="table-cvr">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">{{$t('website.item_label')}}</th>
                        <th scope="col">{{$t('website.Qty')}}</th>
                        <th scope="col">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr  v-for="product in product_cart" :key="product.key" v-if="product.item" >


                        <td>
                            <img class="product" :src="product.item.image" width="70">
                            <span class="product-data">{{ product.item.product.name }}<br>
                      <small>{{ product.item.extraoption }}</small></span>
                        </td>

                        <td><input type="number" :max=" product.item.product.quantity " :min="1"
                                   v-on:change="quantityProductUpdate($event,product.item.product.id)"
                                   class="form-control" :value=" product.item.quantity " style="width: 70px; height: 40px" name="newquantity"></td>
                        <td><a href="#"   @click.prevent="removeFromCart(product.item)"><img src="/images/close.png"></a></td>
                    </tr>

                    </tbody>
                </table>
            </div>


</div>

            <div class="clearfix">
                <a class="update-cart" href="#" @click.prevent="updateQuantityCart(cart)">{{$t('website.Update Shopping Cart')}}</a>
            </div>
            <hr>

            <br>

            <a  href="#" @click.prevent="checkOrderAmount()"><button class="btn-lg btn-primary rounded-0">{{$t('website.Proceed_To_Checkout')}}</button></a>

        </div><!--/.col-sm-9-->

    </div>
</template>

<script>

export default {
  components: {
  },
    props: {
        cart_list: {
            required: true,
            type: Object
        },
        coupon:
            {
              required: false,
            },
        discountamount:{},

        min_order:
        {},
    },
  data() {
    return {
        productsQuantity: [],
        cart: Object,
        groupedcart: Object,
        errorCount: 0,
        optionError: false,
        optionErrorMessage: null,
        selectedProduct: {},
        ProductModaltKey: 0,
        discountCode:'',


    };
  },

  methods: {
      quantityProductUpdate(event , product_id)
      {
           this.productsQuantity[product_id] =  [];
          this.productsQuantity[product_id] =  [event.target.value];

      },
      checkOrderAmount(){
          console.log(this.cart.cartbedforeTotal);
          if(this.cart.cartbedforeTotal<this.min_order)
          {
            this.$swal({
                type: "warning",
                title: "Min Order : "+this.min_order +" KWD",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000
            });
          }
          else
          {
            window.location = "/checkout";
          }

      },
      updateQuantityCart(cart) {

          let productsQuantityArray = this.productsQuantity;
          let data = [];

          cart.cart.map(function (product) {

              if(productsQuantityArray[product.product_id]>product.product.quantity || productsQuantityArray[product.product_id]<1)
              {
                  this.showFailureAlert(this.$t("website.unavaliable_quantity"));
              }

              if (productsQuantityArray[product.product_id] !='' &&  productsQuantityArray[product.product_id] > 0 && productsQuantityArray[product.product_id]<product.product.quantity && productsQuantityArray[product.product_id]!== undefined) {

                  let prodata = [{
                      'product': product,
                      'quantity': productsQuantityArray[product.product_id][0]
                  }];
                  data.push(prodata);
              }
          });



          this.$store.commit("updateQuantityCart", data );

      },
      updateCart(cart) {
          let discount_code = $('#discount_code').val();
          let productsQuantityArray = this.productsQuantity;
          let data = [];
           data['discount_code'] = discount_code;
          cart.cart.map(function (product) {

                if(productsQuantityArray[product.product_id]>product.product.quantity || productsQuantityArray[product.product_id]<1)
                {
                    this.showFailureAlert(this.$t("website.unavaliable_quantity"));
                }

              if (productsQuantityArray[product.product_id] !='' &&  productsQuantityArray[product.product_id] > 0 && productsQuantityArray[product.product_id]<product.product.quantity && productsQuantityArray[product.product_id]!== undefined) {

                  let prodata = [{
                      'product': product,
                      'quantity': productsQuantityArray[product.product_id][0]
                  }];
                  data.push(prodata);
              }
          });



          this.$store.commit("quantityUpdate", data );

      },

    showAlert() {
      this.$swal({
        type: "success",
        title: "Product successfully updated to your cart",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 10000
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
    removeFromCart(product) {
      this.$store.commit("removeFromCart", product);
      if (this.cart.cartCount == 0) {
        window.location = "/products";
      }
    },

  },


      created() {
    this.cart = this.$store.state;
    this.discountCode = this.cart.discountCode;
    this.enableOrder = this.$store.state.enableOrder;

      }
};
</script>
