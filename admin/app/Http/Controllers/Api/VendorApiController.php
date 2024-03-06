<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Hash;
use Image;

// Models
use App\Models\{
    Slider, Admins, Category, Product, Cart, Brand, ShopInfo, User, Order, OrderDetails,
    Status, Addresses, AttributeSets, Notifications, Review, ReturnRequest, Wishlist,
    SellerAccountHistory, ProductMeta, Option, OptionValue, ProductImage, WithdrawalRequest
};

// JWT
use Tymon\JWTAuth\Exceptions\{
    JWTException, TokenExpiredException, TokenInvalidException
};
use Tymon\JWTAuth\JWTAuth;

// Helper
use Helper;




class VendorApiController extends Controller{
	public function __construct(){
        $this->middleware('auth:vendor-api')->only([
            'my_account', 'update_account', 'update_address', 'logout'
        ]);
    }
	
	
	public function login(Request $request){
		
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
			$data['message']= 'Login failed';
			$data['status'] = 0;
			return response()->json($data, 200);
        }

        try {
            if (filter_var($request->phone, FILTER_VALIDATE_EMAIL)) {
                if($request->get('phone')){
                    $credentials = ['email'=>$request->get('phone'),'password'=>$request->get('password')];
                }
            }else{
                if($request->get('phone')){
                    $credentials = ['phone'=>$request->get('phone'),'password'=>$request->get('password')];
                }
            }

            if (!$token = auth('vendor-api')->attempt($credentials)) {
				$data['message']= 'Credentials not found!';
				$data['status'] = 0;
                return response()->json($data, 200);
            }

        } catch (JWTException $e) {
			$data['message']= 'Token creation failed!';
			$data['status'] = 0;
			return response()->json($data, 200);
        }
		
		$user = auth('vendor-api')->user();
		
		if($device_token = $request->device_token){
			DB::table('admins')->where('id',$user->id)->update(['device_token'=>$device_token]);
		}
		
