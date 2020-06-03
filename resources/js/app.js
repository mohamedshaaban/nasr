require('./bootstrap');
import store from './store.js';
import VueSweetalert2 from 'vue-sweetalert2';
import VueInternationalization from 'vue-i18n';
import Locale from './i18n/vue-i18n-locales.generated';


Vue.component('products' , require('./components/products/Products.vue').default);
Vue.component('productsoffers' , require('./components/offerProducts/Products.vue').default);
Vue.component('productdet' , require('./components/products/Product.vue').default);
Vue.component('products-row' , require('./components/products/ProductsRow.vue').default);
Vue.component('cart-dropdown', require('./components/cart/CartDropDown.vue').default);
Vue.component('checkout-my-cart', require('./components/checkout/MyCart.vue').default);
Vue.component('orders' , require('./components/orders/Orders.vue').default);
Vue.component('my_orders' , require('./components/orders/MyOrders.vue').default);
Vue.component('wishlist' , require('./components/wishlist/Wish.vue').default);
Vue.component('checkout-checkout' , require('./components/checkout/Checkout.vue').default);
Vue.component('checkout-registered' , require('./components/checkout/RegisteredCheckout.vue').default);
Vue.component('recentorders' , require('./components/orders/RecentOrders.vue').default);
Vue.component('cart-count', require('./components/cart/CartCount.vue').default);


Vue.use(VueInternationalization);

const lang = document.documentElement.lang.substr(0, 2);
// or however you determine your current app locale

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

new Vue({
    el: '#app',
    store: new Vuex.Store(store),
    strict: true,
    i18n,
    created: function(){

        var currentUrl = window.location.pathname;



    }
});
Vue.use(VueSweetalert2);
