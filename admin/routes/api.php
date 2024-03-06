<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\VendorApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('v1')->group(function () {

Route::get('slide-show', [ApiController::class, 'sliders']);
Route::get('brand-list', [ApiController::class, 'brands'])->middleware('localization');
Route::get('search-brands', [ApiController::class, 'searchBrands']);
Route::get('featured-vendors', [ApiController::class, 'featured_seller']);
Route::get('category-list', [ApiController::class, 'categories'])->middleware('localization');
Route::get('information', [ApiController::class, 'site_info'])->middleware('localization');
Route::get('navigation-bars', [ApiController::class, 'getNavbars'])->middleware('localization');
Route::get('search-category', [ApiController::class, 'searchcategories']);
Route::get('category-id/{id}', [ApiController::class, 'singleCategory']);
Route::get('category-slug/{slug}', [ApiController::class, 'singleCategorybySlug']);
   
//Products
Route::get('deals', [ApiController::class, 'getFlashDeals']); 
Route::get('deal/{slug}', [ApiController::class, 'getFlashDeal']);
Route::get('category-deal/{category_id}/{slug}', [ApiController::class, 'CategoryWisegetFlashDeal']);
Route::get('category-products', [ApiController::class, 'getProductsByCategoryId']);
Route::get('brand-products', [ApiController::class, 'getProductsByBrandId']);
Route::get('seller-products', [ApiController::class, 'getProductsBySellerId']);
Route::get('seller-info', [ApiController::class, 'sellerInformation']);
Route::get('product/{slug}', [ApiController::class, 'getProductBySlug'])->middleware('localization');
Route::any('search', [ApiController::class, 'getSearchProduct']);
Route::get('sale', [ApiController::class, 'getOnsaleProduct'])->middleware('localization');
Route::get('featured', [ApiController::class, 'getFeaturedProduct'])->middleware('localization');
Route::get('best-sellers', [ApiController::class, 'getbestSellingProduct']);
Route::get('most-viewed', [ApiController::class, 'getmostViewedProduct'])->middleware('localization');
Route::get('new-arrivals', [ApiController::class, 'getnewArrivalProduct']);
Route::get('flash-sale', [ApiController::class, 'getflashSaleProduct'])->middleware('localization'); 
	
Route::get('deal/{slug}', [ApiController::class, 'getFlashDeal']);
Route::get('category-deal/{category_id}/{slug}', [ApiController::class, 'CategoryWisegetFlashDeal']);
Route::get('category-products', [ApiController::class, 'getProductsByCategoryId']);
Route::get('brand-products', [ApiController::class, 'getProductsByBrandId']);
Route::get('seller-products', [ApiController::class, 'getProductsBySellerId']);
Route::get('seller-info', [ApiController::class, 'sellerInformation']);
Route::get('product/{slug}', [ApiController::class, 'getProductBySlug'])->middleware('localization');
Route::any('search', [ApiController::class, 'getSearchProduct']);
Route::get('sale', [ApiController::class, 'getOnsaleProduct'])->middleware('localization');
Route::get('featured', [ApiController::class, 'getFeaturedProduct'])->middleware('localization');
Route::get('best-sellers', [ApiController::class, 'getbestSellingProduct']);
Route::get('most-viewed', [ApiController::class, 'getmostViewedProduct'])->middleware('localization');
Route::get('new-arrivals', [ApiController::class, 'getnewArrivalProduct']);
Route::get('flash-sale', [ApiController::class, 'getflashSaleProduct'])->middleware('localization');

Route::post('get-cart', [ApiController::class, 'getCart']);
Route::get('check-user', [ApiController::class, 'checkUuser']);
Route::post('get-sub-category', [ApiController::class, 'SubCategoryById']);
Route::get('get-all-sellers', [ApiController::class, 'getAllSellers']);
Route::post('get-all-sellers-post', [ApiController::class, 'getAllSellersPost']);

//User
Route::post('login', [ApiController::class, 'login']);
Route::post('user-register', [ApiController::class, 'userRegister']);
Route::get('get-user-details', [ApiController::class, 'get_user_details']);
Route::post('update-user-details', [ApiController::class, 'update_user_details']);
Route::get('get-address', [ApiController::class, 'getAddress']);
Route::post('vendor-register', [ApiController::class, 'vendorRegister']);
Route::post('corporate-user-register', [ApiController::class, 'corporateUserRegister']);

Route::get('get-my-affiliate-details', [ApiController::class, 'getMyAffiliateDetails']);
Route::post('affiliate-withdrawal-request', [ApiController::class, 'affiliateWithdrawalRequest']);


//Compare
Route::get('get-compare-list', [ApiController::class, 'initCompare']);
Route::post('add-to-compare', [ApiController::class, 'addToCompare']);
Route::post('check-compare', [ApiController::class, 'checkCompare']);
Route::post('remove-compare', [ApiController::class, 'removeCompare']);

//Wishlist
Route::get('/initwishlist', [ApiController::class, 'initWishlist']);
Route::post('add-to-wishlist', [ApiController::class, 'addToWishlist']);
Route::get('remove-wishlist/{wishlist_id}', [ApiController::class, 'removeWishlist']);


//Review
Route::post('add-review', [ApiController::class, 'review']);
Route::get('get-all-rating/{productId}', [ApiController::class, 'AllRating']);
Route::get('get-review-by-product-id/{productId}', [ApiController::class, 'reveiwByProductId']);
Route::post('check-reviewed', [ApiController::class, 'checkReviewed']);
//Vendor rating
Route::get('get-seller-rating/{vendor_id}', [ApiController::class, 'getSellerRating']);
Route::get('get-seller-product-comments/{slug}', [ApiController::class, 'get_seller_product_comments']);


//Notifications
Route::get('get-notifications', [ApiController::class, 'getNotifications']);
Route::get('get/customer/notifications', [ApiController::class, 'getUserwiseNotifications']);
Route::get('get/seller/notifications', [VendorApiController::class, 'getVendorwiseNotifications']);
Route::post('view-notification', [ApiController::class, 'viewNotification']);

//Simillar product 
Route::get('/get-simillar-product', [ApiController::class, 'getSimillarProduct']);
Route::get('get-brand-info-by-id/{brandId}', [ApiController::class, 'brandInfoByID']);
Route::get('get-products-by-seller-slug/{slug}', [ApiController::class, 'sellerProductsBySlug']);

Route::post('newsletter-subscribtion', [ApiController::class, 'newsletterSubscribtion']);

//Cart
Route::get('/alladdress', [ApiController::class, 'initUser']);
Route::get('/initcart', [ApiController::class, 'initCart']);
Route::get('/initCompare', [ApiController::class, 'initCompare']);

Route::post('/remove-cart-item', [ApiController::class, 'RemoveCartItem']);
Route::post('add-to-cart', [ApiController::class, 'addToCart']);
Route::post('restock-request', [ApiController::class, 'restockRequest']);

Route::post('update-qty', [ApiController::class, 'updateQty']);
Route::post('update-shipping-option', [ApiController::class, 'updateShippingOption']);
Route::post('variable-add-to-cart', [ApiController::class, 'variableAddToCart']);
Route::post('digital-add-to-cart', [ApiController::class, 'digitalAddToCart']);

//Order
Route::post('order', [ApiController::class, 'Order']);
Route::get('pay-again/{order_id}', [ApiController::class, 'payAgain']);
Route::get('digital-payment-link/{order_id}', [ApiController::class, 'digitalPaymentLink']);
Route::get('cancel-order/{order_id}', [ApiController::class, 'cancelOrder']);
Route::post('product-recieve-confirmation/{order_details_id}', [ApiController::class, 'productRecieveConfirmation']);

Route::post('order-auto-renewal', [ApiController::class, 'orderAutoRenewal']);
Route::post('cancel-order-auto-renewal', [ApiController::class, 'cancelOrderAutoRenewal']);


Route::post('order-status-success', [SslCommerzPaymentController::class, 'success']);
Route::post('order-status-failed', [SslCommerzPaymentController::class, 'failed']);
Route::post('order-status-canceled', [SslCommerzPaymentController::class, 'cancel']);


Route::post('shipping-information', [ApiController::class, 'shippingInformation']);
Route::get('get-shipping-information', [ApiController::class, 'getShippingInformation']);
Route::post('get-coupon-amount', [ApiController::class, 'getCouponAmount']);
Route::get('get-user-orders', [ApiController::class, 'ordersByUserId']);
Route::get('get-user-quatations', [ApiController::class, 'quatationsByUserId']);
Route::get('get-order-list', [ApiController::class, 'getOrderList']);
Route::get('get-single-order/{order_id}', [ApiController::class, 'getSingleOrder']);
Route::get('download-file/{product_id}/{order_id}', [ApiController::class, 'downloadFile']);

Route::get('get-single-quatation/{id}', [ApiController::class, 'getSingleQuatation']);
Route::get('get-return-product', [ApiController::class, 'getReturnProduct']);
Route::post('request-for-quatation', [ApiController::class, 'requestForQuatation']);


//Password
Route::post('change-password', [ApiController::class, 'changePassword']);
Route::post('forgot-password', [ApiController::class, 'forgotPassword']);


//Get product
Route::get('get-category-search/{data}', [ApiController::class, 'categorySearch']);

Route::get('get-promotion-product/{product_type}', [ApiController::class, 'getPromotionProduct']);
Route::get('get-groceries', [ApiController::class, 'getGroceries'])->middleware('localization');
Route::get('get-popular-categories',  [ApiController::class, 'popular_categories']);
Route::get('get-category-product/{slug}', [ApiController::class, 'getCategoryProduct'])->middleware('localization');
Route::get('get-promotional-category-product/{slug}', [ApiController::class, 'getPromotionalCategoryProduct'])->middleware('localization');
Route::get('get-all-product', [ApiController::class, 'getAllProduct'])->middleware('localization');

//Location
Route::get('/get-divisons', [ApiController::class, 'get_divisons']);
Route::get('/get-district/{id}', [ApiController::class, 'get_district']);
Route::get('/get-upazila/{id}', [ApiController::class, 'get_upazila']);
Route::get('/get-union/{id}', [ApiController::class, 'get_union']);
Route::get('/get-upazila-by-title/{title}', [ApiController::class, 'get_get_upazila_by_title']);
Route::get('/get-shipping-rates/{district_id}/{product_id}/{seller_id}', [ApiController::class, 'get_shipping_rates']);


Route::post('update-default-address', [ApiController::class, 'updateDefaultAddress']);
Route::post('add-new-address', [ApiController::class, 'addNewAddress']);
Route::get('delete-address/{id}', [ApiController::class, 'deleteAddress']);
Route::get('get-single-address/{id}', [ApiController::class, 'getSingleAddress']);
Route::post('get-selected-corporate-address', [ApiController::class, 'getSelectedCorporateAddress']);

//Social Login
Route::post('social-login/facebook', [SocialController::class, 'loginWithFacebook']);
Route::post('social-login/google', [SocialController::class, 'loginWithGoogle']);

//Social Login App
Route::post('app-social-login', [ApiController::class, 'social_login']);

//Voucher
Route::get('get-voucher', [ApiController::class, 'getCustomerVouchers']);
Route::post('collect-voucher', [ApiController::class, 'collectCustomerVoucher']);
Route::get('get-home-page-voucher', [ApiController::class, 'getHomePageVoucher']);
Route::get('initcollectedvoucher', [ApiController::class, 'getCollectedVouchers']);
Route::get('inituseablevoucher', [ApiController::class, 'getUseableVouchers']);
Route::get('get-my-collected-vouchers', [ApiController::class, 'getMyCollectedVouchers']);




// Offers
Route::get('get-regular-offer', [ApiController::class, 'getCustomerRegularOffers']);
Route::get('get-promotional-offer', [ApiController::class, 'getCustomerPromotionalOffers']);

//OTP
Route::post('generate-otp', [ApiController::class, 'generateOTP']);
Route::post('otp-login', [ApiController::class, 'otpLogin']);
Route::post('user-name', [ApiController::class, 'userName']);




//Ad
Route::get('get-add', [ApiController::class, 'getAd']);
Route::get('get-promotional-banner', [ApiController::class, 'getPromotionalBanner']);


//Check Auth
Route::post('checkauth', [ApiController::class, 'checkAuth']);

//Vouchers
Route::get('get-collected-vouchers', [ApiController::class, 'getCollectedVouchers']);
Route::get('get-useable-vouchers', [ApiController::class, 'getUseableVouchers']);
//Route::get('get-init-voucher',[ApiController::class, 'initVouchers']);

//Coupons
Route::get('get-my-coupons', [ApiController::class, 'getAllCoupons']);
Route::post('by-coupon-using-loyaltypoin', [ApiController::class, 'buyCouponWithLoyaltyPoint']);

//Others
Route::get('get-promotion-title', [ApiController::class, 'getPromotionTitle']);
Route::get('get-page-content/{slug}', [ApiController::class, 'pageContent'])->middleware('localization');
Route::get('get-static-pages', [ApiController::class, 'staticPages']);
Route::get('get-search-suggetion/{searchContent}', [ApiController::class, 'suggetionProduct']);
Route::post('contact', [ApiController::class, 'contact']);
Route::post('return-request', [ApiController::class, 'returnRequest']);
Route::post('save-what-user-search', [ApiController::class, 'saveWhatUserSearch']);

Route::post('quotation-action', [ApiController::class, 'quotationAction']);


Route::get('get-saller-review', [ApiController::class, 'getSellerReview']);



//Blog
Route::get('get-blog-categories', [ApiController::class, 'blogCategories'])->middleware('localization');
Route::get('get-blog-by-slug/{slug}', [ApiController::class, 'blogByCategory']);
Route::get('get-blogs', [ApiController::class, 'getBlogs'])->middleware('localization');
Route::get('get-category-wise-blogs/{slug}', [ApiController::class, 'getCategoryWiseBlogs'])->middleware('localization');

Route::get('get-single-blog/{slug}', [ApiController::class, 'getSingleBlog'])->middleware('localization');


Route::post('app-link-request', [ApiController::class, 'appLinkRequest']);

// Career 
Route::get('get-career', [ApiController::class, 'getCareers'])->middleware('localization');
Route::get('get-career-details/{id}', [ApiController::class, 'getCareerDetails'])->middleware('localization');
Route::post('apply/for/job', [ApiController::class, 'applyForJob']);

});

include_once('vendor-api.php');