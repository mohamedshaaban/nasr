<?php
Route::prefix('vendor')->group(function () {
    Route::get('/getsizestype/{type_id}', 'Vendor\ProductsController@getTypeSizes')->name('vendor.getTypeSizes');
    Route::get('/getSubCategories/{parent_id}', 'Vendor\ProductsController@getSubCategories')->name('vendor.getSubCategories');
    Route::get('/vendorregister', 'Vendor\RegisterController@showRegisterForm')->name('vendor.register');
    Route::post('/submitregister', 'Vendor\RegisterController@register')->name('vendor.submit.register');
    Route::get('/vendorlogin', 'Vendor\RegisterController@showLoginForm')->name('vendor.login');
    Route::post('/submitlogin', 'Vendor\RegisterController@login')->name('vendor.submit.login');
    Route::get('/requestpassword', 'Vendor\RegisterController@showRequestPasswordForm')->name('vendor.password.request');
    Route::post('/password/email', 'Vendor\RegisterController@sendEmailLink')->name('vendor.password.request.email');
    Route::get('/setpassword', 'Vendor\RegisterController@setPassword')->name('vendor.password.reset');
    Route::post('/setpassword', 'Vendor\RegisterController@updatePassword')->name('vendor.password.update');



    Route::post('/logout', 'Vendor\RegisterController@logout')->name('vendor.logout');

    Route::get('/activate/{token}', 'Vendor\RegisterController@activateaccount')->name('vendor.activate');

});

Route::middleware(['auth:vendor'])->prefix('vendor')->group(function () {



// dashboard
Route::get('/', 'Vendor\DashboardController@index');
Route::get('/dashboard', 'Vendor\DashboardController@index')->name('vendor.dashboard.index');
Route::get('/dashboard/calendar_orders', 'Vendor\DashboardController@calendarOrders')->name('vendor.dashboard.calendar');
    // users
    Route::resource('users', 'Vendor\UsersController', [
        'names' => [
            'index' => 'vendor.user.index',
            'store' => 'vendor.user.store',
            'show' => 'vendor.user.show',
            'update' => 'vendor.user.update',
            'edit' => 'vendor.user.edit',
            'create' => 'vendor.user.create'
        ]
    ])->only(['index', 'store', 'show', 'update', 'edit' ,'create']);


    // delivery charge and minimum order
    Route::resource('delivery_charge', 'Vendor\DeliveryChargeController', [
        'names' => [
            'index' => 'vendor.delivery_charge.index',
            'store' => 'vendor.delivery_charge.store',
        ]
    ])->only(['index', 'store']);

    // profile
    Route::resource('profile', 'Vendor\ProfileController', [
        'names' => [
            'index' => 'vendor.profile.index',
            'store' => 'vendor.profile.store',
        ]
    ])->only(['index', 'store']);
    Route::resource('products/offers/managment', 'Vendor\OfferController', [
        'names' => [
            'index' => 'vendor.offer.index',
            'create' => 'vendor.offer.create',
            'store' => 'vendor.offer.store',
            'edit' => 'vendor.offer.edit',
            'update' => 'vendor.offer.update',
         ]
    ])->only(['index', 'create' ,'store','edit','update']);
    Route::get('products/offers/managment/{id}/destroy','Vendor\OfferController@destroy')->name('vendor.offer.destroy');
    Route::get('payment/index', 'Vendor\PaymentController@index')->name('vendor.payment.index');
    // Route::get('commsission/index', 'Vendor\PaymentController@commission')->name('vendor.commsiision.index');
    // Route::post('commsission/store', 'Vendor\PaymentController@storeCommission')->name('vendor.commission.store');
    Route::post('payment/change_bank_info', 'Vendor\PaymentController@changeBankInfo')->name('vendor.payment.change_bank_info');
    Route::get('payment/download_transferred_payments/{file_name}', 'Vendor\PaymentController@downloadTransferredPaymnet')->name('vendor.payment.download_transferred_payments');
    Route::get('/delivery_charge/analytics', 'Vendor\DeliveryChargeController@getAnalyticsAjax')->name('analytics.get_ajax');
    // products
    Route::resource('products', 'Vendor\ProductsController', [
        'names' => [
            'index' => 'vendor.product.index',
            'create' => 'vendor.product.create',
            'store' => 'vendor.product.store',
            'show' => 'vendor.product.show',
            'update' => 'vendor.product.update',
            'edit' => 'vendor.product.edit',
            'destroy' => 'vendor.product.destroy',
        ]
    ])->only(['index', 'create','store', 'show', 'update', 'edit','destroy']);
    Route::get('/getProductPrice/{id}', 'Vendor\ProductsController@getProductPrice')->name('vendor.product.price');
    Route::get('product/{id}/delete', 'Vendor\ProductsController@delete')->name('vendor.product.delete');
    Route::get('product/{id}/hide_product', 'Vendor\ProductsController@hideProduct')->name('vendor.product.hide_product');
    Route::get('orders/print_invoice/{id}', 'Vendor\OrderController@printInvoice')->name('vendor.order.print_invoice');

    // products offers

    // orders
    Route::resource('orders', 'Vendor\OrderController', [
        'names' => [
            'index' => 'vendor.order.index',
            'show' => 'vendor.order.show',
            'create' => 'vendor.order.create',
            'edit' => 'vendor.order.edit',

        ]
    ])->only(['index', 'show', 'create', 'edit']);

    Route::post('orders/save', 'Vendor\OrderController@save')->name('vendor.order.save');

    // reports
    Route::get('reports/{type}', 'Vendor\ReportController@index')->name('vendor.reports');
    Route::get('reportssss/reviews', 'Vendor\ReportController@getReviewsAjax')->name('vendor.reports.reviews_ajax');
    Route::get('404', 'Vendor\DashboardController@unauthorized')->name('vendor.404');
    // payment


});