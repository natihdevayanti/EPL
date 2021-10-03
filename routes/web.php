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
Route::get('/', 'Ecommerce\FrontController@index')->name('front.index');
Route::get('/about-us', 'Ecommerce\FrontController@about')->name('front.about');
Route::get('/product', 'Ecommerce\FrontController@product')->name('front.product');
Route::get('/category/{slug}', 'Ecommerce\FrontController@categoryProduct')->name('front.category');
Route::get('/product/{id}', 'Ecommerce\FrontController@show')->name('front.show_product');
Route::get('/product/variant/{var}', 'Ecommerce\FrontController@getVariantInfo')->name('front.show_product.variant');

Route::get('/find-order', 'Ecommerce\FrontController@findOrder')->name('front.find_order');
Route::post('/find-order', 'Ecommerce\FrontController@showOrder')->name('front.find_order.show');

Route::post('cart', 'Ecommerce\CartController@addToCart')->name('front.cart');
Route::get('/cart', 'Ecommerce\CartController@listCart')->name('front.list_cart');
Route::post('/cart/update-reg', 'Ecommerce\CartController@updateSeamlessReg')->name('front.update_seamless_reg');
Route::post('/cart/update-unreg', 'Ecommerce\CartController@updateSeamlessUnreg')->name('front.update_seamless_unreg');
Route::post('/cart/update', 'Ecommerce\CartController@updateCart')->name('front.update_cart');
Route::get('/cart/remove/{id}', 'Ecommerce\CartController@removeFromCart')->name('front.remove_from_cart');

Route::get('/checkout', 'Ecommerce\CartController@checkout')->name('front.checkout');
Route::post('/checkout', 'Ecommerce\CartController@processCheckout')->name('front.store_checkout');
Route::get('/checkout/{invoice}', 'Ecommerce\CartController@checkoutFinish')->name('front.finish_checkout');

Route::group(['prefix' => 'member', 'namespace' => 'Ecommerce'], function() {
    Route::get('login', 'LoginController@loginForm')->name('customer.login');
    Route::post('login', 'LoginController@login')->name('customer.post_login');
    Route::get('verify/{token}', 'FrontController@verifyCustomerRegistration')->name('customer.verify');
    Route::post('verify', 'FrontController@setPassword')->name('customer.set_password');
    Route::get('signup', 'SignupController@signupForm')->name('customer.signup');
    Route::post('signup', 'SignupController@signup')->name('customer.post_signup');
    Route::get('orders/x/{invoice}', 'OrderController@view')->name('customer.view_order_unregistered');
    Route::get('orders/x/pdf/{invoice}', 'OrderController@pdf')->name('customer.order_pdf_unregistered');
    Route::post('orders/x/accept', 'OrderController@acceptOrder')->name('customer.order_accept_unregistered');
    Route::get('orders/x/cancel/{invoice}', 'OrderController@cancelOrder')->name('customer.order_cancel_unregistered');
    Route::get('payment/x', 'OrderController@paymentForm')->name('customer.paymentForm_unregistered');
    Route::post('payment/x', 'OrderController@storePayment')->name('customer.savePayment_unregistered');
    Route::post('/init-payment', 'OrderController@setPaymentDestination')->name('customer.set_payment_destination');

    Route::group(['middleware' => 'customer'], function() {
        Route::get('dashboard', 'LoginController@dashboard')->name('customer.dashboard');
        Route::get('logout', 'LoginController@logout')->name('customer.logout');

        Route::get('orders', 'OrderController@index')->name('customer.orders');
        Route::get('orders/a/{invoice}', 'OrderController@view')->name('customer.view_order');
        Route::get('orders/a/pdf/{invoice}', 'OrderController@pdf')->name('customer.order_pdf');
        Route::post('orders/a/accept', 'OrderController@acceptOrder')->name('customer.order_accept');
        Route::get('orders/a/cancel/{invoice}', 'OrderController@cancelOrder')->name('customer.order_cancel');
        Route::get('orders/return/{invoice}', 'OrderController@returnForm')->name('customer.order_return');
        Route::put('orders/return/{invoice}', 'OrderController@processReturn')->name('customer.return');

        Route::get('payment/a', 'OrderController@paymentForm')->name('customer.paymentForm');
        Route::post('payment/a', 'OrderController@storePayment')->name('customer.savePayment');

        Route::get('setting', 'FrontController@customerSettingForm')->name('customer.settingForm');
        Route::post('setting', 'FrontController@customerUpdateProfile')->name('customer.setting');
    });
});

