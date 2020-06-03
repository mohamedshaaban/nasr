<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('sliders', \Sliders\SlidersController::class);
    $router->resource('ads', \Ads\AdsController::class);
    $router->resource('teams', \Teams\TeamsController::class);
    $router->resource('posts_categories', \PostsCategories\PostsCategoriesController::class);
    $router->resource('posts', \Posts\PostsController::class);
    $router->resource('payment_methods', \PaymentMethods\PaymentMethodsController::class);
    $router->resource('coupons', \Coupons\CouponsController::class);

    $router->resource('pages', \Pages\PagesController::class);
    $router->resource('settings', \Settings\SettingsController::class);
    $router->resource('faqs', \Faqs\FaqsController::class);
    $router->resource('howworks', \BastaatWorks\BastaatWorksController::class);
    $router->resource('categories', \Categories\CategoriesController::class);
    //Product Attrbuites
    $router->resource('breeds', \Breeds\BreedsController::class);
    $router->resource('ages', \Ages\AgesController::class);
    $router->resource('sizes', \Sizes\SizesController::class);
    $router->resource('colors', \Colors\ColorsController::class);
    $router->resource('genders', \Genders\GendersController::class);
    //Product Attributes
    $router->resource('product_categories', \ProductCategories\ProductCategoriesController::class);
    $router->resource('products', \Products\ProductsController::class);
    $router->resource('offers', \Offers\OffersController::class);

    $router->resource('banks', \Banks\BanksController::class);



    $router->resource('countries', \Countries\CountriesController::class);
    $router->resource('governorate', \Governorate\GovernorateController::class);

    $router->resource('order', Order\OrderController::class)->names('admin_order');
    $router->resource('returnorder', Order\OrderReturnsController::class)->names('admin_order_return');
    $router->resource('order_requesters', Order\OrderRequestersController::class)->names('admin_order_requesters');
    $router->resource('order_destination', Order\OrderDestinationController::class)->names('admin_order_destination');
    $router->resource('vendors', Vendors\VendorsController::class)->names('admin_vendros');
    $router->resource('user_vendors', Vendors\UserVendorsController::class)->names('admin_vendros');


    // vendor delivery charge
    $router->get('delivery_charge/vendors', '\App\Admin\Controllers\Vendors\DeliveryChargeController@index');
    $router->get('delivery_charge/vendors/{id}/edit', '\App\Admin\Controllers\Vendors\DeliveryChargeController@edit');
    $router->post('delivery_charge/vendors/{id}/update', '\App\Admin\Controllers\Vendors\DeliveryChargeController@update')->name('admin.delivery_charger.update');

    $router->resource('customers', \Customers\CustomersController::class);
    $router->resource('guests', \Customers\GuestsController::class);

    $router->get('reports/vendor_orders', '\App\Admin\Controllers\Reports\VendorOrdersController@index')->name('admin.reports.vendor_orders');
    $router->get('reports/sales_orders', '\App\Admin\Controllers\Reports\SalesOrdersController@index')->name('admin.reports.sales_orders');
    $router->get('ajax/vendor', Order\OrderController::class . '@vendor_ajax')->name('ajax.vendor');
    $router->get('ajax/customer', Order\OrderController::class . '@customer_ajax')->name('ajax.customer');
    $router->get('ajax/product', Order\OrderController::class . '@product_ajax')->name('ajax.product');
    $router->get('ajax/product_price', Order\OrderController::class . '@product_price')->name('ajax.product.price');
    $router->get('/getsizestype/{type_id}', '\App\Admin\Controllers\Products\ProductsController@getTypeSizes')->name('admin.getTypeSizes');
    $router->get('importProducts', '\App\Admin\Controllers\Products\ProductsController@importProducts')->name('admin.importProducts');

});
