<?php

use Illuminate\Support\Facades\Route;


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

//Admin Routes
include 'custom.php';

Route::get('/', function () {
    return redirect('/admin');
    //echo phpinfo(); exit;
});


//Image URL Manipulation
// Route::get('media/thumbnail/{image?}', function($source) {

//     if($source){
//         $src = explode('/',$source);
//         if($src){
//             $src = end($src);
//             if($src){
//                 if(file_exists(public_path('/media/thumbnail/'.$source))){
//                     return '/media/thumbnail/'.$source;
//                 }else{
//                     $image = Image::make(public_path('/').$source)->resize(230,230);
//                     $image->save(public_path('/media/thumbnail/'.$source), 60);
//                    // return '/media/thumbnail/'.$source;
//                    return $image->response('jpg', 70);
//                 }
//             }
//         }

//     }
// })->where('image', '[A-Za-z0-9\/\.\-\_]+');



//Admin Login Routes
Route::get('/admin/login', 'Auth\Admin\LoginController@showLoginForm')->name('login');
Route::post('admin/login', 'Auth\Admin\LoginController@login')->name('admin.login');

// //Password email send
Route::get('/admin/password/reset', 'Auth\Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
Route::post('/admin/password/reset', 'Auth\Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');

Route::post('/admin/reset/send/otp', 'Auth\Admin\ForgotPasswordController@sendOtp')->name('admin.reset.send.otp');
Route::post('/admin/change/password/withotp', 'Auth\Admin\ForgotPasswordController@changePasswordWithOtp')->name('admin.change.password.withotp');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', 'Backend\PagesController@admin')->name('admin.index');
    Route::get('/search-dashboard-index', 'Backend\PagesController@searchDashboardIndex')->name('admin.search.dashboard.index');
    Route::get('/get-search-dashboard-index', 'Backend\PagesController@getSearchDashboardIndex')->name('admin.get.search.dashboard.index');

    Route::get('/create-search-dashboard', 'Backend\PagesController@createSearchDashboard')->name('admin.create.search.dashboard');
    Route::post('/store-search-dashboard', 'Backend\PagesController@storeSearchDashboard')->name('admin.store.search.dashboard');

    Route::get('/edit-search-dashboard/{id}', 'Backend\PagesController@editSearchDashboard')->name('admin.edit.search.dashboard');
    Route::post('/update-search-dashboard/{id}', 'Backend\PagesController@updateSearchDashboard')->name('admin.update.search.dashboard');

    Route::get('/delete-search-dashboard/{id}', 'Backend\PagesController@deleteSearchDashboard')->name('admin.delete.search.dashboard');


    Route::get('/search-dashboard', 'Backend\PagesController@searchDashboard')->name('admin.search.dashboard');

    Route::resource('roles', 'Backend\RolesController');

    Route::get('/notifications', 'Backend\PagesController@notifications')->name('admin.notifications');
    Route::get('/notifications-list', 'Backend\PagesController@notificationsList')->name('admin.get.notification.list');
    Route::get('/notifications/delete/{id}', 'Backend\PagesController@notificationsDelete')->name('admin.notification.delete');

    //Cache Clean
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        session()->flash('success', 'Cache Successfully Cleaned !');
        return back();
    })->name('admin.cache.clear');


    //Run Laravel Scheduler
    Route::get('/schedule-run', function () {
        Artisan::call('schedule:run');
        session()->flash('success', 'Schedule job has been run successfully!');
        return back();
    })->name('admin.schedule.run');




    //Admin Logout Routes
    Route::post('/logout', 'Auth\Admin\LoginController@logout')->name('admin.logout');

    //Products Routes for admin
    Route::group(['prefix' => '/products'], function () {
        Route::get('/', 'Backend\ProductsController@index')->name('admin.product');
        Route::get('/create', 'Backend\ProductsController@create')->name('admin.product.create');
        Route::get('/edit/{id}', 'Backend\ProductsController@edit')->name('admin.product.edit');
        Route::get('/view/{id}', 'Backend\ProductsController@view')->name('admin.product.view');
        Route::post('/store', 'Backend\ProductsController@store')->name('admin.product.store');
        Route::post('/update/{id}', 'Backend\ProductsController@update')->name('admin.product.update');
        Route::get('/delete/{id}', 'Backend\ProductsController@delete')->name('admin.product.delete');
        Route::get('/image_delete/{id}', 'Backend\ProductsController@image_delete')->name('admin.product.image.delete');
        Route::post('/action', 'Backend\ProductsController@action')->name('admin.product.action');
        Route::get('/action/fetch_data', 'Backend\ProductsController@fetch_data');
        Route::get('/get/product/list', 'Backend\ProductsController@getProductList');

        Route::get('/return/request', 'Backend\ProductsController@returnRequest')->name('admin.product.return.request');

        Route::get('/get/return/request/list', 'Backend\ProductsController@getReturnRequestList')->name('admin.product.return.request.list');

        Route::get('/return/request/complete/{id}', 'Backend\ProductsController@returnRequestComplete')->name('admin.product.return.request.complete');

        Route::get('/return/request/return/{id}', 'Backend\ProductsController@returnRequestReturn')->name('admin.product.return.request.return');

        Route::get('/shipping/cost', 'Backend\ProductsController@getShippingCost')->name('admin.product.shipping.cost');
        Route::post('/shipping/cost/store', 'Backend\ProductsController@shippingCostStore')->name('admin.product.shipping.cost.store');

        Route::get('/check/shipping/cost/{type}/{weight}/{unit}', 'Backend\ProductsController@checkShippingCost')->name('admin.product.check.shipping.cost');

        // restock request
        Route::get('/restock/request', 'Backend\ProductsController@restockRequest')->name('admin.product.restock.request');
        Route::get('/get/restock/request/list', 'Backend\ProductsController@getRestockRequestList')->name('admin.product.restock.request.list');
        Route::get('/restock/request/change/status/{id}', 'Backend\ProductsController@restockRequestChangeStatus');
        Route::get('/restock/request/send/pushnotification/{id}', 'Backend\ProductsController@restockRequestPushnotification');
        Route::get('/restock/request/send/sms/{id}', 'Backend\ProductsController@restockRequestSms');
    });


    Route::group(['prefix' => '/import'], function () {
        // product import
        Route::get('/product/csv', 'Backend\ImportController@productCsv')->name('admin.import.product.csv');
        Route::post('/product/csv/store', 'Backend\ImportController@storeProductCsv')->name('admin.import.product.store.csv');
        Route::get('/product/update', 'Backend\ImportController@productUpdateCsv')->name('admin.import.product.update.csv');
        Route::post('/product/update/csv/download', 'Backend\ImportController@productUpdateCsvDownload')->name('admin.import.product.update.csv.download');
        Route::post('/product/update/csv/store', 'Backend\ImportController@storeProductUpdateCsv')->name('admin.import.product.update.csv.store');

        Route::get('/product/image/csv', 'Backend\ImportController@productImageCsv')->name('admin.import.product.image.csv');
        Route::post('/product/image/csv/store', 'Backend\ImportController@storeProductImageCsv')->name('admin.import.product.image.store.csv');

        // category import
        Route::get('/category/csv', 'Backend\ImportController@categoryCsv')->name('admin.import.category.csv');
        Route::post('/category/csv/store', 'Backend\ImportController@storeCategoryCsv')->name('admin.import.category.store.csv');

        // brand import
        Route::get('/brand/csv', 'Backend\ImportController@brandCsv')->name('admin.import.brand.csv');
        Route::post('/brand/csv/store', 'Backend\ImportController@storeBrandCsv')->name('admin.import.brand.store.csv');

        // seller import
        Route::get('/seller/csv', 'Backend\ImportController@sellerCsv')->name('admin.import.seller.csv');
        Route::post('/seller/csv/store', 'Backend\ImportController@storeSellerCsv')->name('admin.import.seller.store.csv');

        // customer import
        Route::get('/customer/csv', 'Backend\ImportController@customerCsv')->name('admin.import.customer.csv');
        Route::post('/customer/csv/store', 'Backend\ImportController@storeCustomerCsv')->name('admin.import.customer.store.csv');
    });

    Route::group(['prefix' => '/pos'], function () {
        Route::get('/', 'Backend\PosController@index')->name('admin.pos');
        Route::post('/customer/create', 'Backend\PosController@customerCreat')->name('admin.pos.customer.create');
        Route::post('/customer/shipping/address', 'Backend\PosController@customerShippingAddress');
        Route::post('/customer/shipping/address/add', 'Backend\PosController@customerShippingAddressAdd');
        Route::post('/customer/shipping/option', 'Backend\PosController@customerShippingOption');

        Route::get('/serach/product', 'Backend\PosController@searchProduct');

        Route::get('/get/cart/{id}', 'Backend\PosController@getCart');
        Route::post('/get/shipping/cost', 'Backend\PosController@getShippingCost');

        Route::post('/simple/add-to-cart', 'Backend\PosController@addToCart');
        Route::post('/digital/add-to-cart', 'Backend\PosController@digitalAddToCart');

        Route::get('/variable/product/{id}', 'Backend\PosController@getVariableProduct');
        Route::post('/variable/add-to-cart', 'Backend\PosController@variableAddToCart');

        Route::post('/update/cart', 'Backend\PosController@updateCart');
        Route::get('/remove/cart/{id}', 'Backend\PosController@removeCart');

        Route::post('/check/coupon/code', 'Backend\PosController@checkCouponCode');

        Route::post('/order', 'Backend\PosController@order_function');
    });

    //Attribute Routes for admin
    Route::group(['prefix' => '/attribute-list'], function () {
        Route::get('/', 'Backend\AttributesController@index')->name('admin.attribute-list');
        Route::get('/create', 'Backend\AttributesController@create')->name('admin.attribute.create');
        Route::get('/edit/{id}', 'Backend\AttributesController@edit')->name('admin.attribute.edit');
        Route::post('/store', 'Backend\AttributesController@store')->name('admin.attribute.store');
        Route::post('/update/{id}', 'Backend\AttributesController@update')->name('admin.attribute.update');
        Route::get('/delete/{id}', 'Backend\AttributesController@delete')->name('admin.attribute.delete');
    });

    //Attribute Routes for admin
    Route::group(['prefix' => '/attribute-set'], function () {
        Route::get('/', 'Backend\AttributesetsController@index')->name('admin.attribute-set');
        Route::get('/create', 'Backend\AttributesetsController@create')->name('admin.attribute-set.create');
        Route::get('/edit/{id}', 'Backend\AttributesetsController@edit')->name('admin.attribute-set.edit');
        Route::post('/store', 'Backend\AttributesetsController@store')->name('admin.attribute-set.store');
        Route::post('/update/{id}', 'Backend\AttributesetsController@update')->name('admin.attribute-set.update');
        Route::get('/delete/{id}', 'Backend\AttributesetsController@delete')->name('admin.attribute-set.delete');
    });



    //Categories Routes for admin
    Route::group(['prefix' => '/categories'], function () {
        Route::get('/', 'Backend\CategoriesController@index')->name('admin.category');
        Route::get('/create', 'Backend\CategoriesController@create')->name('admin.category.create');
        Route::get('/edit/{id}', 'Backend\CategoriesController@edit')->name('admin.category.edit');
        Route::post('/store', 'Backend\CategoriesController@store')->name('admin.category.store');
        Route::post('/update/{id}', 'Backend\CategoriesController@update')->name('admin.category.update');
        Route::get('/delete/{id}', 'Backend\CategoriesController@delete')->name('admin.category.delete');
        Route::post('save-nested-categories', 'Backend\CategoriesController@saveNestedCategories')->name('save.nested.categories');

        Route::post('save-reorder-categories', 'Backend\CategoriesController@saveReorderCategories')->name('save.reorder.categories');
    });

    //Brands Routes for admin
    Route::group(['prefix' => '/brands'], function () {
        Route::get('/', 'Backend\BrandsController@index')->name('admin.brand');
        Route::get('/get-brand-list', 'Backend\BrandsController@getBrandList')->name('admin.get.brand.list');
        Route::get('/create', 'Backend\BrandsController@create')->name('admin.brand.create');
        Route::get('/edit/{id}', 'Backend\BrandsController@edit')->name('admin.brand.edit');
        Route::post('/store', 'Backend\BrandsController@store')->name('admin.brand.store');
        Route::post('/update/{id}', 'Backend\BrandsController@update')->name('admin.brand.update');
        Route::get('/delete/{id}', 'Backend\BrandsController@delete')->name('admin.brand.delete');
    });


    //Brands Routes for admin
    Route::group(['prefix' => '/reviews'], function () {
        Route::get('/', 'Backend\ReviewsController@index')->name('admin.review');
        Route::get('/get-review-list', 'Backend\ReviewsController@getReviewList')->name('admin.get.review.list');
        Route::get('/get-single-review-list/{id}', 'Backend\ReviewsController@getSingleReviewList')->name('admin.get.single.review.list');
        Route::get('/edit/{id}/{approved}', 'Backend\ReviewsController@edit')->name('admin.review.edit');
        Route::get('/delete/{id}', 'Backend\ReviewsController@delete')->name('admin.review.delete');
        Route::post('/replay', 'Backend\ReviewsController@replay')->name('admin.review.replay');
    });


    //Options Routes for admin
    Route::group(['prefix' => '/options'], function () {
        // Route::get('/', 'Backend\OptionsController@index')->name('admin.option');
        // Route::get('/create', 'Backend\OptionsController@create')->name('admin.option.create');
        // Route::get('/edit/{id}', 'Backend\OptionsController@edit')->name('admin.option.edit');
        // Route::post('/store', 'Backend\OptionsController@store')->name('admin.option.store');
        // Route::post('/update/{id}', 'Backend\OptionsController@update')->name('admin.option.update');
        Route::post('/get', 'Backend\OptionsController@ajax')->name('admin.option.ajax');
        // Route::get('/delete/{id}', 'Backend\OptionsController@delete')->name('admin.option.delete');
    });


    //Currency
    Route::group(['prefix' => '/currencies'], function () {
        Route::get('/', 'Backend\SettingsController@currency')->name('admin.currency');
        Route::post('/create', 'Backend\SettingsController@currency_store')->name('admin.currency.store');
        Route::get('/delete/{id}', 'Backend\SettingsController@currency_delete')->name('admin.currency.delete');
    });

    //Languages
    Route::group(['prefix' => '/languages'], function () {
        Route::get('/', 'Backend\SettingsController@language')->name('admin.language');
        Route::post('/create', 'Backend\SettingsController@language_store')->name('admin.language.store');
        Route::get('/delete/{id}', 'Backend\SettingsController@language_delete')->name('admin.language.delete');
    });

    //Blogs Routes for admin
    Route::group(['prefix' => '/blogs'], function () {
        Route::get('/', 'Backend\BlogController@index')->name('admin.blog');
        Route::get('/get-blog-list', 'Backend\BlogController@getBlogList')->name('admin.get.blog.list');

        Route::get('/create', 'Backend\BlogController@create')->name('admin.blog.create');
        Route::get('/edit/{id}', 'Backend\BlogController@edit')->name('admin.blog.edit');
        Route::post('/store', 'Backend\BlogController@store')->name('admin.blog.store');
        Route::post('/update/{id}', 'Backend\BlogController@update')->name('admin.blog.update');
        Route::get('/delete/{id}', 'Backend\BlogController@delete')->name('admin.blog.delete');

        Route::post('/bulk/action', 'Backend\BlogController@action')->name('admin.blog.bulk.action');
        Route::get('/view/{id}', 'Backend\BlogController@view')->name('admin.blog.view');
    });

    //Blog Category Routes for admin
    Route::group(['prefix' => '/blog-category'], function () {
        Route::get('/', 'Backend\BlogcategoryController@index')->name('admin.blog.category');
        Route::get('/blog-category-list', 'Backend\BlogcategoryController@getBlogCategoryList')->name('admin.blog.category.list');
        Route::get('/create', 'Backend\BlogcategoryController@create')->name('admin.blog.category.create');
        Route::get('/edit/{id}', 'Backend\BlogcategoryController@edit')->name('admin.blog.category.edit');
        Route::post('/store', 'Backend\BlogcategoryController@store')->name('admin.blog.category.store');
        Route::post('/update/{id}', 'Backend\BlogcategoryController@update')->name('admin.blog.category.update');
        Route::get('/delete/{id}', 'Backend\BlogcategoryController@delete')->name('admin.blog.category.delete');
    });

    //Pages Routes for admin
    Route::group(['prefix' => '/pages'], function () {
        Route::get('/', 'Backend\PagesController@index')->name('admin.page');
        Route::get('/preview/{id}', 'Backend\PagesController@preview')->name('admin.page.view');
        Route::get('/create', 'Backend\PagesController@create')->name('admin.page.create');
        Route::get('/edit/{id}', 'Backend\PagesController@edit')->name('admin.page.edit');
        Route::post('/store', 'Backend\PagesController@store')->name('admin.page.store');
        Route::post('/update/{id}', 'Backend\PagesController@update')->name('admin.page.update');
        Route::get('/delete/{id}', 'Backend\PagesController@delete')->name('admin.page.delete');
    });

    Route::get('/media-gallery', 'Backend\PagesController@mediaGallery')->name('admin.media.gallery');



    //Admin Settings
    Route::get('/settings', 'Backend\SettingsController@settings')->name('admin.settings');
    Route::post('/settings/update', 'Backend\SettingsController@settingsSave')->name('admin.update.settings');

    Route::get('/change-password', 'Backend\SettingsController@changePassword')->name('admin.change.password');
    Route::post('/update-password', 'Backend\SettingsController@updatePassword')->name('admin.update.password');

    // ADMIN  COUPONS
    Route::get('/coupons', 'Backend\SettingsController@coupons')->name('admin.coupons');
    Route::get('/get-coupons', 'Backend\SettingsController@getCoupons')->name('admin.get.coupons');
    Route::post('/coupons/store', 'Backend\SettingsController@coupons_store')->name('admin.coupons.store');
    Route::get('/coupons/edit/{id}', 'Backend\SettingsController@coupons_edit')->name('admin.coupons.edit');
    Route::post('/coupons/update', 'Backend\SettingsController@coupons_update')->name('admin.coupons.update');
    Route::get('/coupons/delete/{id}', 'Backend\SettingsController@coupons_delete')->name('admin.coupons.delete');

    //Voucher Routes for admin
    Route::group(['prefix' => '/voucher'], function () {
        Route::get('/', 'Backend\VoucherController@index')->name('admin.voucher');
        Route::get('/get-voucher', 'Backend\VoucherController@getVoucher')->name('admin.get.voucher');

        Route::get('/edit/{id}', 'Backend\VoucherController@edit')->name('admin.voucher.edit');
        Route::post('/store', 'Backend\VoucherController@store')->name('admin.voucher.store');
        Route::post('/update', 'Backend\VoucherController@update')->name('admin.voucher.update');
        Route::get('/delete/{id}', 'Backend\VoucherController@delete')->name('admin.voucher.delete');

        Route::get('/category', 'Backend\VoucherController@categoryindex')->name('admin.voucher.category');
        Route::post('/category/store', 'Backend\VoucherController@categorystore')->name('admin.voucher.category.store');
        Route::post('/category/update', 'Backend\VoucherController@categoryupdate')->name('admin.voucher.category.update');
        Route::get('/category/delete/{id}', 'Backend\VoucherController@categorydelete')->name('admin.voucher.category.delete');
    });


    //Orders Routes for admin
    Route::group(['prefix' => '/orders'], function () {
        Route::get('/', 'Backend\OrdersController@index')->name('admin.order');
        Route::get('/get-order-list/{start_date}/{end_date}', 'Backend\OrdersController@getOrderList')->name('admin.order.sale.list');
       
        Route::get('/view/{id}', 'Backend\OrdersController@show')->name('admin.order.show');
        Route::get('/delete/{id}', 'Backend\OrdersController@destroy')->name('admin.order.delete');
        
        Route::get('/action/fetch_data', 'Backend\OrdersController@fetch_data');
        Route::post('/update/order', 'Backend\OrdersController@updateOrder')->name('admin.order.update.order');
        Route::get('/product/details/{id}/{customer_id}/{address_id}', 'Backend\OrdersController@productDetails');

        Route::post('order-auto-renewal-create', 'Backend\OrderAutoRenewalController@orderAutoRenewal')->name('admin.orderautorenewal.create');
        Route::get('order-auto-renewal-sync', 'Backend\OrderAutoRenewalController@orderAutoRenewalSync')->name('admin.orderautorenewal');


        //Promotional
        Route::get('/promotional', 'Backend\OrdersController@promotional')->name('admin.order.promotional');
        Route::get('/get-promotional-order-list/{start_date}/{end_date}', 'Backend\OrdersController@getOrderPromotionalList')->name('admin.order.sale.list.promotional');
        Route::post('/promotional/update', 'Backend\OrdersController@update')->name('admin.order.update.promotional');
        Route::get('/promotional/view/{id}', 'Backend\OrdersController@show')->name('admin.order.show.promotional');
        Route::get('/promotional/delete/{id}', 'Backend\OrdersController@destroy_promotional')->name('admin.order.delete.promotional');
        Route::get('/promotional/action/fetch_data', 'Backend\OrdersController@fetch_data');

        // order edit
        Route::get('/edit/{id}', 'Backend\OrdersController@editOrder')->name('admin.order.edit');
        Route::post('/update', 'Backend\OrdersController@update')->name('admin.order.update');

        Route::get('/get/shipping/cost/{address_type}/{address}/{subtotal}', 'Backend\OrdersController@getOrderShippingCost');


    });




    //Orders Routes for admin
    Route::group(['prefix' => '/reports'], function () {
        Route::get('/', 'Backend\ReportsController@sale_report')->name('admin.report');
        Route::get('/get-sale-report', 'Backend\ReportsController@getSalesReport')->name('admin.get.report');
        Route::get('/filter/sale/report', 'Backend\ReportsController@filter_sale_report');

        Route::any('/product/sale', 'Backend\ReportsController@product_sale_report')->name('admin.product.sale.report');
        Route::get('/get-product-sale/{id}', 'Backend\ReportsController@fetch_product_sale_data')->name('admin.get.product.sale.data');

        Route::any('/product/refund/request', 'Backend\OrdersController@product_refund_report')->name('admin.product.refund.report');



        //category wise start
        Route::get('/category-wise-product-sale', 'Backend\ReportsController@categoryWiseProduct')->name('admin.catagory.wise.product.sale');

        Route::get('/get-category-wise-product-sale', 'Backend\ReportsController@categoryWiseProductSale')->name('admin.catagory.wise.sale');
        //category wise end

        //seller product wise start
        Route::get('/seller-product-wise-sale', 'Backend\ReportsController@sellerProductWise')->name('admin.seller.product.wise');
        Route::get('/get-seller-product-wise-sale', 'Backend\ReportsController@getSellerProductWise')->name('admin.get.seller.product.wise');
        //seller product wise end

        //Single product wise start
        Route::get('/single-product-wise-sale', 'Backend\ReportsController@singleProductWise')->name('admin.single.product.wise');
        Route::post('/get-single-product-wise-sale', 'Backend\ReportsController@getSingleProductWise')->name('admin.get.single.product.wise');
        //Single product wise end

        //Sale Confirm Status Wise Start
        Route::get('/sale-confirm-status-wise', 'Backend\ReportsController@saleConfirmStatusWise')->name('admin.sale.confirm.status.wise');
        Route::get('/get-sale-confirm-status-wise', 'Backend\ReportsController@getSaleConfirmStatusWise')->name('admin.get.sale.confirm.status.wise');
        Route::get('/get-sale-status-wise', 'Backend\ReportsController@getSaleStatusWise');
        //Sale Confirm Status Wise End

        //Product Wishlist Start
        Route::get('/product-wishlist', 'Backend\ReportsController@productWishlist')->name('admin.product.wishlist');
        Route::get('/get-product-wishlist', 'Backend\ReportsController@getProductWishlist')->name('admin.get.product.wishlist');
        //Product Wishlist End

        //Top Sold Product Report Start
        Route::get('/top-sold-product-report', 'Backend\ReportsController@topSoldProductReport')->name('admin.top.sold.product.report');
        Route::get('/get-top-sold-product-report', 'Backend\ReportsController@getTopSoldProductReport')->name('admin.get.top.sold.product.report');
        //Top Sold Product Report End

        //low Stock Item
        Route::get('/low-stock-item', 'Backend\ReportsController@lowStockItem')->name('admin.low.stock.item.report');
        Route::get('/get-low-stock-item', 'Backend\ReportsController@getLowStockItem')->name('admin.get.low.stock.item.report');

        //Vat
        Route::get('/vat', 'Backend\ReportsController@vat')->name('admin.vat.report');
        Route::get('/get-vat', 'Backend\ReportsController@getVat')->name('admin.get.vat.report');
        Route::get('/get-vat/initialdata', 'Backend\ReportsController@vatInitialData')->name('admin.vat.initialData');


        //send message start
        Route::get('/send-message/{id}', 'Backend\ReportsController@sendMessage')->name('admin.send.message');
        Route::post('/message/send', 'Backend\ReportsController@smsSend')->name('admin.report.send.message');
        //send message end

        //send push notification start
        Route::get('/send-push-notification/{id}', 'Backend\ReportsController@sendNotification')->name('admin.send.notification');
        Route::post('/push/notification/send', 'Backend\ReportsController@pushNotificationSend')->name('admin.report.push.notification.send');
        //send push notification end


        Route::any('/vendor/withdrawal/request', 'Backend\OrdersController@vendor_withdrawal_request')->name('admin.vendor.withdrawal.request');
        Route::any('/get/vendor/withdrawal/request', 'Backend\OrdersController@get_vendor_withdrawal_request')->name('admin.get.vendor.withdrawal.request');
        Route::post('/vendor/withdrawal/request/send', 'Backend\OrdersController@vendor_withdrawal_request_send')->name('admin.vendor.withdrawal.request.send');
        Route::any('/vendor/withdrawal/request/approved', 'Backend\OrdersController@vendor_withdrawal_request_approved')->name('admin.vendor.withdrawal.request.approved');
        Route::get('/vendor/withdrawal/request/delete/{id}', 'Backend\OrdersController@vendor_withdrawal_request_destroy')->name('admin.withdrawal_request.delete');


        Route::get('/seller-account-history', 'Backend\OrdersController@sellerAccountHistory')->name('admin.seller.account.history');
        Route::get('/get-seller-account-history/{id}', 'Backend\OrdersController@getSellerAccountHistory')->name('admin.get.seller.account.history');
        Route::get('/seller/account/history/initialdata', 'Backend\OrdersController@accountHistoryInitialData')->name('admin.seller.account.history.initialData');

        Route::get('/coupon/uses/reports', 'Backend\ReportsController@couponUsesReport')->name('admin.report.coupon.uses.report');
        Route::get('/coupon/uses/reports/list', 'Backend\ReportsController@couponUsesReportList');

        // corporate sale reports
        Route::get('/corporate/sales', 'Backend\ReportsController@corporateSalesReport')->name('admin.report.corporate.sales');
        Route::get('/corporate/sales/reports/list', 'Backend\ReportsController@corporateSalesReportList');
        Route::get('/corporate/sales/reports/initialdata', 'Backend\ReportsController@corporateSalesReportInitialData');
    });


    //Marketing Routes for admin
    Route::group(['prefix' => '/marketing'], function () {
        Route::get('/send-bulk-message', 'Backend\MarketingController@bulk_message')->name('admin.marketing.bulk.message');
        Route::post('/send/bulk/message', 'Backend\MarketingController@sendSms')->name('admin.marketing.send.bulk.message');
        Route::get('/send-push-notification', 'Backend\MarketingController@push_notification')->name('admin.marketing.push.notification');
        Route::post('/send/push/notification', 'Backend\MarketingController@sendPushNotification')->name('admin.marketing.send.push.notification');

        Route::get('/user/search/keywords', 'Backend\MarketingController@userSearchKeyword')->name('admin.marketing.user.search.keyword');

        Route::get('/user/search/keywords/data', 'Backend\MarketingController@userSearchKeywordData')->name('admin.marketing.user.search.keyword.data');

        Route::get('/subscribers', 'Backend\MarketingController@subscribers')->name('admin.marketing.subscribers');
        Route::get('/subscriber/list', 'Backend\MarketingController@subscriberList')->name('admin.marketing.subscribers.list');
    });


    //Users Routes for admin
    Route::group(['prefix' => '/users'], function () {
        Route::get('/', 'Backend\AccountsController@user')->name('admin.user');
        Route::get('/get-customer-list', 'Backend\AccountsController@getCustomerList')->name('admin.get.customer.list');
        Route::get('/view/{id}', 'Backend\AccountsController@user_view')->name('admin.user.view');
        Route::get('/edit/{id}', 'Backend\AccountsController@user_edit')->name('admin.user.edit');
        Route::get('/delete/{id}', 'Backend\AccountsController@user_destroy')->name('admin.user.delete');
        Route::get('/create', 'Backend\AccountsController@user_create')->name('admin.user.create');
        Route::post('/store', 'Backend\AccountsController@user_store')->name('admin.user.store');
        Route::post('/update/{id}', 'Backend\AccountsController@user_update')->name('admin.user.update');
        Route::post('/address/update', 'Backend\AccountsController@user_address_update')->name('admin.user.address.update');

        Route::get('/accounts/{id}', 'Backend\AccountsController@user_accounts')->name('admin.user.accounts');
        Route::get('/accounts/orders/{id}', 'Backend\AccountsController@user_accountsOrders')->name('admin.user.accounts.orders');

        Route::post('/bulk/action', 'Backend\AccountsController@user_action')->name('admin.user.bulk.action');
    });


    //Administrator Routes for admin
    Route::group(['prefix' => '/administrators'], function () {
        Route::get('/', 'Backend\AccountsController@administrator')->name('admin.administrator');
        Route::get('/edit/{id}', 'Backend\AccountsController@administrator_edit')->name('admin.administrator.edit');
        Route::get('/delete/{id}', 'Backend\AccountsController@administrator_destroy')->name('admin.administrator.delete');
        Route::get('/create', 'Backend\AccountsController@administrator_create')->name('admin.administrator.create');
        Route::post('/store', 'Backend\AccountsController@administrator_store')->name('admin.administrator.store');
        Route::post('/update/{id}', 'Backend\AccountsController@administrator_update')->name('admin.administrator.update');
    });

    //Vendor Routes for admin
    Route::group(['prefix' => '/seller'], function () {
        Route::get('/', 'Backend\VendorsController@vendor')->name('admin.vendor');
        Route::get('/get-seller-list', 'Backend\VendorsController@getSellerList')->name('admin.get.seller.list');
        Route::get('/view/{id}', 'Backend\VendorsController@view')->name('admin.vendor.view');
        Route::get('/edit/{id}', 'Backend\VendorsController@edit')->name('admin.vendor.edit');
        Route::get('/delete/{id}', 'Backend\VendorsController@destroy')->name('admin.vendor.delete');
        Route::get('/create', 'Backend\VendorsController@create')->name('admin.vendor.create');
        Route::post('/store', 'Backend\VendorsController@store')->name('admin.vendor.store');
        Route::post('/update/{id}', 'Backend\VendorsController@update')->name('admin.vendor.update');
        Route::get('/edit-profile', 'Backend\VendorsController@editProfile')->name('admin.vendor.edit.profile');
        Route::post('/get-district', 'Backend\VendorsController@get_district');
        Route::post('/get-upazila', 'Backend\VendorsController@get_upazila');
        Route::post('/get-union', 'Backend\VendorsController@get_union');

        Route::get('/accounts/{id}', 'Backend\VendorsController@accounts')->name('admin.vendor.accounts');
        Route::get('/accounts/orders/{id}', 'Backend\VendorsController@accountsOrders')->name('admin.vendor.accounts.orders');
        Route::get('/accounts/products/{id}', 'Backend\VendorsController@accountProducts')->name('admin.vendor.accounts.products');

        Route::post('/bulk/action', 'Backend\VendorsController@action')->name('admin.seller.bulk.action');
    });


    //permissions Routes for admin
    Route::group(['prefix' => '/permissions'], function () {
        Route::get('/', 'Backend\AccountsController@permission')->name('admin.permission');
    });



    //Sliders Routes for admin
    Route::group(['prefix' => '/sliders'], function () {
        Route::get('/', 'Backend\SlidersController@index')->name('admin.slider');
        Route::get('/create', 'Backend\SlidersController@create')->name('admin.slider.create');
        Route::get('/edit/{id}', 'Backend\SlidersController@edit')->name('admin.slider.edit');
        Route::post('/store', 'Backend\SlidersController@store')->name('admin.slider.store');
        Route::post('/update/{id}', 'Backend\SlidersController@update')->name('admin.slider.update');
        Route::get('/delete/{id}', 'Backend\SlidersController@delete')->name('admin.slider.delete');
        Route::post('/re/order', 'Backend\SlidersController@reorder')->name('admin.slider.reorder');
    });


    //Frontend navbar Routes for admin
    Route::group(['prefix' => '/navbar'], function () {
        Route::get('/', 'Backend\NavbarsController@index')->name('admin.navbar');
        Route::get('/create', 'Backend\NavbarsController@create')->name('admin.navbar.create');
        Route::get('/edit/{id}', 'Backend\NavbarsController@edit')->name('admin.navbar.edit');
        Route::post('/store', 'Backend\NavbarsController@store')->name('admin.navbar.store');
        Route::post('/update/{id}', 'Backend\NavbarsController@update')->name('admin.navbar.update');
        Route::get('/delete/{id}', 'Backend\NavbarsController@delete')->name('admin.navbar.delete');
        Route::post('/re/order', 'Backend\NavbarsController@reorder')->name('admin.navbar.reorder');
    });



    //flash-deals Routes for admin
    Route::group(['prefix' => '/flash-deals'], function () {
        Route::get('/', 'Backend\FlashDealsController@index')->name('admin.flash_deal');
        Route::get('/create', 'Backend\FlashDealsController@create')->name('admin.flash_deal.create');
        Route::get('/edit/{id}', 'Backend\FlashDealsController@edit')->name('admin.flash_deal.edit');
        Route::post('/store', 'Backend\FlashDealsController@store')->name('admin.flash_deal.store');
        Route::post('/update/{id}', 'Backend\FlashDealsController@update')->name('admin.flash_deal.update');
        Route::get('/delete/{id}', 'Backend\FlashDealsController@delete')->name('admin.flash_deal.delete');
        Route::post('/re/order', 'Backend\FlashDealsController@reorder')->name('admin.flash_deal.reorder');

        Route::get('/get/products/{id}', 'Backend\FlashDealsController@getProducts')->name('admin.flash_deal.get.products');

        Route::get('/send/pushnotification/{id}', 'Backend\FlashDealsController@sendPushNotification')->name('admin.flash_deal.send.pushnotification');

        Route::get('/send/sms/{id}', 'Backend\FlashDealsController@sendSms')->name('admin.flash_deal.send.sms');
    });




    //Trash Routes for admin
    Route::group(['prefix' => '/trash'], function () {
        Route::get('/', 'Backend\TrashController@index')->name('admin.trash');
        Route::get('/get-trash-list/{start_date}/{end_date}', 'Backend\TrashController@getTrashList')->name('admin.trash.list');
        Route::get('/delete/{id}', 'Backend\TrashController@delete')->name('admin.trash.delete');
        Route::get('/undo/{id}', 'Backend\TrashController@undoDelete')->name('admin.trash.undo.delete');
        Route::post('/bulk-action', 'Backend\TrashController@bulkAction')->name('admin.trash.bulk.action');
    });


    //Pick points
    Route::group(['prefix' => '/pick-points'], function () {
        Route::get('/', 'Backend\PickpointsController@index')->name('admin.pick_points');
        Route::get('/create', 'Backend\PickpointsController@create')->name('admin.pick_points.create');
        Route::get('/edit/{id}', 'Backend\PickpointsController@edit')->name('admin.pick_points.edit');
        Route::post('/store', 'Backend\PickpointsController@store')->name('admin.pick_points.store');
        Route::post('/update/{id}', 'Backend\PickpointsController@update')->name('admin.pick_points.update');
        Route::get('/delete/{id}', 'Backend\PickpointsController@delete')->name('admin.pick_points.delete');
    });


    //Blogs Routes for admin
    Route::group(['prefix' => '/location'], function () {

        Route::group(['prefix' => '/division'], function () {
            Route::get('/', 'Backend\DivisionController@index')->name('admin.location.division');
            Route::get('/create', 'Backend\DivisionController@create')->name('admin.location.division.create');
            Route::get('/edit/{id}', 'Backend\DivisionController@edit')->name('admin.location.division.edit');
            Route::post('/store', 'Backend\DivisionController@store')->name('admin.location.division.store');
            Route::post('/update/{id}', 'Backend\DivisionController@update')->name('admin.location.division.update');
            Route::get('/delete/{id}', 'Backend\DivisionController@delete')->name('admin.location.division.delete');
            Route::get('/get-division', 'Backend\DivisionController@getDivision')->name('admin.location.get.division');
        });
    });


    //District Routes for admin
    Route::group(['prefix' => '/location'], function () {

        Route::group(['prefix' => '/district'], function () {
            Route::get('/', 'Backend\DistrictController@index')->name('admin.location.district');
            Route::get('/create', 'Backend\DistrictController@create')->name('admin.location.district.create');
            Route::get('/edit/{id}', 'Backend\DistrictController@edit')->name('admin.location.district.edit');
            Route::post('/store', 'Backend\DistrictController@store')->name('admin.location.district.store');
            Route::post('/update/{id}', 'Backend\DistrictController@update')->name('admin.location.district.update');
            Route::get('/delete/{id}', 'Backend\DistrictController@delete')->name('admin.location.district.delete');
            Route::get('/get-district', 'Backend\DistrictController@getDistrict')->name('admin.location.get.district');
        });
    });



    //Upazila Routes for admin
    Route::group(['prefix' => '/location'], function () {

        Route::group(['prefix' => '/upazila'], function () {
            Route::get('/', 'Backend\UpazilaController@index')->name('admin.location.upazila');
            Route::get('/create', 'Backend\UpazilaController@create')->name('admin.location.upazila.create');
            Route::get('/edit/{id}', 'Backend\UpazilaController@edit')->name('admin.location.upazila.edit');
            Route::post('/store', 'Backend\UpazilaController@store')->name('admin.location.upazila.store');
            Route::post('/update/{id}', 'Backend\UpazilaController@update')->name('admin.location.upazila.update');
            Route::get('/delete/{id}', 'Backend\UpazilaController@delete')->name('admin.location.upazila.delete');
            Route::get('/get-upazila', 'Backend\UpazilaController@getUpazila')->name('admin.location.get.upazila');
        });
    });



    //Union Routes for admin
    Route::group(['prefix' => '/location'], function () {

        Route::group(['prefix' => '/union'], function () {
            Route::get('/', 'Backend\UnionController@index')->name('admin.location.union');
            Route::get('/create', 'Backend\UnionController@create')->name('admin.location.union.create');
            Route::get('/edit/{id}', 'Backend\UnionController@edit')->name('admin.location.union.edit');
            Route::post('/store', 'Backend\UnionController@store')->name('admin.location.union.store');
            Route::post('/update/{id}', 'Backend\UnionController@update')->name('admin.location.union.update');
            Route::get('/delete/{id}', 'Backend\UnionController@delete')->name('admin.location.union.delete');
            Route::get('/get-union', 'Backend\UnionController@getUnion')->name('admin.location.get.union');
        });
    });



    //testimonials Routes for admin
    Route::group(['prefix' => '/testimonials'], function () {
        Route::get('/', 'Backend\TestimonialsController@index')->name('admin.testimonial');
        Route::get('/create', 'Backend\TestimonialsController@create')->name('admin.testimonial.create');
        Route::get('/edit/{id}', 'Backend\TestimonialsController@edit')->name('admin.testimonial.edit');
        Route::post('/store', 'Backend\TestimonialsController@store')->name('admin.testimonial.store');
        Route::post('/update/{id}', 'Backend\TestimonialsController@update')->name('admin.testimonial.update');
        Route::get('/delete/{id}', 'Backend\TestimonialsController@delete')->name('admin.testimonial.delete');
    });

    //Design settings for admin
    Route::group(['prefix' => '/designs'], function () {
        Route::get('/', 'Backend\SettingsController@index')->name('admin.designs');
        Route::post('/store', 'Backend\SettingsController@store')->name('admin.design.store');

        Route::get('/environment', 'Backend\SettingsController@environment')->name('admin.designs.environment');
        Route::post('/environment/store', 'Backend\SettingsController@environmentStore')->name('admin.designs.environment.store');
    });

    //Localization
    Route::group(['prefix' => '/localization'], function () {
        Route::get('/get-fields', 'Backend\LocalizationController@getFields')->name('admin.localization.get.fields');
    });

    //Search Dashboard
    // Route::group(['prefix' => '/search'], function () {
    //     Route::get('/dashboard', 'Backend\SettingsController@getFields')->name('admin.search.dashboard');
    // });



    //Tickets Routes for admin
    Route::group(['prefix' => '/ticket'], function () {
        Route::get('/', 'Backend\TicketsController@index')->name('admin.ticket');
        Route::get('/get-ticket-list', 'Backend\TicketsController@getTicketList')->name('admin.get.ticket.list');

        Route::get('/create', 'Backend\TicketsController@create')->name('admin.ticket.create');
        Route::get('/edit/{id}', 'Backend\TicketsController@edit')->name('admin.ticket.edit');
        Route::post('/store', 'Backend\TicketsController@store')->name('admin.ticket.store');
        Route::post('/update/{id}', 'Backend\TicketsController@update')->name('admin.ticket.update');
        Route::get('/delete/{id}', 'Backend\TicketsController@delete')->name('admin.ticket.delete');

        Route::post('/bulk/action', 'Backend\TicketsController@action')->name('admin.ticket.bulk.action');

        Route::get('/replay/{id}', 'Backend\TicketsController@replay')->name('admin.ticket.replay');

        Route::post('/replay/store', 'Backend\TicketsController@ticket_replay_store');

        Route::get('/view/replay/{id}', 'Backend\TicketsController@getAllReplay')->name('admin.ticket.view.replay');
    });


    //Activity Log
    Route::get('/activity-log', 'Backend\LogController@getLog')->name('activity.log');
    Route::get('/get-activity-log/{start_date}/{end_date}', 'Backend\LogController@getActivityLog')->name('get.activity.log');
    Route::get('/activity-log/view/{id}', 'Backend\LogController@view')->name('activity.log.view');
    Route::get('/delete/{id}', 'Backend\LogController@destroy')->name('activity.log.delete');
    Route::post('/activity-log/delete', 'Backend\LogController@destroySelected')->name('admin.activity.log.selected.delete');


    // Affialte route
    Route::group(['prefix' => '/affiliate'], function () {
        Route::get('/', 'Backend\AffiliateController@index')->name('admin.affiliate');
        Route::get('/list', 'Backend\AffiliateController@affiliateList')->name('admin.affiliate.list');
        Route::get('/affiliate/matured/{id}', 'Backend\AffiliateController@affiliateChangeStatus')->name('admin.affiliate.change.status');


        Route::get('/withdrawal-request', 'Backend\AffiliateController@affiliateWithdrawal')->name('admin.affiliate.withdrawal');
        Route::get('/withdrawal-request/approve/{id}', 'Backend\AffiliateController@withdrawalApprove')->name('admin.affiliate.withdrawal.approve');
        Route::get('/get-withdrawal-request', 'Backend\AffiliateController@getAffiliateWithdrawal')->name('admin.get.affiliate.withdrawal');
    });

    // Career route
    Route::group(['prefix' => '/career'], function () {
        Route::get('/list', 'Backend\CareerController@careerList')->name('admin.career.list');
        Route::get('/get-career-list', 'Backend\CareerController@getCareertList')->name('admin.get.career.list');

        Route::get('/create', 'Backend\CareerController@careerCreate')->name('admin.career.create');
        Route::post('/career/store', 'Backend\CareerController@careerStore')->name('admin.career.store');

        Route::get('/edit/{id}', 'Backend\CareerController@careerEdit')->name('admin.career.edit');
        Route::post('/career/update', 'Backend\CareerController@careerUpdate')->name('admin.career.update');

        Route::get('/view/{id}', 'Backend\CareerController@careerView')->name('admin.career.view');
        Route::get('/delete/{id}', 'Backend\CareerController@careerDelete')->name('admin.career.delete');


        Route::get('/request', 'Backend\CareerController@careerRequest')->name('admin.career.request');
        Route::get('/get-career-request', 'Backend\CareerController@getCareertRequest')->name('admin.get.career.request');
        Route::get('/request/view/{id}', 'Backend\CareerController@careerRequestView')->name('admin.career.request.view');
        Route::get('/request/delete/{id}', 'Backend\CareerController@careerRequestDelete')->name('admin.career.request.delete');
    });


    // Corporate route
    Route::group(['prefix' => '/corporate'], function () {

        //corporate request routes
        Route::get('/request/list', 'Backend\CorporateController@corporateRequestIndex')->name('admin.corporate.request.index');
        Route::get('/get-corporate-request-list', 'Backend\CorporateController@getCorporateRequestList')->name('admin.corporate.request.list');
        Route::get('/request/view/{id}', 'Backend\CorporateController@corporateRequestView')->name('admin.corporate.request.view');

        Route::post('/request/update', 'Backend\CorporateController@corporateRequestUpdate')->name('admin.corporate.request.update');
        Route::get('/request/delete/{id}', 'Backend\CorporateController@corporateRequestDelete')->name('admin.corporate.request.delete');


        //corporate deal routes
        Route::get('/deal/list', 'Backend\CorporateController@corporateDealIndex')->name('admin.corporate.deal.index');
        Route::get('/get-corporate-deal-list', 'Backend\CorporateController@getCorporateDealList')->name('admin.corporate.deal.list');
        Route::get('/deal/view/{id}', 'Backend\CorporateController@corporateDealView')->name('admin.corporate.deal.view');
        Route::get('/deal/delete/{id}', 'Backend\CorporateController@corporateDealDelete')->name('admin.corporate.deal.delete');
    });
});