<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Slider;
use App\Models\Navbar;
use App\Models\NavbarLocalization;
use App\Models\Admins;
use App\Models\Category;
use App\Models\Product;
use App\Models\FlashDeal;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Brand;
use App\Models\ShopInfo;
use App\Models\User;
use App\Models\UsersMeta;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderAutoRenewal;
use App\Models\Compare;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Addresses;
use App\Models\Voucher;
use App\Models\VoucherCategory;
use App\Models\CollectedVoucher;
use App\Models\ProductLocalization;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogLocalization;
use App\Models\CategoryLocalization;
use App\Models\BlogCategoryLocalization;
use App\Models\BrandLocalization;
use App\Models\PageLocalization;
use App\Models\Affiliate;
use App\Models\AffiliateWithdrawal;
use App\Models\Pickpoints;
use App\Models\Career;
use App\Models\CareerRequest;
use App\Models\CorporateRequests;
use App\Models\CorporateRequestDetails;
use App\Models\Coupon;
use App\Models\LoyaltyPurchases;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;
use Helper;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
//JWT
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

use App\Models\Client;
use Response;

class ApiController extends Controller
{
	public $base_url;
	public function __construct()
	{
		$this->middleware('auth:customer-api')->only([
			'my_account', 'update_account', 'update_address', 'logout'
		]);
		$this->base_url = env('APP_URL');
	}

	//Simple Product Add to Cart 


	public function restockRequest(Request $request)
	{
		$user = auth('customer-api')->user();
		$session_key = $request->session_key;

		if ($user || $session_key) {
			if ($request->product_id) {
				$product = Product::where('id', $request->product_id)->where('is_active', 1)->where('is_deleted', 0)->first();
				if ($product) {
					if ($user) {
						$already = DB::table('restock_request')->where('user_id', $user->id)->where('product_id', $request->product_id)->first();
						$request_data['user_id'] = $user->id;
					} else if ($session_key) {
						$already = DB::table('restock_request')->where('session_key', $session_key)->where('product_id', $request->product_id)->first();
						$request_data['session_key'] = $session_key;
					}
					if ($already) {
						$data['status'] = 0;
						$data['message'] = 'You have already requested this product for restock. Once this product is availble we will let you know.';
						return response()->json($data, 200);
					} else {

						$request_data['product_id'] = $request->product_id;
						$request_data['seller_id'] = $request->seller_id ?? null;

						$request_data['ip_address'] = $request->ip();
						$insert_id = DB::table('restock_request')->insertGetId($request_data);
						if ($insert_id) {
							$data['status'] = 1;
							$data['message'] = 'Thank you for restock request. Once this product is availble we will let you know.';
							return response()->json($data, 200);
						} else {
							$data['status'] = 0;
							$data['message'] = 'Something went wrong. Please try again later!';
							return response()->json($data, 200);
						}
					}
				} else {
					$data['status'] = 0;
					$data['message'] = 'Product not found.';
					return response()->json($data, 200);
				}
			} else {
				$data['status'] = 0;
				$data['message'] = 'Product not found.';
				return response()->json($data, 200);
			}
		} else {
			$d['status'] = 0;
			$d['message'] = 'Failed to request. Please try again later!';
			return response()->json($d, 200);
		}
	}