        return response()->json([
			'status' => 1,
            'vendor' => $user,
            'token' => $token,
            'expire' => auth('vendor-api')->factory()->getTTL() * 6000
        ], 200);
    }
	
	
 //Register
    public function sellerRegister(Request $request){
		

        $validator = Validator::make($request->all(),[
            'name' => 'required | max:191 | string',
            'email'=> 'nullable | max:50 | email | unique:admins',
            'phone'=> 'required | max:15 | unique:admins',
            'slug'=> 'required | unique:shop_info',
            'nid_front_side'  => 'required',
            'nid_back_side'  => 'required',

        ],[
            'email.unique' => 'This email has already been taken!',
            'phone.unique' => 'This phone number has already been taken!',
        ]);
		
        if($validator->fails()){
			$data['status'] = 0;
			$data['message'] = $validator->errors();
            return response()->json($data, 200);
        }
		
	
        
        $vendor = new Admins();
        $vendor->name = $request->name;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
		
        if ($request->hasFile('nid_front_side')){
                $uploadedImage = $request->file('nid_front_side');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $vendor->nid_front_side = $filePath;
                }
            }
            // $vendor->nid_back_side = $request->nid_back_side;
            if ($request->hasFile('nid_back_side')){
                $uploadedImage = $request->file('nid_back_side');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $vendor->nid_back_side = $filePath;
                }
            }
            // $vendor->avatar = $request->avatar;

            if ($request->hasFile('avatar')){
                $uploadedImage = $request->file('avatar');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $vendor->avatar = $filePath;
                }
            }

        $tempPass = $request->password;
        $vendor->password = Hash::make($tempPass);
        $vendor->status = $request->status ? 1 : 0 ;
		$vendor->assignRole(['seller']);
        $vendor->save();

        $shopInfo = new ShopInfo;
        $shopInfo->seller_id = $vendor->id;
        $shopInfo->name = $request->shop_name;
        $shopInfo->slug =  \Helper::generateUniqueSlug('seller','title',$request->shop_name);
        $shopInfo->phone = $request->shop_phone;
        $shopInfo->email = $request->shop_email;
		
		//File
        if ($request->hasFile('shop_logo')){
            $uploadedImage = $request->file('shop_logo');
            $imageTitle = uniqid();
            $imageName = $imageTitle. '.' . $uploadedImage->extension();
            $image_resize = Image::make($uploadedImage->getRealPath());
            $upload_success = $image_resize->save(public_path('/media/' .$imageName));
            $filePath = 'media/'.$imageName;
            
            if( $upload_success) {
                $gGata = [
                    'title' =>  $imageTitle,
                    'file_url' => $filePath,
                    'uploaded_by' => $user->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
    
                \DB::table('concave_media')->insert($gGata);
                $shopInfo->logo = $filePath;
            }
        }
        // $shopInfo->banner = $request->shop_banner;
        if ($request->hasFile('shop_banner')){
            $uploadedImage = $request->file('shop_banner');
            $imageTitle = uniqid();
            $imageName = $imageTitle. '.' . $uploadedImage->extension();
            $image_resize = Image::make($uploadedImage->getRealPath());
            $upload_success = $image_resize->save(public_path('/media/' .$imageName));
            $filePath = 'media/'.$imageName;
            
            if( $upload_success) {
                $gGata = [
                    'title' =>  $imageTitle,
                    'file_url' => $filePath,
                    'uploaded_by' => $user->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
    
                \DB::table('concave_media')->insert($gGata);
                $shopInfo->banner = $filePath;
            }
        }
        // $shopInfo->trade_license = $request->trade_license;
        if ($request->hasFile('trade_license')){
            $uploadedImage = $request->file('trade_license');
            $imageTitle = uniqid();
            $imageName = $imageTitle. '.' . $uploadedImage->extension();
            $image_resize = Image::make($uploadedImage->getRealPath());
            $upload_success = $image_resize->save(public_path('/media/' .$imageName));
            $filePath = 'media/'.$imageName;
            
            if( $upload_success) {
                $gGata = [
                    'title' =>  $imageTitle,
                    'file_url' => $filePath,
                    'uploaded_by' => $user->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
    
                \DB::table('concave_media')->insert($gGata);
                $shopInfo->trade_license = $filePath;
            }
        }

        $shopInfo->division = $request->division;
        $shopInfo->district = $request->district;
        $shopInfo->area = $request->area;
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
		
		return response()->json([
			'status' => 1,
            'message' => 'Account Successfully Created!'
		], 200);
	}

	public function sellerProfile(){
		$user = auth('vendor-api')->user();
		if($user){
			$seller = $user->id;
			$user_details = Admins::find($seller);
			$shop_info = ShopInfo::where('seller_id',$seller)->first();
            return response()->json([
				'user' => $user_details,
				'shop_info' => $shop_info,
			], 200);
		}else{
			$data['message']= 'User not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
	}
	

	public function sellerProfileUpdate(Request $request){
		$user = auth('vendor-api')->user();
		if($user){
			$id = $user->id;

			$vendor = Admins::find($id);
	        $vendor->name = $request->name;
	        // $vendor->nid_front_side = $request->nid_front_side;
	        if ($request->hasFile('nid_front_side')){
                $uploadedImage = $request->file('nid_front_side');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $vendor->nid_front_side = $filePath;
                }
            }
	        // $vendor->nid_back_side = $request->nid_back_side;
	        if ($request->hasFile('nid_back_side')){
                $uploadedImage = $request->file('nid_back_side');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $vendor->nid_back_side = $filePath;
                }
            }
	        // $vendor->avatar = $request->avatar;

	        if ($request->hasFile('avatar')){
                $uploadedImage = $request->file('avatar');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $vendor->avatar = $filePath;
                }
            }

	        $vendor->save();

	        $shopInfo = ShopInfo::where('seller_id',$id)->first();
	        $shopInfo->name = $request->shop_name;
	        $shopInfo->phone = $request->shop_phone;
	        $shopInfo->email = $request->shop_email;
	        // $shopInfo->logo = $request->shop_logo;
	        if ($request->hasFile('shop_logo')){
                $uploadedImage = $request->file('shop_logo');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $shopInfo->logo = $filePath;
                }
            }
	        // $shopInfo->banner = $request->shop_banner;
	        if ($request->hasFile('shop_banner')){
                $uploadedImage = $request->file('shop_banner');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $shopInfo->banner = $filePath;
                }
            }
	        // $shopInfo->trade_license = $request->trade_license;
	        if ($request->hasFile('trade_license')){
                $uploadedImage = $request->file('trade_license');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $shopInfo->trade_license = $filePath;
                }
            }

	        $shopInfo->division = $request->shop_division;
	        $shopInfo->district = $request->shop_district;
	        $shopInfo->area = $request->shop_area;
	        $shopInfo->shop_union = $request->shop_union;
	        $shopInfo->address = $request->shop_address;

	        
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

	        return response()->json([
				'status' => 1,
	            'message' => 'Successfully Updated!'
			], 200);
		}else{
			$data['message']= 'User not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
	}

	public function sellerChangePassword(Request $request){
		$user = auth('vendor-api')->user();
		if($user){
			$id = $user->id;
			$user_details = Admins::find($id);

			if (Hash::check($request->previous_password, $user_details->password)) {
				$user_details->password = Hash::make($request->new_password);
				$user_details->save();
				return response()->json([
		            'message' => 'Password Successfully Updated!',
		            'status' => 1
				], 200);

			}else{
				$data['message']= 'Password not match!';
				$data['status'] = 0;
				return response()->json($data, 200);
			}

		}else{
			$data['message']= 'User not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
	}
	
// Dashboard
	public function seller_dashboard(){
		$user = auth('vendor-api')->user();
		if($user){
			$seller = $user->id;
            $data = [];
            $data['order'] = count(DB::select("SELECT orders.id
                            FROM orders, order_details
                            WHERE orders.id = order_details.order_id
                            AND order_details.seller_id = '$seller'"));

            $data['product'] = Product::where('seller_id',$user->id)->where('is_active', 1)->where('is_deleted', 0)->count();
            $data['users'] = User::count();
            $data['orders'] = DB::SELECT("SELECT orders.*
                                FROM orders, order_details
                                WHERE orders.id = order_details.order_id
                                AND order_details.seller_id = '$seller'
                                ORDER BY id
                                LIMIT 10;");

            $data['completed_orders'] = DB::SELECT("SELECT orders.*
                                FROM orders, order_details
                                WHERE orders.id = order_details.order_id
                                AND order_details.seller_id = '$seller'
                                AND orders.status = 6;");

            $data['all_orders'] = DB::SELECT("SELECT orders.*
                                FROM orders, order_details
                                WHERE orders.id = order_details.order_id
                                AND order_details.seller_id = '$seller';");

            $data['status'] = Status::all();

            $data['top_category_sale'] = DB::select("SELECT product_id, (SELECT products.category_title FROM products WHERE products.id = order_details.product_id) as 'category',  SUM(price * product_qty) as 'total_sale' FROM order_details 
                WHERE seller_id = '$seller'
                GROUP by product_id
                order by SUM(price * product_qty) desc LIMIT 10;");
            
            $data['top_sale_product'] = DB::SELECT("SELECT products.id, admins.name, products.title
                                FROM order_details, admins,products
                                WHERE order_details.product_id = products.id
                                AND order_details.seller_id = admins.id
                                AND order_details.seller_id = '$seller'
                                GROUP BY order_details.product_id
                                ORDER BY SUM(order_details.price * order_details.product_qty) desc
                                LIMIT 10;");

            $data['top_seller'] = DB::SELECT("SELECT admins.id, admins.name, SUM(order_details.price * order_details.product_qty) as 'total_earn'
                                FROM order_details, admins
                                where order_details.seller_id = admins.id
                                GROUP BY order_details.seller_id
                                ORDER BY SUM(order_details.price * order_details.product_qty) desc
                                LIMIT 10;");

            $data['top_customer'] = DB::table('orders')
                            ->select('orders.user_id', DB::raw('SUM(orders.paid_amount) as total_buy_amount'), DB::raw('count(orders.id) as total_buy'), 'users.name')
                            ->join('users', 'users.id', '=', 'orders.user_id')
                            ->groupBy('orders.user_id')
                            ->orderByRaw('SUM(orders.paid_amount) DESC')
                            ->limit(10)
                            ->get();
							
			$data['vendor_customer'] = DB::SELECT("SELECT DISTINCT order_details.user_id, users.*
													FROM orders, order_details, users
													WHERE orders.id = order_details.order_id
													AND users.id = order_details.user_id
													AND order_details.seller_id = '$seller';");

            $query = DB::SELECT("SELECT *
                                FROM order_details
                                WHERE seller_id = '$seller';");
								
            $data['revenue'] = 0;
            foreach ($query as $row) {
                $data['revenue'] += ($row->price * $row->product_qty) + $row->shipping_cost;
            }
			
			
			foreach ($data['vendor_customer'] as $rows) {
				$rows->address = Addresses::find($rows->default_address_id);
				$rows->total_orders = COUNT(OrderDetails::where('seller_id',$seller)->where('user_id',$rows->user_id)->get());
				$rows->total_order_amount = DB::SELECT("SELECT SUM(orders.paid_amount) AS 'order_amount'
													FROM orders, order_details
													WHERE orders.id = order_details.order_id
													AND order_details.seller_id = '$seller';");
			}
			
            return response()->json($data, 200);
		}else{
			$data['message']= 'Page not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
	}
	
	public function seller_customerlist(){
		$user = auth('vendor-api')->user();
		if($user){
			$seller = $user->id;
			$data['vendor_customer'] = DB::SELECT("SELECT DISTINCT order_details.user_id, users.*
													FROM orders, order_details, users
													WHERE orders.id = order_details.order_id
													AND users.id = order_details.user_id
													AND order_details.seller_id = '$seller';");

			foreach ($data['vendor_customer'] as $rows) {
				$rows->address = Addresses::find($rows->default_address_id);
				$rows->total_orders = COUNT(OrderDetails::where('seller_id',$seller)->where('user_id',$rows->user_id)->get());
				$total_order_amount = DB::SELECT("SELECT SUM(orders.paid_amount) AS 'order_amount'
													FROM orders, order_details
													WHERE orders.id = order_details.order_id
													AND order_details.seller_id = '$seller';");
				foreach($total_order_amount as $single){
					$rows->total_order_amount = $single->order_amount;
				}
				
			}
			
            return response()->json($data, 200);
		}else{
			$data['message']= 'Page not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
	}
// Product 

	public function product_list(){
		
		$user = auth('vendor-api')->user();
		if($user){
			$product = Product::where('seller_id',$user->id)->where('is_deleted', 0)->paginate(10);
			
			return response()->json([
				'user' => $user->id,
				'product' => $product,
			], 200);
		}else{
			$data['message']= 'Page not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
	}
	
	public function product_create(){
		$brands = Brand::orderBy('id','desc')->get();
        $attributeSet = AttributeSets::orderBy('id','desc')->get();
		return response()->json([
			'brands' => $brands,
            'attributeSet' => $attributeSet,
		], 200);
	}
	
	
	public function product_store( Request $request){
		$user = auth('vendor-api')->user();
		if($user){
			if($request->product_type == 'simple'){
				$validator = Validator::make($request->all(),[
					'title' => 'required|max:255',
					'weight' => 'required',
					'weight_unit' => 'required',
					'short_description' => 'required',
					'description' => 'nullable',
					'price' => 'required | numeric',
					'in_stock' => 'required',
					'category_id' => 'required',
					'sku' => 'required',
				]);
			}elseif($request->product_type == 'variable'){
			
				$validator = Validator::make($request->all(),[
					'title' => 'required|max:255',
					'weight' => 'required',
					'weight_unit' => 'required',
					'price' => 'required | numeric',
					'short_description' => 'required',
					'description' => 'nullable',
					'category_id' => 'required',
				]);
			}elseif($request->product_type == 'digital'){
				$request->validate();
				
				$validator = Validator::make($request->all(),[
					'title' => 'required|max:255',
					'short_description' => 'required',
					'description' => 'nullable',
					'price' => 'required | numeric',
					'in_stock' => 'required',
					'category_id' => 'required',
					'sku' => 'required',
				]);

			}else{
				$data['message']= 'Product type not found!';
				$data['status'] = 0;
				return response()->json($data, 200);
			}
			

			if($validator->fails()){
				$data['status'] = 0;
				$data['message'] = $validator->errors();
				return response()->json($data, 200);
			}
			
			$slug =  \Helper::generateUniqueSlug('product','title',$request->title);


			$product = new Product; 
			$product->title = $request->title;
			$product->weight = $request->weight;
			$product->weight_unit = $request->weight_unit;
			$product->is_approximate = $request->is_approximate ? 1 : 0;
			$product->brand_id = $request->brand_id;

			$brand = Brand::where('id', $request->brand_id)->first();
			$product->brand_title = $brand->title ?? '';

			$product->product_type = $request->product_type;
			$product->attribute_set_id = ($request->attribute_set_id == -1) ? null : $request->attribute_set_id ;
			$product->category_id = implode(',',$request->category_id);

			$category = Category::where('id', $request->category_id)->first();
			$product->category_title = $category->title ?? '';

			$product->short_description = $request->short_description;
			$product->description = $request->description;
			$product->slug  = $slug;
			$product->price = $request->price ?? 0;
			$product->seller_id = $user->id;
			$product->special_price = $request->special_price;
			$product->special_price_type = $request->special_price_type;
			$product->special_price_start = $request->special_price_start;
			$product->special_price_end = $request->special_price_end;
			$product->sku = $request->sku;
			$product->manage_stock = $request->manage_stock ?? 0;
			$product->qty = $request->qty ?? 0;
			$product->in_stock = $request->in_stock ?? 0;
			$product->viewed = 1;
			$product->is_active = $request->is_active ? 1 : 0 ;
		
		
			
    	    if ($request->hasFile('default_image')){
                $uploadedImage = $request->file('default_image');
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $uploadedImage->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $product->default_image = $filePath;
                }
            }
            
     	    
            if($request->hasfile('gallery_images')) {
    			$allImage = [];
    			$filePath = '';
    			foreach($request->file('gallery_images') as $file) {
        			$uploadedImage = $file;
                    $imageTitle = uniqid();
                    $imageName = $imageTitle. '.' . $file->extension();
                    $image_resize = Image::make($uploadedImage->getRealPath());
                    $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                    $filePath = 'media/'.$imageName;
                    
                    if( $upload_success) {
                        $gGata = [
                            'title' =>  $imageTitle,
                            'file_url' => $filePath,
                            'uploaded_by' => $user->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
            
                        \DB::table('concave_media')->insert($gGata);
                        $allImage[] = implode(',',$allImage);
                    }
    			}
    			
    			$product->gallery_images = $filePath;
    			
    		}
		


			$product->save();

			//SEO SECTION STARTS
			if($request->meta_title){
				$meta = new ProductMeta;
				$meta->product_id = $product->id;
				$meta->meta_key = 'meta_title';
				$meta->meta_value = $request->meta_title;
				$meta->save();
			}

			if($request->meta_keyword){
				$meta = new ProductMeta;
				$meta->product_id = $product->id;
				$meta->meta_key = 'meta_keyword';
				$meta->meta_value = $request->meta_keyword;
				$meta->save();
			}

			if($request->meta_description){
				$meta = new ProductMeta;
				$meta->product_id = $product->id;
				$meta->meta_key = 'meta_description';
				$meta->meta_value = $request->meta_description;
				$meta->save();
			}

			 //SEO SECTION ENDS
			 if($request->product_sale_option){
				$meta = new ProductMeta;
				$meta->product_id = $product->id;
				$meta->meta_key = 'product_sale_option';
				$meta->meta_value = $request->product_sale_option;
				$meta->save();
			}
			

			if($request->specification){
				$meta = new ProductMeta;
				$meta->product_id = $product->id;
				$meta->meta_key = 'product_sepecification';
				$meta->meta_value = serialize($request->specification);
				$meta->save();
			}

			// shipping option start
			if($request->shipping_option){
				$meta = new ProductMeta;
				$meta->product_id = $product->id;
				$meta->meta_key = 'product_shipping_option';
				$meta->meta_value = serialize($request->shipping_option);
				$meta->save();
			}
			
			if($request->miscellaneous_information){
				$meta = new ProductMeta;
				$meta->product_id = $product->id;
				$meta->meta_key = 'product_miscellaneous_information';
				$meta->meta_value = serialize($request->miscellaneous_information);
				$meta->save();
			}
			
			// shipping option end

			if($request->option){
				
				$option = array_values($request->option);
				for($x = 0; $x < count($option); $x++){
					$option[$x]['value'] = array_values($option[$x]['value']);
				}
				
				$meta = new ProductMeta;
				$meta->product_id = $product->id;
				$meta->meta_key = 'custom_options';
				$meta->meta_value = serialize($request->option);
				$meta->save();
			}

			if($request->product_type == 'digital'){
				if($request->product_sale_option == 'downloadable'){

					if($request->downloadable_file_url){
						$meta = new ProductMeta;
						$meta->product_id = $product->id;
						$meta->meta_key = 'product_downloadable_file_url';
						$meta->meta_value = $request->downloadable_file_url;
						$meta->save();
					}


					if ($request->hasFile('downloadable_file')) {
						$file = $request->file('downloadable_file');
					   
						$fileName = round(microtime(true)).'.'.$file->getClientOriginalExtension();
						$location = public_path('products/downloadable/'.$fileName);

						if (move_uploaded_file($file, $location)) {
							$meta = new ProductMeta;
							$meta->product_id = $product->id;
							$meta->meta_key = 'product_downloadable_file';
							$meta->meta_value = $fileName;
							$meta->save();
						}
					}
				}
			}

			$data['message']= 'Product successfully created!';
			$data['status'] = 1;
			return response()->json($data, 200);
		}else{
			$data['message']= 'User not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
    }
    


	public function product_edit($id){
		
		$product = Product::find($id);
		
		$product->attributes = AttributeSets::find($product->attribute_set_id);

		$category = Category::where('id',$product->category_id)->first();
		$product->category_image = $category->image;

        $meta = ProductMeta::where('product_id',$id)->get();
        foreach ($meta as $row) {
			if(gettype($row->meta_value) != 'string') continue;
			
	
			if($row->meta_key == 'product_sepecification'){
				if($row->meta_value){
					$row->meta_values = unserialize($row->meta_value);
				}
			}
			
			if($row->meta_key == 'product_shipping_option'){
				if($row->meta_value){
					$row->meta_values = unserialize($row->meta_value);
				}
			}
			
			if($row->meta_key == 'product_miscellaneous_information'){
				if($row->meta_value){
					$row->meta_values = unserialize($row->meta_value);
				}
			}
			
        	
        }

		return response()->json([
			'product'    => $product,
			'meta'    => $meta,
		], 200);
		
		
	}
	
	
	public function product_update(Request $request,$id){

	
		$seller = auth('vendor-api')->user();
		if(!$seller){
			$data['message']= 'Token not found or expired!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}

		$seller_id = $seller->id;


		if($request->product_type == 'simple'){
            $request->validate([
                'title' => 'required|max:255',
                'weight' => 'required',
                'weight_unit' => 'required',
                'short_description' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric',
                'in_stock' => 'required',
                'category_id' => 'required',
                'sku' => 'required'
            ]);

        }elseif($request->product_type == 'variable'){
            $request->validate([
                'title' => 'required|max:255',
                'weight' => 'required',
                'weight_unit' => 'required',
                'short_description' => 'required',
                'description' => 'nullable',
                'category_id' => 'required'
            ]);
        }elseif($request->product_type == 'digital'){
            $request->validate([
                'title' => 'required|max:255',
                'short_description' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric',
                'in_stock' => 'required',
                'category_id' => 'required',
                'sku' => 'required'
            ]);
        }else{
            $data['message']= 'Product Type not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
        }

        $product = Product::find($id);
        $product->title = $request->title;
        $product->weight = $request->weight;
        $product->weight_unit = $request->weight_unit;
        $product->is_approximate = $request->is_approximate ? 1 : 0;
        $product->brand_id = $request->brand_id;


        $brand = Brand::where('id', $request->brand_id)->first();
        $product->brand_title = $brand->title ?? '';

        
        $product->product_type = $request->product_type;
        $product->attribute_set_id = ($request->attribute_set_id == -1) ? null : $request->attribute_set_id ;
        $product->category_id = $request->category_id;

        $category = Category::where('id', $request->category_id)->first();
        $product->category_title = $category->title ?? '';

        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->price = $request->price ?? 0;
        $product->seller_id = $seller_id;
        $product->special_price = $request->special_price;
        $product->special_price_type = $request->special_price_type;
        $product->special_price_start = $request->special_price_start;
        $product->special_price_end = $request->special_price_end;
        $product->sku = $request->sku;
        $product->manage_stock = $request->manage_stock ?? 0;
        $product->qty = $request->qty ?? 0;
        $product->in_stock = $request->in_stock ?? 0;
        $product->viewed = 1;
        $product->is_active = $request->is_active ? 1 : 0 ;
    
        // if($request->default_image) $product->default_image = $request->default_image;
        // if($request->gallery_images) $product->gallery_images = implode(',',$request->gallery_images);

        if ($request->hasFile('default_image')){
            $uploadedImage = $request->file('default_image');
            $imageTitle = uniqid();
            $imageName = $imageTitle. '.' . $uploadedImage->extension();
            $image_resize = Image::make($uploadedImage->getRealPath());
            $upload_success = $image_resize->save(public_path('/media/' .$imageName));
            $filePath = 'media/'.$imageName;
            
            if( $upload_success) {
                $gGata = [
                    'title' =>  $imageTitle,
                    'file_url' => $filePath,
                    'uploaded_by' => $user->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
    
                \DB::table('concave_media')->insert($gGata);
                $product->default_image = $filePath;
            }
        }
        
        
        if($request->hasfile('gallery_images')) {
            $allImage = [];
            $filePath = '';
            foreach($request->file('gallery_images') as $file) {
                $uploadedImage = $file;
                $imageTitle = uniqid();
                $imageName = $imageTitle. '.' . $file->extension();
                $image_resize = Image::make($uploadedImage->getRealPath());
                $upload_success = $image_resize->save(public_path('/media/' .$imageName));
                $filePath = 'media/'.$imageName;
                
                if( $upload_success) {
                    $gGata = [
                        'title' =>  $imageTitle,
                        'file_url' => $filePath,
                        'uploaded_by' => $user->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
        
                    \DB::table('concave_media')->insert($gGata);
                    $allImage[] = implode(',',$allImage);
                }
            }
            
            $product->gallery_images = $filePath;
            
        }

        $product->save();

        //SEO SECTION STARTS
        if($request->meta_title){
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id, 'meta_key' =>  'meta_title'],
                [
                    'meta_key' =>  'meta_title',
                    'meta_value' =>  $request->meta_title
                ]
            );
        }

        if($request->meta_keyword){
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id,'meta_key' =>  'meta_keyword'],
                [
                    'meta_key' =>  'meta_keyword',
                    'meta_value' => $request->meta_keyword
                ]
            );
        }

        if($request->meta_description){
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id,'meta_key' =>  'meta_description'],
                [
                    'meta_key' =>  'meta_description',
                    'meta_value' => $request->meta_description
                ]
            );
        }


        if($request->product_sale_option){
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id,'meta_key' =>  'product_sale_option'],
                [
                    'meta_key' =>  'product_sale_option',
                    'meta_value' => $request->product_sale_option
                ]
            );
        }


        //SEO SECTION ENDS

    
        if($request->specification){
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id,'meta_key' =>  'product_sepecification'],
                [
                    'meta_key' =>  'product_sepecification',
                    'meta_value' => serialize($request->specification)
                ]
            );
        }
		
		if($request->shipping_option){
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id,'meta_key' =>  'product_shipping_option'],
                [
                    'meta_key' =>  'product_shipping_option',
                    'meta_value' => serialize($request->shipping_option)
                ]
            );
        }
		
		
		if($request->miscellaneous_information){
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id,'meta_key' =>  'product_miscellaneous_information'],
                [
                    'meta_key' =>  'product_miscellaneous_information',
                    'meta_value' => serialize($request->miscellaneous_information)
                ]
            );
        }
		

		
		if($request->option){
			$option = array_values($request->option);
			for($x = 0; $x < count($option); $x++){
				$option[$x]['value'] = array_values($option[$x]['value']);
			}
			
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id,'meta_key' =>  'custom_options'],
                [
                    'meta_key' =>  'custom_options',
                    'meta_value' => serialize($request->option)
                ]
            );
        }
		
		$data['message']= 'Product successfully updated!';
		$data['status'] = 0;
		return response()->json($data, 200);
	}
	
	
	public function product_delete(Request $request,$id){
		$product = Product::find($id);
		$product->is_deleted = 1;
		$product->save();
		
		$data['message']= 'Product successfully deleted!';
		$data['status'] = 0;
		return response()->json($data, 200);
	}
	
	
	
	// Orders
	public function order_list(){
		$user = auth('vendor-api')->user();
		if($user){
			$orders = Order::orderBy('id','desc')->with('statuses')->get();
			foreach($orders as $items){
				$is_seller = false;
				foreach(OrderDetails::where('order_id', $items->id)->get() as $row){
					if($row->seller_id == $user->id){
						$is_seller = true;
					}
				}
				if($is_seller == true){
					$items->seller = $user->id;
				}
			}
			$order = [];
			foreach($orders as $row){
				if($row->seller == $user->id){
					$order[] = $row;
				}
			}
			$data['orders'] = $order;
			return response()->json($data, 200);
			
		}else{
			$data['message']= 'No Order found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
		
	}
	
	public function order_details($id){
		$user = auth('vendor-api')->user();
		if($user){
			//Order
			$orders = Order::find($id);
			$orders->order_status = Status::find($orders->status);
			
			//Customer
			$customer = User::find($orders->user_id);
			
			//Address
			$address = Addresses::where('id',$orders->address_id)->with('division')->with('district')->with('upazila')->with('union')->first();
			
			//Order details
			$orders_details = OrderDetails::where('order_id', $id)->where('seller_id',$user->id)->get();
			foreach($orders_details as $items){
				$items->details_status = Status::find($items->status);
				$items->product_details = Product::find($items->product_id);
			}
			
			$data['orders'] = $orders;
			$data['orders_details'] = $orders_details;
			$data['customer'] = $customer;
			$data['address'] = $address;
			return response()->json($data, 200);
		}else{
			$data['message']= 'Page not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
	}
	
	// Order details status change
	public function order_update(Request $request){
		$user = auth('vendor-api')->user();

		if($user){
            // status 
            $status = DB::TABLE('statuses')->where('id',$request->status)->first();

			$order_details = OrderDetails::find($request->id);
			$order_details->status = $request->status;
			$order_details->save();
			
			$data['message']= 'Order status successfully updated !';
			$data['status'] = 1;
			
			
			

            //Send Push Notification to User
            $user_id = Order::find($order_details->order_id)->user_id;
            $message = [
                    'order_id' =>'DR'. Carbon::parse($order_details->created_at)->format('y') .$order_details->order_id,
                    'type' =>'order',
                    'message' =>'Order status successfully updated !',
                    'status_id' =>$request->status,
                    ];

            Helper::sendPushNotification($user_id,1,'Order Status','Order Status Changed !',json_encode($message));
            \Helper::sendSms($user->phone,'আপনার অর্ডার স্ট্যাটাস পরিবর্তন করা হয়েছে! অর্ডার আইডি হল #'.'DR' . date('y', strtotime(Carbon::now())) .'  অর্ডার স্ট্যাটাস# '.$status->title);

            //Send Push Notification to Seller
            Helper::sendPushNotification($user->id,2,'Order Status','Order Status Changed!',json_encode($message));

            $data['user_id'] = $user_id;
			return response()->json($data, 200);
		}else{
			$data['message']= 'You have to login first!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}
	}
	
	
	//Notifications

    public function getVendorwiseNotifications(){
		$user = auth('vendor-api')->user();
		if($user){
			$notifications = DB::table('notifications')->where('user_type', 2)->where('user_id', $user->id)->where('status', 1)->orderBy('id','DESC')->get();
			if(count($notifications) > 0){
				
				foreach($notifications as $item){
					$item->description = json_decode($item->description);
				}
				$data['status'] = '1';
				$data['notification'] = $notifications;
				return response()->json($data, 200);
			}else{
				$data['status'] = '0';
				$data['notification'] = $notifications;
				return response()->json($data, 200);
			}
		}else{
			$data['status'] = '0';
			$data['message'] = 'User Not found.';
			return response()->json($data, 200);  
		}
    }
	
	public function viewNotification($id){
		$user = auth('vendor-api')->user();
		if($user){
			$notification = Notifications::find($id);
			if($notification){
				DB::table('notifications')
                ->where('id', $id)
                ->update(['status' => 0]);
				
				$data['status'] = '1';
				$data['message'] = 'Notification Seen.';
				return response()->json($data, 200);
			}
			
		}else{
			$data['status'] = '0';
			$data['message'] = 'User Not found.';
			return response()->json($data, 200);  
		}
	}
	
	public function attribute_set(){
		$user = auth('vendor-api')->user();

		if($user){
			$data = DB::table('attribute_sets')->where('is_active',1)->where('is_deleted',0)->get();
			if($data){
				foreach($data as $d){
					if($d->attribute_ids){
						$attribute_ids = explode(',',$d->attribute_ids);
						if($attribute_ids){
							foreach($attribute_ids as $attribute_id){
								$attribute = DB::table('attributes')->where('id',$attribute_id)->where('is_active',1)->where('is_deleted',0)->first();
								if($attribute){
									if($attribute->attribute_values){
										$attribute->attribute_values = unserialize($attribute->attribute_values);
									}
									$d->attribute[] = $attribute;
								}
								
							}
						}
						
						
					}
				}
			}
			return response()->json($data, 200); 
		}else{
			$data['message']= 'User not found!';
			$data['status'] = 0;
			return response()->json($data, 200);
		}

	}

    public function brands(){
        $user = auth('vendor-api')->user();

        if($user){
            $data = DB::table('brands')->where('is_active',1)->where('is_deleted',0)->get();
            return response()->json($data, 200);
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }


    public function reviews(){
        $user = auth('vendor-api')->user();
        
        if($user){
            $comment_ids = [];
            $allData = Review::orderBy('id','desc')->where('parent_id', null)->get();
            foreach($allData as $d){
                if($d->product->seller_id == $user->id){
                    $comment_ids[] = $d->id;
                }
            }
            
            $data = Review::orderBy('id','desc')->whereIn('id',$comment_ids)->get();
            foreach ($data as $row) {
                $row->user_name = $row->user->name ?? '';
                $row->product_name = $row->product->title ?? '';
                $row->product_image = $row->product->default_image ?? '';
                $row->comment_text = unserialize($row->comment);
            }

            return response()->json($data, 200);
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }


    public function productRefundRequest(){
        $user = auth('vendor-api')->user();
        
        if($user){
            $data = DB::table('order_details')->where('status', 8)->where('seller_id', $user->id)->get();
            foreach ($data as $row ) {
                $row->product_details = DB::table('products')->where('id', $row->product_id)->first();
                $row->user_details = DB::table('users')->where('id', $row->user_id)->first();
            }
            return response()->json($data, 200);
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }

    public function sellerBalance(){
        $user = auth('vendor-api')->user();

        if($user){
            $balance = \Helper::get_seller_balance($user->id);
            $data['status'] = 1;
            $data['balance'] = $balance;
            return response()->json($data, 200);
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }


    public function withdrawalRequest(){
        $user = auth('vendor-api')->user();

        if($user){
            $requests = DB::table('withdrawal_requests')->where('seller_id', $user->id)->where('is_deleted', 0)->get();
            $balance = \Helper::get_seller_balance($user->id);

            $data['balance'] = $balance;
            $data['request'] = $requests;

            return response()->json($data, 200);
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }


    public function vendor_withdrawal_request_send(Request $request){
        $user = auth('vendor-api')->user();
        if($user){
            $validator = Validator::make($request->all(), [
                'payment_method' => 'required',
                'requested_amount' => 'required'
            ]);

            if ($validator->fails()) {
                $data['message']= 'Something went wrong!';
                $data['status'] = 0;
                return response()->json($data, 200);
            }
            if (\Helper::get_seller_balance($user->id) < $request->requested_amount) {
                $data['message']= 'You don\'t have enough balance to withdrawal!';
                $data['status'] = 0;
                return response()->json($data, 200);
            }else {
                $date = date('Y-m-d');
                $withdrawal = new WithdrawalRequest();
                $withdrawal->date = $date;
                $withdrawal->seller_id = $user->id;
                $withdrawal->payment_method = $request->payment_method;
                $withdrawal->requested_amount = $request->requested_amount;
                $withdrawal->message = $request->message;
                $withdrawal->save();

                $data['message']= 'Request sended successfully!';
                $data['status'] = 1;
                return response()->json($data, 200);
            }
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }
	

    public function sellerPaymentMethod(){
        $user = auth('vendor-api')->user();
        if($user){
            $payment_methods = [];
            $shop_info = ShopInfo::where('seller_id', $user->id)->first();
            if ($shop_info->bank_name != null) {
                $payment_methods[] = [
                    'method' => 'Bank',
                    'name' => $shop_info->bank_name,
                    'account_number' => $shop_info->account_number,
                ];
            }if ($shop_info->bkash != null) {
                $payment_methods[] = [
                    'method' => 'Bkash',
                    'name' => 'Bkash',
                    'account_number' => $shop_info->bkash,
                ];
            }if ($shop_info->rocket != null) {
                $payment_methods[] = [
                    'method' => 'Rocket',
                    'name' => 'Rocket',
                    'account_number' => $shop_info->rocket,
                ];
            }if ($shop_info->nagad != null) {
                $payment_methods[] = [
                    'method' => 'Nagad',
                    'name' => 'Nagad',
                    'account_number' => $shop_info->nagad,
                ];
            }if ($shop_info->upay != null) {
                $payment_methods[] = [
                    'method' => 'Upay',
                    'name' => 'Upay',
                    'account_number' => $shop_info->upay,
                ];
            }

            $data = $payment_methods;

            return response()->json($data, 200);
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    } 

    public function sallerSalesReport(Request $request){
        $user = auth('vendor-api')->user();
        if($user){

            $all_order_amount = Order::select('orders.*', 'order_details.seller_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id');

            $total_product_returned = ReturnRequest::orderBy('id', 'desc');

            $total_product_ordered = OrderDetails::orderBy('id', 'desc');

            $total_ordered_customer = OrderDetails::orderBy('id', 'desc');

            $all_order_amount->where('order_details.seller_id', $user->id);
            $total_product_returned->where('seller_id', $user->id);
            $total_product_ordered->where('seller_id', $user->id);
            $total_ordered_customer->where('seller_id', $user->id);

            $total_product_exchanged = DB::table('product_return')->where('seller_id', $request->seller_id);
            $total_product_refunded = DB::table('product_return')->where('seller_id', $request->seller_id);
            $refunded_amount = DB::table('product_return')->where('seller_id', $request->seller_id);

            if ($request->filter_by == 'today') {
                $today = date('Y-m-d');

                $all_order_amount->whereDay('orders.created_at', $today);

                $total_product_returned->whereDay('created_at', $today);
                $total_product_ordered->whereDay('created_at', $today);
                $total_ordered_customer->whereDay('created_at', $today);

                $total_product_exchanged = DB::table('product_return')->whereDay('created_at', $today);
                $total_product_refunded = DB::table('product_return')->whereDay('created_at', $today);
                $refunded_amount = DB::table('product_return')->whereDay('created_at', $today);
            } elseif ($request->filter_by == 'this month') {
                $today = date("m");

                $all_order_amount->whereMonth('orders.created_at', $today);

                $total_product_returned->whereMonth('created_at', $today);
                $total_product_ordered->whereMonth('created_at', $today);
                $total_ordered_customer->whereMonth('created_at', $today);

                $total_product_exchanged = DB::table('product_return')->whereMonth('created_at', $today);
                $total_product_refunded = DB::table('product_return')->whereMonth('created_at', $today);
                $refunded_amount = DB::table('product_return')->whereMonth('created_at', $today);
            } elseif ($request->filter_by == 'this year') {
                $today = date("Y");

                $all_order_amount->whereYear('orders.created_at', $today);

                $total_product_returned->whereYear('created_at', $today);
                $total_product_ordered->whereYear('created_at', $today);
                $total_ordered_customer->whereYear('created_at', $today);

                $total_product_exchanged = DB::table('product_return')->whereYear('created_at', $today);
                $total_product_refunded = DB::table('product_return')->whereYear('created_at', $today);
                $refunded_amount = DB::table('product_return')->whereYear('created_at', $today);
            } elseif ($request->filter_by == '7 day') {
                $today  = Carbon::today();
                $filter_option = Carbon::today()->subDays(7);

                $all_order_amount->where('orders.created_at', '>=', $filter_option);

                $total_product_returned->where('created_at', '>=', $filter_option);
                $total_product_ordered->where('created_at', '>=', $filter_option);
                $total_ordered_customer->where('created_at', '>=', $filter_option);

                $total_product_exchanged = DB::table('product_return')->where('created_at', '>=', $filter_option);
                $total_product_refunded = DB::table('product_return')->where('created_at', '>=', $filter_option);
                $refunded_amount = DB::table('product_return')->where('created_at', '>=', $filter_option);

            } elseif ($request->filter_by == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $all_order_amount->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);

                    $total_product_returned->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $total_product_ordered->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $total_ordered_customer->whereBetween('created_at', [$request->start_date, $request->end_date]);

                    $total_product_exchanged = DB::table('product_return')->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $total_product_refunded = DB::table('product_return')->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $refunded_amount = DB::table('product_return')->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            }


            if ($request->status > 0) {
                $all_order_amount->where('orders.status', $request->status_id);

                $total_product_exchanged = DB::table('product_return')->where('status', $request->status_id);
                $total_product_refunded = DB::table('product_return')->where('status', $request->status_id);
                $refunded_amount = DB::table('product_return')->where('status', $request->status_id);
            }


            $order_amount = $all_order_amount->groupBy('orders.id')->with('user')->get();
            $amount = 0;
            foreach ($order_amount as $row) {
                $amount = $amount + $row->total_amount;

                if ($row->is_pickpoint == 1) {
                    $row->billing_name  = $row->pickpoint_address->title;
                    $row->billing_phone  = $row->pickpoint_address->phone;
                }else{
                    $row->billing_name = $row->address->shipping_first_name .' '.$row->address->shipping_last_name;
                    $row->billing_phone = $row->address->shipping_phone;
                }
                
            }

            $order_item = count($all_order_amount->groupBy('orders.id')->get());

            $order_porduct_return = $total_product_returned->count();
            $order_items = $total_product_ordered->sum('product_qty');
            $customers = $total_ordered_customer->count();

            $total_product_exchanged = $total_product_exchanged->where('return_type', 'exchange')->count();
            $total_product_refunded = $total_product_refunded->where('return_type', 'refund')->count();
            $refunded_amount = $refunded_amount->where('return_type', 'refund')->sum('refund_amount');

            $data['total_order_amount'] = $amount;
            $data['total_orders'] = $order_item;
            $data['total_product_returned'] = $order_porduct_return;
            $data['total_product_ordered'] = $order_items;
            $data['total_ordered_customer'] = $customers;
            $data['total_product_exchanged'] = $total_product_exchanged;
            $data['total_product_refunded'] = $total_product_refunded;
            $data['refunded_amount'] = $refunded_amount;

            $data['all_orders'] = $order_amount;

            $data['status'] = 1;
            return response()->json($data, 200);
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }


    public function sallerBalanceHistory(){
        $user = auth('vendor-api')->user();
        if($user){
            $history = SellerAccountHistory::where('status', '!=', 0)->where('seller_id', $user->id)->orderby('id', 'desc')->with('product')->get();
            $pending_maturation_balance = \Helper::getSellerPendingMaturationBalance($user->id);
            $revenue = \Helper::get_seller_revenue($user->id);
            $withdrawal_amount = \Helper::getSellerWithdrawalAmount($user->id);
            $balance = \Helper::get_seller_balance($user->id);


            $data['pending_maturation_balance'] = $pending_maturation_balance;
            $data['revenue'] = $revenue;
            $data['withdrawal_amount'] = $withdrawal_amount;
            $data['balance'] = $balance;
            $data['history'] = $history;

            $data['status'] = 1;
            return response()->json($data, 200);

        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }


    public function getSingleProductWise($product_id){
        $user = auth('vendor-api')->user();
        if($user){
            $product = Product::find($product_id);
            if ($product && $product->seller_id == $user->id) {
                $total_sold = OrderDetails::where('product_id', $product_id)->sum('product_qty');
                $total_amount = $total_sold *  $product->price;
                $wishlists = Wishlist::where('product_id', $product_id)->with('product')->get();
                $carts = Cart::where('product_id', $product_id)->with('product')->get();
                $product_return = DB::table('product_return')->where('product_id', $product_id)->count();
                $total_refund = OrderDetails::where('product_id', $product_id)->where('status', 8)->sum('product_qty');


                $data['status'] = 1;
                $data['total_sold'] = $total_sold;
                $data['total_amount'] = $total_amount;
                $data['product_return'] = $product_return;
                $data['total_refund'] = $total_refund;
                $data['wishlists'] = $wishlists;
                $data['carts'] = $carts;

                return response()->json($data, 200);
            }else{
                $data['message']= 'Product not found!';
                $data['status'] = 0;
                return response()->json($data, 200);
            }
            
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }


    public function getStatusWiseSale(Request $request){
        $user = auth('vendor-api')->user();
        if($user){
            $order = Order::select('orders.*', 'order_details.seller_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id');

            $order->where('order_details.seller_id', $user->id);
            
            if ($request->filter_by == 'today') {
                $today = date('Y-m-d');
                $order->whereDay('orders.created_at', $today);
            } elseif ($request->filter_by == 'this month') {
                $today = date("m");
                $order->whereMonth('orders.created_at', $today);
            } elseif ($request->filter_by == 'this year') {
                $today = date("Y");
                $order->whereYear('orders.created_at', $today);
            } elseif ($request->filter_by == '7 day') {
                $today  = Carbon::today();
                $filter_option = Carbon::today()->subDays(7);

                $order->where('orders.created_at', '>=', $filter_option);
            } elseif ($request->filter_by == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $order->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
                }
            }

            if ($request->status_id > 0) {
                $order->where('orders.status', $request->status_id);
            }

            $all_orders = $order->with('statuses')->with('user')->orderBy('orders.id', 'desc')->groupBy('orders.id')->get();

            $total_amount = 0;
            $total_order = 0;

            foreach ($all_orders as $row) {
                $total_amount = $total_amount + $row->total_amount;
                $total_order++;
            }

            $data['status'] = 0;
            $data['total_order_amount'] = $total_amount;
            $data['total_orders'] = $total_order;
            $data['orders'] = $all_orders;

            return response()->json($data, 200);
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }


    public function lowStockProducts(){
        $user = auth('vendor-api')->user();
        if($user){
            $alert_qty = Helper::getSettings('low_stock_alert');

            $products = Product::where('is_deleted', 0);

            $products->where('seller_id', $user->id);
            
            $collection = $products->where('qty', '<=', $alert_qty)->with('seller')->get();

            foreach ($collection as $key => $product) {
                if ($product->product_type == "variable") {
                    $meta = DB::table('product_metas')->where('product_id', $product->id)->where('meta_key', 'custom_options')->first();
                    if ($meta) {
                        if ($meta_value = $meta->meta_value) {
                            $meta_value = unserialize($meta_value);
                            $qtyArray = [];

                            foreach ($meta_value as $single_value) {
                                foreach ($single_value['value'] as $value) {
                                    $qtyArray[] = $value['qty'];
                                }
                            }

                            if ($qtyArray) {
                                $minimumQty = min($qtyArray);

                                if ($minimumQty <= $alert_qty) {
                                    $product->qty = $minimumQty;
                                } else {
                                    $collection->forget($key);
                                }
                            }
                        }
                    }
                }
            }

            $data['status'] = 1;
            $data['products'] = $collection;

            return response()->json($data, 200);
        }else{
            $data['message']= 'User not found!';
            $data['status'] = 0;
            return response()->json($data, 200);
        }
    }

}