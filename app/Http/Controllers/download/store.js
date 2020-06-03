import { isNull } from "util";


let cart =[];
let groupedCart =[];

let cartCount = 0;
let cartTotal = 0;
let cartbedforeTotal = 0;
let cartTotalDelivery = 0;
let discountamount = 0;
let cartId    ='';
let discountCode    ='';
let enableOrder    =true;
let filterQuery    =[];
let added = '';


export const strict = false;

const store = {

    state: {
        cart:  [],
        groupedCart:  [],
        cartCount:  0,
        cartTotal :  0,
        cartbedforeTotal :  0,
        cartTotalDelivery :  0,
        discountamount :  0,
        cartId : '',
        discountCode : '',
        enableOrder:true,
        filterQuery : [],
        added :'',

    },
actions:{
    getServerData(state, response) {

        axios
        .get("/cart/get", {
          params: {
            // cartId : window.localStorage.getItem('cartId'),
              discountCode:this.state.discountCode
          }
        })
        .then(response => {
          if(response.data){

            this.state.cart =JSON.parse(response.data.cart);
             this.state.groupedCart =JSON.parse(response.data.groupedCart);
             this.state.cartCount= response.data.cartCount;
           this.state.cartTotal =response.data.totalCart;
           this.state.cartbedforeTotal =response.data.cartbedforeTotal;
           this.state.discountamount =response.data.discountamount;
           this.state.enableOrder =response.data.enableOrder;
          }

        });


    },
    addToWishlist(state,product_id){
        axios
            .post("/add_to_wishlist",{'product_id': product_id})
            .catch(error => {
                if (error.response.status === 401) {

                }
            });
    },
    removeFromWishList(state,product_id){
        axios
            .post("/removeFromWishList",{'product_id': product_id})
            .catch(error => {
                if (error.response.status === 401) {
                    $('#login-reg').modal('show');
                }
            });
    },
},
    mutations: {
        quantityUpdate(state ,payload  )
        {

            payload.map(function(items) {


                    // exit();

                    let item = items;

                    let price = items.price;
                    let quantity = items.quantity   ;

                    axios
                        .post("/cart/update", {
                            params: {
                                cartId: state.cartId,
                                product: item,
                                quantity: quantity,

                            }
                        })
                        .then(response => {
                            state.cartTotal = response.data.totalCart;
                            state.cartbedforeTotal = response.data.totalCart;
                            state.discountamount = response.data.value;


                            state.cart = JSON.parse(response.data.cart);
                            state.groupedCart = JSON.parse(response.data.groupedCart);
                            state.cartCount = response.data.cartCount;
                            state.enableOrder = response.data.enableOrder;

                        });

                // let found = state.cart.find(product => product.product_id == item.id);
            });
if(payload['discount_code']!='undefined')
{

     axios
        .post("/cart/checkdiscount", {
            params: {
                cartId: state.cartId,
                discount_code: payload['discount_code'],

            }
        })
        .then(response => {
                this.state.cartTotal = response.data.total;


                if (response.data.success == true) {
                    $('#discountcodeDiv').show();
                    $('#discountcodeVal').html('- '+response.data.value);
                    this.state.discountCode = payload['discount_code'];
                    swal({
                        type: "success",
                        title: " Coupon Applied",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    $('.subtotal').html(response.data.totalbefore);

                    var deliverycharge = parseInt($('#delivery_charges').html());
                    $('.subtotal').html(parseFloat(response.data.totalbefore+deliverycharge).toFixed(2));
                    $('#total_delivery_charges').html(parseFloat(deliverycharge+response.data.totalbefore).toFixed(2));
                    $('#ordertotal').html(parseFloat(response.data.total).toFixed(2));
                }

            else
            {
$('#discountcodeDiv').hide();
                var deliverycharge = parseInt($('#delivery_charges').html());
                $('#ordertotal').html(parseFloat(deliverycharge+response.data.totalbefore).toFixed(2));

                this.state.discountCode = '';
                swal({
                    type: "success",
                    title: "Invalid Coupon ",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000
                });
            }

        });
}


        },
        updateQuantityCart(state ,payload  )
        {

            payload.map(function(items) {


                    // exit();

                    let item = items;

                    let price = items.price;
                    let quantity = items.quantity   ;

                    axios
                        .post("/cart/update", {
                            params: {
                                cartId: state.cartId,
                                product: item,
                                quantity: quantity,

                            }
                        })
                        .then(response => {
                            state.cartTotal = response.data.totalCart;
                            state.cartbedforeTotal = response.data.totalCart;
                            state.discountamount = response.data.value;


                            state.cart = JSON.parse(response.data.cart);
                            state.groupedCart = JSON.parse(response.data.groupedCart);
                            state.cartCount = response.data.cartCount;
                            state.enableOrder = response.data.enableOrder;

                        });

                // let found = state.cart.find(product => product.product_id == item.id);
            });


        },
        quantityInc(state , payload)
        {
            let item = payload.product;
            let options = payload.options;
            let price = payload.price;
            let quantity =payload.quantity;
            let found = state.cart.find(product => product.product_id == item.id);


                found.quantity = quantity;
                found.total += quantity * price;
                state.cartTotal +=   quantity * price;
                axios
                .post("/cart/update", {
                  params: {
                    cartId : state.cartId,
                    product:item,
                    quantity:quantity,
                    increment:'true'
                  }
                })
                .then(response => {

                    state.cart = JSON.parse(response.data.cart);
                    state.groupedCart = JSON.parse(response.data.groupedCart);
                    state.cartCount = response.data.cartCount;
                    state.cartTotal = response.data.totalCart;
                    state.enableOrder = response.data.enableOrder;
                });

                // this.commit("saveCart");

        },
        quantityDec(state , payload)
        {
            let item = payload.product;
            let quantity = 1;
            let price = payload.price;
            let found = state.cart.find(product => product.product_id == item.id);


            found.quantity = quantity;
            found.total += quantity * price;
            state.cartTotal +=   quantity * price;
            axios
            .post("/cart/update", {
              params: {
                cartId : state.cartId,
                product:item,
                quantity:quantity,
                increment:'false'
              }
            })
            .then(response => {
                window.localStorage.setItem('cartId', response.data.cartId);
                // window.localStorage.setItem('cart', response.data.cart);
                // window.localStorage.setItem('cartCount', response.data.cartCount);
                // window.localStorage.setItem('cartTotal', response.data.totalCart);
                state.cart = JSON.parse(response.data.cart);
                state.cartCount = response.data.cartCount;
                state.cartTotal = response.data.totalCart;

            });

            this.commit("saveCart");
        },
        reOrder(state,order_id)
        {

            axios.post('/reorder/order', {'order_id':order_id } ).then(response => {
                this.state.cart =JSON.parse(response.data.cart);
                this.state.groupedCart =JSON.parse(response.data.groupedCart);

                this.state.cartCount= response.data.cartCount;
                this.state.cartTotal =response.data.totalCart;
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
       addToCart(state, payload) {

           let item = payload.product;

           let price = payload.price;
           let quantity = payload.quantity;
            let extraoption = payload.extraoption;
            let extraoptionvalue = payload.extraoptionvalue;


           let found = state.cart.find(product => product.product_id == item.id);
            if (found) {
                found.quantity += quantity;
                found.total += quantity * price;
                // state.cartTotal +=   quantity * price;
                axios
                .post("/cart/add", {
                  params: {
                    cartId : state.cartId,
                    product:item,
                    quantity:quantity,
                    extraoption:extraoption,
                    extraoptionvalue:extraoptionvalue
                  }
                })
                .then(response => {

                    this.state.cart =JSON.parse(response.data.cart);
                    this.state.groupedCart =JSON.parse(response.data.groupedCart);

                    this.state.cartCount= response.data.cartCount;
                    this.state.cartTotal =response.data.totalCart;
                    this.state.added =response.data.added;
                    this.state.enableOrder =response.data.enableOrder;
                });
            } else {

            axios
                .post("/cart/add", {
                  params: {
                    cartId : state.cartId,
                    product:item,
                    quantity:quantity,
                    extraoption:extraoption,
                    extraoptionvalue:extraoptionvalue

                  }
                })
                .then(response => {

                    this.state.cart =JSON.parse(response.data.cart);
                    this.state.groupedCart =JSON.parse(response.data.groupedCart);

                    this.state.cartCount= response.data.cartCount;
                    this.state.cartTotal =response.data.totalCart;

                    this.state.added =response.data.added;
                    this.state.enableOrder =response.data.enableOrder;                    
                });

            }

            // this.commit("saveCart");
        },
        removeFromCart(state, item) {

             axios
                .get("/cart/remove", {
                  params: {
                    product_id: item.product_id
                  }
                })
                .then(response => {
                    this.state.cart =JSON.parse(response.data.cart);
                    this.state.groupedCart =JSON.parse(response.data.groupedCart);
                    this.state.cartCount=response.data.cartCount;
                    this.state.cartTotal =response.data.totalCart;
                    this.state.enableOrder =response.data.enableOrder;



                });
            // this.commit("saveCart");
        },

        saveCart(state) {

            window.localStorage.setItem('cart', JSON.stringify(state.cart));
            window.localStorage.setItem('cartCount', state.cartCount);
            window.localStorage.setItem('cartTotal', state.cartTotal);

        },


        addToWishlist(state,product_id){
            axios
            .post("/add_to_wishlist",{'product_id': product_id})
            .catch(error => {
              if (error.response.status === 401) {
                $('#login-reg').modal('show');
              }
            });
        },
        removeFromWishList(state,product_id){
            axios
            .post("/customer/removeFromWishList",{'product_id': product_id})
            .catch(error => {
              if (error.response.status === 401) {
                $('#login-reg').modal('show');
              }
            });
        },


    },

    getters :{
        isLoggedIn(state) {
            return window.auth_user;
        }

    },

      strict: false
};

export default store;