	public function addToCart(Request $request)
	{
		$user = auth('customer-api')->user();
		$session_key = $request->session_key;

		if ($user || $session_key) {

			if ($request->product_id) {
				$product = Product::where('id', $request->product_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->first();

				if ($product) {
					if ($request->qty > $product->max_cart_qty) {
						$data['status'] = 0;
						$data['message'] = 'You can not buy more than ' . $product->max_cart_qty . ' items for this product at a time.';
						return response()->json($data, 200);
					} 
					//Conditional Cart Validation Starts
					if ($user) {
						$cartSum  = Cart::where('user_id', $user->id)->sum('price');
					} else {
						$cartSum = Cart::where('session_key', $session_key)->sum('price');
					}

					if ($cartSum < $product->minimum_cart_value) {
						$data['status'] = 0;
						$data['message'] = 'You can buy this item when you have a minimum amount of BDT ' . $product->minimum_cart_value . ' in your cart.';
						return response()->json($data, 200);
					}

					//Conditional Cart Validation Ends



					if ($request->qty > $product->max_cart_qty) {
						$data['status'] = 0;
						$data['message'] = 'You can not buy more than ' . $product->max_cart_qty . ' items for this product at a time.';
						return response()->json($data, 200);
					} else {

						if ($user) {
							$already_in_cart = Cart::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
						} else {
							$already_in_cart = Cart::where('session_key', $session_key)->where('product_id', $request->product_id)->first();
						}

						if ($already_in_cart) {
							//Already Added to cart, we just need to update this product

							if ($request->qty) {

								$updatedQTY = $already_in_cart->qty + $request->qty;

								if ($updatedQTY > $product->max_cart_qty) {
									$data['status'] = 0;
									$data['message'] = 'You can not buy more than ' . $product->max_cart_qty . ' items for this product at a time.';
									return response()->json($data, 200);
								}

								$available = \Helper::simple_product_stock($request->product_id, $updatedQTY);
								if ($available == 1) {
									$data['qty'] = $updatedQTY;
								} else {
									$d['status'] = 0;
									$d['message'] = 'This product out out of stock.';
									return response()->json($d, 200);
								}
							} else {
								$data['qty'] = $already_in_cart->qty;
							}

							if ($user) {
								$update = DB::table('carts')->where('user_id', $user->id)->where('product_id', $request->product_id)->update($data);
							} else {
								$update = DB::table('carts')->where('session_key', $session_key)->where('product_id', $request->product_id)->update($data);
							}

							if ($update) {
								$cart['status'] = 1;
							} else {
								$cart['status'] = 0;
								$cart['message'] = 'Something went wrong. Please try again later!';
							}

							return response()->json($cart, 200);
						} else {
							//We need to add this product to cart
							$product = Product::where('id', $request->product_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->first();

							//Validate Stock for Simple product
							if ($request->qty) {
								$available = \Helper::simple_product_stock($request->product_id, $request->qty);
								if ($available == 1) {
									$qty = $request->qty;
								} else {
									$data['status'] = 0;
									$data['message'] = 'This product out out of stock.';
									return response()->json($data, 200);
								}
							} else {
								$qty = 1;
							}

							//Validate Price for Simple product
							$data['price'] = \Helper::price_after_offer($product->id);
							$data['discount'] = \Helper::discount_amount_by_IDS($product->id) * $qty;
							$data['product_id'] = $product->id;
							$data['seller_id'] = $product->seller_id;
							$data['product_type'] = $product->product_type;

							$data['packaging_cost'] = $product->packaging_cost ?? 0;
							$data['security_charge'] = $product->security_charge ?? 0;

							$data['user_id'] =  $user->id ?? null;
							$data['session_key'] =  $session_key ?? null;
							$data['qty'] = $qty;
							$data['row_id'] = uniqid();
							$insert_id = DB::table('carts')->insertGetId($data);
							if ($insert_id) {
								$cart['status'] = 1;
								return response()->json($cart, 200);
							} else {
								$data['status'] = 0;
								$data['message'] = 'Something went wrong. Please try again later!';
								return response()->json($data, 200);
							}
						}
					}
				} else {
					$data['status'] = 0;
					$data['message'] = 'Product not found.';
					return response()->json($data, 200);
				}
			} else {
				$data['status'] = 0;
				$data['message'] = 'Product not found.';
				return response()->json($data, 200);
			}
		} else {
			$d['status'] = 0;
			$d['message'] = 'Failed to add product in your cart. Please try again later!';
			return response()->json($d, 200);
		}
	}

	//Variable Product Add To Cart
	public function variableAddToCart(Request $request)
	{
		$variants = [];
		$shipping_option = '';
		foreach ($request->all() as $key => $value) {
			if ($key == 'shipping_option') {
				$shipping_option =  $value;
			} else {
				$variants[$key] =  $value;
			}
		}

		$session_key = $request->session_key;
		$user = auth('customer-api')->user();
		if ($user || $session_key) {
			$variable_option = $request->all();
			unset($variable_option['product_id']);
			unset($variable_option['qty']);
			unset($variable_option['shipping_option']);
			unset($variable_option['session_key']);
			unset($variable_option['variable_sku']);

			if ($request->product_id) {

				if ($user) {
					$already_in_cart = Cart::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
				} else {
					$already_in_cart = Cart::where('session_key', $session_key)->where('product_id', $request->product_id)->first();
				}

				$product = Product::where('id', $request->product_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->first();

				if ($product) {


					//Conditional Cart Validation Starts
					if ($user) {
						$cartSum  = Cart::where('user_id', $user->id)->sum('price');
					} else {
						$cartSum = Cart::where('session_key', $session_key)->sum('price');
					}

					if ($cartSum < $product->minimum_cart_value) {
						$data['status'] = 0;
						$data['message'] = 'You can buy this item when you have a minimum amount of BDT ' . $product->minimum_cart_value . ' in your cart.';
						return response()->json($data, 200);
					}

					//Conditional Cart Validation Ends



					if ($request->qty > $product->max_cart_qty) {
						$data['status'] = 0;
						$data['message'] = 'You can not buy more than ' . $product->max_cart_qty . ' items for this product at a time.';
						return response()->json($data, 200);
					} else {
						if ($already_in_cart) {
							//Already Added to cart, we just need to update this product
							$qty = $request->qty ? $request->qty : $already_in_cart->qty;
							$availableOptions = \Helper::variable_product_stock($request->product_id, $qty, $variable_option);
							if ($availableOptions) {
								$productPrice = \Helper::price_after_offer($product->id) + $availableOptions['totalAdditional'];
								$data['price'] = $productPrice;
								$data['discount'] = \Helper::discount_amount_by_IDS($product->id) * $qty;
								$data['variable_options'] = json_encode($variable_option);
								$data['qty'] = $qty;
								$data['variable_sku'] = $request->variable_sku;
							} else {
								$d['status'] = 0;
								$d['message'] = 'Quantity not available.';
								return response()->json($d, 200);
							}

							if ($user) {
								$update = Cart::where('user_id', $user->id)->where('product_id', $request->product_id)->update($data);
							} else {
								$update = Cart::where('session_key', $session_key)->where('product_id', $request->product_id)->update($data);
							}


							if ($update) {
								$cart['status'] = 1;
							} else {
								$cart['status'] = 0;
								$cart['message'] = 'Something went wrong. Please try again later!';
							}
							return response()->json($cart, 200);
						} else {
							//We need to add this product to cart
							$product = Product::where('id', $request->product_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->first();
							if ($product) {
								$qty = $request->qty ? $request->qty : 1;
								$availableOptions = \Helper::variable_product_stock($request->product_id, $qty, $variable_option);

								if ($availableOptions) {
									//Product is available to add to cart
									$productPrice = \Helper::price_after_offer($product->id) + $availableOptions['totalAdditional'];
									$data['price'] = $productPrice;
									$data['discount'] = \Helper::discount_amount_by_IDS($product->id) * $qty;
									$data['product_id'] = $product->id;
									$data['product_type'] = 'variable';
									$data['seller_id'] = $product->seller_id;
									$data['user_id'] =   $user->id ?? null;
									$data['session_key'] =   $session_key ?? null;
									$data['variable_options'] = json_encode($variable_option);
									$data['qty'] = $qty;
									$data['row_id'] = uniqid();
									$data['variable_sku'] = $request->variable_sku;

									$data['packaging_cost'] = $product->packaging_cost ?? 0;
									$data['security_charge'] = $product->security_charge ?? 0;

									$insert_id = DB::table('carts')->insertGetId($data);

									if ($insert_id) {
										$cart['status'] = 1;
									} else {
										$cart['status'] = 0;
										$cart['message'] = 'Something went wrong. Please try again later!';
									}
									return response()->json($cart, 200);
								} else {
									$data['status'] = 0;
									$data['message'] = 'Quantity not available.';
									return response()->json($data, 200);
								}
							} else {
								$data['status'] = 0;
								$data['message'] = 'Product not found.';
								return response()->json($data, 200);
							}
						}
					}
				} else {
					$data['status'] = 0;
					$data['message'] = 'Product not found.';
					return response()->json($data, 200);
				}
			} else {
				$data['status'] = 0;
				$data['message'] = 'Product not found.';
				return response()->json($data, 200);
			}
		} else {
			$d['status'] = 0;
			$d['message'] = 'Please login first.';
			return response()->json($d, 200);
		}
	}

	//Digital Product Add to Cart
	public function digitalAddToCart(Request $request)
	{
		$user = auth('customer-api')->user();
		if (!$user) {
			$d['status'] = 0;
			$d['message'] = 'Please login first.';
			return response()->json($d, 200);
		}
		if ($request->product_id) {
			$product = Product::where('id', $request->product_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->first();
			if ($product) {


				//Conditional Cart Validation Starts
				if ($user) {
					$cartSum  = Cart::where('user_id', $user->id)->sum('price');
				} else {
					$cartSum = Cart::where('session_key', $request->session_key)->sum('price');
				}
				if ($cartSum < $product->minimum_cart_value) {
					$data['status'] = 0;
					$data['message'] = 'You can buy this item when you have a minimum amount of BDT ' . $product->minimum_cart_value . ' in your cart.';
					return response()->json($data, 200);
				}
				//Conditional Cart Validation Ends
				if ($request->qty > $product->max_cart_qty) {
					$data['status'] = 0;
					$data['message'] = 'You can not buy more than ' . $product->max_cart_qty . ' items for this product at a time.';
					return response()->json($data, 200);
				} else {
					$already_in_cart = Cart::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
					if ($already_in_cart) {
						//Already Added to cart, we just need to update this product
						if ($request->qty) {
							$updatedQTY = $already_in_cart->qty + $request->qty;
							if ($updatedQTY > $product->max_cart_qty) {
								$data['status'] = 0;
								$data['message'] = 'You can not buy more than ' . $product->max_cart_qty . ' items for this product at a time.';
								return response()->json($data, 200);
							}
							$available = \Helper::simple_product_stock($request->product_id, $updatedQTY);
							if ($available == 1) {
								$data['qty'] = $updatedQTY;
							} else {
								$cart['status'] = 0;
								$cart['message'] = 'This product out out of stock.';
								return response()->json($cart, 200);
							}
						} else {
							$data['qty'] = $already_in_cart->qty;
						}


						$update = DB::table('carts')->where('user_id', $user->id)->where('product_id', $request->product_id)->update($data);

						if ($update) {
							$cart['status'] = 1;
						} else {
							$cart['status'] = 0;
							$cart['message'] = 'Something went wrong. Please try again later!';
						}
						return response()->json($cart, 200);
					} else {
						//We need to add this product to cart
						$product = Product::where('id', $request->product_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->first();
						if ($product) {

							//Validate Stock for Simple product
							if ($request->qty) {
								$available = \Helper::simple_product_stock($request->product_id, $request->qty);
								if ($available == 1) {
									$qty = $request->qty;
								} else {
									$data['status'] = 0;
									$data['message'] = 'This product out out of stock.';
									return response()->json($data, 200);
								}
							} else {
								$qty = 1;
							}

							//Validate Price for Simple product
							$now_price =  \Helper::price_after_offer($product->id);

							//Product is available to add to cart
							$data['price'] = $now_price;
							$data['discount'] = \Helper::discount_amount_by_IDS($product->id) * $qty;
							$data['product_id'] = $product->id;
							$data['seller_id'] = $product->seller_id;
							$data['product_type'] = $product->product_type;
							$data['user_id'] =  $user->id;
							$data['qty'] = $qty;
							$data['row_id'] = uniqid();

							if($request->service_date){
								$serviceOption = [
									'service_date' => $request->service_date,
									'service_time' => $request->service_time,
								];

								$data['variable_options'] = json_encode($serviceOption);
							}else{
								$data['variable_options'] = ($request->phone_number == -1) ? null : $request->phone_number;
							}

							

							$data['packaging_cost'] = $product->packaging_cost ?? 0;
							$data['security_charge'] = $product->security_charge ?? 0;

							$insert_id = DB::table('carts')->insertGetId($data);
							if ($insert_id) {
								$cart['status'] = 1;
							} else {
								$cart['status'] = 0;
								$cart['message'] = 'Something went wrong. Please try again later!';
							}
							return response()->json($cart, 200);
						} else {
							$data['status'] = 0;
							$data['message'] = 'product not found.';
							return response()->json($data, 200);
						}
					}
				}
			} else {
				$data['status'] = 0;
				$data['message'] = 'Product not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'Product not found.';
			return response()->json($data, 200);
		}
	}


	public function sliders()
	{
		$data = Slider::where('status', 1)->orderBy('sort_order', 'desc')->get();
		foreach ($data as $d) {

			$d->app_key = 'unknown';
			$d->app_value = $d->button_link;
			$app_key = explode('/', $d->button_link);

			if ($app_key) {

				if ($app_key[0] == 'product') {
					$d->app_key = $app_key[0];
					$d->app_value = $app_key[1];
				} elseif ($app_key[0] == 'shop') {
					$d->app_key = $app_key[0];

					$shopSlug =  $app_key[1];
					$shop = \DB::table('shop_info')->select('id')->where('slug', $shopSlug)->first();

					if ($shop) {
						$d->app_value =  $shop->id;
					}
				} elseif ($app_key[0] == 'category') {
					$d->app_key = $app_key[0];

					$categorySlug =  $app_key[1];
					$category = \DB::table('categories')->select('id')->where('slug', $categorySlug)->first();
					if ($category) {
						$d->app_value =  $category->id;
					}
				} elseif ($app_key[0] == 'offer') {
					$d->app_key = $app_key[0];
					$categorySlug =  $app_key[2];
					$category = \DB::table('categories')->select('id')->where('slug', $categorySlug)->first();
					if ($category) {
						$d->app_value =  $category->id;
					}
				} elseif ($app_key[0] == 'blog') {
					$d->app_key = $app_key[0];
					$d->app_value = $app_key[1];
				}
			}
		}

		return response()->json($data, 200);
	}
	public function brands()
	{
		$data = Brand::where('is_active', 1)->where('is_deleted', 0)->get();
		foreach ($data as $item) {
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = BrandLocalization::where('brand_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
			}
		}
		return response()->json($data, 200);
	}




	public function searchBrands()
	{
		$data = Brand::where('is_active', 1)->where('is_deleted', 0)->paginate(10);
		return response()->json($data, 200);
	}
	public function featured_seller(Request $request)
	{
		$data = [];
		if ($request->sellers == 'all') {
			$sellers = Admins::select('id')->where('status', 1)->with('shopinfo')->get();
			$adminsArray = [];
			foreach ($sellers as $key => $val) {
				if (!$val->hasRole('seller')) {
					$adminsArray[] = $val->id;
				}
			}
			$data['items'] = Admins::select('id')->where('status', 1)->with('shopinfo')->whereNotIn('id', $adminsArray)->paginate(10);
			foreach ($data['items'] as $item) {
				$item->shop_verified = \Helper::is_verified_seller($item->shopinfo->seller_id);
			}
			$data['status'] = 1;
		} else {
			$featured_sellers_ids = \Helper::getsettings('featured_sellers');
			$featured_sellers_ids_array = explode(',', $featured_sellers_ids);
			$sellers = Admins::select('id')->where('status', 1)->with('shopinfo')->whereIn('id', $featured_sellers_ids_array)->get();
			foreach ($sellers as $item) {
				$item->shop_verified = \Helper::is_verified_seller($item->shopinfo->seller_id);
			}
			$data['items'] = $sellers;
			$data['title'] = \Helper::getsettings('featured_seller_title') ?? 'Featured Sellers';
			$data['status'] = \Helper::getsettings('featured_sellers_status');
		}


		return response()->json($data, 200);
	}

	public function categories()
	{

		$categories = Category::where('is_active', 1)
			->with('categories.categories.categories')
			->where('parent_id', 0)
			->where('is_deleted', 0)
			->where('hide_on_menu', 0)
			->orderBy('sort_order', 'asc')
			->get();
		foreach ($categories as $item) {
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {


				$langData = CategoryLocalization::where('category_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}

				foreach ($item->categories as $layer1) {

					$langData = CategoryLocalization::where('category_id', $layer1->id)->where('is_active', 1)->where('lang_code', $lang)->first();
					if ($langData) {
						$layer1->title = $langData->title ? $langData->title : $layer1->title;
					}

					foreach ($layer1->categories as $layer2) {
						$langData = CategoryLocalization::where('category_id', $layer2->id)->where('is_active', 1)->where('lang_code', $lang)->first();
						if ($langData) {
							$layer2->title = $langData->title ? $langData->title : $layer2->title;
						}

						foreach ($layer2->categories as $layer3) {

							$langData = CategoryLocalization::where('category_id', $layer3->id)->where('is_active', 1)->where('lang_code', $lang)->first();
							if ($langData) {
								$layer3->title = $langData->title ? $langData->title : $layer3->title;
							}
						}
					}
				}
			}
		}


		return response()->json($categories, 200);
	}



	public function site_info(Request $request){

		// return response()->json('sucesss', 200);
		// exit;


		$data['popup_banner_status'] = \Helper::getsettings('popup_banner_status') ?? null;
		$data['popup_banner_image'] = \Helper::getsettings('popup_banner_image') ?? null; 

		$data['header_logo']  =  \Helper::getsettings('header_logo');
		$data['footer_logo']  = \Helper::getsettings('footer_logo');
		$data['phone_number'] = \Helper::getsettings('phone_number');
		$data['copyright_text'] = \Helper::getsettings('copyright_text');
		$data['accepted_payment_image'] = \Helper::getsettings('accepted_payment_image');


		$data['header_offer_banner_status'] = \Helper::getsettings('header_offer_banner_status') ?? null;
		$data['header_offer_banner_image'] = \Helper::getsettings('header_offer_banner_image') ?? null;
		$data['header_offer_banner_linktype'] = \Helper::getsettings('header_offer_banner_linktype') ?? null;
		$data['header_offer_banner_link'] = \Helper::getsettings('header_offer_banner_link') ?? null;

		$data['favicon'] = \Helper::getsettings('favicon');
		$data['return_policy'] = \Helper::getsettings('return_policy');
		$data['privacy_policy'] = \Helper::getsettings('privacy_policy');
		$data['warranty_policy'] = \Helper::getsettings('warranty_policy');
		$data['terms_of_use'] = \Helper::getsettings('terms_of_use');
		$data['facebook'] = \Helper::getsettings('social_links_facebook') ?? null;
		$data['twitter'] = \Helper::getsettings('social_links_twitter') ?? null;
		$data['instagram'] = \Helper::getsettings('social_links_instagram') ?? null;
		$data['youtube'] = \Helper::getsettings('social_links_youtube') ?? null;
		$data['pinterest'] = \Helper::getsettings('social_links_pinterest') ?? null;
		$data['welcome_text'] = \Helper::getsettings('welcome_text');
		$data['social_login'] = \Helper::getsettings('social_login');
		$data['cnf_appname'] = config('concave.cnf_appname');
		$data['cnf_appdesc'] = config('concave.cnf_appdesc');
		$data['cnf_comname'] = config('concave.cnf_comname');
		$data['cnf_address'] = config('concave.cnf_address');
		$data['cnf_email'] = config('concave.cnf_email');

		$data['partial_payment_enable'] = (int)\Helper::getsettings('partial_payment_enable') ?? null;
		// $data['partial_payment_type'] = \Helper::getsettings('partial_payment_type') ?? null;
		// $data['partial_payment_fixed_or_percentage_amount'] = \Helper::getsettings('partial_payment_fixed_or_percentage_amount') ?? null;
		// $data['partial_payment_minimum_amount'] =  (int)\Helper::getsettings('partial_payment_minimum_amount') ?? null;
		$allphones = explode(',', config('concave.cnf_phone'));
		$data['cnf_phone1'] = $allphones[0] ?? '';
		$data['cnf_phone2'] = $allphones[1] ?? '';
		$data['cnf_phone3'] = $allphones[2] ?? '';
		$data['cnf_phone4'] = $allphones[3] ?? '';
		
		$upazila = DB::table('upazilas')->where('id', $request->upazila_id)->first();
		if($upazila){
			 $data['upazila_title'] = $upazila->title;
		}else{
			  $data['upazila_title'] = null;
		}
		return response()->json($data, 200);
	}

	
	public function getNavbars(Request $request){
		$navbars = Navbar::orderBy('sort_order', 'DESC')->get();
		foreach ($navbars as $item) {
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = NavbarLocalization::where('navbar_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
			}
		}
		if($navbars){
			return response()->json($navbars, 200);
		}else{
			return null;
		}
		
	}





	public function searchcategories()
	{
		$categories = Category::where('is_active', 1)
			->with('categories.categories.categories')
			->where('parent_id', 0)
			->where('is_deleted', 0)
			->where('hide_on_menu', 0)

			->orderBy('sort_order', 'asc')
			->paginate(10);



		foreach ($categories as $item) {
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = CategoryLocalization::where('category_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
				foreach ($item->categories as $layer1) {
					$langData = CategoryLocalization::where('category_id', $layer1->id)->where('is_active', 1)->where('lang_code', $lang)->first();
					if ($langData) {
						$layer1->title = $langData->title ? $langData->title : $layer1->title;
					}
					foreach ($layer1->categories as $layer2) {
						$langData = CategoryLocalization::where('category_id', $layer2->id)->where('is_active', 1)->where('lang_code', $lang)->first();
						if ($langData) {
							$layer2->title = $langData->title ? $langData->title : $layer2->title;
						}
						foreach ($layer2->categories as $layer3) {
							$langData = CategoryLocalization::where('category_id', $layer3->id)->where('is_active', 1)->where('lang_code', $lang)->first();
							if ($langData) {
								$layer3->title = $langData->title ? $langData->title : $layer3->title;
							}
						}
					}
				}
			}
		}
		return response()->json($categories, 200);
	}









	public function singleCategory($id)
	{
		$category = Category::where('id', $id)->where('is_active', 1)->where('is_deleted', 0)->first();
		return response()->json($category, 200);
	}



	public function singleCategorybySlug($slug)
	{
		$slug_cat = Category::where('slug', $slug)->where('is_active', 1)->where('is_deleted', 0)->first();

		$categories = Category::where('is_active', 1)->where('id', $slug_cat->id)
			->with('categories.categories.categories')

			->where('is_deleted', 0)
			->where('hide_on_menu', 0)

			->orderBy('sort_order', 'asc')
			->get();


		foreach ($categories as $item) {
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = CategoryLocalization::where('category_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
				foreach ($item->categories as $layer1) {
					$langData = CategoryLocalization::where('category_id', $layer1->id)->where('is_active', 1)->where('lang_code', $lang)->first();
					if ($langData) {
						$layer1->title = $langData->title ? $langData->title : $layer1->title;
					}
					foreach ($layer1->categories as $layer2) {
						$langData = CategoryLocalization::where('category_id', $layer2->id)->where('is_active', 1)->where('lang_code', $lang)->first();
						if ($langData) {
							$layer2->title = $langData->title ? $langData->title : $layer2->title;
						}
						foreach ($layer2->categories as $layer3) {
							$langData = CategoryLocalization::where('category_id', $layer3->id)->where('is_active', 1)->where('lang_code', $lang)->first();
							if ($langData) {
								$layer3->title = $langData->title ? $langData->title : $layer3->title;
							}
						}
					}
				}
			}
		}

		$data['category'] = $slug_cat;
		$data['categories'] = $categories;


		return response()->json($data, 200);
	}





	// public function groceryCategories()
	// {

	// 	$grocery_id = \Helper::getsettings('grocery_parent_category');

	// 	if ($grocery_id) {
	// 		// $categories = Category::where('is_active',1)->where('id', $grocery_id)
	// 		// 	->with('categories.categories.categories')
	// 		// 	->where('parent_id', 0)
	// 		// 	->where('is_deleted',0)
	// 		// 	->where('hide_on_menu',0)
	// 		// 	->orderBy('sort_order','asc')
	// 		// 	->get();

	// 		// 	$groceries_categories = [];
	// 		// 	foreach ($categories as $layer1) {
	// 		// 		$groceries_categories[] = $layer1;
	// 		// 		if(count($layer1->categories) > 0){
	// 		// 			foreach ($layer1->categories as $layer2) {
	// 		// 				$groceries_categories[] = $layer2;
	// 		// 				if(count($layer2->categories) > 0){
	// 		// 					foreach ($layer2->categories as $layer3) {
	// 		// 						$groceries_categories[] = $layer3;
	// 		// 					}
	// 		// 				}
	// 		// 			}
	// 		// 		}

	// 		// 	}

	// 		// 	$ids = [];
	// 		// 	foreach ($groceries_categories as $item) {
	// 		// 		$ids[] = $item->id;
	// 		// 	}

	// 		// $categories = Category::where('is_active',1)->whereIn('id', $ids)
	// 		// ->orderBy('sort_order','asc')
	// 		// ->paginate(12);

	// 		$categories = Category::where('is_active', 1)
	// 			->where('parent_id', $grocery_id)
	// 			->where('is_deleted', 0)
	// 			->where('hide_on_menu', 0)
	// 			->orderBy('sort_order', 'asc')
	// 			->paginate(12);

	// 		foreach ($categories as $item) {
	// 			//Localization
	// 			$lang = app()->getLocale();
	// 			if ($lang != 'en') {
	// 				$langData = CategoryLocalization::where('category_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
	// 				if ($langData) {
	// 					$item->title = $langData->title ? $langData->title : $item->title;
	// 				}
	// 				foreach ($item->categories as $layer1) {
	// 					$langData = CategoryLocalization::where('category_id', $layer1->id)->where('is_active', 1)->where('lang_code', $lang)->first();
	// 					if ($langData) {
	// 						$layer1->title = $langData->title ? $langData->title : $layer1->title;
	// 					}
	// 					foreach ($layer1->categories as $layer2) {
	// 						$langData = CategoryLocalization::where('category_id', $layer2->id)->where('is_active', 1)->where('lang_code', $lang)->first();
	// 						if ($langData) {
	// 							$layer2->title = $langData->title ? $langData->title : $layer2->title;
	// 						}
	// 						foreach ($layer2->categories as $layer3) {
	// 							$langData = CategoryLocalization::where('category_id', $layer3->id)->where('is_active', 1)->where('lang_code', $lang)->first();
	// 							if ($langData) {
	// 								$layer3->title = $langData->title ? $langData->title : $layer3->title;
	// 							}
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 		return response()->json($categories, 200);
	// 	} else {
	// 		return response()->json([], 200);
	// 	}
	// }




	public function getProductsByCategoryId(Request $request)
	{
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$products = Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('is_promotion', 0)
			->where('product_qc', 1)
			->whereRaw("find_in_set({$request->category_id},category_id)")
			->orderby('shuffle_number', 'desc')
			->get();
		return response()->json($products, 200);
	}
	public function getProductsByBrandId(Request $request)
	{
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;

		$products = Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('is_promotion', 0)
			->where('brand_id', $request->brand_id)
			->where('product_qc', 1)
			->orderby('shuffle_number', 'desc')
			->get();
		return response()->json($products, 200);
	}





	public function getProductsBySellerId(Request $request)
	{
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;

		if ($request->seller_id) {
			$products = Product::where('is_active', 1)
				->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
				->where('is_deleted', 0)
				->where('is_promotion', 0)
				->where('seller_id', $request->seller_id)
				->where('product_qc', 1)
				->orderby('shuffle_number', 'desc')
				->get();
			if (count($products) > 0) {
				foreach ($products as $item) {
					$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
					$item->price = number_format((int)$item->price, 0);
					$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
					$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
					$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
				}
				return response()->json($products, 200);
			} else {
				$data['status'] = 1;
				$data['message'] = 'Product not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 1;
			$data['message'] = 'Seller not found.';
			return response()->json($data, 200);
		}
	}


	public function sellerInformation(Request $request)
	{
		if ($request->seller_id) {
			$data['profile'] = Admins::where('id', $request->seller_id)->with('shopinfo')->first();
			$products = Product::where('seller_id', $request->seller_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->where('is_promotion', 0)->orderby('shuffle_number', 'desc')->get();
			$product_ids = [];
			foreach ($products as $item) {
				$product_ids[] = $item->id;
			}
			$seller_products = Product::where('seller_id', $request->seller_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->where('is_promotion', 0)->orderby('shuffle_number', 'desc')->paginate(15);
			foreach ($seller_products as $item) {
				$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
				$item->price = number_format((int)$item->price, 0);
				$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
				$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
				$item->single_prodcut_ratings = \Helper::reveiwByProductId($item->id);
			}
			$reviewData = [];
			$reviews = DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->groupBy('id')->get();
			$reveiwed_products = [];
			$average = 0;
			if (count($reviews) > 0) {
				foreach ($reviews as $item) {
					$average +=  $item->rate;
					$pro =  \Helper::get_product_by_id($item->commentable_id);
					$pro->rating = \Helper::reveiwByProductId($item->commentable_id);
					$reveiwed_products[] = $pro;
				}
				$average = $average / count($reviews);
				$reviewData['average_rating'] =  number_format($average, 2);
				$reviewData['total_ratings'] = count($reviews);
				$reviewData['average_percentage'] = 'width:' . ($average / 5 * 100) . '%;';
				$reviewData['five_star']  = count(DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 5)->get());
				$reviewData['five_star_percentage'] = 'width:' . number_format($reviewData['five_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				$reviewData['four_star']  = count(DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 4)->get());
				$reviewData['four_star_percentage'] = 'width:' . number_format($reviewData['four_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				$reviewData['three_star']  = count(DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 3)->get());
				$reviewData['three_star_percentage'] = 'width:' . number_format($reviewData['three_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				$reviewData['two_star']  = count(DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 2)->get());
				$reviewData['two_star_percentage'] = 'width:' . number_format($reviewData['two_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				$reviewData['one_star']  = count(DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 1)->get());
				$reviewData['one_star_percentage'] = 'width:' . number_format($reviewData['one_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				foreach ($reviews as $item) {
					$item->product =  \Helper::get_product_by_id($item->commentable_id);
				}
			}
			$data['product'] = $seller_products;
			$data['ratings'] = $reviewData;
			$data['seller_ratings_info'] = \Helper::sellerRatingCalculation($request->seller_id);
			return response()->json($data, 200);
		} else {
			$data['status'] = 1;
			$data['message'] = 'Seller not found.';
			return response()->json($data, 200);
		}
	}










	public function getBlogs(){
		$blog =  Blog::where('is_active', 1)->where('is_deleted', 0)->paginate(15);
		$category = BlogCategory::where('is_active', 1)->orderby('id', 'desc')->limit(20)->get();

		foreach ($blog as $item) {
			$item->category_name = \Helper::get_blog_category_by_id($item->id);
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = BlogLocalization::where('id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
					$item->description = $langData->description ? $langData->description : $item->description;
				}
			}
		}

		$data['blog'] = $blog;
		$data['category'] = $category;
		$data['category_title'] = null;
		$data['status'] = 1;

		if (count($blog) > 0 || count($category) > 0) {
			return response()->json($data, 200);
		} else {
			$d['status'] = 0;
			$d['products'] = [];
			$d['message'] = 'Blog not found.';
			$d['category_title'] = null;
			$d['allblogs'] = null;
			return response()->json($d, 200);
		}
	}

	public function getCategoryWiseBlogs($slug){
		$category = BlogCategory::where('is_active', 1)->orderby('id', 'desc')->limit(20)->get();
		$slugcategory = BlogCategory::where('is_active', 1)->where('slug', $slug)->first();
		$cat_id = $slugcategory->id??null;
		$blog =  Blog::where('is_active', 1)->where('category_id', $cat_id)->where('is_deleted', 0)->paginate(6);

		foreach($blog as $item){
			$item->category_name = \Helper::get_blog_category_by_id($item->id);
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = BlogLocalization::where('id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
					$item->description = $langData->description ? $langData->description : $item->description;
				}
			}
		}

		$data['blog'] = $blog;
		$data['category'] = $category;
		$data['category_title'] = $slugcategory->title??null;

		if ($blog) {
			return response()->json($data, 200);
		} else {
			$d['status'] = 0;
			$d['products'] = [];
			$d['message'] = 'Blog not found.';
			$d['category_title'] = null;
			return response()->json($d, 200);
		}
	}


	









	public function getSingleBlog($slug)
	{
		$blog = DB::table('blogs')->where('slug', $slug)->where('is_active', 1)->where('is_deleted', 0)->first();

		$latestBlogs = DB::table('blogs')->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'DESC')->limit(4)->get();
		$relatedProducts = [];
		if ($blog) {
			if ($blog->related_products) {
				$related_products = [];
				$related_products = explode(',', $blog->related_products);
				$relatedProducts = Product::whereIn('id', $related_products)->where('is_deleted', 0)->where('product_qc', 1)->orderby('shuffle_number', 'desc')->get();

				foreach ($relatedProducts as $item) {
					$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
					$item->price = number_format((int)$item->price, 0);
					$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
					$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
					$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
				}
			}
		}


		foreach ($latestBlogs as $item) {
			$item->category_name = \Helper::get_blog_category_by_id($item->id);
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = BlogLocalization::where('id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
					$item->description = $langData->description ? $langData->description : $item->description;
				}
			}
		}

		//Localization
		$lang = app()->getLocale();
		if ($lang != 'en') {
			$langData = BlogLocalization::where('id', $blog->id)->where('is_active', 1)->where('lang_code', $lang)->first();
			if ($langData) {
				$blog->title = $langData->title ? $langData->title : $blog->title;
				$blog->description = $langData->description ? $langData->description : $blog->description;
			}
		}



		if ($blog) {
			$data['status'] = 1;
			$data['blog'] = $blog;
			$data['category_name'] = \Helper::get_blog_category_by_id($blog->id);
			$data['latestBlogs'] = $latestBlogs;
			$data['relatedProducts'] = $relatedProducts;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Blog not found.';
			return response()->json($data, 200);
		}
	}





	public function getProductBySlug($slug)
	{

	
		$product = Product::where('slug', $slug)
			->where('is_active', 1)
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->with('meta')
			->with('specification')
			->first();


		if (!$product) {
			$data['status'] = 0;
			$data['message'] = 'Product Not Found.';
			return response()->json($data, 200);
		}

		//Localization
		$lang = app()->getLocale();
		if ($lang != 'en') {
			$langData = ProductLocalization::where('product_id', $product->id)->where('is_active', 1)->where('lang_code', $lang)->first();
			if ($langData) {
				$product->title = $langData->title ? $langData->title : $product->title;
				$product->short_description = $langData->short_description ? $langData->short_description :  $product->short_description;
				$product->description = $langData->description ? $langData->description : $product->description;
			}
		}

		$user = auth('customer-api')->user();
		$availableShippingOptions = [];

		if ($user) {
			$purchased = DB::table('order_details')->where('user_id', $user->id)->where('product_id', $product->id)->where('status', 6)->first();
			if ($purchased) {
				$product->purchased = true;
			} else {
				$product->purchased = false;
			}
			$user_id = $user->id;
			$availableShippingOptions = \Helper::get_shipping_cost($user_id, $product->seller_id, $product->id);
		}

		$product->shipping_options =  $availableShippingOptions;

		$product->price_after_offer = \Helper::price_after_offer($product->id);
		$product->price = $product->price;

		if ($product->seller_id) {
			$shop = ShopInfo::select('name', 'slug')->where('seller_id', $product->seller_id)->first();
			$product->shop_name = $shop ?$shop->name:'';
			$product->shop_slug = $shop ? $shop->slug:'';
			$product->seller_ratings_info =  \Helper::sellerRatingCalculation($product->seller_id);
		}

		if ($product->meta) {
			foreach ($product->meta as $meta) {
				if ($meta->meta_key == 'custom_options' || $meta->meta_key == 'product_shipping_option' || $meta->meta_key == 'product_miscellaneous_information' || $meta->meta_key == 'tab_option') {
					$meta->meta_value = unserialize($meta->meta_value);
					if ($meta->meta_key == 'custom_options') {
						$quantities = [];
						$specificStock = [];
						$option_wise_stock = [];

						foreach ($meta->meta_value as $single_op) {
							foreach ($single_op['value'] as $item) {
								$quantities[] = $item;

								if ($item['qty']) {
									$specificStock[$single_op['title']][] = (int) $item['qty'];
								} else {
									$specificStock[$single_op['title']][] = 0;
								}
							}
						}
						foreach ($specificStock as $key => $val) {
							if (max($val) > 0) {
								$option_wise_stock[$key] = true;
							} else {
								$option_wise_stock[$key] = false;
							}
						}

						$product->hidden_option = $option_wise_stock;

						$total_qty = [];
						foreach ($quantities as $single_variant) {
							$total_qty[] = $single_variant['qty'];
						}


						if (count(array_filter($total_qty)) == 0) {
							$product->calculated_in_stock = false;
						} else {
							$product->calculated_in_stock = true;
						}
					}
				}
			}
		}

		//Breadcrumb
		$category1 = Category::where('id', $product->category_id)->orderBy('id', 'DESC')->first();
		if ($category1) {
			$category2 = Category::where('id', $category1->parent_id)->orderBy('id', 'DESC')->first();
			if ($category2) {
				$step2 = \Helper::category_title_by_id((int)$category2->parent_id) . ' / ';
			} else {
				$step2 = '';
			}
			$step1 = $step2 . \Helper::category_title_by_id((int)$category1->parent_id) . ' / ' . $category1->title . ' / ';
		} else {
			$step1 = '';
		}

		$average = $product->averageRate() > 0 ? $product->averageRate() : 0;

		$product->star_ratig_average = $average;
		$product->shop_verified = \Helper::is_verified_seller($product->seller_id);
		DB::table('products')->where('id', $product->id)->update(['viewed' => ($product->viewed + 1)]);
		return response()->json($product, 200);
	}



	public function getOnsaleProduct(Request $request)
	{
		$data = [];
		$on_sale_products_ids = \Helper::getsettings('on_sale_products');
		$on_sale_products_ids_array = explode(',', $on_sale_products_ids);
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$products = Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->where('is_grocery', 'other')
			->whereIn('id', $on_sale_products_ids_array)
			->orderby('shuffle_number', 'desc')
			->get();
		$lang = app()->getLocale();
		foreach ($products as $item) {
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
			}
		}
		$data['items'] = $products;
		$data['status'] = \Helper::getsettings('on_sale_products_status');
		$data['title'] = \Helper::getsettings('onsale_products_title') ?? 'Onsale Products';
		//$data['title'] = DB::table('settings')->where('key', 'onsale_products_title')->first();
		return response()->json($data, 200);
	}


	public function getflashSaleProduct(Request $request){
		$data = [];
		$flash_sale_products_ids = \Helper::getsettings('flash_sale_products');
		$flash_sale_products_ids_array = explode(',', $flash_sale_products_ids);
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		//return response()->json($flash_sale_products_ids_array, 200);
		$products = Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->where('is_grocery', 'other')
			->whereIn('id', $flash_sale_products_ids_array)
			->with('meta')
			->with('specification')
			->orderby('shuffle_number', 'desc')
			->get();
		
		foreach ($products as $product) {
			$product->price_after_offer = number_format((int)\Helper::price_after_offer($product->id), 0);
			$product->price = number_format((int)$product->price, 0);
			$product->offer_percentage = number_format((int)\Helper::offer_percentage_byID($product->id), 0);
			$product->shop_verified = \Helper::is_verified_seller($product->seller_id);
			$product->AllRating = $this->AllRating($product->id)->original;

			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $product->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$product->title = $langData->title ? $langData->title : $product->title;
				}
			}
		}

		$products = $this->chunkArray($products->toArray(), 2);
		$data['flash_sale_products_ids_array'] = $flash_sale_products_ids_array;
		$data['items'] = $products;
		$data['status'] = \Helper::getsettings('flash_sale_products_status');
		$data['banner'] = \Helper::getsettings('flashsale_products_banner');
		$data['expired_date'] = \Helper::getsettings('expired_date');
		$data['title'] = \Helper::getsettings('flashsale_products_title') ?? 'Flashsale Products';
		
		return response()->json($data, 200);
	}

	public function chunkArray($array, $chunkSize) {
		$chunkedArray = [];
	
		for ($i = 0; $i < count($array); $i += $chunkSize) {
			$chunk = array_slice($array, $i, $chunkSize);
			$chunkedArray[] = $chunk;
		}
	
		return $chunkedArray;
	}
	













	public function getFeaturedProduct(Request $request)
	{
		$data = [];
		$featured_products_ids = \Helper::getsettings('featured_products');
		$featured_products_ids_array = explode(',', $featured_products_ids);

		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$products = Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->where('is_grocery', 'other')
			->whereIn('id', $featured_products_ids_array)
			->with('meta')
			->with('specification')
			->orderby('shuffle_number', 'desc')
			->get();
		foreach ($products as $item) {
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;

			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
			}
		}
		$data['items'] = $products;
		$data['status'] = \Helper::getsettings('featured_products_status');
		$data['title'] = \Helper::getsettings('featured_products_title') ?? 'Featured Products';
		return response()->json($data, 200);
	}
	public function getbestSellingProduct(Request $request){
		$data = [];
		$bestSelling_products_ids = \Helper::getsettings('bestselling_products');

		$bestSelling_products_ids_array = explode(',', $bestSelling_products_ids);
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
;
		$products = Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->where('is_grocery', 'other')
			->whereIn('id', $bestSelling_products_ids_array)
			->with('meta')
			->with('specification')
			->orderby('shuffle_number', 'desc')
			->get();


			

		foreach ($products as $item) {
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
			}
		}
		$data['items'] = $products;
		$data['title'] = \Helper::getsettings('bestselling_products_title') ?? 'Bestselling Products';
		$data['status'] = \Helper::getsettings('bestselling_products_status');
		return response()->json($data, 200);
	}
	public function getmostViewedProduct(Request $request)
	{
		$mostViewed_products_ids = \Helper::getsettings('most_viewed_products');
		$mostViewed_products_ids_array = explode(',', $mostViewed_products_ids);
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$products = Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->where('is_grocery', 'other')
			->whereIn('id', $mostViewed_products_ids_array)
			->with('meta')
			->with('specification')
			->orderby('shuffle_number', 'desc')
			->get();
		foreach ($products as $item) {
			//$item->section_title = \Helper::getsettings('mostvied_products_title');
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;


			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
			}
		}
		$data['items'] = $products;
		$data['title'] = \Helper::getsettings('mostvied_products_title') ?? 'Most viewed Products';
		$data['status'] = \Helper::getsettings('most_viewed_products_status');
		return response()->json($data, 200);
	}
	public function getnewArrivalProduct(Request $request)
	{
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$data = Product::where('is_active', 1)->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")->where('is_deleted', 0)->where('is_promotion', 0)->orderby('shuffle_number', 'desc')->where('product_qc', 1)->limit(18)->get();
		return response()->json($data, 200);
	}
	public function getCart(Request $request)
	{

		if ($request->user_id) {
			$cart = Cart::where('user_id', $request->user_id)->with('product')->get();
			if (count($cart) > 0) {
				$data['status'] = 1;
				$data['cart'] = $cart;
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'No data found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}
	public function checkUuser()
	{
		return 5;
	}
	public function SubCategoryById(Request $request)
	{
		$subCategories = Category::where('is_active', 1)->where('parent_id', $request->id)->where('is_deleted', 0)->get();
		return response()->json($subCategories, 200);
	}

	public function contact(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'phone' => 'required|min:11|max:11',
			'subject' => 'max:70',
			'message' => 'required',
		]);
		if ($request->email) {
			$validator = Validator::make($request->all(), [
				'email' => 'email',
			]);
		}
		if ($validator->fails()) {
			$data['status'] = 0;
			$data['message'] = $validator->errors();
			return response()->json($data, 200);
		}
		$sent = DB::table('contact')->insertGetId([
			'name' => $request->name,
			'phone' => $request->phone,
			'email' => $request->email,
			'subject' => $request->subject,
			'message' =>  $request->message,
		]);
		if ($sent) {
			$data['status'] = 1;
			$data['message'] = 'Message sent successfully.';
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Something went wrong.';
			return response()->json($data, 200);
		}
	}

	public function returnRequest(Request $request)
	{
		$user = auth('customer-api')->user();
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'Please login first.';
			return response()->json($data, 200);
		}

		$validator = Validator::make(
			$request->all(),
			[
				'return_type' => 'required',
				'message' => 'required',
			],
			[
				'return_type.required' => 'Return Type field is required.',
				'message.required' => 'Return or refund cause descripiton field is required.',
			]
		);

		if ($validator->fails()) {
			$data['status'] = 0;
			$data['message'] = $validator->errors();
			return response()->json($data, 200);
		}
		$product =  Product::where('id', $request->product_id)->first();
		$details =  OrderDetails::where('order_id', $request->order_id)->where('product_id', $request->product_id)->first();

		$already = DB::table('product_return')->where('order_id', $request->order_id)->where('product_id', $request->product_id)->first();
		if ($already) {
			$data['status'] = 2;
			$data['message'] = 'You already requested to return.';
			return response()->json($data, 200);
		}

		$sent = DB::table('product_return')->insertGetId([
			'user_id' => $user->id,
			'order_id' => $request->order_id,
			'product_id' => $request->product_id,
			'seller_id' => $product->seller_id,
			'order_details_id' => $details->id,
			'return_type' => $request->return_type,
			'description' =>  $request->message,
			'status' =>  1,
		]);
		$details->status = 11;
		$details->save();
		
		if ($sent) {
			$data['status'] = 1;
			$data['message'] = 'Request sent successfully.';
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Something went wrong.';
			return response()->json($data, 200);
		}
	}





	//User Login Through API
	public function login(Request $request){

		$validator = Validator::make(
			$request->all(),
			[
				'phone' => 'required',
				'password' => 'required'
			],
			[
				'phone.required' => 'Phone number or Email is required.',
			]
		);

		// if ($validator->fails()) {
		// 	$data['message'] = 'Login failed';
		// 	$data['status'] = 0;
		// 	return response()->json($data, 200);
		// }
		if ($validator->fails()) {
			$data['status'] = 0;
			$data['message'] = $validator->errors();
			return response()->json($data, 200);
		}


		try {
			if (filter_var($request->phone, FILTER_VALIDATE_EMAIL)) {
				if ($request->get('phone')) {
					$credentials = ['email' => $request->get('phone'), 'password' => $request->get('password')];
				}
			} else {
				if ($request->get('phone')) {
					$credentials = ['phone' => $request->get('phone'), 'password' => $request->get('password')];
				}
			}

			if (!$token = auth('customer-api')->attempt($credentials)) {
				$data['message'] = 'Your email/phone or password does not match.';
				$data['status'] = 2;
				return response()->json($data, 200);
			}
		} catch (JWTException $e) {
			//return response()->json(['msg' => 'Token creation failed!'], 404);
			$data['message'] = 'Token creation failed!';
			$data['status'] = 2;
			return response()->json($data, 200);
		}
		$user = auth('customer-api')->user();

		if ($device_token = $request->device_token) {
			DB::table('users')->where('id', $user->id)->update(['device_token' => $device_token]);
		}

		if ($session_key = $request->session_key) {
			DB::table('carts')->where('session_key', $session_key)->update(['user_id' => $user->id]);
			DB::table('compares')->where('session_key', $session_key)->update(['user_id' => $user->id]);
			DB::table('wishlists')->where('session_key', $session_key)->update(['user_id' => $user->id]);
		}

		//Send Push Notification 
		$cartNumber = DB::table('carts')->where('user_id', $user->id)->count();

		if ($cartNumber > 0) {
			$message_body = 'You have ' . $cartNumber . ' item(s) in your cart. Please buy them before they run out of stock.';
			$message = ['type' => 'cart', 'message' => $message_body,];
			$title = $cartNumber . 'item(s) in your cart.';
			\Helper::sendPushNotification($user->id, 1, $title, $message_body, json_encode($message));
		}


		
		$total_order = Order::where('user_id', $user->id)->where('status', 6)->count();
	

	

		
		$compares = DB::table('compares')->where('user_id', $user->id)->count();
		if($compares < 1 && $request->session_key){
			$compares = DB::table('compares')->where('session_key', $request->session_key)->count();
		}

		$wishlists = Wishlist::where('user_id', $user->id)->count();
	


		$total_spends = Order::where('user_id', $user->id)->sum('total_amount');
		$loyalty_points = \Helper::UserLoyaltyPoits($user->id);
	


		return response()->json([
			'status' => 1,
			'customer' => $user,
			'compares' => $compares,
			'wishlists' => $wishlists,
			'address' => Addresses::where('user_id', $user->id)->with('division', 'district', 'upazila', 'union')->first(),
			'token' => $token,
			'expire' => auth('customer-api')->factory()->getTTL() * 6000
		], 200);
	}







	public function social_login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'email|required',
		]);

		if ($validator->fails()) {
			return response()->json(['msg' => 'Login failed'], 404);
		}

		$name = $request->input('name');
		$email = $request->input('email');
		$device_token = $request->input('device_token');

		// check if they're an existing user
		$existingCustomer = User::where('email', $email)->first();

		if ($existingCustomer) {
			// log them in
			$token = auth('customer-api')->login($existingCustomer, true);
		} else {
			// create a new user
			$newCustomer = User::create([
				'name' => $name,
				'email' => $email
			]);

			$token = auth('customer-api')->login($newCustomer, true);
		}

		if (!$token) {
			return response()->json(['msg' => 'Your email/phone or password does not match.'], 404);
		}

		$user = auth('customer-api')->user();
		if ($device_token = $request->device_token) {
			DB::table('users')->where('id', $user->id)->update(['device_token' => $device_token]);
		}

		return response()->json([
			'status' => 1,
			'customer' => $user,
			'address' => Addresses::where('user_id', $user->id)->with('division', 'district', 'upazila', 'union')->first(),
			'token' => $token,
			'expire' => auth('customer-api')->factory()->getTTL() * 6000
		], 200);
	}












	//Register
	public function userRegister(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'phone' => 'required|unique:users,phone',
			'email'    => 'email|nullable|unique:users,email',
			'password' => 'required|min:6',
			//'password_confirmation' => 'required_with:password|same:password|min:6'
		]);

		if ($validator->fails()) {
			$data['status'] = 0;
			$data['message'] = $validator->errors();
			return response()->json($data, 200);
		}

		$user = DB::table('users')->insertGetId([
			'name' => $request->name,
			'phone' => $request->phone,
			'email' => $request->email,
			'password' => bcrypt($request->password),
			'affiliate_referer' => $request->affiliate_referer ?? null,
			'status' => 1,
		]);
		//$data['customer'] = User::where('id', $user)->first();
		try {
			if ($request->get('phone')) {
				$credentials = ['phone' => $request->get('phone'), 'password' => $request->get('password')];
			}
			if (!$token = auth('customer-api')->attempt($credentials)) {
				$data['message'] = 'Your email/phone or password does not match.';
				$data['status'] = 2;
				return response()->json($data, 200);
			}
		} catch (JWTException $e) {
			//return response()->json(['msg' => 'Token creation failed!'], 404);
			$data['message'] = 'Token creation failed!';
			$data['status'] = 2;
			return response()->json($data, 200);
		}
		$user = auth('customer-api')->user();
		if ($device_token = $request->device_token) {
			DB::table('users')->where('id', $user->id)->update(['device_token' => $device_token]);
		}
		return response()->json([
			'status' => 1,
			'customer' => $user,
			'address' => Addresses::where('user_id', $user->id)->with('division', 'district', 'upazila', 'union')->first(),
			'token' => $token,
			'expire' => auth('customer-api')->factory()->getTTL() * 6000
		], 200);
	}


	//Vendor Register
	public function vendorRegister(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required | max:191 | string',
			'email' => 'unique:admins',
			'phone' => 'required | max:15 | unique:admins',
			'shop_name' => 'required',
			'shop_division' => 'required',
			'shop_district' => 'required',
			'shop_area' => 'required',
			'shop_union' => 'required',
			'password' => 'required|min:6',
			'password_confirmation' => 'required_with:password|same:password|min:6'

		]);



		if ($validator->fails()) {
			$data['status'] = 0;
			$data['message'] = $validator->errors();
			return response()->json($data, 200);
		}


		$vendor = new Admins();
		if ($request->nid_front_side) {
			$imageName = 'media/' . uniqid() . '.' . $request->nid_front_side->getClientOriginalExtension();
			$request->nid_front_side->move(public_path('media/'), $imageName);
			$vendor->nid_front_side = $imageName;
		}

		if ($request->nid_back_side) {
			$imageName = 'media/' . uniqid() . '.' . $request->nid_back_side->getClientOriginalExtension();
			$request->nid_back_side->move(public_path('media/'), $imageName);
			$vendor->nid_back_side = $imageName;
		}

		if ($request->profile_picture) {
			$imageName = 'media/' . uniqid() . '.' . $request->profile_picture->getClientOriginalExtension();
			$request->profile_picture->move(public_path('media/'), $imageName);
			$vendor->avatar = $imageName;
		}

		$vendor->name = $request->name;
		$vendor->email = $request->email;
		$vendor->phone = $request->phone; 
		$vendor->password = Hash::make($request->password);
		$vendor->status = 0;
		$vendor->assignRole(['seller']);
		$vendor->save();

		$shopInfo = new ShopInfo;
		if ($request->shop_logo) {
			$uploadedImage = $request->file('shop_logo');
			$imageTitle = uniqid();
			$imageName = $imageTitle . '.' . $request->shop_logo->getClientOriginalExtension();
			$image_resize = Image::make($uploadedImage->getRealPath());
			$image_resize->encode($request->shop_logo->getClientOriginalExtension(), 65);
			$image_resize->save(public_path('/media/' . $imageName));
			$image_thumbnail = Image::make($uploadedImage->getRealPath())->fit(400, 400);
			$image_thumbnail->save(public_path('/media/thumbnail/media/' . $imageName), 60);
			$shopInfo->logo = 'media/' . $imageName;
		}

		if ($request->shop_banner) {
			$imageName = 'media/' . uniqid() . '.' . $request->shop_banner->getClientOriginalExtension();
			$request->shop_banner->move(public_path('media/'), $imageName);
			$shopInfo->banner = $imageName;
		}

		if ($request->trade_license) {
			$imageName = 'media/' . uniqid() . '.' . $request->trade_license->getClientOriginalExtension();
			$request->trade_license->move(public_path('media/'), $imageName);
			$shopInfo->trade_license = $imageName;
		}

		$shopInfo->seller_id = $vendor->id;
		$shopInfo->name = $request->shop_name;
		$shopInfo->slug = \Helper::generateUniqueSlug('seller', 'title', $request->shop_name);
		$shopInfo->phone = $request->shop_phone;
		$shopInfo->email = $request->shop_email;
		$shopInfo->division = $request->shop_division;
		$shopInfo->district = $request->shop_district;
		$shopInfo->area = $request->shop_area;
		$shopInfo->shop_union = $request->shop_union;
		$shopInfo->address = $request->address;

		//Bank Account
		$shopInfo->bank_name = $request->bank_name;
		$shopInfo->account_name = $request->account_name;
		$shopInfo->account_number = $request->account_number;
		$shopInfo->routing_number = $request->routing_number;
		$shopInfo->bkash = $request->bkash;
		$shopInfo->rocket = $request->rocket;
		$shopInfo->nagad = $request->nagad;
		$shopInfo->upay = $request->upay;
		$shopInfo->save();


		$nData = [];
		$message = [
			'type' => 'seller account',
			'message' => 'Seller account has been registered successfully'
		];
		$nData['user_id'] = $vendor->id;
		$nData['user_type'] = 2;
		$nData['title'] = 'Seller account created.';
		$nData['description'] = json_encode($message);
		$nData['status'] = 1;
		\DB::table('notifications')->insert($nData);

		$data['status'] = 1;
		$data['message'] = 'Seller account has been registered successfully.';
		return response()->json($data, 200);
	}















	//Corporate user Register
	public function corporateUserRegister(Request $request){
		$validator = Validator::make($request->all(), [
			'user_name' => 'required | max:191 | string',
			'company_name' => 'required | max:191 | string',
			//'user_email' => 'required',
			'dbid' => 'required',
			//'company_email' => 'required',
			'user_phone' => 'required | max:15',
			'company_phone' => 'required | max:15',
			'company_division' => 'required',
			'company_district' => 'required',
			'company_area' => 'required',
			'company_union' => 'required',
			'password' => 'required|min:6',
			'password_confirmation' => 'required_with:password|same:password|min:6'
		]);
		if ($validator->fails()) {
			$data['status'] = 0;
			$data['message'] = $validator->errors();
			return response()->json($data, 200);
		}

		$already = User::where('email', $request->user_email)->orWhere('phone', $request->user_phone)->first();
		if($already){
			$data['status'] = 5;
			$data['message'] = 'User email or user phone already exist.';
			return response()->json($data, 200);
		}else{
			$user = new User();
			if ($request->corporate_user_picture) {
				$imageName = 'media/' . uniqid() . '.' . $request->corporate_user_picture->getClientOriginalExtension();
				$request->corporate_user_picture->move(public_path('media/'), $imageName);
				$user->avatar = $imageName;
			}
			$user->name = $request->user_name;
			$user->email = $request->user_email;
			$user->phone = $request->user_phone;
			$user->password = Hash::make($request->password);
			$user->status = 0;
			$user->user_type = 2;
			$user->save();
			$new_user_id = $user->id;
			
			if ($request->company_name) {
				$meta = new UsersMeta;
				$meta->user_id = $new_user_id;
				$meta->meta_key = 'company_name';
				$meta->meta_value = $request->company_name;
				$meta->save();
			}
			if ($request->company_phone) {
				$meta = new UsersMeta;
				$meta->user_id = $new_user_id;
				$meta->meta_key = 'company_phone';
				$meta->meta_value = $request->company_phone;
				$meta->save();
			}
			if ($request->company_email) {
				$meta = new UsersMeta;
				$meta->user_id = $new_user_id;
				$meta->meta_key = 'company_email';
				$meta->meta_value = $request->company_email;
				$meta->save();
			}
			if ($request->company_division) {
				$meta = new UsersMeta;
				$meta->user_id = $new_user_id;
				$meta->meta_key = 'company_division';
				$meta->meta_value = $request->company_division;
				$meta->save();
			}
			if ($request->company_district) {
				$meta = new UsersMeta;
				$meta->user_id = $new_user_id;
				$meta->meta_key = 'company_district';
				$meta->meta_value = $request->company_district;
				$meta->save();
			}
			if ($request->company_area) {
				$meta = new UsersMeta;
				$meta->user_id = $new_user_id;
				$meta->meta_key = 'company_area';
				$meta->meta_value = $request->company_area;
				$meta->save();
			}
			if ($request->company_union) {
				$meta = new UsersMeta;
				$meta->user_id = $new_user_id;
				$meta->meta_key = 'company_union';
				$meta->meta_value = $request->company_union;
				$meta->save();
			}
			if ($request->company_address) {
				$meta = new UsersMeta;
				$meta->user_id = $new_user_id;
				$meta->meta_key = 'company_address';
				$meta->meta_value = $request->company_address;
				$meta->save();
			}
			if ($request->dbid) {
				$meta = new UsersMeta;
				$meta->user_id = $new_user_id;
				$meta->meta_key = 'dbid';
				$meta->meta_value = $request->dbid;
				$meta->save();
			}
			

			$nData = [];
			$message = [
				'type' => 'Corporate user account',
				'message' => 'Corporate user account has been registered successfully'
			];
			$nData['user_id'] = $new_user_id;
			$nData['user_type'] = 2;
			$nData['title'] = 'Corporate user account created.';
			$nData['description'] = json_encode($message);
			$nData['status'] = 1;
			\DB::table('notifications')->insert($nData);
			$data['status'] = 1;
			$data['message'] = 'Corporate user account has been registered successfully.';
			return response()->json($data, 200);
		}
	}

	







	public function getSingleAddress($address_id)
	{
		$user = auth('customer-api')->user();
		if ($user) {
			$address = Addresses::find($address_id);
			if ($address) {

				$selected['divisons'] = DB::table('divisions')->where('status', 1)->get();
				$selected['district'] = DB::table('districts')->where('division_id', $address->shipping_division)->get();
				$selected['upazila'] = DB::table('upazilas')->where('district_id', $address->shipping_district)->get();
				$selected['union'] = DB::table('unions')->where('upazila_id', $address->shipping_thana)->get();
				$data['status'] = 1;
				$data['selected_address'] = $selected;
				$data['singleAddress'] = $address;
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Address not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}
	public function getSelectedCorporateAddress(Request $request){
		if($request->division){
			$selected['district'] = DB::table('districts')->where('division_id', $request->division)->get();
		}
		if($request->district){
			$selected['upazila'] = DB::table('upazilas')->where('district_id', $request->district)->get();
		}
		if($request->upazila){
			$selected['union'] = DB::table('unions')->where('upazila_id', $request->upazila)->get();
		}
		if($selected['district'] || $selected['upazila'] || $selected['union']){
			$data['status'] = 1;
			$data['selected_address'] = $selected;
			return response()->json($data, 200);
		}else{
			$data['status'] = 0;
			$data['selected_address'] = null;
			return response()->json($data, 200);
		}
	}

	public function addNewAddress(Request $request)
	{

		$user = auth('customer-api')->user();
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}


		$validator = Validator::make(
			$request->all(),
			[
				'shipping_first_name' => 'required',
				'selected_division' => 'required',
				'selected_district' => 'required',
				'selected_upazila' => 'required',
				'selected_union' => 'required',
				'shipping_phone' => 'required',
				'shipping_address' => 'required|max:100',

			],
			[
				'selected_division.required' => 'Please select any division',
				'selected_district.required' => 'Please select any district',
				'selected_upazila.required' => 'Please select any Upazila/Thana',
				'selected_union.required' => 'Please select any Union/Area',
			]
		);

		if ($validator->fails()) {
			$data['status'] = 0;
			$data['message'] = $validator->errors();
			return response()->json($data, 200);
		}



		if ((int)$request->address_id > 0) {
			$address = Addresses::find($request->address_id);
			$address->user_id = $user->id;
			$address->shipping_first_name = $request->shipping_first_name;
			$address->shipping_last_name =  null;
			$address->shipping_address = $request->shipping_address;
			$address->shipping_division = (int)$request->selected_division;
			$address->shipping_district = (int)$request->selected_district;
			$address->shipping_thana =  (int)$request->selected_upazila;
			$address->shipping_union =  (int)$request->selected_union;
			//$address->shipping_postcode = $request->shipping_postcode;
			$address->shipping_phone = $request->shipping_phone;
			$address->shipping_email =  $request->shipping_email ?? null;
			if ($request->address_id) {
				$address->save();
				$data['status'] = 1;
				$data['message'] = 'Address updated successfully.';
				return response()->json($data, 200);
			}
		}else {
			$newAddressData['user_id'] =  $user->id;
			$newAddressData['shipping_first_name'] = $request->shipping_first_name;
			$newAddressData['shipping_last_name'] = null;
			$newAddressData['shipping_address'] = $request->shipping_address;
			$newAddressData['shipping_division'] =(int)$request->selected_division;
			$newAddressData['shipping_district'] =(int)$request->selected_district;
			$newAddressData['shipping_thana'] =(int)$request->selected_upazila;
			$newAddressData['shipping_union'] =(int)$request->selected_union;
			$newAddressData['shipping_phone'] = $request->shipping_phone;
			$newAddressData['shipping_email'] = $request->shipping_email;
			$addressID = DB::table('addresses')->insertGetId($newAddressData);
			
			DB::table('users')->where('id', $user->id)->update(['default_address_id' => $addressID]);
			$data['status'] = 1;
			$data['message'] = 'New address has been added successfully.';
			return response()->json($data, 200);
		}


	}


	public function deleteAddress($address_id)
	{
		$user = auth('customer-api')->user();
		if ($user && $address_id) {

			$address = Addresses::find($address_id);
			if ($address) {
				\DB::table('addresses')
					->where('user_id', $user->id)
					->where('id', $address_id)
					->update(['is_deleted' => 1]);

				$data['status'] = 1;
				$data['message'] = 'Address deleted successfully.';
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Address not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}


	public function get_user_details(Request $request)
	{


		$userchekc = auth('customer-api')->user();
		$user = User::where('id', $userchekc->id)->with('meta')->first();
		// foreach ($user->meta as $item) {
		// 	$item->meta_key = ucwords(str_replace('_', ' ', $item->meta_key));
		// }

		if ($user) {

			$total_order = Order::where('user_id', $user->id)->where('status', 6)->count();
			$order_details = OrderDetails::where('user_id', $user->id)->get();
			$total_spends = Order::where('user_id', $user->id)->where('status', 6)->sum('total_amount');

			$loyalty_points = \Helper::UserLoyaltyPoits($user->id);

			// $loyalty_point_validity_days = \Helper::getSettings('loyalty_point_validity_days') ?? 365;

			// foreach ($order_details as $row) {
			// 	$date = $row->updated_at;
   //  			$after_add_date = date('Y-m-d', strtotime($date. ' + '.$loyalty_point_validity_days.' days'));
    			
   //  			// return response()->json($after_add_date, 200);

   //  			if ($date <= $after_add_date) {
   //  				$loyalty_points += $row->loyalty_point;
   //  			}
			// }



			$user->total_order = $total_order;
			$user->total_spends = $total_spends;
			$user->loyalty_points = $loyalty_points;



			foreach ($user->meta as $item) {
				if($item->meta_key == 'company_division'){
					$item->division_title = \Helper::get_division_title_by_id((int)$item->meta_value);
				}
				if($item->meta_key == 'company_district'){
					$item->district_title = \Helper::get_district_title_by_id((int)$item->meta_value);
				}
				if($item->meta_key == 'company_area'){
					$item->upazila_title = \Helper::get_upazila_title_by_id((int)$item->meta_value);
				}
				if($item->meta_key == 'company_union'){
					$item->union_title = \Helper::get_union_title_by_id((int)$item->meta_value);
				}
			}


			return response()->json($user, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}
	public function update_user_details(Request $request){
		$user = auth('customer-api')->user();
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}


		if ($request->email == 'undefined') {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'phone'    => 'nullable|unique:users,phone',
			]);
		} else {
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'phone'    => 'nullable|unique:users,phone',
				'email'    => 'nullable|email|unique:users,email',
			]);
		}



		if ($validator->fails()) {
			$data['status'] = 0;
			$data['message'] = $validator->errors();
			return response()->json($data, 200);
		}
		if ($request->image) {
			$imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();
			$request->image->move(public_path('/media/'), $imageName);
			$data['avatar'] = 'media/' . $imageName;
		}

		if ($user->id) {
			$data['name']  = $request->name ?? null;

			if (!$user->phone) {
				$data['phone'] = $request->phone ?? null;
			}

			if (!$user->email) {
				$data['email'] = $request->email ?? null;
			}

			if ($request->company_name) {
				UsersMeta::where('user_id', $user->id)->where('meta_key', 'company_name')->update(['meta_value' => $request->company_name]);
			}
			if ($request->company_phone) {
				UsersMeta::where('user_id', $user->id)->where('meta_key', 'company_phone')->update(['meta_value' => $request->company_phone]);
			}
			if ($request->company_email) {
				UsersMeta::where('user_id', $user->id)->where('meta_key', 'company_email')->update(['meta_value' => $request->company_email]);
			}
			if ($request->company_division) {
				UsersMeta::where('user_id', $user->id)->where('meta_key', 'company_division')->update(['meta_value' => $request->company_division]);
			}
			if ($request->district_title) {
				UsersMeta::where('user_id', $user->id)->where('meta_key', 'company_district')->update(['meta_value' => $request->district_title]);
			}
			if ($request->upazila_title) {
				UsersMeta::where('user_id', $user->id)->where('meta_key', 'company_area')->update(['meta_value' => $request->upazila_title]);
			}
			if ($request->union_title) {
				UsersMeta::where('user_id', $user->id)->where('meta_key', 'company_union')->update(['meta_value' => $request->union_title]);
			}
			if ($request->union_title) {
				UsersMeta::where('user_id', $user->id)->where('meta_key', 'company_union')->update(['meta_value' => $request->union_title]);
			}
			if ($request->dbid) {
				UsersMeta::where('user_id', $user->id)->where('meta_key', 'dbid')->update(['meta_value' => $request->dbid]);
			}
			
			$update = DB::table('users')->where('id', $user->id)->update($data);
			$user = User::where('id', $user->id)->with('meta')->first();

			// foreach ($user->meta as $item) {
			// 	$item->meta_key = ucwords(str_replace('_', ' ', $item->meta_key));
			// }

			$d['status'] = 1;
			$d['message'] = 'Update success.';
			$d['user'] = $user;
			return response()->json($d, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}

	//Wishlist


	public function initWishlist(Request $request)
	{
		$user = auth('customer-api')->user();
		$session_key = $request->session_key;
		if ($user || $session_key) {

			if ($user) {
				$wishlistData = Wishlist::where('user_id', $user->id)->with('product')->with('meta')->get();
			} else {
				$wishlistData = Wishlist::where('session_key', $session_key)->with('product')->with('meta')->get();
			}

			foreach ($wishlistData as $single_item) {
				$single_item->price_before_offer = (int)\Helper::price_before_offer($single_item->product_id);
				$single_item->price = (int)\Helper::price_after_offer($single_item->product_id);
				$single_item->offer_percentage = (int)number_format(\Helper::offer_percentage_byID($single_item->product_id), 0);
			}

			$w = (int)count($wishlistData);
			$data['status'] = 1;
			$data['wishlist'] = $wishlistData;
			$data['total'] = $w > 0 ?$w:0;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}

	public function addToWishlist(Request $request)
	{
		$session_key = $request->session_key;
		$user = auth('customer-api')->user();
		if ($user || $session_key) {

			if ($user) {
				$already = Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
			} else {
				$already = Wishlist::where('session_key', $session_key)->where('product_id', $request->product_id)->first();
			}

			if ($already) {
				$data['status'] = 0;
				$data['message'] = 'Already exist in your wishlist.';
				return response()->json($data, 200);
			} else {
				$data['user_id'] = $user->id ?? null;
				$data['session_key'] = $session_key ?? null;
				$data['product_id'] = $request->product_id;
				Wishlist::insert($data);
				$d['status'] = 1;
				$d['wishlists'] = Wishlist::where('user_id', $data['user_id'])->orWhere('session_key', $data['session_key'])->count();
				$d['message'] = 'Product added to wishlist.';
				return response()->json($d, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}


	public function removeWishlist(Request $request)
	{
		$session_key = $request->session_key;
		$user = auth('customer-api')->user();
		if ($user || $session_key) {
			if ($wishlist_id = $request->wishlist_id) {
				if ($user) {
					$delete = Wishlist::where('user_id', $user->id)->where('id', $wishlist_id)->delete();
					$wishlist = Wishlist::where('user_id', $user->id)->with('product')->count();
				} else {
					$delete = Wishlist::where('session_key', $session_key)->where('id', $wishlist_id)->delete();
					$wishlist = Wishlist::where('session_key', $session_key)->with('product')->count();
				}

			// foreach ($wishlist as $single_item) {
			// 	$single_item->price_before_offer = (int)\Helper::price_before_offer($single_item->product_id);
			// 	$single_item->price = (int)\Helper::price_after_offer($single_item->product_id);
			// 	$single_item->offer_percentage = (int)number_format(\Helper::offer_percentage_byID($single_item->product_id), 0);
			// }



				if ($delete) {
					$data['status'] = 1;
					$data['wishlist'] = $wishlist;
					$data['message'] = 'Product has been removed from your wishlist..';
					return response()->json($data, 200);
				} else {
					$data['status'] = 0;
					$data['wishlist'] = $wishlist;
					$data['message'] = 'System could not delete wishlist product. Please try again later!';
					return response()->json($data, 200);
				}
			} else {
				$data['status'] = 0;
				$data['message'] = 'Wishlist product not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}



	//Compare
	public function addToCompare(Request $request)
	{
		$session_key = $request->session_key;
		$user = auth('customer-api')->user();
		if ($user || $session_key) {
			if ($user) {
				$three = Compare::where('user_id', $user->id)->get();
			} else {
				$three = Compare::where('session_key', $session_key)->get();
			}

			if (count($three) > 2) {
				$data['status'] = 0;
				$data['message'] = 'You can add 3 products in your compare list.';
				return response()->json($data, 200);
			}

			if ($user) {
				$already = Compare::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
				
			} else {
				$already = Compare::where('session_key', $session_key)->where('product_id', $request->product_id)->first();
			}

			if ($already) {
				$data['status'] = 0;
				$data['message'] = 'Already exist in your compare list.';
				return response()->json($data, 200);
			} else {
				$data['user_id'] = $user->id ?? null;
				$data['session_key'] = $session_key ?? null;
				$data['product_id'] = $request->product_id;
				Compare::insert($data);
				$d['status'] = 1;
				
				
				
				$d['compares'] = Compare::where('product_id', $request->product_id)->where('user_id', $user->id)->orwhere('session_key', $session_key)->count();
				



				
				$d['message'] = 'Product added to compare list.';
				return response()->json($d, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}

	public function checkCompare(Request $request)
	{
		$user = auth('customer-api')->user();
		if ($user) {
			if ($request->product_id && $user->id) {
				$already = Compare::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
				if ($already) {
					return response()->json('1', 200);
				} else {
					return response()->json('This product is available to add in compare list.', 200);
				}
			} else {
				return response()->json('User or product not found.', 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}


	public function initCompare(Request $request)
	{
		$user = auth('customer-api')->user();
		$session_key = $request->session_key;
		if ($user || $session_key) {
			if ($user) {
				$compare = Compare::where('user_id', $user->id)->with('product')->with('meta')->with('specification')->get();
			} else {
				$compare = Compare::where('session_key', $session_key)->with('product')->with('meta')->with('specification')->get();
			}

			foreach ($compare as $item) {
				if ($item->product) {
					$item->product->price_after_offer = number_format((int)\Helper::price_after_offer($item->product->id), 0);
					$item->product->offer_percentage = number_format(\Helper::offer_percentage_byID($item->product->id));
					$item->shop_verified = \Helper::is_verified_seller($item->product->id);
					$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
				}
				if ($item->meta) {
					foreach ($item->meta as $metas) {
						if ($metas->meta_key == 'custom_options') {
							$metas->decoded_custom_options = unserialize($metas->meta_value);
						}
						if ($metas->meta_key == 'product_miscellaneous_information') {
							$metas->decoded_miscellaneous_information = unserialize($metas->meta_value);
						}

						if ($metas->meta_key == 'product_shipping_option') {
							$metas->decoded_shipping_option = unserialize($metas->meta_value);
						}
					}
				}
			}
			$data['status'] = 1;
			$data['compares'] = $compare;
			$data['total'] = $compare ? count($compare) : 0;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}



	public function removeCompare(Request $request)
	{
		$session_key = $request->session_key;
		$user = auth('customer-api')->user();
		if ($user || $session_key) {
			if ($request->product_id) {
				if ($user) {
					$already = Compare::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
					
				} else {
					$already = Compare::where('session_key', $session_key)->where('product_id', $request->product_id)->first();
				}

				if ($already) {
					$res = false;
					if ($user) {
						$res = Compare::where('user_id', $user->id)->where('product_id', $request->product_id)->delete();
						$total = Compare::where('user_id', $user->id)->where('product_id', $request->product_id)->count();
					} else {
						$res = Compare::where('session_key', $session_key)->where('product_id', $request->product_id)->delete();
						$total = Compare::where('session_key', $session_key)->where('product_id', $request->product_id)->count();
					}

					if ($res) {
						$data['status'] = 1;
						$data['total'] = $total;
						$data['message'] = 'Compare item has been removed successfully';
						return response()->json($data, 200);
					} else {
						$data['status'] = 0;
						$data['message'] = 'Unable to remove compare item!';
						return response()->json($data, 200);
					}
				} else {
					$data['status'] = 0;
					$data['message'] = 'Product not found.';
					return response()->json($data, 200);
				}
			} else {
				return response()->json('Product not found.', 200);
				$data['status'] = 0;
				$data['message'] = 'You have to login first to avail this option.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}



	public function initvoucher()
	{
		return response()->json('success from api controller', 200);
	}


	//Review
	public function review(Request $request)
	{
		$user = auth('customer-api')->user();
		if ($user && $request->product_id) {
			$user = User::where('id', $user->id)->where('status', 1)->where('is_deleted', 0)->first();
			$product = Product::where('id', $request->product_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->first();
			if ($user && $product) {
				if ($request->comment || $request->rate) {

					if ($request->hasfile('files')) {
						$allImage = [];
						foreach ($request->file('files') as $file) {
							$name = time() . rand(1, 100) . '.' . $file->extension();
							$file->move(public_path('uploads/comments'), $name);
							$allImage[] = 'uploads/comments/' . $name;
						}
						$comments_data['image'] =  implode(',', $allImage);
					} else {
						$comments_data['image'] =  [];
					}
					$comments_data['comment'] =  $request->comment;
					$user->comment($product, serialize($comments_data), $request->rate);
					if($request->require_moderation != 1){
						$product->comments[0]->approve();
					}
					OrderDetails::where('id', $request->order_details_id)->update(['reviewed' => 1]);
					return response()->json('1', 200);
				} else {
					return response()->json('Please rate or comment for this product.', 200);
				}
			} else {
				return response()->json('User or product not found.', 200);
			}
		} else {
			return response()->json('Something went wrong.', 200);
		}
	}
	public function AllRating($productId)
	{
		$product = Product::where('id', $productId)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->first();
		if ($product) {
			$data['average'] = $product->averageRate() > 0 ? $product->averageRate() : 0;

			$total_rating = DB::table('comments')->where('parent_id', null)->where('commentable_id', $productId)->where('approved', 1)->count();
		
			$data['total_rating'] = $total_rating > 0 ? $total_rating : 1;
			$data['total_rate'] = $total_rating > 0 ? $total_rating : 0;

		} else {
			$data['average'] =  0;
			$data['total_rating'] = 1;
			$data['total_rate'] = 0;
		}



		$data['five_star']  = DB::table('comments')->where('commentable_id', $productId)->where('approved', 1)->where('rate', 5)->count();
		$data['five_star_percentage'] = 'width:' . number_format($data['five_star'] / $data['total_rating'] * 100, 0, '.', '') . '%;';
		$data['four_star']  = DB::table('comments')->where('commentable_id', $productId)->where('approved', 1)->where('rate', 4)->count();
		$data['four_star_percentage'] = 'width:' . number_format($data['four_star'] / $data['total_rating'] * 100, 0, '.', '') . '%;';
		$data['three_star']  = DB::table('comments')->where('commentable_id', $productId)->where('approved', 1)->where('rate', 3)->count();
		$data['three_star_percentage'] = 'width:' . number_format($data['three_star'] / $data['total_rating'] * 100, 0, '.', '') . '%;';
		$data['two_star']  = DB::table('comments')->where('commentable_id', $productId)->where('approved', 1)->where('rate', 2)->count();
		$data['two_star_percentage'] = 'width:' . number_format($data['two_star'] / $data['total_rating'] * 100, 0, '.', '') . '%;';
		$data['one_star']  = DB::table('comments')->where('commentable_id', $productId)->where('approved', 1)->where('rate', 1)->count();
		$data['one_star_percentage'] = 'width:' . number_format($data['one_star'] / $data['total_rating'] * 100, 0, '.', '') . '%;';
		$data['average_percentage'] = 'width:' . ($data['average'] / 5 * 100) . '%;';


		return response()->json($data, 200);
	}

	public function sellerProductsBySlug($seller_slug){


		$seller = DB::table('shop_info')->where('slug', $seller_slug)->first();

		if ($seller->seller_id) {
			
			$data['profile'] = Admins::where('id', $seller->seller_id)->with('shopinfo')->first();
			$products = Product::where('seller_id', $seller->seller_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->where('is_promotion', 0)->orderby('shuffle_number', 'desc')->get();
			$product_ids = [];
			foreach ($products as $item) {
				$product_ids[] = $item->id;
			}

			$seller_products = Product::where('seller_id', $seller->seller_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->where('is_promotion', 0)->orderby('shuffle_number', 'desc')->paginate(18);
			foreach ($seller_products as $item) {
				$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
				$item->price = number_format((int)$item->price, 0);
				$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
				$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
				$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
			}

			$reviewData = [];
			$reviews = DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->groupBy('id')->get();
			$reveiwed_products = [];
			$average = 0;

			if (count($reviews) > 0) {
				foreach ($reviews as $item) {
					$average +=  $item->rate;
					$pro =  \Helper::get_product_by_id($item->commentable_id);
					$pro->rating = \Helper::reveiwByProductId($item->commentable_id);
					$reveiwed_products[] = $pro;
				}
				$average = $average / count($reviews);
				$reviewData['average_rating'] =  number_format($average, 2);
				$reviewData['total_ratings'] = count($reviews);
				$reviewData['average_percentage'] = 'width:' . ($average / 5 * 100) . '%;';
				$reviewData['five_star']  = DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 5)->count();
				$reviewData['five_star_percentage'] = 'width:' . number_format($reviewData['five_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				$reviewData['four_star']  = DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 4)->count();
				$reviewData['four_star_percentage'] = 'width:' . number_format($reviewData['four_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				$reviewData['three_star']  = DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 3)->count();
				$reviewData['three_star_percentage'] = 'width:' . number_format($reviewData['three_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				$reviewData['two_star']  = DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 2)->count();
				$reviewData['two_star_percentage'] = 'width:' . number_format($reviewData['two_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				$reviewData['one_star']  = DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('rate', 1)->count();
				$reviewData['one_star_percentage'] = 'width:' . number_format($reviewData['one_star'] / $reviewData['total_ratings'] * 100, 0, '.', '') . '%;';
				foreach ($reviews as $item) {
					$item->product =  \Helper::get_product_by_id($item->commentable_id);
				}
			}else{
				$reviewData['average_rating'] = number_format(0, 2);
				$reviewData['total_ratings'] = 0;
				$reviewData['average_percentage'] = 'width:' . 0 . '%;';
				$reviewData['five_star']  = 0;
				$reviewData['five_star_percentage'] = 'width:' . 0 . '%;';
				$reviewData['four_star']  = 0;
				$reviewData['four_star_percentage'] = 'width:' . 0 . '%;';
				$reviewData['three_star']  = 0;
				$reviewData['three_star_percentage'] = 'width:' . 0 . '%;';
				$reviewData['two_star']  = 0;
				$reviewData['two_star_percentage'] = 'width:' . 0 . '%;';
				$reviewData['one_star']  = 0;
				$reviewData['one_star_percentage'] = 'width:' . 0 . '%;';
			}
			$data['ratings'] = $reviewData;
			$data['product'] = $seller_products;
			return response()->json($data, 200);
		} else {
			$data['status'] = 1;
			$data['product'] = null;
			$data['message'] = 'product not found.';
			return response()->json($data, 200);
		}
	}


	public function get_seller_product_comments($seller_slug)
	{
		$seller = DB::table('shop_info')->where('slug', $seller_slug)->first();

		$data['reveiwed_products'] = '';
		$data['seller_ratings_info'] = '';

		if ($seller->seller_id) {
			$products = Product::where('seller_id', $seller->seller_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->orderby('shuffle_number', 'desc')->get();
			$product_ids = [];
			foreach ($products as $item) {
				$product_ids[] = $item->id;

			}
			$reviews = DB::table('comments')->whereIn('commentable_id', $product_ids)->where('approved', 1)->where('approved', 1)->groupBy('id')->paginate(20);
			
			$reveiwed_products = [];
			$average = 0;
			$data['seller_ratings_info'] = \Helper::sellerRatingCalculation($seller->seller_id);


			if (count($reviews) > 0) {
				foreach ($reviews as $item) {
					$average +=  $item->rate;
					$pro =  \Helper::get_product_by_id($item->commentable_id);
					$pro->rating = \Helper::reveiwByProductId($item->commentable_id);
					$reveiwed_products[] = $pro;
					$unserialize_comnt = unserialize($item->comment);
					$item->comment_text = $unserialize_comnt['comment'];
					if (array_key_exists('image', $unserialize_comnt)) {
						if ($unserialize_comnt['image']) {
							$item->image = explode(",", $unserialize_comnt['image']);
						} else {
							$item->image = [];
						}
					}
					$user = User::where('id', $item->commented_id)->first();
					$item->user_name = $user->name ?? '';
				}
				foreach ($reviews as $item) {
					$item->product =  \Helper::get_product_by_id($item->commentable_id);
				}


				$data['reveiwed_products'] = $reviews;

				return response()->json($data, 200);
			} else {
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 1;
			$data['message'] = 'product not found.';
			return response()->json($data, 200);
		}
	}



	public function getSellerRating($slug)
	{
		$data['product'] = $slug;
		$data['average'] = 3;
		return response()->json($data, 200);
	}


	public function pageContent($slug)
	{
		$content = DB::table('pages')->where('slug', $slug)->first();
		if ($content) {
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = PageLocalization::where('page_id', $content->id)->where('lang_code', $lang)->first();
				if ($langData) {
					$content->title = $langData->title ? $langData->title : $content->title;
					$content->description = $langData->description ? $langData->description : $content->description;
				}
			}
			$data['status'] = 1;
			$data['content'] = $content;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Content not found.';
			return response()->json($data, 200);
		}
	}
	public function staticPages()
	{
		$data['privacy_policy']  =  \Helper::getsettings('privacy_policy');
		$data['return_policy']  =  \Helper::getsettings('return_policy');
		$data['terms_of_use']  =  \Helper::getsettings('terms_of_use');
		return response()->json($data, 200);
	}




	public function reveiwByProductId($productId)
	{
		$comments = DB::table('comments')->where('commentable_id', $productId)->where('approved', 1)->where('parent_id', NULL)->paginate(10);
		foreach ($comments as $key => $item) {
			$unserialize_comnt = unserialize($item->comment);
			$item->comment_text = $unserialize_comnt['comment'];

			if (array_key_exists('image', $unserialize_comnt)) {
				if ($unserialize_comnt['image']) {
					$item->image = explode(",", $unserialize_comnt['image']);
				} else {
					$item->image = [];
				}
			}
			$user = User::where('id', $item->commented_id)->first();
			$item->user_name = $user->name ?? '';
			$rep = DB::table('comments')->where('parent_id', $item->id)->where('approved', 1)->get();

			foreach ($rep as $k => $v) {
				$unserialize_comnt = unserialize($v->comment);
				$v->comment_text = $unserialize_comnt['comment'];

				if (array_key_exists('image', $unserialize_comnt)) {
					if ($unserialize_comnt['image']) {
						$v->image = $unserialize_comnt['image'];
					} else {
						$v->image = [];
					}
				}



				$admin = Admins::where('id', $v->commented_id)->first();

				if ($admin->hasRole('seller')) {
					$seller = Admins::where('id', $v->commented_id)->with('shopinfo')->first();
					$v->user_name = $seller->shopinfo->name ?? '';
				} else {
					$v->user_name = config('concave.cnf_appname') ?? 'Administrator';
				}
			}

			$item->replay = $rep;
		}
		return response()->json($comments, 200);
	}





	public function checkReviewed(Request $request)
	{
		if ($request->product_id && $request->user_id) {
			$already = DB::table('comments')->where('commentable_id', $request->product_id)->where('approved', 1)->where('commented_id', $request->user_id)->first();
			if ($already) {
				$data['message'] = 'You already reviewed this product.';
				return response()->json($data, 200);
			} else {
				$data['message'] = '1';
				return response()->json($data, 200);
			}
		} else {
			return response()->json('User or product not found.', 200);
		}
	}
	public function getNotifications()
	{
		$user = auth('customer-api')->user();
		if ($user) {
			$notifications = DB::table('notifications')->where('user_id', $user->id)->where('status', 1)->orderBy('id', 'desc')->get();
			if (count($notifications) > 0) {
				return response()->json($notifications, 200);
			} else {
				return response()->json('Nothing to notify.', 200);
			}
		} else {
			$data['status'] = '0';
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}

	public function getUserwiseNotifications()
	{

		$user = auth('customer-api')->user();
		if ($user) {
			$notifications = DB::table('notifications')->where('user_type', 1)->where('user_id', $user->id)->where('status', 1)->orderby('id', 'desc')->get();
			if (count($notifications) > 0) {

				foreach ($notifications as $item) {
					$item->decoded_description = json_decode($item->description);
				}

				$data['status'] = '1';
				$data['notification'] = $notifications;
				$data['notification_total'] = count($notifications);
				return response()->json($data, 200);
			} else {
				$data['status'] = '0';
				$data['notification'] = 'Nothing to notify.';
				$data['notification_total'] = 0;
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = '0';
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}


	public function getSimillarProduct(Request $request)
	{
		$produt_id = $request->produt_id ?? null;
		$categoryId = $request->categoryId ?? null;
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$simillarProducts = Product::where('category_id', $categoryId)->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")->where('id', '!=', $produt_id)
			->where('is_active', 1)
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->where('is_grocery', 'other')
			->with('meta')
			->with('specification')
			->orderby('shuffle_number', 'desc')
			->limit(16)->get();



		foreach ($simillarProducts as $item) {
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
		}

		if (count($simillarProducts) > 0) {
			$data['status'] = 1;
			$data['simillar_products'] = $simillarProducts;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Simillar product not found.';
			$data['simillar_products'] = [];
			return response()->json($data, 200);
		}
	}
	public function suggetionProduct(Request $request, $content)
	{
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$products = Product::where(function($query) use($content) {
			$query->where('title', 'LIKE', "%$content%")
			->orWhere('sku', 'LIKE', "%$content%");
		})
		->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
		->where('is_active', 1)
		->where('is_deleted', 0)
		->where('product_qc', 1)
		->orderby('shuffle_number', 'desc')->limit(16)->get();

		$shops = ShopInfo::where('name', 'LIKE', "%$content%")->where('status', 1)->limit(10)->get();
		$categories = Category::where('title', 'LIKE', "%$content%")->where('is_active', 1)->limit(10)->get();




		foreach ($products as $item) {
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
		}
		if (count($categories) > 0) {
			$data['categories'] = $categories;
		} else {
			$data['categories'] = null;
		}
		if (count($shops) > 0) {
			$data['shops'] = $shops;
		} else {
			$data['shops'] = null;
		}


		if (count($products) > 0) {
			$data['status'] = 1;
			$data['products'] = $products;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Product not found.';
			return response()->json($data, 200);
		}
	}
	public function saveWhatUserSearch(Request $request)
	{
		$session_key = $request->session_key;
		$user = auth('customer-api')->user();
		if ($session_key || $user) {
			if ($request->searchContent) {
				$searchData['content'] = $request->searchContent;
				$searchData['status'] = 1;

				if ($user) {
					$searchData['user_id'] = $user->id;
					DB::table('user_search')->insert($searchData);
					$data['status'] = 1;
					$data['ip_address'] = $request->ip();
					$data['message'] = 'Search content inserted';
					return response()->json($data, 200);
				} elseif ($session_key) {
					$data['ip_address'] = $request->ip();
					$searchData['session_key'] = $session_key;
					DB::table('user_search')->insert($searchData);

					$data['status'] = 1;
					$data['message'] = 'Search content inserted';
					return response()->json($data, 200);
				}
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'User or session key not found.';
			return response()->json($data, 200);
		}
	}




	public function initUser()
	{
		$user = auth('customer-api')->user();
		//$user = User::where('id',$user->id)->first();
		if ($user) {
			$data['status'] = 1;
			$data['selected_pickpoint'] = null;
			if ($user->default_pickpoint_address == 1) {
				$data['selected_pickpoint'] = Pickpoints::where('id', $user->default_address_id)->with('division', 'district', 'upazila', 'union')->first();
			}

			$data['address'] = Addresses::where('user_id', $user->id)->where('is_deleted', 0)->with('division', 'district', 'upazila', 'union')->get();

			$data['pickpoints'] = Pickpoints::where('status', 1)->with('division', 'district', 'upazila', 'union')->get();
			$data['user'] = $user;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}


	public function updateDefaultAddress(Request $request)
	{
		$user = auth('customer-api')->user();
		if ($user && $request->address_id) {

			DB::table('users')->where('id', $user->id)->update([
				'default_address_id' => $request->address_id,
				'default_pickpoint_address' => $request->pickpoint ?? 0,
			]);

			$data['status'] = 1;
			$data['message'] = 'Address selected successfully.';
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}



	public function initCart(Request $request)
	{
		$user = auth('customer-api')->user();
		$session_key = $request->session_key;
		if ($user || $session_key) {

			if ($user) {
				$cartData = Cart::where('user_id', $user->id)->with('product')->with('meta')->get();
			} else {
				$cartData = Cart::where('session_key', $session_key)->with('product')->with('meta')->get();
			}

			$data['status'] = 1;
			$sub_total = 0;
			$discount_amount = 0;
			$shipping_cost = 0;
			$shipping_cost_grocery = 0;
			$grocery_total_price = 0;
			$packaging_cost = 0;
			$security_charge = 0;
			$vat = 0;

			$SellerWiseGroup = [];

			foreach ($cartData as $single_item) {

				$singleProduct = Product::where('id', $single_item->product_id)->first();

				$single_item->price_before_offer = (int)\Helper::price_before_offer($single_item->product_id);
				$single_item->offer_percentage = (int)number_format(\Helper::offer_percentage_byID($single_item->product_id), 0);

				$sub_total += $single_item->price * $single_item->qty;
				$vat +=  (($single_item->price*$singleProduct->vat)/100) * $single_item->qty;

				$packaging_cost += $single_item->packaging_cost * $single_item->qty;
				$security_charge += $single_item->security_charge * $single_item->qty;
				$discount_amount += $single_item->discount * $single_item->qty;

				if ($single_item->product_type == 'variable') {
					$single_item->variable_options = json_decode($single_item->variable_options);
				}

				if ($single_item->product_type == 'digital') {
					$single_item->variable_options = $single_item->variable_options;
				}

				if ($single_item->product_type == 'service') {
					$single_item->variable_options = json_decode($single_item->variable_options);
				}

				if ($single_item->seller_id) {
					$shop = ShopInfo::select('name', 'slug')->where('seller_id', $single_item->seller_id)->first();
					$single_item->shop_name = $shop->name??'';
					$single_item->shop_slug = $shop->slug??'';
				}

				// Filtered Shipping Option

				$availableShippingOptions = [];

				if ($user) {
					$user_id = $user->id;
					$availableShippingOptions = \Helper::get_shipping_cost($user_id, $single_item->seller_id, $single_item->product_id);
				}

				$single_item->shipping_options =  $availableShippingOptions;


				if ($single_item->product_type != 'digital' && $single_item->product_type != 'service' && $singleProduct->is_grocery != 'grocery') {
					if ($user) {
						$shipping_cost += $single_item->qty * $availableShippingOptions['standard_shipping'] ?? 0;
					}
				}

				if ($singleProduct->is_grocery == 'grocery') {
					$grocery_total_price += $single_item->price * $single_item->qty;
				}


				$SellerWiseGroup[$single_item->seller_id]['shop_info'] = [
					'shop_name' => $single_item->shop_name,
					'shop_slug' => $single_item->shop_slug,
					'seller_id' => $single_item->seller_id,
				];

				$SellerWiseGroup[$single_item->seller_id]['items'][] = $single_item;
			}


			if ($grocery_total_price > 0) {
				$settingAmountForGrocery = \Helper::getsettings('shipping_validation_amount') ?? 500;
				$minimumShippingAmount = \Helper::getsettings('shipping_minimum_amount') ?? 30;
				$maximumShippingAmount = \Helper::getsettings('shipping_maximum_amount') ?? 50;

				if ($grocery_total_price >= $settingAmountForGrocery) {
					$shipping_cost_grocery = (int)$minimumShippingAmount;
				} else {
					$shipping_cost_grocery = (int)$maximumShippingAmount;
				}
			}



			$SellerWiseGroup = array_values($SellerWiseGroup);
			$data['cart'] = $SellerWiseGroup;
			$data['discount_amount'] = $discount_amount;
			$data['sub_total'] = $sub_total;
			$data['pickpoint'] = 0;
			$data['vat'] = $vat;


			if ($user) {
				if ($user->default_pickpoint_address == 1) {
					$pickpoint =  Pickpoints::select('discount_type', 'discount')->where('id', $user->default_address_id)->first();

					$data['packaging_cost'] = 0;
					$data['security_charge'] = 0;
					$data['grocery_shipping_cost'] = 0;
					$data['pickpoint'] = 1;
					$data['shipping_cost'] = ($shipping_cost + $shipping_cost_grocery);

					if ($pickpoint) {
						if ($pickpoint->discount_type == 1) { //Fixed
							$data['shipping_cost'] = $pickpoint->discount;
						} else {
							$data['shipping_cost'] =  ($sub_total * $pickpoint->discount) / 100;
						}
					}
				} else {
					$data['packaging_cost'] = $packaging_cost;
					$data['security_charge'] = $security_charge;
					$data['grocery_shipping_cost'] = $shipping_cost_grocery;
					$data['shipping_cost'] = ($shipping_cost + $shipping_cost_grocery);
				}
			}else{
				$data['packaging_cost'] = $packaging_cost;
				$data['security_charge'] = $security_charge;
				$data['grocery_shipping_cost'] = $shipping_cost_grocery;
				$data['shipping_cost'] = ($shipping_cost + $shipping_cost_grocery);
			}

			$data['total_items'] = count($cartData);
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}







	

	public function RemoveCartItem(Request $request)
	{
		$user = auth('customer-api')->user();
		$session_key = $request->session_key;
		if ($user || $session_key) {
			if ($user) {
				$delete = DB::table('carts')->where('row_id', $request->row_id)->where('user_id', $user->id)->delete();
			} else {
				$delete = DB::table('carts')->where('row_id', $request->row_id)->where('session_key', $session_key)->delete();
			}

			if ($delete) {

				if ($user) {
					$DB_cart = Cart::where('user_id', $user->id)->with('product')->get();
				} else {
					$DB_cart = Cart::where('session_key', $session_key)->with('product')->get();
				}

				$data['status'] = 1;
				$SellerWiseGroup = [];
				$sub_total = 0;
				$discount_amount = 0;
				$shipping_cost = 0;
				foreach ($DB_cart as $single_item) {
					$sub_total += $single_item->price * $single_item->qty;
					$discount_amount += $single_item->discount * $single_item->qty;
					$shipping_cost += $single_item->shipping_cost * $single_item->qty;
					if ($single_item->product_type == 'variable') {
						$single_item->variable_options = json_decode($single_item->variable_options);
					}

					if ($single_item->product_type == 'digital') {
						$single_item->variable_options = $single_item->variable_options;
					}

					if ($single_item->product_type == 'digital') {
						$single_item->variable_options = $single_item->variable_options;
					}
					if ($single_item->seller_id) {
						$shop = ShopInfo::select('name', 'slug')->where('seller_id', $single_item->seller_id)->first();
						$single_item->shop_name = $shop->name??'';
						$single_item->shop_slug = $shop->slug??'';
					}

					if ($single_item->meta) {
						foreach ($single_item->meta as $item) {
							if ($item->meta_key == 'product_shipping_option') {
								$item->decoded_shipping_option = unserialize($item->meta_value);
								$item->suggested_shipping_option = 'Standard Shippnig';
								$item->suggested_shipping_cost = 100;
							}
						}
					}
					$SellerWiseGroup[$single_item->seller_id]['shop_info'] = [
						'shop_name' => $single_item->shop_name,
						'shop_slug' => $single_item->shop_slug,
						'seller_id' => $single_item->seller_id,
					];
					$SellerWiseGroup[$single_item->seller_id]['items'][] = $single_item;
				}


				$SellerWiseGroup = array_values($SellerWiseGroup);
				$data['cart'] = $SellerWiseGroup;
				$data['discount_amount'] = $discount_amount;
				$data['sub_total'] = $sub_total;
				$data['shipping_cost'] = $shipping_cost;
				$data['total_items'] = count($DB_cart);
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Your cart item is already deleted. Please refresh the page!';
			}

			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}



	public function updateQty(Request $request){
		$user = auth('customer-api')->user();
		$session_key = $request->session_key;

		if ($user || $session_key) {

			if ($user) {
				$cart = DB::table('carts')->where('row_id', $request->rowId)->where('user_id', $user->id)->first();
			} else {
				$cart = DB::table('carts')->where('row_id', $request->rowId)->where('session_key', $session_key)->first();
			}


			if ($cart) {
				$quantity = $cart->qty + ($request->update);
				if ($quantity > 0 && $cart->product_id) {

					if ($cart->product_type == 'variable') {

						$availableOptions = \Helper::variable_product_stock($cart->product_id, $quantity, json_decode($cart->variable_options));

						if ($availableOptions) {
							$data['qty'] = $quantity;
						} else {
							$d['status'] = 0;
							$d['message'] = 'Requested quantity not available.';
							return response()->json($d, 200);
						}
						
						if($data['qty'] > 5){
							$data['status'] = 0;
							$data['message'] = 'You can not buy more than 5 items for this product at a time.';
							return response()->json($data, 200);
						}

						if ($user) {
							Cart::where('user_id', $user->id)->where('product_id', $request->product_id)->update($data);
						} else {
							Cart::where('session_key', $session_key)->where('product_id', $request->product_id)->update($data);
						}
					} else {
						$available = \Helper::simple_product_stock($cart->product_id, $quantity);
						if ($available == 1) {
							
							$data['qty'] = $quantity;
						} else {
							$data['qty'] = $quantity - 1;
						}
					}
				} else {
					$data['qty'] = 1;
				}
				$product = Product::where('id', $cart->product_id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->first();
				if ($data['qty'] > $product->max_cart_qty) {
					$data['status'] = 0;
					$data['message'] = 'You can not buy more than ' . $product->max_cart_qty . ' items for this product at a time.';
					return response()->json($data, 200);
				} 
				// if($data['qty'] > 8){
				// 	$data['status'] = 0;
				// 	$data['message'] = 'You can not buy more than 5 items for this product at a time.';
				// 	return response()->json($data, 200);
				// }


				if ($user) {
					Cart::where('row_id', $request->rowId)->where('user_id', $user->id)->update($data);
					$DB_cart = Cart::where('user_id', $user->id)->with('product')->get();
				} else {
					Cart::where('row_id', $request->rowId)->where('session_key', $session_key)->update($data);
					$DB_cart = Cart::where('session_key', $session_key)->with('product')->get();
				}


				$data['status'] = 1;
				$SellerWiseGroup = [];
				$sub_total = 0;
				$discount_amount = 0;
				$shipping_cost = 0;
				foreach ($DB_cart as $single_item) {
					$sub_total += $single_item->price * $single_item->qty;
					$discount_amount += $single_item->discount * $single_item->qty;
					$shipping_cost += $single_item->shipping_cost * $single_item->qty;

					if ($single_item->product_type == 'variable') {
						$single_item->variable_options = json_decode($single_item->variable_options);
					}

					if ($single_item->product_type == 'digital') {
						$single_item->variable_options = $single_item->variable_options;
					}

					if ($single_item->product_type == 'digital') {
						$single_item->variable_options = $single_item->variable_options;
					}
					if ($single_item->seller_id) {
						$shop = ShopInfo::select('name', 'slug')->where('seller_id', $single_item->seller_id)->first();
						$single_item->shop_name = $shop->name??'';
						$single_item->shop_slug = $shop->slug??'';
					}

					if ($single_item->meta) {
						foreach ($single_item->meta as $item) {
							if ($item->meta_key == 'product_shipping_option') {
								$item->decoded_shipping_option = unserialize($item->meta_value);
								$item->suggested_shipping_option = 'Standard Shippnig';
								$item->suggested_shipping_cost = 100;
							}
						}
					}
					$SellerWiseGroup[$single_item->seller_id]['shop_info'] = [
						'shop_name' => $single_item->shop_name,
						'shop_slug' => $single_item->shop_slug,
						'seller_id' => $single_item->seller_id,
					];
					$SellerWiseGroup[$single_item->seller_id]['items'][] = $single_item;
				}


				$SellerWiseGroup = array_values($SellerWiseGroup);
				$data['cart'] = $SellerWiseGroup;
				$data['discount_amount'] = $discount_amount;
				$data['sub_total'] = $sub_total;
				$data['shipping_cost'] = $shipping_cost;
				$data['total_items'] = count($DB_cart);

				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Cart not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}







	public function updateShippingOption(Request $request)
	{
		$user = auth('customer-api')->user();
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
		$cart = Cart::where('row_id', $request->rowId)->first();
		if ($cart) {
			$updatable['shipping_method'] = $request->shipping_method;
			if ($request->shipping_cost == 'on') {
				$updatable['shipping_cost'] = 0;
			} else {
				$updatable['shipping_cost'] = $request->shipping_cost;
			}
			Cart::where('row_id', $request->rowId)->update($updatable);
			$data['status'] = 1;
			$data['message'] = 'Shipping method updated successfully.';
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Something went wrong.';
			return response()->json($data, 200);
		}
	}

	public function shippingInformation(Request $request)
	{
		$user = auth('customer-api')->user();
		if ($user) {
			$shipping_info['user_id'] =  $user->id;
			$shipping_info['shipping_first_name'] =  $request->shipping_first_name;
			$shipping_info['shipping_last_name']  =  $request->shipping_last_name;
			$shipping_info['shipping_address']    =  $request->shipping_address;
			$shipping_info['shipping_division']   =  $request->shipping_division;
			$shipping_info['shipping_district']   =  $request->shipping_district;
			$shipping_info['shipping_thana']      =  $request->shipping_thana;
			$shipping_info['shipping_postcode']   =  $request->shipping_postcode;
			$shipping_info['shipping_phone']      =  $request->shipping_phone;
			$shipping_info['shipping_email']      =  $request->shipping_email;

			$already = DB::table('addresses')->where('user_id', $user->id)->first();
			if ($already) {
				DB::table('addresses')->where('user_id', $user->id)->update($shipping_info);
			} else {
				DB::table('addresses')->insert($shipping_info);
			}
			$data['status']      = 1;
			$data['message']     = 'Billing and Shipping information inserted successfully.';
			$data['information'] = Addresses::where('user_id', $user->id)->with('division', 'district', 'upazila', 'union')->get();
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}
	public function getShippingInformation()
	{
		$user = auth('customer-api')->user();
		if ($user) {
			$data['status'] = 1;
			$data['information'] = Addresses::where('user_id', $user->id)->where('is_deleted', 0)->with('division', 'district', 'upazila', 'union')->get();
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}

	public function getCouponAmount(Request $request)
	{
		$user = auth('customer-api')->user();
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'Please login to use coupon codes.';
			return response()->json($data, 200);
		}
		$couponData = \Helper::validateCoupon($request->coupon, $user->id);
		return response()->json($couponData, 200);
	}



	//Order
	public function Order(Request $request){
		$user = auth('customer-api')->user();
		$pickpoint =  Pickpoints::where('id', $user->default_address_id)->first();


		$partial_payment = false;
		if($request->payment_method == 'online_payment'){
			$partial_payment = $request->partial_payment;
		}

		if ($user) {
			$validForGroceryShipping = true;
			$userAddress = Addresses::find($user->default_address_id);

			if ($user->default_pickpoint_address != 1) {
				if (!$userAddress) {
					$data['status'] = 0;
					$data['message'] = 'You do not have any default shipping address! Please add address first to place an order.';
					return response()->json($data, 200);
				} else {

					// $grocery_allowed_shipping_location = \DB::table('unions')->where('grocery_shipping_allowed', 1)->pluck('id')->toArray();
					// $shipping_union = $userAddress->shipping_union;

					// if (in_array($shipping_union, $grocery_allowed_shipping_location)) {
					// 	$validForGroceryShipping = true;
					// }
				}
			}


			$shipping_methods = $request->shipping_method;
			$keyEnabledShipping = [];

			foreach ($shipping_methods as $filteredShipping) {
				$keyEnabledShipping[$filteredShipping['product_id']] = $filteredShipping['shipping_method'];
			}

			$user_id = $user->id;
			$cartData = DB::table('carts')->where('user_id', $user_id)->get();
			if (!$cartData) {
				$data['status'] = 0;
				$data['message'] = 'Product not found in your cart.';
				return response()->json($data, 200);
			}

			$sub_total = 0;
			$vat = 0;
			$discount_amount = 0;
			$shipping_cost = 0;
			$packaging_cost = 0;
			$security_charge = 0;
			$aff_commission_amount = 0;
			$productIds = [];

			foreach ($cartData as $cart) {
				//Update packaging_cost & security_charge
				$sProduct = Product::select('is_grocery', 'packaging_cost', 'security_charge', 'aff_commission_amount', 'id','vat')->where('id', $cart->product_id)->first();
				if ($sProduct) {

					$vat +=  (($cart->price*$sProduct->vat)/100) * $cart->qty;

					if ($user->default_pickpoint_address != 1) {

						if ($cart->packaging_cost != $sProduct->packaging_cost) {
							DB::table('carts')->where('id', $cart->id)->update(['packaging_cost' => $sProduct->packaging_cost]);
							$data['status'] = 2;
							$data['message'] = 'Product packaging cost has been changed. Please review new packaging cost and place order again!';
							return response()->json($data, 200);
						}

						if ($cart->security_charge != $sProduct->security_charge) {
							DB::table('carts')->where('id', $cart->id)->update(['security_charge' => $sProduct->security_charge]);
							$data['status'] = 2;
							$data['message'] = 'Product security charge has been changed. Please review new security charge and place order again!';
							return response()->json($data, 200);
						}

						if ($sProduct->is_grocery == 'grocery') {
							if (!$validForGroceryShipping) {
								$data['status'] = 0;
								$data['message'] = 'Sorry! We are not currently delivery grocery product in your area! Please check grocery help center for further details.';
								return response()->json($data, 200);
							}
						}
					}

					if ($sProduct->aff_commission_amount) {
						$aff_commission_amount += $sProduct->aff_commission_amount * $cart->qty;
						$productIds[] = $sProduct->id;
					}
				}


				if ($cart->product_type == 'simple' || $cart->product_type == 'digital' || $cart->product_type == 'service') {
					$price = \Helper::price_after_offer($cart->product_id);

					//Validate Price Update
	
					if ($price != $cart->price) {
						$newDiscount =  \Helper::discount_amount_by_IDS($cart->product_id) * $cart->qty;
						DB::table('carts')->where('id', $cart->id)->update(['price' => $price, 'discount' => $newDiscount]);

						$data['status'] = 2;
						$data['message'] = 'Product price has been changed. Please review new price and place order again!';
						return response()->json($data, 200);
					}

					$qty = \Helper::simple_product_stock($cart->product_id, $cart->qty);
					if ($qty == 1) {
						$sub_total += $price * $cart->qty;
						$packaging_cost += $cart->packaging_cost * $cart->qty;
						$security_charge += $cart->security_charge * $cart->qty;
					} else {
						$data['status'] = 0;
						$data['message'] = 'Product is out of stock.';
						return response()->json($data, 200);
					}
				}

				if ($cart->product_type == 'variable') {

					if ($cart->variable_options) {
						$availableOptions = \Helper::variable_product_stock($cart->product_id, $cart->qty, json_decode($cart->variable_options));
						if ($availableOptions) {
							$price = \Helper::price_after_offer($cart->product_id) + $availableOptions['totalAdditional'];

							//Validate Price Update
							if ($price != $cart->price) {
								$newDiscount =  \Helper::discount_amount_by_IDS($cart->product_id) * $cart->qty;
								DB::table('carts')->where('id', $cart->id)->update(['price' => $price, 'discount' => $newDiscount]);
								$data['status'] = 2;
								$data['message'] = 'Product price has been changed. Please review new price and place order again!';
								return response()->json($data, 200);
							}

							$sub_total += $price * $cart->qty;
						}
					}
				}
			}


			//Coupon Validation
			$coupon_amount = 0;
			if ($request->coupon) {
				$couponData = \Helper::validateCoupon($request->coupon, $user->id);
				if ($couponData['status'] == 1) {
					$coupon_amount = $couponData['amount'];
					$coupon_code = $couponData['code'];
				}
			}


			//Voucher Validation for Collection and Activation
			$collected = CollectedVoucher::where('user_id', $user->id)->where('status', 0)->first();
			if ($collected) {
				$voucherForActivation = $collected->voucher_id;
				$voucherData = \Helper::validateVoucher($voucherForActivation, $user->id);
				if ($voucherData['status'] == 1) {
					CollectedVoucher::where('voucher_id', $voucherData['code'])->where('user_id', $user->id)->update(['status' => 1]);
				}
			}


			//Voucher Validation for Checkout
			$voucher_amount = 0;
			if ($request->usedVoucher) {
				$usedVoucher = $request->usedVoucher;
				$voucherData = \Helper::validateVoucher($request->usedVoucher, $user->id);

				if ($voucherData['status'] == 1) {
					$voucher_amount = $voucherData['amount'];
					$voucher_code = $voucherData['code'];
					CollectedVoucher::where('voucher_id', $voucherData['code'])
					->where('user_id', $user->id)
					->first()
					->update(['status' => 2]);
				}
			}


			//Partial Payment validation for Checkout
			if($partial_payment){
				$partial_payment_validation = \Helper::validatePartialPayment($partial_payment,$sub_total);
				if($partial_payment_validation['status'] == 0){
					$data['status'] = 0;
					$data['message'] = $partial_payment_validation['message'];
					return response()->json($data, 200);
				}
			}

			$orderData['total_amount'] = 0;
			$orderData['paid_amount'] = 0;
			$orderData['is_partial_payment'] = 0;
			$orderData['order_from'] = $request->order_from ?? 'web';
			$orderData['coupon_code'] = $coupon_code ?? null;
			$orderData['coupon_amount'] = $coupon_amount ?? 0;
			$orderData['voucher_code'] = $voucher_code ?? null;
			$orderData['voucher_amount'] = $voucher_amount ?? 0;
			$orderData['discount_amount'] = $orderData['coupon_amount'] + $orderData['voucher_amount'];
			$orderData['payment_method'] = $request->payment_method;
			$orderData['status'] = 1;
			$orderData['vat'] = $vat;
			$orderData['user_id'] = $user_id;
			$orderData['address_id'] = $user->default_address_id ?? null;
			$orderData['payment_id'] = uniqid();
			$orderData['ip_address'] = request()->ip();
			$orderData['note']    = $request->note;
			$orderData['total_packaging_cost']  = $packaging_cost;
			$orderData['total_security_charge'] = $security_charge;
			$order_id = DB::table('orders')->insertGetId($orderData);
			$totalShippingCost = 0;
			$grocery_total_price = 0;
			$shipping_cost_grocery = 0;


			//Insert Order details
			foreach ($cartData as $single_item) {
				$product = \Helper::get_product_by_id($single_item->product_id);

				$detailsData['user_id'] = $single_item->user_id;
				$detailsData['order_id'] = $order_id;
				$detailsData['product_id'] = $single_item->product_id;

				$detailsData['product_sku'] = ($product->sku) ? $product->sku : $single_item->variable_sku;
				$detailsData['vat'] = ($single_item->price*$product->vat)/100 * $single_item->qty;
				$detailsData['product_qty'] = $single_item->qty;
				$detailsData['price'] = $single_item->price ?? null;
				$detailsData['is_promotion'] = $product->is_promotion;
				$detailsData['loyalty_point'] = $product->loyalty_point ?? 0;


				if ($single_item->product_type != 'digital' && $single_item->product_type != 'service' && $product->is_grocery != 'grocery') {
					//Shipping Method and Shipping Cost

					if ($user->default_pickpoint_address != 1) {
						$detailsData['shipping_method'] = $keyEnabledShipping[$single_item->product_id];
						$detailsData['shipping_cost'] = 0;
						$availableShippings = \Helper::get_shipping_cost($single_item->user_id, $product->seller_id, $product->id, $userAddress->shipping_district);

						foreach ($availableShippings as $key => $val) {
							if ($key == $keyEnabledShipping[$single_item->product_id]) {
								$val = (int) $val;
								$detailsData['shipping_cost'] = $val * $single_item->qty;
								$totalShippingCost += $val * $single_item->qty;
							}
						}
					} else {
						$detailsData['shipping_method'] = 'pick_point';
						$detailsData['shipping_cost'] = 0;
					}
				}

				if ($product->is_grocery == 'grocery') {
					$grocery_total_price += $single_item->price * $single_item->qty;
				}

				$detailsData['product_options'] = $single_item->variable_options;
				$detailsData['seller_id'] = $product->seller_id ?? null;
				$orderData['payment_method'] = $request->payment_method;

				if ($user->default_pickpoint_address != 1) {
					$detailsData['packaging_cost'] = $single_item->packaging_cost ?? 0;
					$detailsData['security_charge'] = $single_item->security_charge ?? 0;
				}


				if ($orderDetailsId = DB::table('order_details')->insertGetId($detailsData)) {
					if ($request->payment_method == 'cash_on_delivery') {
						\Helper::update_product_quantity($single_item->product_id, $single_item->qty, $single_item->variable_options, 'subtraction');
					}

					//Set Seller Commission
					\Helper::setSellerBalance($orderDetailsId);
				} else {
					$data['status'] = 0;
					$data['message'] = 'Something went wrong.';
					return response()->json($data, 200);
				}
			}



			if ($grocery_total_price > 0) {
				$settingAmountForGrocery = \Helper::getsettings('shipping_validation_amount') ?? 500;
				$minimumShippingAmount = \Helper::getsettings('shipping_minimum_amount') ?? 30;
				$maximumShippingAmount = \Helper::getsettings('shipping_maximum_amount') ?? 50;

				if ($grocery_total_price >= $settingAmountForGrocery) {
					$shipping_cost_grocery = (int)$minimumShippingAmount;
				} else {
					$shipping_cost_grocery = (int)$maximumShippingAmount;
				}
			}


			if ($user->default_pickpoint_address == 1) { //Pick Point Order
				$pData = [];
				$pData['grocery_shipping_cost'] = 0;
				$pData['shipping_cost'] = $totalShippingCost + $shipping_cost_grocery;
				$pData['is_pickpoint'] = 1;

				if ($pickpoint) {
					if ($pickpoint->discount_type == 1) { //Fixed
						$pData['shipping_cost'] = $pickpoint->discount;
					} else {
						$pData['shipping_cost'] =  ($sub_total * $pickpoint->discount) / 100;
					}
				}

				$total_amount = ($sub_total + $pData['shipping_cost']+$vat) - ($coupon_amount + $orderData['voucher_amount']);

				$pData['total_amount'] = $total_amount;
				$pData['paid_amount'] = 0;
				$pData['is_partial_payment'] = $partial_payment ? 1 :0;

				DB::table('orders')->where('id', $order_id)->update($pData);
			} else {
				$total_amount = ($sub_total + $totalShippingCost + $shipping_cost_grocery + $packaging_cost + $security_charge+$vat) - ($coupon_amount + $orderData['voucher_amount']);
				DB::table('orders')->where('id', $order_id)->update([
					'shipping_cost' => $totalShippingCost + $shipping_cost_grocery,
					'grocery_shipping_cost' => $shipping_cost_grocery,
					'total_amount'		=> $total_amount,
					'paid_amount'		=> 0,
					'is_partial_payment'=> $partial_payment ? 1 :0
				]);
			}



			//Affiliate Commision
			if ($user->affiliate_referer && $productIds && $aff_commission_amount > 0) {
				$affData['user_id'] = $user->affiliate_referer;
				$affData['buyer_id'] = $user->id;
				$affData['product_ids'] = implode(',', $productIds);
				$affData['order_id'] = $order_id;
				$affData['commission_amount'] = $aff_commission_amount;
				$affData['note'] = '';
				$affData['status'] = 1;
				DB::table('affiliate_history')->insert($affData);
			}

			$invoice = DB::table('order_details')->where('order_id', $order_id)->get();
			foreach ($invoice as $key => $item) {
				$p = \Helper::get_product_by_id($item->product_id);
				$item->image = $p->default_image ?? null;
				$item->title = $p->title ?? null;
				$item->slug  = $p->slug ?? null;
			}
			$in['total'] = $total_amount;
			$in['sub_total'] = $sub_total;
			$in['discount_amount'] = $discount_amount;
			$in['coupon_amount'] = $coupon_amount;
			$in['order_id'] = $order_id;
			$in['products'] = $invoice;
			$in['shipping_cost'] = $shipping_cost;

			$data['status'] = 1;
			$data['message'] = 'Order placed successfully.';
			$data['invoice'] = $in;

			//Send Email
			$order = Order::find($order_id);


			if ($request->server('HTTP_HOST') != '127.0.0.1:8000') {
				if ($email = $user->email) {
					\Helper::sendEmail($email, 'Order Placed', $order, 'invoice');
				}
			}

			//Send Push notifications
			//Customer
			$message = [
				'order_id' =>  'DR' . date('y', strtotime(Carbon::now())) . $order_id,
				'type' => 'order',
				'message' => 'Order placed successfully!',
			];

			//Seller
			$sellers = DB::table('carts')->where('user_id', $user_id)->get();
			$sellers_id_for_order = array();

			foreach ($sellers as $row) {
				$sellers_id_for_order[$row->seller_id] = $row->seller_id; // Get unique seller by id.
			}

			foreach ($sellers_id_for_order as $key => $val) {
				$seller = Admins::find($val);
				////\Helper::sendPushNotification($val, 2, 'Order Placed', 'Order placed successfully!', json_encode($message));
				//\Helper::sendSms($seller->phone, 'একটি নতুন অর্ডার সফলভাবে স্থাপন করা হয়েছে! অর্ডার আইডি হল  #' . 'DR' . date('y', strtotime(Carbon::now())) . $order_id);
			}

			// Customer
			//\Helper::sendSms($user->phone, 'অর্ডার সফলভাবে স্থাপন করা হয়েছে! আপনার অর্ডারের জন্য আপনাকে ধন্যবাদ. আপনার অর্ডার আইডি #' . 'DR' . date('y', strtotime(Carbon::now())) . $order_id);

			//Delete Cart Data
			DB::table('carts')->where('user_id', $user_id)->delete();


			if ($request->payment_method == 'online_payment') {

				$post_data = array();
				$post_data['total_amount'] = $partial_payment ? $partial_payment : $total_amount; 
				$post_data['currency'] = "BDT";
				$post_data['tran_id'] = $orderData['payment_id'];

				# CUSTOMER INFORMATION
				if ($user->default_pickpoint_address != 1) {
					$post_data['cus_name'] = $userAddress->shipping_first_name ?? '' . ' ' . $userAddress->shipping_last_name ?? '';
					$post_data['cus_email'] = $userAddress->shipping_email ?? 'guest-customer@guest.com';
					$post_data['cus_add1'] = $userAddress->shipping_address ?? '';
					$post_data['cus_add2'] = $userAddress->shipping_thana ?? '';
					$post_data['cus_city'] = $userAddress->shipping_district ?? '';
					$post_data['cus_state'] = $userAddress->shipping_division ?? '';
					$post_data['cus_postcode'] = $userAddress->shipping_postcode ?? '';
					$post_data['cus_country'] = "Bangladesh";
					$post_data['cus_phone'] = $userAddress->shipping_phone ?? '';
					$post_data['cus_fax'] = "";
				} else {
					$post_data['cus_name'] = $pickpoint->title;
					$post_data['cus_email'] = $pickpoint->email ?? 'guest-customer@guest.com';
					$post_data['cus_add1'] = $pickpoint->address ?? '';
					$post_data['cus_add2'] = $pickpoint->union->title ?? '';
					$post_data['cus_city'] = $pickpoint->district->title ?? '';
					$post_data['cus_state'] = $pickpoint->division->title ?? '';
					$post_data['cus_postcode'] = $pickpoint->post_code ?? '';
					$post_data['cus_country'] = "Bangladesh";
					$post_data['cus_phone'] = $pickpoint->phone ?? '';
					$post_data['cus_fax'] = "";
				}


				# SHIPMENT INFORMATION
				$post_data['ship_name'] = "Store Test";
				$post_data['ship_add1'] = "Dhaka";
				$post_data['ship_add2'] = "Dhaka";
				$post_data['ship_city'] = "Dhaka";
				$post_data['ship_state'] = "Dhaka";
				$post_data['ship_postcode'] = "1000";
				$post_data['ship_phone'] = "";
				$post_data['ship_country'] = "Bangladesh";

				$post_data['shipping_method'] = "NO";
				$post_data['product_name'] = "Computer";
				$post_data['product_category'] = "Goods";
				$post_data['product_profile'] = "physical-goods";

				# OPTIONAL PARAMETERS
				$post_data['value_a'] = $orderData['payment_id'];
				$post_data['value_b'] = "ref002";
				$post_data['value_c'] = "ref003";
				$post_data['value_d'] = "ref004";

				$sslc = new SslCommerzNotification();
				# initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
				$redirectUrl = $sslc->makePayment($post_data, 'hosted');
				$redirectData = [
					'status' => 302,
					'url'	=> $redirectUrl
				];
				return response()->json($redirectData, 200);
			}elseif ($request->payment_method == 'cash_on_delivery') {
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to place an order.';
			return response()->json($data, 200);
		}
	}



	public function productRecieveConfirmation($orderDetailsId)
	{
		$user = auth('customer-api')->user();
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}

		$orderDetails = OrderDetails::where('id', $orderDetailsId)->where('user_id', $user->id)->first();
		if ($orderDetails) {
			OrderDetails::where('id', $orderDetailsId)->where('user_id', $user->id)->update(['status' => 6]);
			$data['status'] = 1;
			$data['message'] = 'Thank you for your feedback. This product will be marked as successfully delivered to you.';
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'You are not allowed to perform this action.';
			return response()->json($data, 200);
		}
	}




	//Pay again
	public function payAgain(Request $request,$order_id)
	{
		$user = auth('customer-api')->user();
		$pickpoint =  Pickpoints::where('id', $user->default_address_id)->first();
		if ($user) {
			$orders = Order::where('id', $order_id)->first();
			if ($orders) {
				$data['status'] = 1;

				$total_amount = $orders->total_amount;
				$dueAmount = $orders->total_amount -  $orders->paid_amount;

				if($orders->is_partial_payment == 1){
					if($request->amount < 10){
						$data['status'] = 0;
						$data['message'] = 'You can not pay less than BDT 10!';
						return response()->json($data, 200);
					}elseif($request->amount > $dueAmount){
						$data['status'] = 0;
						$data['message'] = 'You can not pay more than your due amount!';
						return response()->json($data, 200);
					}else{
						$total_amount = $request->amount;
					}
				}



				$userAddress = Addresses::find($orders->address_id);

				if ($orders->payment_method == 'online_payment') {

					$post_data = array();
					$post_data['total_amount'] = $total_amount; # You cant not pay less than 10
					$post_data['currency'] = "BDT";


					$post_data['tran_id'] = $orders->payment_id;

					# CUSTOMER INFORMATION
					if ($user->default_pickpoint_address != 1) {
						$post_data['cus_name'] = $userAddress->shipping_first_name ?? '' . ' ' . $userAddress->shipping_last_name ?? '';
						$post_data['cus_email'] = $userAddress->shipping_email ?? 'guest-customer@guest.com';
						$post_data['cus_add1'] = $userAddress->shipping_address ?? '';
						$post_data['cus_add2'] = $userAddress->shipping_thana ?? '';
						$post_data['cus_city'] = $userAddress->shipping_district ?? '';
						$post_data['cus_state'] = $userAddress->shipping_division ?? '';
						$post_data['cus_postcode'] = $userAddress->shipping_postcode ?? '';
						$post_data['cus_country'] = "Bangladesh";
						$post_data['cus_phone'] = $userAddress->shipping_phone ?? '';
						$post_data['cus_fax'] = "";
					} else {
						$post_data['cus_name'] = $pickpoint->title;
						$post_data['cus_email'] = $pickpoint->email ?? 'guest-customer@guest.com';
						$post_data['cus_add1'] = $pickpoint->address ?? '';
						$post_data['cus_add2'] = $pickpoint->union->title ?? '';
						$post_data['cus_city'] = $pickpoint->district->title ?? '';
						$post_data['cus_state'] = $pickpoint->division->title ?? '';
						$post_data['cus_postcode'] = $pickpoint->post_code ?? '';
						$post_data['cus_country'] = "Bangladesh";
						$post_data['cus_phone'] = $pickpoint->phone ?? '';
						$post_data['cus_fax'] = "";
					}

					# SHIPMENT INFORMATION
					$post_data['ship_name'] = "Store Test";
					$post_data['ship_add1'] = "Dhaka";
					$post_data['ship_add2'] = "Dhaka";
					$post_data['ship_city'] = "Dhaka";
					$post_data['ship_state'] = "Dhaka";
					$post_data['ship_postcode'] = "1000";
					$post_data['ship_phone'] = "";
					$post_data['ship_country'] = "Bangladesh";

					$post_data['shipping_method'] = "NO";
					$post_data['product_name'] = "Computer";
					$post_data['product_category'] = "Goods";
					$post_data['product_profile'] = "physical-goods";

					# OPTIONAL PARAMETERS
					$post_data['value_a'] = $orders->payment_id;
					$post_data['value_b'] = "ref002";
					$post_data['value_c'] = "ref003";
					$post_data['value_d'] = "ref004";

					$sslc = new SslCommerzNotification();
					# initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
					$redirectUrl = $sslc->makePayment($post_data, 'hosted');
					$redirectData = [
						'status' => 302,
						'url'	=> $redirectUrl
					];
					return response()->json($redirectData, 200);
				}
			} else {
				$data['status'] = 0;
				$data['message'] = 'Something went wrong.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'User not found.';
			return response()->json($data, 200);
		}
	}



	//digitalPaymentLink
	public function digitalPaymentLink($order_id)
	{
		$order_id = base64_decode($order_id);
		$orders = Order::where('id', $order_id)->first();
		if ($orders) {
			$data['status'] = 1;

			$total_amount = $orders->total_amount;
			$userAddress = Addresses::find($orders->address_id);

			if ($orders->payment_method == 'online_payment') {

				$post_data = array();
				$post_data['total_amount'] = $total_amount; # You cant not pay less than 10
				$post_data['currency'] = "BDT";
				$post_data['tran_id'] = $orders->payment_id;

				# CUSTOMER INFORMATION
				$post_data['cus_name'] = $userAddress->shipping_first_name ?? '' . ' ' . $userAddress->shipping_last_name ?? '';
				$post_data['cus_email'] = $userAddress->shipping_email ?? 'guest-customer@biponi.com';
				$post_data['cus_add1'] = $userAddress->shipping_address ?? '';
				$post_data['cus_add2'] = $userAddress->shipping_thana ?? '';
				$post_data['cus_city'] = $userAddress->shipping_district ?? '';
				$post_data['cus_state'] = $userAddress->shipping_division ?? '';
				$post_data['cus_postcode'] = $userAddress->shipping_postcode ?? '';
				$post_data['cus_country'] = "Bangladesh";
				$post_data['cus_phone'] = $userAddress->shipping_phone ?? '';
				$post_data['cus_fax'] = "";

				# SHIPMENT INFORMATION
				$post_data['ship_name'] = "Store Test";
				$post_data['ship_add1'] = "Dhaka";
				$post_data['ship_add2'] = "Dhaka";
				$post_data['ship_city'] = "Dhaka";
				$post_data['ship_state'] = "Dhaka";
				$post_data['ship_postcode'] = "1000";
				$post_data['ship_phone'] = "";
				$post_data['ship_country'] = "Bangladesh";

				$post_data['shipping_method'] = "NO";
				$post_data['product_name'] = "Computer";
				$post_data['product_category'] = "Goods";
				$post_data['product_profile'] = "physical-goods";

				# OPTIONAL PARAMETERS
				$post_data['value_a'] = "ref001";
				$post_data['value_b'] = "ref002";
				$post_data['value_c'] = "ref003";
				$post_data['value_d'] = "ref004";

				$sslc = new SslCommerzNotification();
				# initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
				$redirectUrl = $sslc->makePayment($post_data, 'hosted');
				return redirect($redirectUrl);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'Something went wrong.';
			return response()->json($data, 200);
		}
	}


	//Cancel Order
	public function cancelOrder($order_id)
	{
		$user = auth('customer-api')->user();
		if ($user) {
			$orders = Order::where('id', $order_id)->first();
			if ($orders) {

				$oldStatus = \Helper::get_status_by_status_id($orders->status)->title;

				if ($orders->payment_method == 'online_payment' && $orders->status == 2) {
					$orders = Order::where('id', $order_id)->update(['status' => 7]); //Status 8 : Refund Requested
					$newStatus = \Helper::get_status_by_status_id(7)->title;
					//Notification and Sms
				} else {
					$orders = Order::where('id', $order_id)->update(['status' => 5]);
					$newStatus = \Helper::get_status_by_status_id(5)->title;
				}


				//Insert Order Log
				$generated_text = $user->name . '(customer) canceled this order and order status has beed changed from ' . $oldStatus . ' to ' . $newStatus . '.';
				\Helper::setOrderLog($order_id, null, $generated_text, $user->id, null);


				$orderDetails = OrderDetails::where('order_id', $order_id)->get();
				if ($orderDetails) {
					foreach ($orderDetails as $single_item) {
						\Helper::update_product_quantity($single_item->product_id, $single_item->product_qty, $single_item->product_options, 'addition');
					}
				}

				$data['status'] = 1;
				$data['message'] = 'Order canceled successfully.';
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Something went wrong.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'User not found.';
			return response()->json($data, 200);
		}
	}



	public function ordersByUserId()
	{
		$user = auth('customer-api')->user();
		if ($user) {
			$orders = Order::where('user_id', $user->id)->where('is_deleted', 0)->orderBy('id', 'desc')->paginate(15);
			foreach ($orders as $key => $item) {
				$p = \Helper::get_product_by_id($item->product_id);
				$item->image = $p->default_image ?? null;
				$item->title = $p->title ?? null;
				$item->slug  = $p->slug ?? null;
				$status = \Helper::get_status_by_status_id($item->status);
				$item->order_status = $status->title;
				$item->status_color = $status->color_code;
			}
			if (count($orders) > 0) {
				$data['status'] = 1;
				$data['orders'] = $orders;
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Orders not found.';
				$data['orders'] = $orders;
				return response()->json($data, 200);
			}
		}else{
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}

	public function getSingleOrder($order_id){
		$user = auth('customer-api')->user();
		if ($user) {

			$order = Order::where('id', $order_id)
				->with('order_details.product', 'order_details.shopinfo')
				->with('statuses')
				->with('address')
				->with('auto_renewal')
				->first();

			$global_subtotal = 0;
			$refunded = 0;

			if ($order) {
				$products = OrderDetails::where('order_id', $order_id)->with('product')->get();

				foreach ($products as $item) {
					if ($item->status == 9) { //Status 9 is about product is delivered
						$now = Carbon::now();
						if ($item->updated_at->diffInDays($now) < 8) {
							$item->is_returnable =  true;
						} else {
							$item->is_returnable =  false;
						}
					}

					if(!$item->shipping_cost){
						$item->shipping_cost = 0;
					}

					$product_sale_option = DB::table('product_metas')->where('product_id', $item->product_id)->where('meta_key', 'product_sale_option')->first();
					if($product_sale_option){
						$item->isDownloadable = $product_sale_option->meta_value;
					}else{
						$item->isDownloadable = 0;
					}
					$file = DB::table('product_metas')->where('product_id', $item->product_id)->where('meta_key', 'product_downloadable_file')->first();
					if($file){
						$extention = explode('.', $file->meta_value);
						$item->file_extension = $extention['1'];
					}else{
						$item->file_extension = null;
					}

					if ($item->product_options) {
						$item->product_options = json_decode($item->product_options);
					}
				}

			
				foreach($order->order_details as $item){
					if ($item->status == 5) {
						$refunded += ($item->product_qty * $item->price);
					}
					$global_subtotal += ($item->product_qty * $item->price); 
				}



				$order->global_subtotal = $global_subtotal;
				$order->refunded = $refunded;
				$order->paid_amount = $order->paid_amount;
				$data['status'] = 1;
				$data['statuses'] = DB::table('statuses')->get();
				$data['products'] = $products;
				$data['order'] = $order;

				if ($order->is_pickpoint != 1) {
					$data['shipping_address'] = Addresses::where('id', $order->address_id)->with('division', 'district', 'upazila', 'union')->first();
				} else {
					$data['shipping_address'] = Pickpoints::where('id', $order->address_id)->with('division', 'district', 'upazila', 'union')->first();
				}

				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Order not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}


	public function downloadFile($prodcut_id, $order_id){
		$product_meta = DB::table('product_metas')->where('product_id', $prodcut_id)->where('meta_key', 'product_downloadable_file')->first();
		if($product_meta){
			$order = DB::table('orders')->where('id', $order_id)->where('status', 6)->where('is_deleted', 0)->first();
			return response()->json($order, 200);

			if($order){
				$user = DB::table('users')->where('id', $order->user_id)->where('status', 1)->where('is_deleted', 0)->first();
				if($user){
					$filename = $product_meta->meta_value;
					$file= storage_path().'/'."downloadable/".$filename;
					return Response::download($file);
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	



	




	public function getReturnProduct(Request $request)
	{
		$order_id = $request->order_id;
		$product_id = $request->product_id;

		$order = Order::where('id', $order_id)
			->with('order_details.product', 'order_details.shopinfo')
			->with('statuses')
			->with('address')
			->first();
		if ($order) {
			$products = OrderDetails::where('order_id', $order_id)->where('product_id', $product_id)->with('product')->with('meta')->first();
			if ($products) {
				$data['status'] = 1;
				$data['products'] = $products;
				$data['order'] = $order;
				$data['shopinfo'] = ShopInfo::where('seller_id', $products->seller_id)->first();
				$data['allStatuses'] = DB::table('statuses')->get();
				$data['shipping_address'] = Addresses::where('id', $order->address_id)->with('division', 'district', 'upazila', 'union')->first();
				return response()->json($data, 200);
			} else {
				$data['status'] = 3;
				$data['message'] = 'Product not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 2;
			$data['message'] = 'Order not found.';
			return response()->json($data, 200);
		}
	}


	


	public function getAllProduct(Request $request)
	{
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$data =  Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->where('is_grocery', 'other')
			->orderby('shuffle_number', 'desc')
			//->with('meta')
			->with('specification')
			->paginate(10);


		if (count($data) > 0) {
			foreach ($data as $product) {
				$product->price_after_offer = number_format((int)\Helper::price_after_offer($product->id), 0);
				$product->price = number_format((int)$product->price, 0);
				$product->offer_percentage = number_format((int)\Helper::offer_percentage_byID($product->id), 0);
				$product->shop_verified = \Helper::is_verified_seller($product->seller_id);
				$product->AllRating = $this->AllRating($product->id) ? $this->AllRating($product->id)->original : null;

				if ($product->meta) {
					foreach ($product->meta as $meta) {
						if ($meta->meta_key == 'custom_options' || $meta->meta_key == 'tab_option') {
							$meta->meta_value = unserialize($meta->meta_value);
							if ($meta->meta_key == 'custom_options') {
								$quantities = [];
								$specificStock = [];
								$option_wise_stock = [];

								foreach ($meta->meta_value as $single_op) {
									foreach ($single_op['value'] as $item) {
										$quantities[] = $item;

										if ($item['qty']) {
											$specificStock[$single_op['title']][] = (int) $item['qty'];
										} else {
											$specificStock[$single_op['title']][] = 0;
										}
									}
								}
								foreach ($specificStock as $key => $val) {
									if (max($val) > 0) {
										$option_wise_stock[$key] = true;
									} else {
										$option_wise_stock[$key] = false;
									}
								}

								$product->hidden_option = $option_wise_stock;

								$total_qty = [];
								foreach ($quantities as $single_variant) {
									$total_qty[] = $single_variant['qty'];
								}


								if (count(array_filter($total_qty)) == 0) {
									$product->calculated_in_stock = false;
								} else {
									$product->calculated_in_stock = true;
								}
							}
						}
					}
				}
				//Localization
				$lang = app()->getLocale();
				if ($lang != 'en') {
					$langData = ProductLocalization::where('product_id', $product->id)->where('is_active', 1)->where('lang_code', $lang)->first();
					if ($langData) {
						$product->title = $langData->title ? $langData->title : $product->title;
					}
				}
			}
			return response()->json($data, 200);
		} else {
			$d['status'] = 0;
			$d['products'] = [];
			$d['message'] = 'Product not found.';
			return response()->json($d, 200);
		}
	}

	


	public function getPromotionProduct(Request $request, $product_type)
	{
		if ($product_type == 'flashsale-products') {
			$product_type = 'flash_sale_products';
			$banner = $this->base_url.'/'. \Helper::getsettings('flashsale_products_banner');
		} elseif ($product_type == 'onsale-products') {
			$product_type = 'on_sale_products';
			$banner =   $this->base_url.'/'. \Helper::getsettings('onsalesale_products_banner');
		} elseif ($product_type == 'featured-products') {
			$product_type = 'featured_products';
			$banner =  $this->base_url.'/'. \Helper::getsettings('featured_products_banner');
		} elseif ($product_type == 'bestselling-products') {
			$product_type = 'bestselling_products';
			$banner =  $this->base_url.'/'. \Helper::getsettings('bestselling_products_banner');
		} elseif ($product_type == 'most-viewed-products') {
			$product_type = 'most_viewed_products';
			$banner = $this->base_url.'/'. \Helper::getsettings('most_viewed_products_banner');
		} else {
			$d['status'] = 0;
			$d['products'] = '';
			$d['message'] = 'Product not found. 1';
			return response()->json($d, 200);
		}
		$on_sale_products_ids = \Helper::getsettings($product_type);
		$on_sale_products_ids_array = explode(',', $on_sale_products_ids);
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$data = Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->whereIn('id', $on_sale_products_ids_array)
			->with('meta')
			->with('specification')
			->orderby('shuffle_number', 'desc')
			->paginate(18);
		foreach ($data as $item) {
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $product->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$product->title = $langData->title ? $langData->title : $product->title;
				}
			}
		}
		if (count($data) > 0) {


			$d['status'] = 1;
			$d['products'] = $data;
			$d['banner'] = $banner;


			return response()->json($d, 200);
		} else {
			$d['status'] = 0;
			$d['products'] = '';
			$d['message'] = 'Product not found. 2';
			return response()->json($d, 200);
		}
	}


	public function getGroceries(Request $request)
	{
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$data = Product::where('is_active', 1)
			->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->with('meta')
			->with('specification')
			->orderby('shuffle_number', 'desc')
			->paginate(15);
		foreach ($data as $item) {
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $product->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$product->title = $langData->title ? $langData->title : $product->title;
				}
			}
		}
		return response()->json($data, 200);
	}









	public function getCategoryProduct($slug, $promotion = null)
	{
		$category = Category::where('slug', $slug)->first();
		if (!$category && !$category->id) {
			$d['status'] = 0;
			$d['message'] = 'Category not found.';
			return response()->json($d, 200);
		}

		$category_id = $category->id;
		$categories_ids = [];

		if ($category->show_child_products == 1) {
			$catLevel1 = Category::select('id', 'show_child_products')->where('parent_id', $category_id)->get();

			if ($catLevel1) {
				foreach ($catLevel1 as $cat1) {
					$categories_ids[] = $cat1->id;

					if ($cat1->show_child_products == 1) {
						$catLevel2 = Category::select('id', 'show_child_products')->where('parent_id', $cat1->id)->get();
						if ($catLevel2) {

							foreach ($catLevel2 as $cat2) {
								$categories_ids[] = $cat2->id;


								if ($cat2->show_child_products == 1) {
									$catLevel3 = Category::select('id', 'show_child_products')->where('parent_id', $cat2->id)->get();
									if ($catLevel3) {
										foreach ($catLevel3 as $cat3) {
											$categories_ids[] = $cat3->id;
										}
									}
								}
							}
						}
					}
				}
			}
		}

		$query = "FIND_IN_SET($category_id,category_id)";

		if ($categories_ids) {
			foreach ($categories_ids as $catId) {
				$query .= ' OR FIND_IN_SET(' . $catId . ',category_id)';
			}
		}


		$data = Product::whereRaw($query)
			->where('is_active', 1)
			->where('is_deleted', 0)
			->where('product_qc', 1)
			->where('is_promotion', 0)
			->with('meta')
			->with('specification')
			->orderby('shuffle_number', 'desc')
			->paginate(5);


		foreach ($data as $item) {
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
			}
		}
		if (count($data) > 0) {
			$d['category_banner'] = $category->banner;
			$d['status'] = 1;
			$d['products'] = $data;
			return response()->json($d, 200);
		} else {
			$d['status'] = 0;
			$d['products'] = '';
			$d['message'] = 'Product not found.';
			return response()->json($d, 200);
		}
	}


	public function getPromotionalCategoryProduct($slug, $promotion = null)
	{

		$category = Category::where('slug', $slug)->first();
		if (!$category && !$category->id) {
			$d['status'] = 0;
			$d['message'] = 'Category not found.';
			return response()->json($d, 200);
		}

		$data = Product::whereRaw("find_in_set({$category->id},category_id)")
			->where('is_active', 1)
			->where('is_deleted', 0)
			->where('product_qc', 1)
			//->where('is_promotion', 1)
			->with('meta')
			->with('specification')
			->orderby('shuffle_number', 'desc')
			->paginate(15);


		foreach ($data as $item) {
			$item->price_after_offer = number_format((int)\Helper::price_after_offer($item->id), 0);
			$item->price = number_format((int)$item->price, 0);
			$item->offer_percentage = number_format((int)\Helper::offer_percentage_byID($item->id), 0);
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->AllRating = $this->AllRating($item->id) ? $this->AllRating($item->id)->original : null;
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
			}
		}
		if (count($data) > 0) {
			$d['category_banner'] = $category->banner;
			$d['status'] = 1;
			$d['products'] = $data;
			return response()->json($d, 200);
		} else {
			$d['status'] = 0;
			$d['products'] = '';
			$d['message'] = 'Product not found.';
			return response()->json($d, 200);
		}
	}




	public function changePassword(Request $request)
	{
		$user = auth('customer-api')->user();
		$old_password = $request->old_password;
		if ($user) {
			if ($request->new_password == $request->retype_password) {
				$data['status'] = 1;
				$data['message'] = 'Password change successfully.';
				DB::table('users')->where('id', $user->id)->update(['password' => Hash::make($request->retype_password)]);
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Password does not match.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'Please login first.';
			return response()->json($data, 200);
		}
	}

	public function forgotPassword(Request $request)
	{

		$otp_code = $request->otp_code;
		$mobile_number = $request->mobile_number;

		if (filter_var($mobile_number, FILTER_VALIDATE_EMAIL)) {
			$user = DB::table('users')->where('email', $mobile_number)->where('status', 1)->first();
			
			if (!$user) {
				$data['status'] = 0;
				$data['message'] = 'This email is not associated with any account';
				return response()->json($data, 200);
			}

		} else {
			$user = DB::table('users')->where('phone', $mobile_number)->where('status', 1)->first();

			if (!$user) {
				$data['status'] = 0;
				$data['message'] = 'This mobile number is not associated with any account';
				return response()->json($data, 200);
			}
		}


		if ($this->verifyOTP($user->phone, $otp_code) == 1) {
			if ($request->new_password == $request->retype_password) {
				$data['status'] = 1;
				$data['message'] = 'Password change successfully.';

				DB::table('users')->where('id', $user->id)->update(['password' => Hash::make($request->retype_password)]);

				DB::table('otp')->where('otp_code', $otp_code)->update(['status' => 1]);
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Password does not match.';
				return response()->json($data, 200);
			}
		}else if($this->verifyOTP($user->phone, $otp_code) == 0){
			$data['status'] = 0;
			$data['message'] = 'response 0';
			return response()->json($data, 200);
		}else if($this->verifyOTP($user->phone, $otp_code) == 2){
			$data['status'] = 0;
			$data['message'] = 'response 2';
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'OTP does not match or expired.';
			return response()->json($data, 200);
		}
	}




	public function categorySearch($categories)
	{
		$products =  DB::table('products')->whereIn('category_id',  explode(",", $categories))->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)
			->with('meta')
			->with('specification')
			->paginate(10);
		if (count($products) > 0) {
			$data['status'] = 1;
			$data['products'] = $products;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['products'] = 'Product not found.';
			return response()->json($data, 200);
		}
	}
	public function getOrderList()
	{
		$user = auth('customer-api')->user();
		if ($user) {
			$orders =  DB::table('orders')->where('user_id', $user->id)->orderBy('id', 'desc')->get();
			if (count($orders) > 0) {
				$data['status'] = 0;
				$data['orders'] = $orders;
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Order not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}


	public function getSearchProduct(Request $request){
		$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$query =  product::where('is_active', 1)->where('is_deleted', 0)
			->with('meta')
			->with('specification')
			->where('product_qc', 1);
		$content = $request->content ?? null;
		$productspage = $request->productspage ?? null;
		if ($request->brands) {
			$x = explode(',', $request->brands);
			$brands = [];
			foreach ($x as $key => $value) {
				$brands[] = (int)$value;
			}
		} else {
			$brands = null;
		}

		if ($content) {
			if (product::where('title', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0)->count() > 0) {
				$query->where('title', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0);
			} elseif (product::where('category_title', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0)->count() > 0) {
				$query->where('category_title', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0);
			} elseif (product::where('brand_title', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0)->count() > 0) {
				$query->where('brand_title', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0);
			} elseif (product::where('sku', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0)->count() > 0) {
				$query->where('sku', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0);
			} elseif (product::where('short_description', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0)->count() > 0) {
				$query->where('short_description', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0);
			} elseif (product::where('product_type', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0)->count() > 0) {
				$query->where('product_type', 'LIKE', "%$content%")->where('is_active', 1)->where('is_deleted', 0);
			}
		}


		//IF it from category
		if ($request->slug && $request->slug != "null") {
			$cat = DB::table('categories')->where('slug', $request->slug)->first();
			if ($cat) {
				$categories = "$cat->id";
			} else {
				$categories = null;
			}
		} else {
			$categories = $request->categories;
		}


		if ($request->brands) {
			$query->whereIn('brand_id', $brands);
		}


		$categories_ids = [];
		if ($categories) {
			foreach (explode(',', $categories) as $category_id) {
				$category = Category::select('id', 'show_child_products')->where('id', $category_id)->first();
				if ($category) {
					$categories_ids[] = $category->id;
					if ($category->show_child_products == 1) {
						$catLevel1 = Category::select('id', 'show_child_products')->where('parent_id', $category->id)->get();

						if ($catLevel1) {
							foreach ($catLevel1 as $cat1) {
								$categories_ids[] = $cat1->id;

								if ($cat1->show_child_products == 1) {
									$catLevel2 = Category::select('id', 'show_child_products')->where('parent_id', $cat1->id)->get();
									if ($catLevel2) {

										foreach ($catLevel2 as $cat2) {
											$categories_ids[] = $cat2->id;


											if ($cat2->show_child_products == 1) {
												$catLevel3 = Category::select('id', 'show_child_products')->where('parent_id', $cat2->id)->get();
												if ($catLevel3) {
													foreach ($catLevel3 as $cat3) {
														$categories_ids[] = $cat3->id;
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			$subquery = "";
			if ($categories_ids) {
				$count = 1;
				foreach ($categories_ids as $catId) {
					if($count == 1){
						$subquery .= 'FIND_IN_SET(' . $catId . ',category_id)';
					}else{
						$subquery .= ' OR FIND_IN_SET(' . $catId . ',category_id)';
					}
					$count++;
				}
			}
			$query->whereRaw('(' . $subquery . ')');
		}


		$query->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))");


		if ($request->orederbyselect && $request->orederbyselect != 'undefined') {
			if ($request->orederbyselect == 'lowtohigh') {
				$query->orderBy('price', 'ASC');
			}
			if ($request->orederbyselect == 'hightolow') {
				$query->orderBy('price', 'DESC');
			}
			if ($request->orederbyselect == 'atoz') {
				$query->orderBy('title', 'ASC');
			}
			if ($request->orederbyselect == 'ztoa') {
				$query->orderBy('title', 'DESC');
			}
		} else {
			$query->orderBy('shuffle_number', 'DESC');
		}




		if (($request->minprice && $request->minprice != 'undefined') && ($request->maxprice  && $request->maxprice != 'undefined')) {
			$query->where('price', '>=', $request->minprice)->where('price', '<=', $request->maxprice);
		}

		if ($request->minprice && $request->minprice != 'undefined') {
			$query->where('price', '>=', $request->minprice);
		}
		if ($request->maxprice && $request->maxprice != 'undefined') {
			$query->where('price', '<=', $request->maxprice);
		}
		if($request->shop_slug) {
			$seller = DB::table('shop_info')->where('slug', $request->shop_slug)->first();
			$query->where('seller_id', $seller->seller_id);
			$search_by['shop_slug'] = $request->shop_slug.' ======= seller id:'.$seller->seller_id;
		}

		$search_by['slug'] = $request->slug;
		$search_by['productspage'] = $productspage;
		$search_by['by_content'] = $content;
		$search_by['by_categories'] = $categories_ids;
		$search_by['by_brans'] = $brands;
		$search_by['by_orderBy'] = $request->orederbyselect;
		$search_by['minprice'] = $request->minprice;
		$search_by['maxprice'] = $request->maxprice;

		$products = $query->paginate(16);

		if ($request->productspage == 1) {
			if ($request->brands == null && $request->categories == null && $content == "undefined" && $request->orederbyselect == "undefined" && $request->minprice == "undefined" && $request->maxprice == "undefined") {
				$products = product::where('is_active', 1)->where('is_deleted', 0)->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")->where('product_qc', 1)->orderBy('shuffle_number', 'DESC')->paginate(12);
			}
		}
		foreach ($products as $product) {
			$product->price_after_offer = number_format((int)\Helper::price_after_offer($product->id), 0);
			$product->price = number_format((int)$product->price, 0);
			$product->offer_percentage = number_format((int)\Helper::offer_percentage_byID($product->id), 0);
			$product->shop_verified = \Helper::is_verified_seller($product->seller_id);
			$product->AllRating = $this->AllRating($product->id) ? $this->AllRating($product->id)->original : null;
			$product->search_by = $search_by;


			if($product->meta) {
				foreach ($product->meta as $meta) {
					if ($meta->meta_key == 'custom_options' || $meta->meta_key == 'tab_option') {
						$meta->meta_value = unserialize($meta->meta_value);
						if ($meta->meta_key == 'custom_options') {
							$quantities = [];
							$specificStock = [];
							$option_wise_stock = [];

							foreach ($meta->meta_value as $single_op) {
								foreach ($single_op['value'] as $item) {
									$quantities[] = $item;

									if ($item['qty']) {
										$specificStock[$single_op['title']][] = (int) $item['qty'];
									} else {
										$specificStock[$single_op['title']][] = 0;
									}
								}
							}
							foreach ($specificStock as $key => $val) {
								if (max($val) > 0) {
									$option_wise_stock[$key] = true;
								} else {
									$option_wise_stock[$key] = false;
								}
							}

							$product->hidden_option = $option_wise_stock;

							$total_qty = [];
							foreach ($quantities as $single_variant) {
								$total_qty[] = $single_variant['qty'];
							}


							if (count(array_filter($total_qty)) == 0) {
								$product->calculated_in_stock = false;
							} else {
								$product->calculated_in_stock = true;
							}
						}
					}
				}
			}
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = ProductLocalization::where('product_id', $product->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$product->title = $langData->title ? $langData->title : $product->title;
				}
			}
		}
		if (count($products) > 0) {
			$data['status'] = 1;
			$data['products'] = $products;
			$data['search_by'] = $search_by;
			return response()->json($data, 200);

		} else {
			$data['status'] = 0;
			$data['products'] = $products;
			$data['message'] = 'Product not found .';
			$data['search_by'] = $search_by;
			return response()->json($data, 200);
		}
	}






	public function getAllSellers()
	{
		$sellers = ShopInfo::where('status', 1)->paginate(24);

		foreach ($sellers as $item) {
			$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
			$item->ratings = \Helper::sellerRatingCalculation($item->seller_id);
		}

		if ($sellers) {
			$data['status'] = 1;
			$data['sellers'] = $sellers;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Seller not found.';
			return response()->json($data, 200);
		}
	}


	public function getAllSellersPost(Request $request)
	{
		if ($request->search) {
			$sellers = ShopInfo::where('name', 'LIKE', "%$request->search%")->where('status', 1)->paginate(24);

			foreach ($sellers as $item) {
				$item->shop_verified = \Helper::is_verified_seller($item->seller_id);
				$item->ratings = \Helper::sellerRatingCalculation($item->seller_id);
			}

			if ($sellers) {
				$data['status'] = 1;
				$data['sellers'] = $sellers;
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Seller not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'Seller not found.';
			return response()->json($data, 200);
		}
	}




	public function compareList(Request $request)
	{
		$user = auth('customer-api')->user();
		if ($user) {
			$list = Compare::where('user_id', $user->id)->get();
			if (count($list) > 0) {
				$data['status'] = 1;
				$data['list'] = $list;
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Data not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}
	
	public function get_divisons(){
		$data['divisons'] = DB::table('divisions')->where('status', 1)->get();
		$data['status'] = 1;
		return response()->json($data, 200);
	}
	public function get_district($id)
	{
		$html = [];
		$division_id = $id;
		$districts = District::where('division_id', $division_id)->orderBy('title', 'asc')->get();
		foreach ($districts as $district) {
			$html[] = [
				'id' => $district->id,
				'title' => $district->title,
			];
		}
		return response()->json($html, 200);
	}


	public function get_upazila($id)
	{
		$html = [];
		$district_id =  $id;
		$upazilas = Upazila::where('district_id', $district_id)->orderBy('title', 'asc')->get();
		foreach ($upazilas as $upazila) {
			$html[] = [
				'id' => $upazila->id,
				'title' => $upazila->title,
			];
		}
		return $html;
	}

	public function get_get_upazila_by_title($title)
	{
		$data = [];
		$upazila = Upazila::where('title', 'like', '%' . $title . '%')->first();
		if($upazila){
			$data['status'] = 200;
			$data['upazila_id'] = $upazila->id;
		}else{
			$data['status'] = 404;
			$data['upazila_id'] = null;
		}
		return response()->json($data, 200);
	}

	

	public function get_union($id)
	{
		$html = [];
		$upazila_id = $id;
		$unions = Union::where('upazila_id', $upazila_id)->orderBy('title', 'asc')->get();
		foreach ($unions as $union) {
			$html[] = [
				'id' => $union->id,
				'title' => $union->title,
			];
		}
		return $html;
	}

	public function getAddress()
	{
		$user = auth('customer-api')->user();
		$address = Addresses::where('user_id', $user->id)->with('division', 'district', 'upazila', 'union')->get();
		return response()->json($address, 200);
	}

	public function get_shipping_rates($district_id, $product_id, $seller_id)
	{
		$user = auth('customer-api')->user();
		$user_id = $user->id;

		$shippingOptions = \Helper::get_shipping_cost($user_id, $seller_id, $product_id, $district_id);
		return response()->json($shippingOptions, 200);
	}

	public function viewNotification(Request $request)
	{
		$user = auth('customer-api')->user();
		if ($user && $request->notification_id) {
			$notification = DB::table('notifications')->where('id', $request->notification_id)->update(['status' => 0]);
			$data['status'] = 1;
			$data['message'] = 'Notification Seen.';
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'User or notification not found.';
			return response()->json($data, 200);
		}
	}



	// get customer vouchers
	public function getCustomerVouchers()
	{
		$user = auth('customer-api')->user();
		if ($user) {
			$categories = VoucherCategory::where('status', 1)->with('voucher')->get();
			if (count($categories) > 0) {
				foreach ($categories as $category) {
					foreach ($category->voucher as $row) {

						if ($row) {
							$collectedVoucher = CollectedVoucher::where('user_id', $user->id)->where('voucher_id', $row->id)->where('status', 0)->first();
							$usedVoucherCount = CollectedVoucher::where('user_id', $user->id)->where('voucher_id', $row->id)->where('status', 2)->count();

							if ($usedVoucherCount < $row->max_qty_per_user) {
								if ($collectedVoucher) {
									$row->collected = 1;
								} else {
									$row->collected = 0;
								}
							} else {
								$row->collected = 2;
							}
						}
					}
				}

				$data['status'] = 1;
				$data['voucher_category'] = $categories;
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Voucher not found.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'User not found.';
			return response()->json($data, 200);
		}
	}

	public function getMyCollectedVouchers()
	{
		$user = auth('customer-api')->user();
		$user_id = $user->id;
		if ($user_id) {
			$vouchers = CollectedVoucher::where('user_id', $user_id)->with('voucher')->get();
			$data['status'] = 1;
			$data['my_vouchers'] = $vouchers;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'User not found.';
			return response()->json($data, 200);
		}
	}





	public function getHomePageVoucher()
	{
		$voucher_ids = \Helper::getSettings('homepage_voucher_list');
		$voucher_ids = explode(',', $voucher_ids);
		$vouchers = Voucher::where('is_active', 1)->where('is_deleted', 0)->whereIn('voucher_category_id', $voucher_ids)->get();
		$user = auth('customer-api')->user();
		$user_id = $user->id ?? null;


		if (count($vouchers) > 0) {
			foreach ($vouchers as $voucher) {
				if ($voucher) {
					$collectedVoucher = CollectedVoucher::where('user_id', $user_id)->where('voucher_id', $voucher->id)->where('status', 0)->first();
					$usedVoucherCount = CollectedVoucher::where('user_id', $user_id)->where('voucher_id', $voucher->id)->where('status', 2)->count();

					if ($usedVoucherCount < $voucher->max_qty_per_user) {
						if ($collectedVoucher) {
							$voucher->collected = 1;
						}
					} else {
						$voucher->collected = 2;
					}
				}
			}

			$data['status'] = 1;
			$data['vouchers'] = $vouchers;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Voucher not found.';
			return response()->json($data, 200);
		}
	}



	// collect voucher
	public function collectCustomerVoucher(Request $request)
	{
		$user = auth('customer-api')->user();

		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		} else {
			if ($request->voucher_id) {
				if (CollectedVoucher::where('user_id', $user->id)->where('status', 0)->exists()) {
					//Existing User

					if ($collec = CollectedVoucher::where('user_id', $user->id)->where('status', 0)->first()) {


						if ($collec->status == 0) {
							CollectedVoucher::where('user_id', $user->id)->where('status', 0)->update(
								[
									'qty' => 1,
									'status' => 0,
									'voucher_id' => $request->voucher_id
								]
							);
							$data['status'] = 1;
							$data['message'] = 'Voucher Collected successfully.';
							return response()->json($data, 200);
						} else {
							$data['status'] = 0;
							$data['message'] = 'This voucher is already collected!';
							return response()->json($data, 200);
						}
					}
				} else {
					$voucher = new CollectedVoucher();
					$voucher->user_id = $user->id;
					$voucher->voucher_id = $request->voucher_id;
					$voucher->qty = 1;
					$voucher->status = 0;
					$voucher->save();

					$data['status'] = 1;
					$data['message'] = 'Voucher Collected successfully.';
					return response()->json($data, 200);
				}
			} else {
				$data['status'] = 0;
				$data['message'] = 'Voucher not found.';
				return response()->json($data, 200);
			}
		}
	}

	public function getCollectedVouchers()
	{
		$user = auth('customer-api')->user();
		$result = [];
		$vouchers = [];
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}

		$collected_vouchers = CollectedVoucher::where('user_id', $user->id)->where('status', 0)->get();
		foreach ($collected_vouchers as $cv) {
			$voucherData = \Helper::validateVoucher($cv->voucher_id, $user->id);
			if ($voucherData['status'] == 1) {
				$vouchers[] = $voucherData['code'];
			}
		}

		$result = CollectedVoucher::whereIn('voucher_id', $vouchers)->where('user_id', $user->id)->where('status', 0)->with('voucher')->get();

		return $result;
	}


	public function getUseableVouchers()
	{
		$user = auth('customer-api')->user();
		$result = [];
		$vouchers = [];
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}


		$collected_vouchers = CollectedVoucher::where('user_id', $user->id)->where('status', 1)->get();
		foreach ($collected_vouchers as $cv) {
			$voucherData = \Helper::validateVoucher($cv->voucher_id, $user->id);
			if ($voucherData['status'] == 1) {
				$vouchers[] = $voucherData['code'];
			}
		}

		$result = CollectedVoucher::whereIn('voucher_id', $vouchers)->where('user_id', $user->id)->where('status', 1)->with('voucher')->get();

		return $result;
	}





	// Offers 

	// get ragular offers 
	public function getCustomerRegularOffers()
	{
		$data['regular_offer_status'] = \Helper::getsettings('regular_offer_status');
		$data['regular_offer_section_name'] = \Helper::getsettings('regular_offer_section_name');
		$data['regular_offer_banner'] = \Helper::getsettings('regular_offer_banner');
		$category = \Helper::getsettings('regular_offer_category');

		$categoryIds = explode(',', $category);
		$data['regular_offer_categories'] = Category::whereIn('id', $categoryIds)->get();
		return response()->json($data, 200);
	}


	// get ragular offers 
	public function getCustomerPromotionalOffers()
	{
		$data['promotional_offer_status'] = \Helper::getsettings('promotional_offer_status');
		$data['promotional_offer_section_name'] = \Helper::getsettings('promotional_offer_section_name');
		$data['promotional_offer_banner'] = \Helper::getsettings('promotional_offer_banner');
		$category = \Helper::getsettings('promotional_offer_category');

		$categoryIds = explode(',', $category);
		$data['promotional_offer_categories'] = Category::whereIn('id', $categoryIds)->get();

		return response()->json($data, 200);
	}
	public function getPromotionTitle()
	{
		$data['promotional_offer'] = \Helper::getsettings('promotional_offer_section_name') ?? 'Cyclone Offer';
		$data['regular_offer'] = \Helper::getsettings('regular_offer_section_name') ?? 'Offers';

		if ($data) {
			return response()->json($data, 200);
		} else {
			return null;
		}
	}


	public function getAd()
	{
		$ad1['status'] = \Helper::getsettings('home_banner_status_1');
		$ad1['banner'] = \Helper::getsettings('homepage_banner_1');
		$ad1['link_type'] = \Helper::getsettings('homepage_add_banner_linktype_1');
		$ad1['link'] = \Helper::getsettings('homepage_add_banner_link_1');

		$ad2['status'] = \Helper::getsettings('home_banner_status_2');
		$ad2['banner'] = \Helper::getsettings('homepage_banner_2');
		$ad2['link_type'] = \Helper::getsettings('homepage_add_banner_linktype_2');
		$ad2['link'] = \Helper::getsettings('homepage_add_banner_link_2');

		$add = true;
		if ($add) {
			$data['status'] = 1;
			$data['ad1'] = $ad1;
			$data['ad2'] = $ad2;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Voucher not found.';
			return response()->json($data, 200);
		}
	}
	public function getPromotionalBanner()
	{
		$promotional_banner_1['status'] = \Helper::getsettings('promotional_1_status');
		$promotional_banner_1['banner'] = \Helper::getsettings('promotional_1_banner');
		$promotional_banner_1['link_type'] = \Helper::getsettings('promotional_1_url_type');
		$promotional_banner_1['link'] = \Helper::getsettings('promotional_1_url');

		$promotional_banner_2['status'] = \Helper::getsettings('promotional_2_status');
		$promotional_banner_2['banner'] = \Helper::getsettings('promotional_2_banner');
		$promotional_banner_2['link_type'] = \Helper::getsettings('promotional_2_url_type');
		$promotional_banner_2['link'] = \Helper::getsettings('promotional_2_url');

		$promotional_banner_3['status'] = \Helper::getsettings('promotional_3_status');
		$promotional_banner_3['banner'] = \Helper::getsettings('promotional_3_banner');
		$promotional_banner_3['link_type'] = \Helper::getsettings('promotional_3_url_type');
		$promotional_banner_3['link'] = \Helper::getsettings('promotional_3_url');

		$promotional_banner_4['status'] = \Helper::getsettings('promotional_4_status');
		$promotional_banner_4['banner'] = \Helper::getsettings('promotional_4_banner');
		$promotional_banner_4['link_type'] = \Helper::getsettings('promotional_4_url_type');
		$promotional_banner_4['link'] = \Helper::getsettings('promotional_4_url');

		$promotional_banner_5['status'] = \Helper::getsettings('promotional_5_status');
		$promotional_banner_5['banner'] = \Helper::getsettings('promotional_5_banner');
		$promotional_banner_5['link_type'] = \Helper::getsettings('promotional_5_url_type');
		$promotional_banner_5['link'] = \Helper::getsettings('promotional_5_url');

		$promotional_banner_6['status'] = \Helper::getsettings('promotional_6_status');
		$promotional_banner_6['banner'] = \Helper::getsettings('promotional_6_banner');
		$promotional_banner_6['link_type'] = \Helper::getsettings('promotional_6_url_type');
		$promotional_banner_6['link'] = \Helper::getsettings('promotional_6_url');




		$howto['how_to_order_from_website_image'] = explode(',', \Helper::getsettings('how_to_order_from_website_image') ?? null);
		$howto['how_to_order_from_website_description'] = \Helper::getsettings('how_to_order_from_website_description') ?? null;




		$data['how_to_order_from_website'] = $howto;
		$data['promotional_banner_1'] = $promotional_banner_1;
		$data['promotional_banner_2'] = $promotional_banner_2;
		$data['promotional_banner_3'] = $promotional_banner_3;
		$data['promotional_banner_4'] = $promotional_banner_4;
		$data['promotional_banner_5'] = $promotional_banner_5;
		$data['promotional_banner_6'] = $promotional_banner_6;

		return response()->json($data, 200);
	}




	//OTP Generator
	public function isOtpLimitExcceds($mobileNumber)
	{
		//Return true if exceeds
		$res = false;
		$olderData = DB::table('otp')->where('mobile_number', $mobileNumber)->whereDate('created_at', date('Y-m-d'))->get();
		if (count($olderData) > 4) {
			$res = true;
		}
		return $res;
	}


	public function generateOTP(Request $request)
	{
		$mobileNumber = $request->mobile_number;
		$is_forgot_password = $request->is_forgot_password;

		if (!$mobileNumber) {
			$data['status'] = 0;
			$data['message'] = 'Please provide mobile number to generate otp.';
			return response()->json($data, 200);
		}
		

		if($is_forgot_password){
			if (filter_var($mobileNumber, FILTER_VALIDATE_EMAIL)) {
				$user = DB::table('users')->where('email', $mobileNumber)->where('status', 1)->first();
				
				if (!$user) {
					$data['status'] = 0;
					$data['message'] = 'This email is not associated with any account';
					return response()->json($data, 200);
				}

			} else {
				$user = DB::table('users')->where('phone', $mobileNumber)->where('status', 1)->first();
				
				if (!$user) {
					$data['status'] = 0;
					$data['message'] = 'This mobile number is not associated with any account';
					return response()->json($data, 200);
				}
			}
		}
		

		if (!$this->isOtpLimitExcceds($mobileNumber)) {

			$otp = mt_rand(100000, 999999);
			$msg = 'আপনার ওটিপি কোড হল ' . $otp . '। কোডটি ৩ মিনিটের পর আর ব্যবহার করা যাবে না। অনুগ্রহ করে আপনার ওটিপি অন্যদের সাথে শেয়ার করবেন না।';

			\Helper::sendSms($mobileNumber, $msg);

			// if ($request->server('HTTP_HOST') != '127.0.0.1:8000') {
			// 	if($user){
			// 		if($user->email){
			// 			$subject = 'ওটিপি কোড';
			// 			\Helper::sendEmail($user->email, $subject, $msg, 'default');
			// 		}
			// 	}
			// }

			$dbData = [
				'mobile_number' => $mobileNumber,
				'otp_code'	=> $otp,
				'status'	=> 0,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
				
			];

			$updated = DB::table('otp')->insert($dbData);  //Insert OTP
			if ($updated) {
				$data['status'] = 1;
				$data['message'] = 'OTP has been generated successfully.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'Maximum OTP generation limit exceeds for today.';
			return response()->json($data, 200);
		}
	}


	public function otpLogin(Request $request)
	{
		$mobileNumber = $request->mobile_number;
		$otp_code = $request->otp;

		if (!$mobileNumber) {
			$data['status'] = 0;
			$data['message'] = 'Invalid mobile number!';
			return response()->json($data, 200);
		}

		if (!$otp_code) {
			$data['status'] = 0;
			$data['message'] = 'Please provide OTP.';
			return response()->json($data, 200);
		}

		if ($this->verifyOTP($mobileNumber, $otp_code) == 1) {
			$user = User::where('phone', $mobileNumber)->first();

			if ($user) {
				if ($user->status == 0) {
					$data['status'] = 0;
					$data['message'] = 'Your account is inactive.';
					return response()->json($data, 200);
				} elseif ($user->is_deleted == 1) {
					$data['status'] = 0;
					$data['message'] = 'Your account has been deleted!';
					return response()->json($data, 200);
				} else {

					if (!$token = Auth::guard('customer-api')->login($user)) {
						$data['status'] = 0;
						$data['message'] = 'Unable to login. Something went wrong!';
						return response()->json($data, 200);
					}
					DB::table('otp')->where('otp_code', $otp_code)->update(['status' => 1]);

					if ($session_key = $request->session_key) {
						DB::table('carts')->where('session_key', $session_key)->update(['user_id' => $user->id]);
						DB::table('compares')->where('session_key', $session_key)->update(['user_id' => $user->id]);
						DB::table('wishlists')->where('session_key', $session_key)->update(['user_id' => $user->id]);
					}

					$total_order = Order::where('user_id', $user->id)->where('status', 6)->count();
					$order_details = OrderDetails::where('user_id', $user->id)->get();
					$total_spends = Order::where('user_id', $user->id)->sum('total_amount');
					$loyalty_points = \Helper::UserLoyaltyPoits($user->id);
					$user->total_order = $total_order;
					$user->total_spends = $total_spends;
					$user->loyalty_points = $loyalty_points;


					return response()->json([
						'status' => 1,
						'old_user'=>true,
						'message' => 'Thank you for login!',
						'customer' => $user,
						'address' => Addresses::where('user_id', $user->id)->with('division', 'district', 'upazila', 'union')->first(),
						'token' => $token,
						'expire' => auth('customer-api')->factory()->getTTL() * 6000
					], 200);
				}
			} else {
				//Create New User
				$password = rand(100000, 999999);
				$user_id = DB::table('users')->insertGetId(
					[
						'phone' => $mobileNumber,
						'password' => \Hash::make($password),
						'status'   => 1
					]
				);

				if ($user_id) {
					$user = User::where('id', $user_id)->first();
					if (!$token = Auth::guard('customer-api')->login($user)) {
						$data['status'] = 0;
						$data['message'] = 'Unable to login. Something went wrong!';
						return response()->json($data, 200);
					}

					DB::table('otp')->where('otp_code', $otp_code)->update(['status' => 1]);

					$msg = 'Your account has been created. Your temporary password is ' . $password . ' . Use this password to access your account.';
					\Helper::sendsms($mobileNumber, $msg);

					if ($session_key = $request->session_key) {
						DB::table('carts')->where('session_key', $session_key)->update(['user_id' => $user->id]);
						DB::table('compares')->where('session_key', $session_key)->update(['user_id' => $user->id]);
						DB::table('wishlists')->where('session_key', $session_key)->update(['user_id' => $user->id]);
					}

					return response()->json([
						'status' => 1,
						'message' => 'Thank you for login!',
						'customer' => $user,
						'address' => Addresses::where('user_id', $user->id)->with('division', 'district', 'upazila', 'union')->first(),
						'token' => $token,
						'expire' => auth('customer-api')->factory()->getTTL() * 6000
					], 200);
				}
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'OTP is invalid or expired!';
			return response()->json($data, 200);
		}
	}



	public function verifyOTP($mobileNumber, $otp_code)
	{
		$res = 0;
		$otpData = DB::table('otp')->where('mobile_number', $mobileNumber)->where('otp_code', $otp_code)->orderby('id', 'desc')->where('status', 0)->first();
		if ($otpData) {
			$otptime = $otpData->created_at;
			$currentTime = date('Y-m-d H:i:s');
			$to_time = strtotime($currentTime);
			$from_time = strtotime($otptime);
			$muniteDiff =  round(abs($to_time - $from_time) / 60, 2);
			if ($muniteDiff < 4) { //3 Min Validity
				$res = 1;
			} else {
				$res = 2;
			}
		}
		return $res;
	}
	public function userName(Request $request){
		$number =  $request->number;
		$name =  $request->name;
		$useName =  DB::table('users')
		->where('phone', $number)
		->update(['name' => $name]);

		if($useName){
			$data['status'] = 1;
			$data['message'] = 'Welcome! '.$name;
			return response()->json($data, 200);
		}else{
			$data['status'] = 0;
			$data['message'] = 'Something is wrong';
			return response()->json($data, 500);
		}
	}
	public function checkAuth()
	{
		$user = auth('customer-api')->user();
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'Please login to access this page.';
			return response()->json($data, 200);
		} else {
			$data['status'] = 1;
			$data['message'] = 'Authenticated!';
			return response()->json($data, 200);
		}
	}



	public function blogCategories()
	{
		$categories = DB::table('blog_categories')->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'DESC')->get();
		foreach ($categories as $item) {
			//Localization
			$lang = app()->getLocale();
			if ($lang != 'en') {
				$langData = BlogCategoryLocalization::where('id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
				if ($langData) {
					$item->title = $langData->title ? $langData->title : $item->title;
				}
			}
		}


		if ($categories) {
			$data['status'] = 1;
			$data['categories'] = $categories;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Blog category not found !';
			return response()->json($data, 200);
		}
	}





	public function blogByCategory($id)
	{

		if ($id == 0) {
			$data = DB::table('blogs')->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'DESC')->paginate(10);
			foreach ($data as $item) {
				$item->category_name = \Helper::get_blog_category_by_id($item->category_id);
			}
			if (count($data) > 0) {
				$d['status'] = 1;
				$d['blogs'] = $data;
				return response()->json($d, 200);
			} else {
				$d['status'] = 0;
				$d['blogs'] = '';
				$d['message'] = 'Blog not found.';
				$d['category'] =  $category->title;
				return response()->json($d, 200);
			}
		}



		$category = DB::table('blog_categories')->where('id', $id)->first();
		if (!$category && !$category->id) {
			$d['status'] = 0;
			$d['message'] = 'Category not found.';
			return response()->json($d, 200);
		}

		$data = DB::table('blogs')->where('category_id', $id)->where('is_active', 1)->where('is_deleted', 0)->orderBy('id', 'DESC')->paginate(10);
		foreach ($data as $item) {
			$item->category_name = \Helper::get_blog_category_by_id($item->category_id);
		}
		if (count($data) > 0) {
			$d['category_banner'] = $category->image;
			$d['status'] = 1;
			$d['blogs'] = $data;
			return response()->json($d, 200);
		} else {
			$d['status'] = 0;
			$d['blogs'] = '';
			$d['message'] = 'Blog not found.';
			$d['category'] =  $category->title;
			return response()->json($d, 200);
		}
	}

	public function getSellerReview()
	{
		$vendors = Admins::orderBy('id', 'desc')->with('shopinfo')->get();
		$vendorArray = [];
		foreach ($vendors as $vendor) {
			if ($vendor->hasRole('seller')) {
				$vendorArray[] = $vendor;
			}
		}


		foreach ($vendorArray as $row) {
			$total_rating = 0;
			$rating_given_by = 0;
			foreach (Product::where('seller_id', $row->id)->where('is_active', 1)->where('is_deleted', 0)->where('product_qc', 1)->orderby('shuffle_number', 'desc')->get() as $product) {
				$total_rating += DB::table('comments')->where('approved', 1)->where('commentable_id', $product->id)->sum('rate');
				$rating_given_by += count(DB::table('comments')->where('approved', 1)->where('commentable_id', $product->id)->whereNotNull('rate')->get());
			}
			if ($total_rating == 0 && $rating_given_by == 0) {
				$row->rating = number_format((float)0, 2, '.', '');;
			} else {
				$row->rating = number_format((float)$total_rating / $rating_given_by, 2, '.', '');
			}

			// return response()->json($rating_given_by, 200);
		}

		$data['status'] = 1;
		$data['vendor'] = $vendorArray;

		return response()->json($data, 200);
	}




	public function appLinkRequest(Request $request)
	{
		$mobile = $request->mobile;
		if (strlen($mobile) > 11) {
			$data['status'] = 0;
			$data['message'] = 'Plesae provide a valid mobile number.';
			return response()->json($data, 200);
		}

		$already = DB::table('app_link_request')->where('mobile', $mobile)->whereDate('created_at', Carbon::today())->get();
		if (count($already) > 4) {
			$data['status'] = 0;
			$data['message'] = 'You can request maximum 5 times in a day.You can request again tommorow.';
			return response()->json($data, 200);
		} else {
			$appdata['name'] = $request->name;
			$appdata['mobile'] = $mobile;
			$appdata['ip_address'] = $request->ip();
			$insert_id = DB::table('app_link_request')->insertGetId($appdata);
			if ($insert_id) {
				$sms = \Helper::getsettings('get_app_link_sms_template');
				if (!empty($sms)) {
					Helper::sendSms($mobile, $sms);
				}
				$data['status'] = 1;
				$data['message'] = 'We have successfully sent you the App download link via SMS';
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Something went wrong.';
				return response()->json($data, 200);
			}
		}
	}



	// public function getGroceryFlashDeals()
	// {
	// 	$query = FlashDeal::select('slug', 'banner', 'title')
	// 		->where('is_grocery', 1)
	// 		->where('status', 1)
	// 		->where('start_date', '<=', Carbon::now())
	// 		->where('end_date', '>=', Carbon::now())
	// 		->orderby('sort_order', 'desc');



	// 	$toArray = $query->get()->toArray();
	// 	$data['flashDeals'] = array_chunk($toArray, 3);
	// 	$data['plain_list'] = $query->get();

	// 	return response()->json($data, 200);
	// }


	public function getFlashDeals(Request $request){
		//$location = $request->upazila_id == "null" ? 0 : (int)$request->upazila_id;
		$flashDeals = FlashDeal::select('slug', 'banner', 'title')
			//->whereRaw("(FIND_IN_SET(0, delivery_location) OR FIND_IN_SET($location, delivery_location))")
			->where('is_grocery', 0)
			->where('status', 1)
			->where('start_date', '<=', Carbon::now())
			->where('end_date', '>=', Carbon::now())
			->orderby('sort_order', 'desc')->get();
		return response()->json($flashDeals, 200);
	}

	public function getFlashDeal($slug){
		$data = [];
		$flashDeals = FlashDeal::where('slug', $slug)
			->where('status', 1)
			->where('start_date', '<=', Carbon::now())
			->where('end_date', '>=', Carbon::now())
			->first();

		if ($flashDeals) {
			$ids = $flashDeals->product_ids;
			$product_ids = explode(',', $flashDeals->product_ids);
			$data['deals'] = $flashDeals;
			$category_wise = [];

			if($flashDeals->show_category_wise == 1){
				$products = Product::whereIn('id', $product_ids)->orderByRaw("FIELD(id, $ids)")->get();
			}else{
				$products = Product::whereIn('id', $product_ids)->orderByRaw("FIELD(id, $ids)")->paginate(18);
			}

			

			if (count($products) > 0) {
				foreach ($products as $product) {

					$product->price_after_offer = number_format((int)\Helper::price_after_offer($product->id), 0);
					$product->price = number_format((int)$product->price, 0);
					$product->offer_percentage = number_format((int)\Helper::offer_percentage_byID($product->id), 0);
					$product->shop_verified = \Helper::is_verified_seller($product->seller_id);
					$product->AllRating = $this->AllRating($product->id) ? $this->AllRating($product->id)->original : null;
					//Localization
					$lang = app()->getLocale();
					if ($lang != 'en') {
						$langData = ProductLocalization::where('product_id', $product->id)->where('is_active', 1)->where('lang_code', $lang)->first();
						if ($langData) {
							$product->title = $langData->title ? $langData->title : $product->title;
						}
					}

					$cat_ids = explode(',', $product->category_id);
					$category_title = Category::whereIn('id', $cat_ids)->pluck('title')->first();

					if(isset($category_wise[$category_title])){
						if( count($category_wise[$category_title]) > 5 ){
							continue;
						}
					}

					$category_wise[$category_title][] = $product;
				}
			}
			$data['products'] = $products;
			$data['category_wise_products'] = $category_wise;
			return response()->json($data, 200);
		}
	}


	public function CategoryWisegetFlashDeal($category_id, $slug){
		// $data['category_id'] = $category_id;
		// $data['slug'] = $slug;
		// return response()->json($data, 200);
		// exit;

		$data = [];
		$flashDeals = FlashDeal::where('slug', $slug)
			->where('status', 1)
			->where('start_date', '<=', Carbon::now())
			->where('end_date', '>=', Carbon::now())
			->first();

		if ($flashDeals) {
			$ids = $flashDeals->product_ids;
			$product_ids = explode(',', $flashDeals->product_ids);
			$data['deals'] = $flashDeals;
			$category_wise = [];
			$products = Product::whereIn('id', $product_ids)->where('category_id', $category_id)->orderByRaw("FIELD(id, $ids)")->paginate(18);
			if (count($products) > 0) {
				foreach ($products as $product) {
					$product->price_after_offer = number_format((int)\Helper::price_after_offer($product->id), 0);
					$product->price = number_format((int)$product->price, 0);
					$product->offer_percentage = number_format((int)\Helper::offer_percentage_byID($product->id), 0);
					$product->shop_verified = \Helper::is_verified_seller($product->seller_id);
					$product->AllRating = $this->AllRating($product->id) ? $this->AllRating($product->id)->original : null;
					//Localization
					$lang = app()->getLocale();
					if ($lang != 'en') {
						$langData = ProductLocalization::where('product_id', $product->id)->where('is_active', 1)->where('lang_code', $lang)->first();
						if ($langData) {
							$product->title = $langData->title ? $langData->title : $product->title;
						}
					}

					$cat_ids = explode(',', $product->category_id);
					$category_title = Category::whereIn('id', $cat_ids)->pluck('title')->first();
					$category_wise[$category_title][] = $product;
				}
			}
			$data['products'] = $products;
			$data['category_wise_products'] = $category_wise;
			return response()->json($data, 200);
		}
	}
	

	public function newsletterSubscribtion(Request $request)
	{
		$email = $request->email;
		if (!$email) {
			$data['status'] = 0;
			$data['message'] = 'Plesae provide a valid email.';
			return response()->json($data, 200);
		}
		$already = DB::table('newsletter_subscribtion')->where('email', $email)->first();
		if ($already) {
			$data['status'] = 0;
			$data['message'] = 'This email already exist.';
			return response()->json($data, 200);
		} else {
			$appdata['email'] = $request->email;
			$appdata['ip_address'] = $request->ip();
			$insert_id = DB::table('newsletter_subscribtion')->insertGetId($appdata);
			if ($insert_id) {
				$data['status'] = 1;
				$data['message'] = 'Thank you for signing up to our newsletter!';
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Something went wrong.';
				return response()->json($data, 200);
			}
		}
	}


	public function orderAutoRenewal(Request $request)
	{
		$data = [];
		$user = auth('customer-api')->user();
		$order_id = $request->order_id;
		$renewal_cycle = $request->renewal_cycle;


		if (Order::find($order_id)->where('user_id', $user->id)->exists()) {

			if (OrderAutoRenewal::where('order_id', $order_id)->where('user_id', $user->id)->exists()) {
				$OrderAutoRenewal = OrderAutoRenewal::where('order_id', $order_id)->first();
				$OrderAutoRenewal->renewal_cycle = $request->renewal_cycle;
				$OrderAutoRenewal->status = 1;
				$OrderAutoRenewal->next_order_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +' . $request->renewal_cycle . ' day'));
				$update = $OrderAutoRenewal->save();
			} else {
				$OrderAutoRenewal = new OrderAutoRenewal();
				$OrderAutoRenewal->order_id = $request->order_id;
				$OrderAutoRenewal->renewal_cycle = $request->renewal_cycle;
				$OrderAutoRenewal->next_order_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +' . $request->renewal_cycle . ' day'));
				$OrderAutoRenewal->user_id = $user->id;
				$OrderAutoRenewal->status = 1;
				$update = $OrderAutoRenewal->save();
			}


			if ($update) {
				$data['status'] = 1;
				$data['message'] = 'Thank you for activate order auto renewal service!';
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Something went wrong! Please try again later.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'Order not found!';
			return response()->json($data, 200);
		}
	}

	public function cancelOrderAutoRenewal(Request $request)
	{
		$data = [];
		$user = auth('customer-api')->user();
		$order_id = $request->order_id;

		if (Order::find($order_id)->where('user_id', $user->id)->exists()) {

			$update = OrderAutoRenewal::where('order_id', $order_id)->update([
				'next_order_date' => null,
				'status' => 0
			]);

			if ($update) {
				$data['status'] = 1;
				$data['message'] = 'Thank you for cancel order auto renewal service!';
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Something went wrong! Please try again later.';
				return response()->json($data, 200);
			}
		} else {
			$data['status'] = 0;
			$data['message'] = 'Order not found!';
			return response()->json($data, 200);
		}
	}

	public function getMyAffiliateDetails(Request $request)
	{

		$user = auth('customer-api')->user();

		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'Please login to access this page.';
			return response()->json($data, 200);
		} else {

			$data['signups'] = User::where('affiliate_referer', $user->id)->count() ?? 0;

			$data['pending_maturation'] = DB::table('affiliate_history')->where('user_id', $user->id)->where('status', 1)->sum('commission_amount');


			$data['amount_withdrawn'] = DB::table('affiliate_withdrawal')
										->where('user_id', $user->id)
										->where(function($query) {
											$query->where('status', 6)
											->orWhere('status', 1);
										})
										->sum('amount');

			$totalBalance = DB::table('affiliate_history')->where('user_id', $user->id)->where('status', 6)->sum('commission_amount');

			$data['balance'] = $totalBalance - $data['amount_withdrawn'];

			$history = Affiliate::where('user_id', $user->id)->with('buyer')->get();
			$withdrawals = AffiliateWithdrawal::where('user_id', $user->id)->get();

			foreach ($history as $h) {
				$productId = $h->product_ids;
				$h->product = Product::select('title', 'slug')->whereIn('id', explode(',', $productId))->get();
			}
			$data['history'] = $history;
			$data['withdrawals'] = $withdrawals;

			return response()->json($data, 200);
		}
	}


	public function affiliateWithdrawalRequest(Request $request)
	{
		$user = auth('customer-api')->user();
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'Please login to access this page.';
			return response()->json($data, 200);
		} else {

			$revenue = DB::table('affiliate_history')->where('user_id', $user->id)->where('status', 6)->sum('commission_amount');
			$amount_withdrawn = DB::table('affiliate_withdrawal')->where('user_id', $user->id)->where('status', 6)->orWhere('status', 1)->sum('amount');
			$balance = $revenue - $amount_withdrawn;

			$requiredAmount = 500;

			if ($requiredAmount > $balance) {
				$data['status'] = 0;
				$data['message'] = 'Minimum limit for affiliate withdrawal request is BDT ' . $requiredAmount . ' . You do not have sufficient balance.';
				return response()->json($data, 200);
			}

			if ($requiredAmount > $request->amount) {
				$data['status'] = 0;
				$data['message'] = 'Minimum limit for affiliate withdrawal request is BDT ' . $requiredAmount;
				return response()->json($data, 200);
			}

			if ($request->amount > $balance) {
				$data['status'] = 0;
				$data['message'] = 'You can not withdraw more than your current balance! Your current balance is BDT ' . $balance;
				return response()->json($data, 200);
			}

			$update = DB::table('affiliate_withdrawal')->insert([
				'user_id' => $user->id,
				'amount' => $request->amount,
				'channel' => $request->channel,
				'account_details' => $request->account_details,
				'status' => 1,
			]);

			if ($update) {
				$data['status'] = 1;
				$data['message'] = 'Your withdrawal request has been received. Please wait till we disburse the amount to you.';
				return response()->json($data, 200);
			} else {
				$data['status'] = 0;
				$data['message'] = 'Something went wrong! Please try again later.';
				return response()->json($data, 200);
			}
		}
	}



	// career 
	public function getCareers()
	{
		$today = date('Y-m-d');
		$career =  Career::where('status', 1)->where('is_deleted', 0)->where('end_date', '>=', $today)->orderBy('id', 'desc')->get();
		foreach ($career as $row) {
			$row->job_date = date_format(date_create($row->post_date), "d ,M Y");
			$row->apply_date = date_format(date_create($row->end_date), "d ,M Y");
		}

		

		if (count($career) > 0) {
			$data['status'] = 1;
			$data['career'] = $career;
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Career not found!';
			$data['career'] = $career;
			return response()->json($data, 200);
 		}
	}

	public function getCareerDetails($id)
	{
		$career = Career::find($id);
		if ($career) {
			$career->job_date = date_format(date_create($career->post_date), "d ,M Y");
			$career->apply_date = date_format(date_create($career->end_date), "d ,M Y");
			$data['career'] = $career;
			return response()->json($data, 200);
		} else {
			$d['status'] = 0;
			$d['message'] = 'Career not fount.';
			return response()->json($d, 200);
		}
	}

	public function applyForJob(Request $request)
	{
		//return response()->json($request->all(), 200);
		// var_dump($request->all());
		//exit();
		$validator = Validator::make($request->all(), [
			'first_name' => 'required',
			'last_name' => 'required',
			'phone' => 'required|min:11|max:11',
			'resume' => 'required|mimes:pdf|max:10000',
			'cover_letter' => 'required',
		]);



		if ($validator->fails()) {
			$data['status'] = 50;
			$data['message'] = $validator->errors();
			return response()->json($data, 200);
		}

		if (CareerRequest::where('phone_number', $request->phone)->where('job_id', $request->job_id)->exists()) {
			$data['status'] = 0;
			$data['message'] = 'You already apply for the job.';
			return response()->json($data, 200);
		}

		$career_requests = new CareerRequest();
		$career_requests->job_id = $request->job_id;
		$career_requests->first_name = $request->first_name;
		$career_requests->last_name = $request->last_name;
		$career_requests->phone_number = $request->phone;
		$career_requests->email = $request->email;
		$career_requests->cover_letter = $request->cover_letter;

		if ($request->hasfile('resume')) {
			$file = $request->file('resume');
			$name = time() . rand(1, 100) . '.' . $file->extension();
			$file->move(public_path('uploads/jobapplication'), $name);

			$career_requests->file = $name;
		}
		$career_requests->save();

		if ($career_requests) {
			$data['status'] = 1;
			$data['message'] = 'Your application submited successfully.';
			return response()->json($data, 200);
		} else {
			$data['status'] = 0;
			$data['message'] = 'Something went wrong.';
			return response()->json($data, 200);
		}
	}












	//Corporate 

	public function requestForQuatation(Request $request){
		$user = auth('customer-api')->user();
		if(!$user || $user->user_type == 1){
			$data['status'] = 0;
			$data['message'] = 'Please login as corporate user to request corporate quotation.';
			return response()->json($data, 200);
		}

		$cartData = Cart::where('user_id',$user->id)->get();
	
		$corporateRequests = new CorporateRequests;
		$corporateRequests->user_id = $user->id;
		$corporateRequests->discount = 0;
		$corporateRequests->payment_amount = 0;
		$corporateRequests->deal_status = 1;
		$corporateRequests->save();

		$amount = 0;
		$qty = 0;

		foreach($cartData as $cart){ 

			$amount += $cart->price;
			$qty += $cart->qty;

			$CorporateRequestDetails = new CorporateRequestDetails;
			$CorporateRequestDetails->user_id = $cart->user_id;
			$CorporateRequestDetails->product_id = $cart->product_id;
			$CorporateRequestDetails->seller_id = $cart->seller_id;
			$CorporateRequestDetails->corporate_request_id = $corporateRequests->id;
			$CorporateRequestDetails->qty = $cart->qty;
			$CorporateRequestDetails->price = $cart->price;
			$CorporateRequestDetails->discount = 0;
			$CorporateRequestDetails->variable_option = $cart->variable_option;
			$CorporateRequestDetails->variable_sku = $cart->variable_sku;
			$CorporateRequestDetails->status = 1;
			$CorporateRequestDetails->save();
		}

		$singleCorporateRequests = CorporateRequests::find($corporateRequests->id);
		$singleCorporateRequests->amount = $amount;
		$singleCorporateRequests->qty = $qty;
		$singleCorporateRequests->save();


		$data['status'] = 1;
		$data['message'] = 'Thank you for your quotation request! We will create an invoice for you and let you know.';
		Cart::where('user_id',$user->id)->delete();
		return response()->json($data, 200);
	}


	public function quatationsByUserId(){
		$user = auth('customer-api')->user();
		if($user){
			$orders = CorporateRequests::where('user_id', $user->id)
			->where('is_deleted', 0)
			->orderBy('id','desc')
			->with('statuses')
			->paginate(15);

			if(count($orders) > 0){
				$data['status'] = 1;
				$data['orders'] = $orders;
				return response()->json($data, 200);
			}else{
				$data['status'] = 0;
				$data['message'] = 'Quatation not found.';
				return response()->json($data, 200);
			}
		}else{
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}


	public function getSingleQuatation($request_id){
		$user = auth('customer-api')->user();
		if($user){
			$quatation = CorporateRequestDetails::where('user_id', $user->id)->where('corporate_request_id',$request_id)
			->where('is_deleted', 0)
			->orderBy('id','desc')
			->with('statuses')
			->with('productname')
			->get();

			foreach($quatation as $q){
				if($q->productname){
					if($q->productname->product_type == 'variable'){
						$q->product_sku = $q->variable_sku;
						if($q->variable_option){
							$q->product_options = json_decode($q->variable_option);
						}
						
					}else{
						$q->product_sku = $q->productname->sku;
					}
				}else{
					$q->product_sku = null;
					$q->product_options = null;
				}
			}

			$groupedQuatation = CorporateRequests::where('user_id', $user->id)->where('id',$request_id)
			->where('is_deleted', 0)
			->with('statuses')
			->with('payment_status')
			->with('negotiations')
			->with('negotiations.username')
			->with('negotiations.adminname')
			->first();

			$groupedQuatation->company_bank_information = \Helper::getSettings('company_bank_information');

			$data['status'] = 1;
			$data['quatation'] = $quatation;
			$data['groupedQuatation'] = $groupedQuatation;
			return response()->json($data, 200);
			
		}else{
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}


	public function quotationAction(Request $request){
		$user = auth('customer-api')->user();
		if($user){
			if($request->quotation_action_status == 2){
				$validator = Validator::make($request->all(), [
					'quotation_action_status' => 'required',
					'preferable_date_for_delivery' => 'required',
					'note' => 'required',
					'workorderfile' => 'required',
					'delivery_address' => 'required',
				]);
			}else{
				$validator = Validator::make($request->all(), [
					'note' => 'required',
				]);
			}

			if ($validator->fails()){
				$data['status'] = 0;
				$data['message'] = $validator->errors();
				return response()->json($data, 200);
			}

			if ($request->workorderfile) {
				$work_order = 'uploads/workorder/' . uniqid() . '.' . $request->workorderfile->getClientOriginalExtension();
				$request->workorderfile->move(public_path('uploads/workorder/'), $work_order);
			}



			//insert corporate_request_negotiations table
			$negotiation_data['note'] = $request->note;
			$negotiation_data['corporate_request_id'] = $request->corporate_request_id;
			$negotiation_data['user_id'] = $user->id;
			DB::table('corporate_request_negotiations')->insert($negotiation_data);

			//update corporate_requests table
			if($request->quotation_action_status == 2){
				$datas['preferable_date_for_delivery'] = $request->preferable_date_for_delivery;
				$datas['work_order'] = $work_order;
				$datas['delivery_address'] = $request->delivery_address;
			}
			$datas['deal_status'] = $request->quotation_action_status;
			$datas['payment_status'] = 1;
			DB::table('corporate_requests')->update($datas);

			$data['status'] = 1;
			$data['message'] = 'Thank you for your feedback!';
			return response()->json($data, 200);
		}else{
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}


	}


	// coupons 
	public function getAllCoupons()
	{
		$user = auth('customer-api')->user();
		$vouchers = [];
		if (!$user) {
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
		$user_id = $user->id;
		$all_coupons = Coupon::where('expire', '>=', Carbon::now())->where('is_deleted', 0)->WhereRaw("FIND_IN_SET($user_id,user_ids)")->get();
		$data['my_coupons'] = $all_coupons;
		$data['coupon_for_sale'] = Coupon::where('expire', '>=', Carbon::now())->where('is_deleted', 0)->where('use_type',2)->get();
		return response()->json($data, 200);
	}


	public function buyCouponWithLoyaltyPoint(Request $request){
		$user = auth('customer-api')->user();
		if ($user) {
			$coupon = Coupon::find($request->coupon);
			if ($coupon) {
				if ($coupon->deduction_points <= \Helper::UserLoyaltyPoits($user->id)) {
					$purchases = new LoyaltyPurchases();
					$purchases->user_id = $user->id;
					$purchases->usges_point = $coupon->deduction_points;
					$purchases->coupon_code = $coupon->code;
					$purchases->coupon_id = $coupon->id;
					$purchases->save();

					$old_users = explode(',', $coupon->user_ids);
					array_push($old_users,$user->id);

					$coupon->user_ids = implode(",", $old_users);
					$coupon->save();

					$data['status'] = 1;
					$data['message'] = 'You successfully buy the coupon!';
				}else{
					$data['status'] = 0;
					$data['message'] = 'You need more loyalty points to buy this coupon!';
				}
			}else{
				$data['status'] = 0;
				$data['message'] = 'Coupon not found';
			}
			return response()->json($data, 200);
		}else{
			$data['status'] = 0;
			$data['message'] = 'You have to login first to avail this option.';
			return response()->json($data, 200);
		}
	}




	public function popular_categories(Request $request){
		$featured_categories_ids = \Helper::getsettings('featured_categories');
		$featured_categories_ids_array = explode(',', $featured_categories_ids);
		if (count($featured_categories_ids_array) > 0) {
			$categories = Category::where('is_active', 1)
				->where('is_deleted', 0)
				->whereIn('id', $featured_categories_ids_array)
				->orderBy('sort_order', 'asc')
				->paginate(12);
			//Localization
			foreach ($categories as $item) {
				$lang ='en';
				if ($lang != 'en') {
					$langData = CategoryLocalization::where('category_id', $item->id)->where('is_active', 1)->where('lang_code', $lang)->first();
					if ($langData) {
						$item->title = $langData->title ? $langData->title : $item->title;
					}

					foreach ($item->categories as $layer1) {
						$langData = CategoryLocalization::where('category_id', $layer1->id)->where('is_active', 1)->where('lang_code', $lang)->first();
						if ($langData) {
							$layer1->title = $langData->title ? $langData->title : $layer1->title;
						}
						foreach ($layer1->categories as $layer2) {
							$langData = CategoryLocalization::where('category_id', $layer2->id)->where('is_active', 1)->where('lang_code', $lang)->first();
							if ($langData) {
								$layer2->title = $langData->title ? $langData->title : $layer2->title;
							}
							foreach ($layer2->categories as $layer3) {
								$langData = CategoryLocalization::where('category_id', $layer3->id)->where('is_active', 1)->where('lang_code', $lang)->first();
								if ($langData) {
									$layer3->title = $langData->title ? $langData->title : $layer3->title;
								}
							}
						}
					}
				}
			}
			return response()->json($categories, 200);
		} else {
			return response()->json([], 200);
		}
	}


}