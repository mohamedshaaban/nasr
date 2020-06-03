<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
require_once 'vendor.php';
Route::get('sitemap.xml', 'HomeController@sitemap');
Route::get('/customerregister', 'Auth\RegisterController@showRegisterForm')->name('website.register');
Route::post('/submitregister', 'Auth\RegisterController@register')->name('website.submit.register');
Route::get('/customerlogin', 'Auth\LoginController@showLoginForm')->name('website.login');

Route::post('/submitlogin', 'Auth\LoginController@login')->name('website.submit.login');

Route::get('/search',                     'Products\ProductsController@search')->name('website.search');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index');
Route::get('/productList/{category_id}',    'Products\ProductsController@index')->name('categoryProductList');
Route::get('/products',                     'Products\ProductsController@index')->name('website.product.index');
Route::post('/chooseProduct',                     'Products\ProductsController@chooseProduct')->name('website.product.chooseProduct');
Route::get('/special-offers',                     'Products\ProductsController@offers')->name('website.product.offers');
Route::get('/special-offers/filter',                     'Products\ProductsController@offersFillter')->name('website.product.offersfillter');
Route::get('/product/{slug}',                 'Products\ProductsController@show')->name('website.product.show');
Route::get('/getSizeInfo/{id}/{product_id}',                 'Products\ProductsController@getSizeInfo')->name('website.product.getSizeInfo');
Route::post('/addReview',                 'Products\ProductsController@addReview')->name('website.product.addReview');
Route::get('/products/filter', 'Products\ProductsController@filter')->name('website.product.filter');
Route::post('/checkEmail', 'Auth\RegisterController@checkEmail')->name('customer.checkEmail');
Route::get('/activate/{token}', 'Auth\LoginController@activateaccount')->name('customer.activate');
Route::post('/country/getgovernate', 'Checkout\CheckoutController@getGovernate')->name('customer.getgovernate');
Route::post('/country/getareas', 'Checkout\CheckoutController@getAreas')->name('customer.getareas');






//Checkout



//Checkout

Route::prefix('cart')->group(function () {
    Route::get('/get', 'Cart\CartController@get')->name('website.cart.get');
    Route::post('/add', 'Cart\CartController@add')->name('website.cart.add');
    Route::get('/remove', 'Cart\CartController@delete')->name('website.cart.remove');
    Route::post('/update', 'Cart\CartController@update')->name('website.cart.update');
    Route::post('/checkdiscount', 'Cart\CartController@checkDiscount')->name('website.cart.checkdiscount');
});
Route::get('orders/print_invoice/{id}', 'Customer\CustomerController@printInvoice')->name('customer.order.print_invoice');
Route::get('orders/vendor_print_invoice/{id}', 'Customer\CustomerController@printVendorInvoice')->name('delivery.order.print_invoice');
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', 'Customer\CustomerController@logout')->name('customer.logout');
    Route::get('/profile', 'Customer\CustomerController@profile')->name('customer.dashboard');
    Route::get('/account-info', 'Customer\CustomerController@accountInfo')->name('customer.account-info');
    Route::get('/address-book', 'Customer\CustomerController@addressBook')->name('customer.address-book');
    Route::get('/address-book/{id}', 'Customer\CustomerController@addressBook')->name('customer.editaddress-book');
    Route::get('/order-history', 'Customer\CustomerController@orderhistory')->name('customer.order-history');
    Route::get('/orders/more',           'Orders\OrdersController@orders')->name('customer.orders');
    Route::get('/my-orders', 'Customer\CustomerController@myorders')->name('customer.my-orders');
    Route::get('/order/{unique_id}', 'Customer\CustomerController@viewOrder')->name('customer.view-order');
    Route::get('/order_invoice/{unique_id}', 'Customer\CustomerController@viewInvoice')->name('customer.view-order');
    Route::get('/order/transaction/{unique_id}', 'Customer\CustomerController@viewOrderTransaction')->name('customer.view-order-trans');

    Route::get('/track-orders', 'Customer\CustomerController@trackorders')->name('customer.track-orders');
    Route::get('/wish-list', 'Customer\CustomerController@wishlist')->name('customer.wish-list');
    Route::post('/add_to_wishlist', 'Customer\CustomerController@addToWishList')->name('customer.add-wish-list');
    Route::post('/customer/removeFromWishList',      'Customer\CustomerController@removeFromWishList')->name('customer.removeFromWishList');
    Route::get('/getAddressInfo/{id}',       'Customer\CustomerController@getAddressInfo')->name('customer.getAddressInfo');
    Route::get('/deleteAddressInfo/{id}',       'Customer\CustomerController@deleteAddressInfo')->name('customer.deleteAddressInfo');
    Route::get('/returnOrderItem/{id}/{quantity}',       'Orders\OrdersController@deleteOrderItem')->name('customer.deleteOrderItem');

//Update profile
    Route::post('/update_profile', 'Customer\CustomerController@updateProfile')->name('customer.update_profile');
    Route::post('/update_password', 'Customer\CustomerController@update_password')->name('customer.update_password');
    Route::post('/delete_address', 'Customer\CustomerController@delete_address')->name('customer.delete_address');
    Route::post('/submit_address', 'Customer\CustomerController@saveAddress')->name('customer.submit_address');

});
Route::prefix('checkout')->group(function () {
    Route::get('', 'Checkout\CheckoutController@checkout')->name('website.checkout.my_cart');
    Route::get('/my_cart', 'Checkout\CheckoutController@myCart')->name('website.checkout.my_cart');
    Route::post('/addCheckoutAddress', 'Checkout\CheckoutController@addCheckoutAddress')->name('customer.addCheckoutAddress');
    Route::post('/checkvendorarea', 'Checkout\CheckoutController@checkVendorArea')->name('customer.checkvendorarea');
    Route::post('/checkaddress',  'Checkout\CheckoutController@checkRegisteredAreaAddress')->name('customer.checkaddress');
    Route::post('/placeorder', 'Orders\OrdersController@placeOrder')->name('customer.placeOrder');

    Route::get('/thankYou/{order_id}', 'Orders\OrdersController@thankYou')->name('customer.thankYou');
});
// switch language
Route::post('/reorder/order', 'Cart\CartController@reorder')->name('customer.placeOrder');
Route::get('/switch_lang/{locale}', function ($locale = '') {
    session(['locale' => $locale]);

    App::setLocale($locale);

    return redirect()->back();
})->name('switch_lang');
Route::get('/page/{slug}',    'Pages\PagesController@index')->name('pageDetails');
Route::get('/contact-us',    'Pages\PagesController@contactus')->name('contactUs');
Route::post('/contact-us',    'Pages\PagesController@sendContactus')->name('sendContactUs');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::post('/knet/success', 'Knet\KnetController@success')->name('knet.success');
Route::post('/knet/failure', 'Knet\KnetController@failure')->name('knet.failure');