Auth::routes();

Route::group(['prefix' => 'administrator', 'middleware' => 'auth'], function() {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/manage', 'HomeController@homeManagement')->name('admin.home_management');

    Route::delete('/delete/{id}', 'HomeController@deleteFromSlider')->name('admin.home_management.slider.delete');
    Route::get('/manage/slider/add', 'HomeController@viewAddSlider')->name('admin.home_management.slider.view_add');
    Route::post('/manage/slider/add', 'HomeController@addToSlider')->name('admin.home_management.slider.add');
    Route::get('/manage/slider/show/{id}', 'HomeController@show')->name('admin.home_management.slider.show');
    Route::get('/manage/slider/edit/{id}', 'HomeController@viewEditSlider')->name('admin.home_management.slider.view_edit');
    Route::post('/manage/slider/edit', 'HomeController@editSlider')->name('admin.home_management.slider.edit');

    Route::delete('/{id}', 'HomeController@deleteFromFeatured')->name('admin.home_management.featured.delete');
    Route::post('/manage/featured/add', 'HomeController@addToFeatured')->name('admin.home_management.featured.add');

    Route::resource('admin', 'AdminController');

    
    Route::get('/product_variant/create/{id}', 'ProductVariantController@createVariant')->name('product_variant.create_variant');
    Route::get('/product_variant/get/{id}', 'ProductVariantController@get')->name('product_variant.get');
    Route::resource('category', 'CategoryController')->except(['create', 'show']);
    Route::resource('product', 'ProductController');
    Route::resource('product_variant', 'ProductVariantController')->except(['index', 'create', 'show']);

    Route::group(['prefix' => 'orders'], function() {
        Route::get('/', 'OrderController@index')->name('orders.index');
        Route::get('/orders_pending', 'OrderController@ordersPending')->name('orders.orders_pending');
        Route::get('/orders_done', 'OrderController@ordersDone')->name('orders.orders_done');
        Route::get('/{invoice}', 'OrderController@view')->name('orders.view');
        Route::get('/pending/{invoice}', 'OrderController@pendingPayment')->name('orders.make_pending');
        Route::get('/cancel/{invoice}', 'OrderController@cancelPayment')->name('orders.cancel_payment');
        Route::get('/payment/{invoice}', 'OrderController@acceptPayment')->name('orders.approve_payment');
        Route::post('/shipping', 'OrderController@shippingOrder')->name('orders.shipping');
        Route::get('/finish/{invoice}', 'OrderController@finishOrder')->name('orders.make_done');
        Route::delete('/{id}', 'OrderController@destroy')->name('orders.destroy');
        Route::get('/return/{invoice}', 'OrderController@return')->name('orders.return');
        Route::post('/return', 'OrderController@approveReturn')->name('orders.approve_return');
    });

    Route::group(['prefix' => 'reports'], function() {
        Route::get('/keuangan', 'HomeController@financeReport')->name('report.finance');
        Route::get('/product_statistic', 'HomeController@productStatistic')->name('report.product_statistic');
        Route::get('/order/pdf/{daterange}', 'HomeController@orderReportPdf')->name('report.order_pdf');
        Route::get('/return', 'HomeController@returnReport')->name('report.return');
        Route::get('/return/pdf/{daterange}', 'HomeController@returnReportPdf')->name('report.return_pdf');
    });
});
