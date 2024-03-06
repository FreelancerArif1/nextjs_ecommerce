<?php
Route::prefix('v1/seller')->group(function () {
	
	// Login & Registration 
	Route::post('login', [VendorApiController::class, 'login']);
	Route::post('seller-register', [VendorApiController::class, 'sellerRegister']);
	Route::get('seller-profile', [VendorApiController::class, 'sellerProfile']);

	Route::post('seller-update-profile', [VendorApiController::class, 'sellerProfileUpdate']);
	Route::post('seller-change-password', [VendorApiController::class, 'sellerChangePassword']);

	// Balance
	Route::get('seller/balance', [VendorApiController::class, 'sellerBalance']);

	// Dashboard
	Route::get('dashboard', [VendorApiController::class, 'seller_dashboard']);
	Route::get('customer/list', [VendorApiController::class, 'seller_customerlist']);

	// Product 
	Route::get('product/list', [VendorApiController::class, 'product_list']);
	Route::get('product/create', [VendorApiController::class, 'product_create']);
	Route::post('product/store', [VendorApiController::class, 'product_store']);
	Route::get('product/edit/{id}', [VendorApiController::class, 'product_edit']);
	Route::post('product/update/{id}', [VendorApiController::class, 'product_update']);
	Route::get('product/delete/{id}', [VendorApiController::class, 'product_delete']);

	//Attribute SET
	Route::get('product/attribute-set', [VendorApiController::class, 'attribute_set']);

	// Orders
	Route::get('order/list', [VendorApiController::class, 'order_list']);
	Route::get('order/details/{id}', [VendorApiController::class, 'order_details']);
	Route::post('order/update', [VendorApiController::class, 'order_update']);

	// Notification
	Route::post('view-notification/{id}', [VendorApiController::class, 'viewNotification']);

	//Brand 
	Route::get('brands', [VendorApiController::class, 'brands']);

	// Review
	Route::get('reviews', [VendorApiController::class, 'reviews']);

	// Accounting
	Route::get('payment/method', [VendorApiController::class, 'sellerPaymentMethod']);
	Route::get('product/refund/request', [VendorApiController::class, 'productRefundRequest']);
	Route::get('withdrawal/request', [VendorApiController::class, 'withdrawalRequest']);
	Route::post('withdrawal/request/send', [VendorApiController::class, 'vendor_withdrawal_request_send']);

	// Reports 
	Route::get('sales-reports', [VendorApiController::class, 'sallerSalesReport']);
	Route::get('balance-history', [VendorApiController::class, 'sallerBalanceHistory']);
	Route::get('get-single-product-wise-sale/{product_id}', [VendorApiController::class, 'getSingleProductWise']);
	Route::post('sale-status-wise', [VendorApiController::class, 'getStatusWiseSale']);
	Route::get('low-stock-products', [VendorApiController::class, 'lowStockProducts']);

});