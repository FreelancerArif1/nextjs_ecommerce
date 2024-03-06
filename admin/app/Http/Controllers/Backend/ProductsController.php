<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductMeta;
use App\Models\ProductSpecification;
use App\Models\Brand;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\ProductImage;
use App\Models\AttributeSets;
use App\Models\Admins;
use App\Models\ShopInfo;
use App\Models\ReturnRequest;
use App\Models\ProductRestockRequest;
use App\Models\Order;
use App\Models\Status;
use App\Models\OrderDetails;
use App\Models\Upazila;
use App\Models\ProductLocalization;
use Yajra\DataTables\DataTables;
use Image;
use DB;
use Auth;
use Response;
use File;
use Helper;
use Artisan;
use Illuminate\Support\Str;
use App\Jobs\ProductFeedGenerate;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }


    public function index()
    {
        if (is_null($this->user) || !$this->user->can('product.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        // Artisan::call('queue:listen');
        $product = Product::orderBy('id', 'desc')->where('is_deleted', 0)->paginate(10);
        return view('backend.pages.product.list')->with('products', $product);
    }



    public function create()
    {
        if (is_null($this->user) || !$this->user->can('product.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if ($this->user->getRoleNames() == '["seller"]') {
            if ($this->user->shopinfo->status == 0) {
                return redirect()->route('admin.index')->with('failed', 'You cann\'t create product because your shop has been disabled!');
            }
        }

       
        $brands = Brand::orderBy('id', 'desc')->get();
        $attributeSet = AttributeSets::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        return view('backend.pages.product.create')->with(array(
            'brands' => $brands,
            'attributeSet' => $attributeSet,

        ));
    }


    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('product.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if ($this->user->getRoleNames() == '["seller"]') {
            if ($this->user->shopinfo->status == 0) {
                return redirect()->route('admin.index')->with('failed', 'You cann\'t update product because your shop has been disabled!');
            }
        }


        $product = Product::find($id);
        $categories = Category::orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('id', 'desc')->get();
        $attributeSet = AttributeSets::where('is_deleted', 0)->orderBy('id', 'desc')->get();
        return view('backend.pages.product.edit')->with(array(
            'product'    => $product,
            'categories' => $categories,
            'brands'     => $brands,
            'attributeSet' => $attributeSet,
        ));
    }

    public function store(Request $request){

        if (is_null($this->user) || !$this->user->can('product.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if ($this->user->getRoleNames() == '["seller"]') {
            if ($this->user->shopinfo->status == 0) {
                return redirect()->route('admin.index')->with('failed', 'You cann\'t create product because your shop has been disabled!');
            }
        }

        if ($request->product_type == 'simple') {
            $request->validate([
                'title' => 'required|max:255',
                'weight' => 'required|numeric',
                'weight_unit' => 'required',
                'short_description' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric|min:1',
                'sku' => 'required',
                'slug' => 'required | unique:products',
            ]);
        } elseif ($request->product_type == 'variable') {
            $request->validate([
                'title' => 'required|max:255',
                'weight' => 'required|numeric',
                'weight_unit' => 'required',
                'price' => 'required | numeric|min:1',
                'short_description' => 'required',
                'description' => 'nullable',
                'slug' => 'required | unique:products',
            ]);
        } elseif ($request->product_type == 'digital') {
            $request->validate([
                'title' => 'required|max:255',
                'short_description' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric|min:1',
                'sku' => 'required',
                'slug' => 'required | unique:products',
            ]);
        } elseif ($request->product_type == 'service') {
            $request->validate([
                'title' => 'required|max:255',
                'short_description' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric|min:1',
                'sku' => 'required',
                'slug' => 'required | unique:products',
            ]);
        } else {
            return back()->with('failed', 'Product type is invalid!');
        }

        if ($request->special_price) {
            $request->validate([
                'special_price' => 'numeric|min:1',
                'special_price_type' => 'required|numeric',
                'special_price_end' => 'required',
                'special_price_start' => 'required',
            ]);
        }

        $product = new Product;
        $product->title = $request->title;

        $product->weight = $request->weight;
        $product->weight_unit = $request->weight_unit;
        $product->is_approximate = $request->is_approximate ? 1 : 0;
        $product->is_grocery = $request->is_grocery ? 'grocery' : 'other';
        $product->brand_id = $request->brand_id;

        $brand = Brand::where('id', $request->brand_id)->first();
        $product->brand_title = $brand->title ?? '';

        $product->product_type = $request->product_type;
        $product->attribute_set_id = ($request->attribute_set_id == -1) ? null : $request->attribute_set_id;

            if($request->category_id){
                $product->category_id = implode(',', $request->category_id);
            }
        
        
      

        $category = Category::where('id', $request->category_id)->first();
        $product->category_title = $category->title ?? '';

        $product->short_description = $request->short_description;
        $product->description = $request->description;

        $slug = str_replace(' ', '-', Str::slug(Str::lower($request->slug)));

        if (!is_null($dbSlug = Product::where('slug', $slug)->first())) {
            $slug = $dbSlug->slug . '-1';
        }

        if ($dbSlugNew = Product::where('slug', $slug)->first()) {
            $slug = $dbSlugNew->slug . '-' . rand(1111, 9999);
        }

        $product->slug  = $slug;

        $product->tag = $request->tag;

        $product->price = $request->price ?? 0;

        if (Auth::user()->getRoleNames() == '["seller"]'){
            $product->product_cost = 0;
            $product->packaging_cost = 0;
            $product->security_charge = 0;
            $product->loyalty_point = 0;
            $product->vat = 0;
            $product->seller_id = Auth::user()->id;
            $product->shuffle_number = Product::orderBy('shuffle_number', 'desc')->first()->shuffle_number ?? 1 + 1;
            $product->allow_review =  1;
            $product->allow_refund =  1;
            
        }
        else{
            $product->product_cost = $request->product_cost ?? 0;
            $product->packaging_cost = $request->packaging_cost ?? 0;
            $product->security_charge = $request->security_charge ?? 0;
            $product->loyalty_point = $request->loyalty_point ?? 0;
            $product->vat = $request->vat ?? 0;
            $product->seller_id = $request->seller_id;
            $product->shuffle_number = $request->shuffle_number ?? 1;
            $product->allow_review = $request->allow_review ? 1 : 0;
            $product->allow_refund = $request->allow_refund ? 1 : 0;
            
        }


        if($request->delivery_location){
            $product->delivery_location = implode(',', $request->delivery_location);
        }
        
        $product->barcode = $request->barcode;
        $product->list_price = $request->list_price ?? 0;
        $product->tour_price = $request->tour_price ?? 0;

        $product->special_price = $request->special_price;
        $product->special_price_type = $request->special_price_type;
        $product->special_price_start = date("Y-m-d H:i:s", strtotime($request->special_price_start));
        $product->special_price_end = date("Y-m-d H:i:s", strtotime($request->special_price_end));
        $product->sku = $request->sku;
        $product->manage_stock = $request->manage_stock ? 1 : 0;



        $product->aff_commission_amount = $request->aff_commission_amount;
        $product->aff_commission_type = $request->aff_commission_type;
        $product->aff_commission_from = date("Y-m-d H:i:s", strtotime($request->aff_commission_from));
        $product->aff_commission_to = date("Y-m-d H:i:s", strtotime($request->aff_commission_to));



        $product->qty = $request->qty ?? 0;
        $product->max_cart_qty = $request->max_cart_qty ?? 5;
        $product->minimum_cart_value = $request->minimum_cart_value ?? 0;
        $product->in_stock = $request->in_stock ?? 0;
        $product->viewed = 1;
        $product->is_active = $request->is_active ? 1 : 0;
        $product->is_promotion = $request->is_promotion ? 1 : 0;


        $product->video_link = $request->video_link ?? null;

        $related_products = '';
        if ($request->related_products) {
            foreach ($request->related_products as $key => $val) {
                $related_products .= $val . ',';
            }
        }
        $product->related_products = $related_products;

        if ($request->default_image) $product->default_image = $request->default_image;
        if ($request->gallery_images) $product->gallery_images = implode(',', $request->gallery_images);

        
        $product->require_moderation = $request->require_moderation ? 1 : 0;
        $product->product_qc = $request->product_qc ? 1 : 0;

        $product->save();

        //SEO SECTION STARTS
        if ($request->meta_title) {
            $meta = new ProductMeta;
            $meta->product_id = $product->id;
            $meta->meta_key = 'meta_title';
            $meta->meta_value = $request->meta_title;
            $meta->save();
        }

        if ($request->meta_keyword) {
            $meta = new ProductMeta;
            $meta->product_id = $product->id;
            $meta->meta_key = 'meta_keyword';
            $meta->meta_value = $request->meta_keyword;
            $meta->save();
        }

        if ($request->meta_description) {
            $meta = new ProductMeta;
            $meta->product_id = $product->id;
            $meta->meta_key = 'meta_description';
            $meta->meta_value = $request->meta_description;
            $meta->save();
        }

        //SEO SECTION ENDS
        if ($request->product_sale_option) {
            $meta = new ProductMeta;
            $meta->product_id = $product->id;
            $meta->meta_key = 'product_sale_option';
            $meta->meta_value = $request->product_sale_option;
            $meta->save();
        }


        if ($request->specification) {
            $data = [];
            if ($specification = $request->specification) {
                foreach ($specification as $key => $val) {
                    $data[] = [
                        'product_id' => $product->id,
                        'meta_key' => $key,
                        'meta_value' => $val,
                    ];
                }
            }

            if ($data) {
                ProductSpecification::insert($data);
            }
        }

        // shipping option start
        $shippingHelper = Helper::shipping_cost($request->is_grocery ? 'grocery' : 'other', $request->weight, $request->weight_unit);
        $shipping = array (
            'inside_origin' => 
            array (
              'inside_standard_shipping' => $shippingHelper['inside_origin_standard'],
              'inside_express_shipping' => $shippingHelper['inside_origin_express'],
            ),
            'outside_origin' => 
            array (
              'outside_standard_shipping' => $shippingHelper['outside_origin_standard'],
              'outside_express_shipping' => $shippingHelper['outside_origin_express'],
            ),
        );
        
        
        // dd($shipping);
    if(Auth::user()->getRoleNames() == '["seller"]'){
        $meta = new ProductMeta;
        $meta->product_id = $product->id;
        $meta->meta_key = 'product_shipping_option';
        $meta->meta_value = serialize($shipping);
        $meta->save();
        
    }
    else{
        if ($request->shipping_option) {
            $meta = new ProductMeta;
            $meta->product_id = $product->id;
            $meta->meta_key = 'product_shipping_option';
            $meta->meta_value = serialize($request->shipping_option);
            $meta->save();
        }
    }
        if ($request->miscellaneous_information) {
            $meta = new ProductMeta;
            $meta->product_id = $product->id;
            $meta->meta_key = 'product_miscellaneous_information';
            $meta->meta_value = serialize($request->miscellaneous_information);
            $meta->save();
        }

        // shipping option end
        if ($request->option) {

            $option = array_values($request->option);
            for ($x = 0; $x < count($option); $x++) {
                $option[$x]['value'] = array_values($option[$x]['value']);
            }
            $meta = new ProductMeta;
            $meta->product_id = $product->id;
            $meta->meta_key = 'custom_options';
            $meta->meta_value = serialize($request->option);
            $meta->save();
        }





        // Tab Option
        if ($request->tab_option) {


            $tab_option = array_values($request->tab_option);
            for ($x = 0; $x < count($tab_option); $x++) {
                $tab_option[$x] = array_values($tab_option[$x]);
            }

            $meta = new ProductMeta;
            $meta->product_id = $product->id;
            $meta->meta_key = 'tab_option';
            $meta->meta_value = serialize($request->tab_option);
            $meta->save();
        }




        if ($request->product_type == 'digital') {

            if ($request->product_sale_option == 'downloadable') {

                if ($request->downloadable_file_url) {
                    $meta = new ProductMeta;
                    $meta->product_id = $product->id;
                    $meta->meta_key = 'product_downloadable_file_url';
                    $meta->meta_value = $request->downloadable_file_url;
                    $meta->save();
                }


                if ($request->hasFile('downloadable_file')) {
                    $file = $request->file('downloadable_file');
                    // $fileName = round(microtime(true)) . '.' . $file->getClientOriginalExtension();
                    // $location = public_path('products/downloadable/' . $fileName);
                    if ($file) {
                        $fileName = uniqid() . '.' . $request->downloadable_file->getClientOriginalExtension();
                        //$request->downloadable_file->move(public_path('products/downloadable/'), $fileName);
                        move_uploaded_file($_FILES['downloadable_file']['tmp_name'], storage_path().'/downloadable'.'/'.  $fileName);
                        $meta = new ProductMeta;
                        $meta->product_id = $product->id;
                        $meta->meta_key = 'product_downloadable_file';
                        $meta->meta_value = $fileName;
                        $meta->save();
                    }
                }
            }
        }


        //Localization Data
        $lanData = [];
        foreach ($request->all() as $key => $val) {
            if (strpos($key, "__") > 0) {
                $paramKey = explode('__', $key)[0];
                $lang = explode('__', $key)[1];
                $lanData[$lang][$paramKey] = $val;
                $lanData[$lang]['lang_code'] = $lang;
            }
        }

        foreach ($lanData as $data) {
            $data['product_id'] = $product->id;
            DB::table('products_translation')->insert([$data]);
        }

        return redirect()->route('admin.product')->with('success', 'Product successfully created!');
    }



    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('product.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if ($this->user->getRoleNames() == '["seller"]') {
            if ($this->user->shopinfo->status == 0) {
                return redirect()->route('admin.index')->with('failed', 'You cann\'t update product because your shop has been disabled!');
            }
        }

        if ($request->product_type == 'simple') {
            $request->validate([
                'title' => 'required|max:255',
                'weight' => 'required|numeric',
                'weight_unit' => 'required',
                'short_description' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric',
                'sku' => 'required'
            ]);
        } elseif ($request->product_type == 'variable') {
            $request->validate([
                'title' => 'required|max:255',
                'weight' => 'required|numeric',
                'weight_unit' => 'required',
                'short_description' => 'required',
                'description' => 'nullable',
            ]);
        } elseif ($request->product_type == 'digital') {
            $request->validate([
                'title' => 'required|max:255',
                'short_description' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric',
                'in_stock' => 'required',
                'sku' => 'required'
            ]);

            if ($request->product_sale_option == 'downloadable') {
                if ($request->downloadable_file_url) {
                    ProductMeta::updateOrCreate(
                        ['product_id' =>  $id, 'meta_key' =>  'product_downloadable_file_url'],
                        [
                            'meta_key' =>  'product_downloadable_file_url',
                            'meta_value' =>  $request->downloadable_file_url
                        ]
                    );
                }
                if($request->hasFile('downloadable_file')) {
                    $fileName = uniqid() . '.' . $request->downloadable_file->getClientOriginalExtension();
                   // $request->downloadable_file->move(public_path('products/downloadable/'), $fileName);
                    move_uploaded_file($_FILES['downloadable_file']['tmp_name'], storage_path().'/downloadable'.'/'.  $fileName);
                    ProductMeta::updateOrCreate(
                        ['product_id' =>  $id, 'meta_key' =>  'product_downloadable_file'],
                        [
                            'meta_key' =>  'product_downloadable_file',
                            'meta_value' =>  $fileName
                        ]
                    );
                }
            }


        } elseif ($request->product_type == 'service') {
            $request->validate([
                'title' => 'required|max:255',
                'short_description' => 'required',
                'description' => 'nullable',
                'price' => 'required | numeric|min:1',
                'sku' => 'required'
            ]);
        } else {
            return back()->with('failed', 'Product type is invalid!');
        }

        $product = Product::find($id);
        $product->title = $request->title;

        if($request->delivery_location){
            $product->delivery_location = implode(',', $request->delivery_location);
        }
        else{
            $product->delivery_location = 0;
        }

        $product->weight = $request->weight;
        $product->weight_unit = $request->weight_unit;
        $product->is_approximate = $request->is_approximate ? 1 : 0;
        $product->is_grocery = $request->is_grocery ? 'grocery' : 'other';
        $product->brand_id = $request->brand_id;


        $brand = Brand::where('id', $request->brand_id)->first();
        $product->brand_title = $brand->title ?? '';


        $product->product_type = $request->product_type;
        $product->attribute_set_id = ($request->attribute_set_id == -1) ? null : $request->attribute_set_id;
        if($request->category_id){
            $product->category_id = implode(',', $request->category_id);
        }

        $category = Category::where('id', $request->category_id)->first();
        $product->category_title = $category->title ?? '';

        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->price = $request->price ?? 0;



        if (Auth::user()->getRoleNames() == '["seller"]'){
            $product->product_cost = 0;
            $product->packaging_cost = 0;
            $product->security_charge = 0;
            $product->loyalty_point = 0;
            $product->vat = 0;
            $product->seller_id = Auth::user()->id;
            $product->shuffle_number = Product::orderBy('shuffle_number', 'desc')->first()->shuffle_number ?? 1 + 1;
            $product->allow_review =  1;
            $product->allow_refund =  1;
        }
        else{
            $product->product_cost = $request->product_cost ?? 0;
            $product->packaging_cost = $request->packaging_cost ?? 0;
            $product->security_charge = $request->security_charge ?? 0;
            $product->loyalty_point = $request->loyalty_point ?? 0;
            $product->vat = $request->vat ?? 0;
            $product->seller_id = $request->seller_id;
            $product->shuffle_number = $request->shuffle_number ?? 1;
            $product->allow_review = $request->allow_review ? 1 : 0;
            $product->allow_refund = $request->allow_refund ? 1 : 0;
            
        }

        $product->tag = $request->tag;

        $product->barcode = $request->barcode;
        $product->list_price = $request->list_price ?? 0;
        $product->tour_price = $request->tour_price ?? 0;
        $product->special_price = $request->special_price;
        $product->special_price_type = $request->special_price_type;
        $product->special_price_start = $request->special_price_start;
        $product->special_price_end = $request->special_price_end;
        $product->sku = $request->sku;
        $product->manage_stock = $request->manage_stock ? 1 : 0;
        $product->qty = $request->qty ?? 0;
        $product->max_cart_qty = $request->max_cart_qty ?? 5;
        $product->minimum_cart_value = $request->minimum_cart_value ?? 0;
        $product->in_stock = $request->in_stock ?? 0;
        $product->viewed = 1;
        $product->is_active = $request->is_active ? 1 : 0;
        $product->is_promotion = $request->is_promotion ? 1 : 0;
        $product->video_link = $request->video_link;

        $product->aff_commission_amount = $request->aff_commission_amount;
        $product->aff_commission_type = $request->aff_commission_type;
        $product->aff_commission_from = $request->aff_commission_from;
        $product->aff_commission_to = $request->aff_commission_to;


        $related_products = '';
        if ($request->related_products) {
            foreach ($request->related_products as $key => $val) {
                $related_products .= $val . ',';
            }
        }
        $product->related_products = $related_products;


        if ($request->default_image) $product->default_image = $request->default_image;
        if ($request->gallery_images) $product->gallery_images = implode(',', $request->gallery_images);

        $product->require_moderation = $request->require_moderation ? 1 : 0;
        $product->product_qc = $request->product_qc ? 1 : 0;

        $product->save();

        //SEO SECTION STARTS
        if ($request->meta_title) {
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id, 'meta_key' =>  'meta_title'],
                [
                    'meta_key' =>  'meta_title',
                    'meta_value' =>  $request->meta_title
                ]
            );
        }

        if ($request->meta_keyword) {
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id, 'meta_key' =>  'meta_keyword'],
                [
                    'meta_key' =>  'meta_keyword',
                    'meta_value' => $request->meta_keyword
                ]
            );
        }

        if ($request->meta_description) {
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id, 'meta_key' =>  'meta_description'],
                [
                    'meta_key' =>  'meta_description',
                    'meta_value' => $request->meta_description
                ]
            );
        }


        if ($request->product_sale_option) {
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id, 'meta_key' =>  'product_sale_option'],
                [
                    'meta_key' =>  'product_sale_option',
                    'meta_value' => $request->product_sale_option
                ]
            );
        }


        //SEO SECTION ENDS


        if ($specification = $request->specification) {
            ProductSpecification::where('product_id', $id)->delete();
            foreach ($specification as $key => $val) {
                ProductSpecification::updateOrCreate(
                    ['product_id' =>  $id, 'meta_key' =>  $key],
                    [
                        'meta_key' =>  $key,
                        'meta_value' => $val
                    ]
                );
            }
        }


        $shippingHelper = Helper::shipping_cost($request->is_grocery ? 'grocery' : 'other', $request->weight, $request->weight_unit);
        $shipping = array (
            'inside_origin' => 
            array (
              'inside_standard_shipping' => $shippingHelper['inside_origin_standard'],
              'inside_express_shipping' => $shippingHelper['inside_origin_express'],
            ),
            'outside_origin' => 
            array (
              'outside_standard_shipping' => $shippingHelper['outside_origin_standard'],
              'outside_express_shipping' => $shippingHelper['outside_origin_express'],
            ),
        );

        if(Auth::user()->getRoleNames() == '["seller"]'){
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id, 'meta_key' =>  'product_shipping_option'],
                [
                    'meta_key' =>  'product_shipping_option',
                    'meta_value' => serialize($shipping)
                ]
            );  
        }
        else{
            if ($request->shipping_option) {
                ProductMeta::updateOrCreate(
                    ['product_id' =>  $id, 'meta_key' =>  'product_shipping_option'],
                    [
                        'meta_key' =>  'product_shipping_option',
                        'meta_value' => serialize($request->shipping_option)
                    ]
                );
            }
            }
        
        


        if ($request->miscellaneous_information) {
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id, 'meta_key' =>  'product_miscellaneous_information'],
                [
                    'meta_key' =>  'product_miscellaneous_information',
                    'meta_value' => serialize($request->miscellaneous_information)
                ]
            );
        }



        if ($request->option) {
            $option = array_values($request->option);
            for ($x = 0; $x < count($option); $x++) {
                $option[$x]['value'] = array_values($option[$x]['value']);
            }

            ProductMeta::updateOrCreate(
                ['product_id' =>  $id, 'meta_key' =>  'custom_options'],
                [
                    'meta_key' =>  'custom_options',
                    'meta_value' => serialize($request->option)
                ]
            );
        }

        if ($request->tab_option) {
            ProductMeta::updateOrCreate(
                ['product_id' =>  $id, 'meta_key' =>  'tab_option'],
                [
                    'meta_key' =>  'tab_option',
                    'meta_value' => serialize($request->tab_option)
                ]
            );
        }




        //Localization Data
        $lanData = [];
        foreach ($request->all() as $key => $val) {
            if (strpos($key, "__") > 0) {
                $paramKey = explode('__', $key)[0];
                $lang = explode('__', $key)[1];
                $lanData[$lang][$paramKey] = $val;
                $lanData[$lang]['lang_code'] = $lang;
            }
        }

        foreach ($lanData as $data) {

            $data['product_id'] = $product->id;

            $product_localization = ProductLocalization::where('product_id', $product->id)->where('lang_code', $data['lang_code'])->first();

            if ($product_localization !== null) {
                $product_localization->title = $data['title'];
                $product_localization->short_description = $data['short_description'];
                $product_localization->description = $data['description'];
                $product_localization->save();
            } else {
                $product_localization = new ProductLocalization();
                $product_localization->product_id = $product->id;
                $product_localization->title = $data['title'];
                $product_localization->short_description = $data['short_description'];
                $product_localization->lang_code = $data['lang_code'];
                $product_localization->description = $data['description'];
                $product_localization->save();
            }
        }


        return redirect()->route('admin.product.edit', $product->id)->with('success', 'Product successfully updated!');
    }


    public function delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('product.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $product = Product::find($id);
        $product->is_deleted = 1;

        //Insert Trash Data
        $type = 'product';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $product;
        Helper::setTrashInfo($type, $type_id, $reason, $data);

        $product->save();

        return redirect()->route('admin.product')->with('success', 'Product successfully deleted!');
    }

    public function hard_delete(Request $request, $id)
    {
        $product = Product::find($id);
        $productMeta = ProductMeta::where('product_id', $id);
        $productImage = ProductImage::where('product_id', $id);
        $product->delete();
        $productMeta->delete();
        $productImage->delete();
        return redirect()->route('admin.product')->with('success', 'Product successfully deleted!');
    }

    public function image_delete(Request $request, $id)
    {
        $productImage = ProductImage::find($id);
        $productImage->delete();
        session()->flash('success', 'Product Image successfully deleted!');
        return back();
    }

    public function generateSlug(Request $request)
    {
        return \Helper::generateUniqueSlug($request->modelName, $request->inputName, $request->inputValue);
    }

    public function action(Request $request)
    {

        if ($request->action ==  "active") {
            if (empty($request->select_all)) {
                session()->flash('success', 'You have to select products!');
                return back();
            }
            foreach ($request->select_all as $id) {
                Product::where('id', $id)->update(['is_active' => 1]);
            }
            session()->flash('success', 'Product successfully activated !');
            return back();
        }
        if ($request->action ==  "inactive") {
            if (empty($request->select_all)) {
                session()->flash('success', 'You have to select products!');
                return back();
            }
            foreach ($request->select_all as $id) {
                Product::where('id', $id)->update(['is_active' => 0]);
            }
            session()->flash('success', 'Product successfully inctivated !');
            return back();
        }
        //promotional
        if ($request->action ==  "promotional") {
            if (empty($request->select_all)) {
                session()->flash('success', 'You have to select products!');
                return back();
            }
            foreach ($request->select_all as $id) {
                Product::where('id', $id)->update(['is_promotion' => 1]);
            }
            session()->flash('success', 'Promotion successfully added!');
            return back();
        }
        //regilar
        if ($request->action ==  "regular") {
            if (empty($request->select_all)) {
                session()->flash('success', 'You have to select products!');
                return back();
            }
            foreach ($request->select_all as $id) {
                Product::where('id', $id)->update(['is_promotion' => 0]);
            }
            session()->flash('success', 'Product promotion status changed!');
            return back();
        }
        if ($request->action ==  "delete") {
            if (empty($request->select_all)) {
                session()->flash('success', 'You have to select products!');
                return back();
            }
            foreach ($request->select_all as $id) {
                Product::where('id', $id)->update(['is_deleted' => 1]);
            }
            session()->flash('success', 'Product successfully deleted !');
            return back();
        }

        if ($request->action ==  "change_special_price") {
            if (empty($request->select_all)) {
                session()->flash('success', 'You have to select products!');
                return back();
            }
            foreach ($request->select_all as $id) {
                $data = Product::find($id);
                $data->special_price = $request->special_price;
                $data->special_price_type = 2;
                $data->special_price_start = $request->special_price_start;
                $data->special_price_end = $request->special_price_end;
                $data->save();
            }
            session()->flash('success', 'Product successfully deleted !');
            return back();
        }

        if ($request->action ==  "syncShippingCost") {
            if (empty($request->select_all)) {
                session()->flash('success', 'You have to select products!');
                return back();
            }

            $csv = public_path() . '/files/sample-csv/shipping_cost.csv';
            $shipping_cost = Helper::csv_to_array($csv);

            foreach ($request->select_all as $id) {
                $data = Product::find($id);

                $inside_origin_standard = 0;
                $outside_origin_standard = 0;
                $inside_origin_express = 0;
                $outside_origin_express = 0;

                $prime_unit = '';
                $prime_weight = 0;

                if ($data->weight_unit == 'gram') {
                    $prime_unit = 'kg';
                    $prime_weight = ceil(($data->weight / 1000));
                } elseif ($data->weight_unit == 'ml') {
                    $prime_unit = 'l';
                    $prime_weight = ceil(($data->weight / 1000));
                } else {
                    $prime_unit = $data->weight_unit;
                    $prime_weight = $data->weight;
                }

                foreach ($shipping_cost as $key => $value) {
                    if ($data->is_grocery == $value['product_type'] && $prime_weight == $value['weight'] && $prime_unit == $value['weight_unit']) {
                        $inside_origin_standard = ($value['inside_origin_standard'] != '') ? $value['inside_origin_standard'] : 0;
                        $outside_origin_standard = ($value['outside_origin_standard'] != '') ? $value['outside_origin_standard'] : 0;
                        $inside_origin_express = ($value['inside_origin_express'] != '') ? $value['inside_origin_express'] : 0;
                        $outside_origin_express = ($value['outside_origin_express'] != '') ? $value['outside_origin_express'] : 0;
                    }
                }

                $shipping_option['inside_origin']['inside_standard_shipping'] = $inside_origin_standard;
                $shipping_option['inside_origin']['inside_express_shipping'] = $inside_origin_express;

                $shipping_option['outside_origin']['outside_standard_shipping'] = $outside_origin_standard;
                $shipping_option['outside_origin']['outside_express_shipping'] = $outside_origin_express;

                ProductMeta::updateOrCreate(
                    ['product_id' =>  $data->id, 'meta_key' =>  'product_shipping_option'],
                    [
                        'meta_key' =>  'product_shipping_option',
                        'meta_value' => serialize($shipping_option)
                    ]
                );
            }
            session()->flash('success', 'Product shipping cost successfully synced !');
            return back();
        }

        if ($request->action ==  "exportItems") {
            if (empty($request->select_all)) {
                session()->flash('success', 'You have to select products!');
                return back();
            }
            $products = \DB::table('products')->whereIn('id', $request->select_all)->get();
            foreach ($products as $item) {
                $item->meta_key =  \Helper::get_meta_by_id($item->id)->meta_key ?? null;
                $item->meta_value =  \Helper::get_meta_by_id($item->id)->meta_value ?? null;
            }
            $headers = array(
                'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Content-Disposition' => 'attachment; filename=download.csv',
                'Expires' => '0',
                'Pragma' => 'public',
            );
            $filename =  public_path("files/download.csv");
            $handle = fopen($filename, 'w');
            fputcsv($handle, [
                "id",
                "brand_id",
                "category_id",
                "product_type",
                "attribute_set_id",
                "title",
                "default_image",
                "gallery_images",
                "short_description",
                "description",
                "price",
                "special_price",
                "special_price_type",
                "special_price_start",
                "special_price_end",
                "slug",
                "sku",
                "manage_stock",
                "qty",
                "weight",
                "weight_unit",
                "in_stock",
                "viewed",
                "is_approximate",
                "is_active",
                "is_deleted",
                "seller_id",
                "created_at",
                "updated_at",
                "meta_key",
                "meta_value",
            ]);
            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->id,
                    $product->brand_id,
                    $product->category_id,
                    $product->product_type,
                    $product->attribute_set_id,
                    $product->title,
                    $product->default_image,
                    $product->gallery_images,
                    $product->short_description,
                    $product->description,
                    $product->price,
                    $product->special_price,
                    $product->special_price_type,
                    $product->special_price_start,
                    $product->special_price_end,
                    $product->slug,
                    $product->sku,
                    $product->manage_stock,
                    $product->qty,
                    $product->weight,
                    $product->weight_unit,
                    $product->in_stock,
                    $product->viewed,
                    $product->is_approximate,
                    $product->is_active,
                    $product->qc_approved,
                    $product->is_deleted,
                    $product->seller_id,
                    $product->created_at,
                    $product->updated_at,
                    $product->meta_key,
                    $product->meta_value,
                ]);
            }
            fclose($handle);
            return Response::download($filename, "download.csv", $headers);
        }
        if ($request->action ==  "exportAll") {

            ProductFeedGenerate::dispatch()->delay(now()->addMinutes(1));
            session()->flash('success', 'Product Feed is generating! Once product feed is ready for download you will be notified.');

            return back();
        }

        if (isset($request->search) && !empty($request->search)) {
            $search = $request->search;

            $query = DB::table('products')->where('is_deleted', 0);

            if ($search == 'simple') {
                $query->where('product_type', 'simple');
            } elseif ($search == 'variable') {
                $query->where('product_type', 'variable');
            } elseif ($search == 'digital') {
                $query->where('product_type', 'digital');
            } else {
                $query->where('title', 'LIKE', '%' . $search . '%');
            }
            $product = $query->paginate(10);

            return view('backend.pages.product.parts.include.product-table')->with('products', $product);
        }
    }

    public function getProductList()
    {

        if (Auth::user()->getRoleNames() == '["seller"]') {
            $data = Product::where('products.seller_id', Auth::user()->id)->where('products.is_deleted', 0)
                ->leftjoin("categories", \DB::raw("FIND_IN_SET(categories.id,products.category_id)"), ">", \DB::raw("'0'"))
                ->leftjoin('shop_info', 'shop_info.seller_id', '=', 'products.seller_id')
                ->leftjoin('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.*', 'shop_info.name as shop_name', 'brands.title as b_title', 'categories.title as cat_title')
                ->groupBy("products.id");
        } else {
            $data = Product::where('products.is_deleted', 0)
                ->leftjoin("categories", \DB::raw("FIND_IN_SET(categories.id,products.category_id)"), ">", \DB::raw("'0'"))
                ->leftjoin('shop_info', 'shop_info.seller_id', '=', 'products.seller_id')
                ->leftjoin('brands', 'brands.id', '=', 'products.brand_id')
                ->select('products.*', 'shop_info.name as shop_name', 'categories.title as cat_title', 'brands.title as b_title')
                ->groupBy("products.id");
        }

        return Datatables::of($data)

            ->addIndexColumn()

            ->addColumn('checkbox', function ($row) {
                return '<div class="form-check form-check-flat">
                            <label class="form-check-label">
                                <input name="select_all[]" type="checkbox"  class="form-check-input checkbox_single_select" value="' . $row->id . '"><i class="input-helper"></i>
                            </label>
                        </div>';
            })


            ->editColumn('created_at', function ($row) {
                return date('Y-m-d', strtotime($row->created_at));
            })
            ->editColumn('delivery_location', function ($row) {
                $location = [];
                $catHtml = '';
                    if ($row->delivery_location) {
                        $delivery_locations = explode(',', $row->delivery_location);
                            foreach ($delivery_locations  as $delivery) {
                                if($delivery == 0){
                                    $location[] = 'All Bangladesh'; 
                                }else{
                                    $upazilas = Upazila::findOrFail($delivery);
                                    if($upazilas){
                                        $location[] = $upazilas->title;

                                    }
                                }     
                        }
                    }
                    if ($location) {
                        foreach ($location as $cat) {
                            $catHtml .= '<span class="badge badge-primary mb-1">' . $cat . '</span>';
                        }
                    }
                    return ($catHtml) ? $catHtml : 'N/A';

    
            })

            ->editColumn('seller_id', function ($row) {
                $name = ($row->shop_name) ? $row->shop_name : '-';
                return  '<a target="_blank" href="' . '/admin/seller/edit/' . $row->seller_id . '" class="seller_name text-decoration-none">' . $name . '</a>';
            })


            ->editColumn('title', function ($row) {

                if ($row->default_image) {
                    $image = '<img class="list_img mr-3" src="' . '/' . $row->default_image . '" alt="">';
                } else {
                    $image = '<img class="list_img mr-3" src="/no_image.png" alt="">';
                }

                return  '<div class="media">
                            ' . $image . '
                            <div class="media-body">
                                <p class="product_title"><a target="_blank" href="' . env("APP_FRONTEND") . '/product/' . $row->slug . '" class="text-dark text-decoration-none">' . $row->title . '</a></p>
                            </div>
                        </div>';
            })

            ->editColumn('sku', function ($row) {
                if ($row->product_type == 'variable') {
                    return ($row->sku) ? $row->sku : 'N/A';
                } else {
                    return ($row->sku) ? $row->sku : 'N/A';
                }
            })

            ->editColumn('category_id', function ($row) {

                $categories = [];
                $catHtml = '';
                $badge = ['info', 'primary', 'dark', 'secondary', 'danger'];
                $category_id = $row->category_id;
                if ($category_id) {
                    $category_id = explode(',', $category_id);
                    if ($category_id) {
                        foreach ($category_id  as $cat_id) {
                            $category = DB::table('categories')->select('title')->where('id', $cat_id)->first();
                            if ($category) {
                                $categories[] = $category->title;
                            }
                        }
                    }
                }

                if ($categories) {
                    foreach ($categories as $cat) {
                        $catHtml .= '<span class="badge badge-' . $badge[rand(0, 4)] . ' mb-1">' . $cat . '</span>';
                    }
                }
                return ($catHtml) ? $catHtml : 'N/A';
            })

            ->editColumn('brand_title', function ($row) {
                return isset($row->brand) ? $row->brand->title : 'N/A';
            })

            ->editColumn('product_type', function ($row) {
                return '<span class="badge_' . $row->product_type . ' plb">' . $row->product_type . '</span>';
            })

            ->editColumn('price', function ($row) {
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->price;
            })

            ->editColumn('weight', function ($row) {
                return ($row->weight) ? $row->weight . $row->weight_unit : 'N/A';
            })

            ->editColumn('qty', function ($row) {
                return ($row->qty != 0) ? $row->qty : '-';
            })

            ->addColumn('status', function ($row) {
                return  '<span class="badge badge_' . strtolower(Helper::getStatusName('default', $row->is_active)) . '">
                           ' . Helper::getStatusName('default', $row->is_active) . '
                           </span>';
            })

            ->addColumn('qc_approved', function ($row) {
                return  '<span class="badge badge_' . strtolower(Helper::getStatusName('default', $row->product_qc)) . '">
                           ' . Helper::getStatusName('default', $row->product_qc) . '
                           </span>';
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('product.view')) {
                    $btn = '<a title="Preview Product" class="icon_btn text-success text-decoration-none productViewBtn" id="' . $row->id . '"  href="#"><i class="mdi mdi-eye"></i></a>';
                }

                if (Auth::user()->can('product.edit')) {
                    $btn = $btn . '<a title="Edit Product" class="icon_btn text-success text-decoration-none" href="' . route('admin.product.edit', $row->id) . '"><i class="mdi mdi-pencil-box-outline"></i></a>';
                }

                if (Auth::user()->can('product.delete')) {
                    $btn = $btn . '<a title="Delete Product" class="icon_btn text-danger delete_btn text-decoration-none" data-url="' . route('admin.product.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['checkbox','delivery_location', 'title', 'sku', 'category_id', 'seller_id', 'product_type', 'price', 'weight', 'status','qc_approved', 'action'])->make(true);
    }

    function fetch_data(Request $request)
    {
        if ($request->ajax()) {

            $search = $request->get('query');
            // var_dump($search);
            $query = DB::table('products')->where('is_deleted', 0);

            if ($search == 'simple') {
                $query->where('product_type', 'simple');
            } elseif ($search == 'variable') {
                $query->where('product_type', 'variable');
            } elseif ($search == 'digital') {
                $query->where('product_type', 'digital');
            } else {
                $query->where('title', 'LIKE', '%' . $search . '%');
            }
            $product = $query->paginate(10);

            return view('backend.pages.product.parts.include.product-table')->with('products', $product);
        }
    }

    public function changePaymentStatus(Request $request)
    {
        $transaction_id = $request->transaction_id;
        $status_id = $request->status_id;
        $reason = $request->reason;


        if ($transaction_id &&  $status_id) {
            $order = DB::table('orders')->where('payment_id', $transaction_id)->first();

            if ($order) {

                $oldStatus = Helper::get_status_by_status_id($order->status)->title;
                $newStatus = Helper::get_status_by_status_id($status_id)->title;


                //Pending Maturation State for seller Balance
                if ($request->status_id == 2 || $request->status_id == 6) {
                    DB::table('seller_account_history')->where('order_id', $order->id)->where('status', 0)->update([
                        'status' => 1
                    ]);
                } else {
                    DB::table('seller_account_history')->where('order_id', $order->id)->where('status', 1)->update([
                        'status' => 0
                    ]);
                }

                if ($order->payment_method == 'cash_on_delivery' && $request->status_id == 6) {
                    Order::where('payment_id', $transaction_id)->update([
                        'status' => $status_id,
                        'paid_amount' => $order->total_amount
                    ]);
                } else {
                    Order::where('payment_id', $transaction_id)->update(['status' => $status_id]);
                }


                //Insert Order Log
                $generated_text = 'Order payment status has been changed from ' . $oldStatus . ' to ' . $newStatus . ' by ' . Auth::user()->name;
                Helper::setOrderLog($order->id, null, $generated_text, Auth::id(), $reason);

                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }



    public function changeNotificationStatus(Request $request)
    {
        $status = $request->status;
        $status_id = $request->status_id;
        if ($status &&  $status_id) {
            $notifications = DB::table('notifications')->where('id', $status_id)->first();
            if ($notifications) {
                DB::table('notifications')->where('id', $status_id)->update(['status' => $status]);

                $now_status = DB::table('notifications')->where('id', $status_id)->first();
                $data['nowStatus'] = 'nowStatus' . $now_status->status;

                if (Auth::user()->hasRole('seller')) {
                    $data['total'] = \DB::table('notifications')
                        ->where('user_type', 2)
                        ->where('user_id', Auth::id())
                        ->where('status', 1)
                        ->orderBy('id', 'desc')
                        ->count();
                } else {
                    $data['total']  = \DB::table('notifications')
                        ->where('user_type', '!=', 1)
                        ->where('status', 1)
                        ->orderBy('id', 'desc')
                        ->count();
                }

                return $data;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }


    public function getLiveNotification(Request $request)
    {

        $currentNotifications = $request->currentNotifications;

        if (Auth::user()->hasRole('seller')) {
            $totalNotification = \DB::table('notifications')
                ->where('user_type', 2)
                ->where('user_id', Auth::id())
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->count();
        } else {
            $totalNotification  = \DB::table('notifications')
                ->where('user_type', '!=', 1)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->count();
        }

        if ($currentNotifications < $totalNotification) {
            $diff = $totalNotification - $currentNotifications;


            if (Auth::user()->hasRole('seller')) {
                $notifications = \DB::table('notifications')
                    ->where('user_type', 2)
                    ->where('user_id', Auth::id())
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->limit($diff)
                    ->get();
            } else {
                $notifications  = \DB::table('notifications')
                    ->where('user_type', '!=', 1)
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->limit($diff)
                    ->get();
            }

            $html = '';
            foreach ($notifications as $n) {
                $description = json_decode($n->description);
                $message = '';
                if ($description) {
                    $message = $description->message;
                }

                $html .= '<div class="toast_notification">
                    <span class="n_close"><i class="mdi mdi-delete"></i></span>
                    <p class="n_title">' . $n->title . '</p>
                    <div class="n_description">' . $message . '</div>
                </div>';
            }

            $data['status'] = 1;
            $data['data'] =  $html;
            $data['count_number'] =  $totalNotification;
            return response()->json($data, 200);
        } else {
            $data['status'] = 0;
            $data['message'] = '';
            return response()->json($data, 200);
        }
    }




    public function view($id)
    {
        if (is_null($this->user) || !$this->user->can('product.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $product = Product::find($id);
        $attributeSet = AttributeSets::orderBy('id', 'desc')->get();

        $product_shipping_option_html1 = '';
        $product_shipping_option_html2 = '';
        $product_shipping_option_html3 = '';
        $product_shipping_option = ProductMeta::where('product_id', $product->id)->where('meta_key', 'product_shipping_option')->first()->meta_value ??  '';

        $product_miscellaneous_information = ProductMeta::where('product_id', $product->id)->where('meta_key', 'product_miscellaneous_information')->first()->meta_value ?? '';
        $product_miscellaneous_information = unserialize($product_miscellaneous_information);

        if ($product_shipping_option) {
            $product_shipping_option = unserialize($product_shipping_option);
            foreach ($product_shipping_option as $pso) {
                foreach ($pso as $key => $value) {
                    if ($key == 'inside_allow_free_shipping') {
                        $product_shipping_option_html1 .= '<div class="form-group col-md-4"><label>Allow Free Shipping : ' . ($value == "on" ? 'YES' : 'NO') . '</label></div>';
                    }
                    if ($key == 'inside_standard_shipping') {
                        $product_shipping_option_html1 .= '<div class="form-group col-md-4"><label>Standard Shipping Cost : BDT ' . $value . ' </label></div>';
                    }
                    if ($key == 'inside_express_shipping') {
                        $product_shipping_option_html1 .= '<div class="form-group col-md-4"><label>Express Shipping Cost : BDT ' . $value . '</label></div>';
                    }

                    if ($key == 'outside_allow_free_shipping') {
                        $product_shipping_option_html2 .= '<div class="form-group col-md-4"><label>Allow Free Shipping : ' . ($value == "on" ? 'YES' : 'NO') . '</label></div>';
                    }
                    if ($key == 'outside_standard_shipping') {
                        $product_shipping_option_html2 .= '<div class="form-group col-md-4"><label>Standard Shipping Cost : BDT ' . $value . '</label></div>';
                    }
                    if ($key == 'outside_express_shipping') {
                        $product_shipping_option_html2 .= '<div class="form-group col-md-4"><label>Express Shipping Cost : BDT ' . $value . '</label></div>';
                    }
                }
            }
        }

        if ($product_miscellaneous_information) {
            foreach ($product_miscellaneous_information as $key => $value) {
                if ($key == 'allow_cash_on_delivery') {
                    $product_shipping_option_html3 .= '<div class="form-group col-md-4"><label>Allow Cash on Delivery : ' . ($value == "on" ? 'YES' : 'NO') . '</label></div>';
                }
                if ($key == 'warrenty_period') {
                    $product_shipping_option_html3 .= '<div class="form-group col-md-4"><label>Warrenty Period (Days) : ' . $value . '</label></div>';
                }
                if ($key == 'allow_change_of_mind') {
                    $product_shipping_option_html3 .= '<div class="form-group col-md-4"><label>Allow Change of Mind : ' . ($value == "on" ? 'YES' : 'NO') . '</label></div>';
                }
            }
        }
        $image_gallery_html = '';
        foreach (explode(',', $product->gallery_images) as $key => $value) {
            $image_gallery_html .= '<img class="list_img mr-3" src="/' . $value . '" alt="">';
        }

        $data = '';
        $data .= '
        <div class="row">
           <div class="col-md-6">
              <h5 class="modal-title"><b>General</b></h5>
              <hr>
              <div class="row">
                 <div class="form-group col-md-6">
                    <label>Title</label>
                    <P>' . $product->title . '</P>
                 </div>
                 <div class="form-group col-md-6">
                    <label>Weight</label>
                    <P>' . $product->weight . ' (' . $product->weight_unit . ')</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Brand</label>
                    <P>' . $product->brand_title . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Category</label>
                    <P>' . $product->category_title . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Seller</label>
                    <P>' . ($product->seller ? $product->seller->name : 'N/A') . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Status</label>
                    <P>' . (($product->is_active == 1) ? ' Active' : "Inactive") . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Defalut Product Image</label><br>
                    <img class="list_img mr-3" src="/' . $product->default_image . '" alt="">
                 </div> 
              </div>
           </div>
           <div class="col-md-6">
              <h5 class="modal-title"><b>Price</b></h5>
              <hr>
              <div class="row">
                 <div class="form-group col-md-6">
                    <label>Price</label>
                    <P>' . $product->price . '</P>
                 </div>
                 <div class="form-group col-md-6">
                    <label>Special Price</label>
                    <P>' . $product->special_price . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Special Price Type</label>
                    <P>' . (($product->special_price_type == 1) ? ' Fixed' : "Percent") . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Special Price Start</label>
                    <P>' . $product->special_price_start . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Special Price End</label>
                    <P>' . $product->special_price_end . '</P>
                 </div> 
              </div>
           </div>
           <div class="col-md-6">
              <h5 class="modal-title"><b>Inventory</b></h5>
              <hr>
              <div class="row">
                 <div class="form-group col-md-6">
                    <label>SKU</label>
                    <P>' . $product->sku . '</P>
                 </div>
                 <div class="form-group col-md-6">
                    <label>Inventory Management</label>
                    <P>' . (($product->manage_stock == 0) ? ' Dont Track Inventory' : "Track Inventory") . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Quantity</label>
                    <P>' . $product->qty . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Maximum Cart Qty</label>
                    <P>' . $product->max_cart_qty . '</P>
                 </div> 
                 <div class="form-group col-md-6">
                    <label>Stock Availability</label>
                    <P>' . (($product->in_stock == 1) ? ' In Stock' : "Out of Stock") . '</P>
                 </div> 
              </div>
           </div>
           <div class="col-md-6">
              <h5 class="modal-title"><b>SEO</b></h5>
              <hr>
              <div class="row">
                 <div class="form-group col-md-6">
                    <label>Slug</label>
                    <P>' . $product->slug . '</P>
                 </div>
                 <div class="form-group col-md-6">
                    <label>Meta Title</label>';
        if (ProductMeta::where('product_id', $product->id)->where('meta_key', 'meta_title')->first()) {

            $data .= '<P>' . ProductMeta::where('product_id', $product->id)->where('meta_key', 'meta_title')->first()->meta_value . '</P>';
        }
        $data .= '
                 </div> 
                 <div class="form-group col-md-12">
                    <label>Meta Keyword</label>';
        if (ProductMeta::where('product_id', $product->id)->where('meta_key', 'meta_keyword')->first()) {
            $data .= '<P>' . ProductMeta::where('product_id', $product->id)->where('meta_key', 'meta_keyword')->first()->meta_value . '</P>';
        }
        $data .= '
                 </div> 
                 <div class="form-group col-md-12">
                    <label>Meta Description</label>';
        if (ProductMeta::where('product_id', $product->id)->where('meta_key', 'meta_description')->first()) {
            $data .= '<P>' . ProductMeta::where('product_id', $product->id)->where('meta_key', 'meta_description')->first()->meta_value . '</P>';
        }
        $data .= '
                 </div> 
              </div>
           </div>
           <div class="col-md-12">
              <h5 class="modal-title"><b>Shipping Options</b></h5>
              <hr>
              <div class="row">
                 <div class="col-md-12 mb-2">
                    <b>Inside My District</b>
                 </div>' .
            $product_shipping_option_html1

            . '
                 <div class="col-md-12 mb-2">
                    <b>Outside My District</b>
                 </div>' .
            $product_shipping_option_html2

            . '

                 <div class="col-md-12 mb-2">
                    <b>Miscellaneous Information</b>
                 </div>
                 
                ' .
            $product_shipping_option_html3

            . '
              </div>
           </div>
           <div class="col-md-12">
              <h5 class="modal-title"><b>Specification</b></h5>
              <hr>
              <div class="row">
                 <div class="form-group col-md-3">
                    <label>Attribute Set</label>
                    <P>' . (($product->attribute_set_id) ? AttributeSets::where('id', $product->attribute_set_id)->first()->title : '') . '</P>
                 </div>
              </div>
           </div>
           <div class="col-md-12">
              <div class="form-group ">
                 <label>Product Image Gallery</label><br>
                 ' . $image_gallery_html . '
              </div> 
              <div class="form-group">
                 <label>Short Description</label>
                 <P>' . $product->short_description . '</P>
              </div> 
              <div class="form-group ">
                 <label>Description</label>
                 <P>' . $product->description . '</P>
              </div> 
           </div>
        </div>';

        return json_encode($data);
    }


    public function returnRequest()
    {
        if (is_null($this->user) || !$this->user->can('product.return.request')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.product.return-request');
    }


    public function getReturnRequestList()
    {

        if (Auth::user()->getRoleNames() == '["seller"]') {
            $data = ReturnRequest::where('seller_id', Auth::user()->id);
        } else {
            $data = ReturnRequest::all();
        }
        return Datatables::of($data)->addIndexColumn()

            ->addColumn('date', function ($row) {
                return date('d-m-Y', strtotime($row->created_at));
            })

            ->addColumn('customer', function ($row) {
                return $row->user->name ?? '-';
            })

            ->addColumn('price', function ($row) {
                return (optional($row->order_details)->price * optional($row->order_details)->product_qty);
            })

            
            ->editColumn('order_id', function ($row) {
                return 'KB'.date('y',strtotime($row->created_at)).$row->order_id;
            })

            ->addColumn('saller', function ($row) {
                return $row->seller->shopinfo->name ?? '-';
            })

            ->addColumn('product', function ($row) {
                $seller = ShopInfo::where('seller_id', $row->seller_id)->first()->name;

                return  '<div class="media">
                        <img class="list_img mr-3" src="' . '/' . $row->product->default_image . '" alt="">
                        <div class="media-body">
                            <p class="product_title"><a target="_blank" href="' . env("APP_FRONTEND") . '/product/' . $row->product->slug . '" class="text-dark text-decoration-none">' . $row->product->title . '</a></p>
                            <a target="_blank" href="' . '/admin/seller/edit/' . $row->product->seller_id . '" class="seller_name text-decoration-none">' . $seller ?? '-' . '</a>
                        </div>
                    </div>';
            })

            ->editColumn('return_type', function ($row) {
                return '<label class="badge badge-warning" >' . $row->return_type . '</label>';
            })


            ->addColumn('status', function ($row) {
                return '<label class="badge text-light" style="background-color: ' . $row->statuses->color_code . '"> ' . $row->statuses->title . ' </label>';
            })

            ->addColumn('action', function ($row) {
                $btn = '';

                $btn = '<a class="icon_btn text-success text-decoration-none detailsBtn" data-return-title="' . $row->title . '" data-return-details="' . $row->description . '" title="View Return Description" id="' . $row->id . '"  href="#"><i class="mdi mdi-eye"></i></a>';

                $btn = $btn . '<a class="icon_btn text-info" target="_blank" title="View Order Details" href="' . route('admin.order.show', $row->order_id) . '"><i class="mdi mdi-cart"></i></a>';


                if (Auth::user()->getRoleNames() == '["seller"]') {
                    if ($row->return_type == 'exchange' && $row->statuses->title != 'Completed' && $row->statuses->title != 'Returned' ) {
                        $btn = $btn . '<a class="icon_btn text-decoration-none text-danger delete_btn" title="Approval for Return" data-url="' . route('admin.product.return.request.complete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-check"></i></a>';
                    }
                } else {
                    if ($row->statuses->title != 'Completed' && $row->statuses->title != 'Returned') {
                        if ($row->return_type == 'exchange') {
                            $btn = $btn . '<a class="icon_btn text-decoration-none text-danger delete_btn" title="Approval for Return" data-url="' . route('admin.product.return.request.complete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-check"></i></a>';
                        } else {
                            $btn = $btn . '<a class="icon_btn text-decoration-none text-danger refund_btn" title="Approval for Refund" data-url="' . route('admin.product.return.request.complete', $row->id) . '" data-id="' . $row->id . '"><i class="mdi mdi-check"></i></a>';
                        }
                    } else if ($row->return_type == 'exchange' && $row->statuses->title == 'Completed') {
                        $btn = $btn . '<a class="icon_btn text-decoration-none text-danger delete_btn" title="Send Product to Customer" data-url="' . route('admin.product.return.request.return', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-replay"></i></a>';
                    }
                }

                return $btn;
            })

            ->rawColumns(['date', 'customer', 'saller', 'product', 'return_type', 'status', 'action'])->make(true);
    }

    public function returnRequestComplete(Request $request, $id)
    {
        $return = ReturnRequest::find($id);

        $return->status = 6;
        if ($return->return_type == 'refund') {
            $return->refund_amount = $request->refund_amount;
        }
        $return->save();

        $order_details = OrderDetails::find($return->order_details_id);
        if ($return->return_type == 'refund') {
            $order_details->status = 8; //Refunded
        } else {
            $order_details->status = 12; //Returned
        }
        $order_details->save();

        if ($return->return_type == 'refund') {
            return 'success';
        } else {
            session()->flash('success', 'Return request approved !');
            return back();
        }
    }

    public function returnRequestReturn($id)
    {
        $return = ReturnRequest::find($id);
        $return->status = 11;
        $return->save();

        $order = Order::find($return->order_id);
        $order_details = OrderDetails::find($return->order_details_id);

        if (($order->payment_method == 'online_payment' && Status::find($order->status)->title == 'Processing')) {

            Helper::product_qty_increment($return->product_id, $order_details->product_qty, $order_details->product_options);
        } elseif (($order->payment_method == 'cash_on_delivery' && Status::find($order->status)->title == 'Pending')) {

            Helper::product_qty_increment($return->product_id, $order_details->product_qty, $order_details->product_options);
        }

        session()->flash('success', 'Product has been return to customer!');
        return back();
    }


    public function restockRequest()
    {
        if (is_null($this->user) || !$this->user->can('product.restock.request')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.product.restock-request');
    }

    public function getRestockRequestList()
    {

        if (Auth::user()->getRoleNames() == '["seller"]') {
            $data = ProductRestockRequest::where('seller_id', Auth::user()->id)->orderBy('created_at', 'desc');
        } else {
            $data = ProductRestockRequest::all();
        }

        return Datatables::of($data)->addIndexColumn()

            ->editColumn('created_at', function ($row) {
                return date('d-m-Y', strtotime($row->created_at));
            })

            ->editColumn('user_id', function ($row) {
                return $row->user->name ?? '-';
            })

            ->editColumn('seller_id', function ($row) {
                return $row->seller->shopinfo->name ?? '-';
            })

            ->editColumn('product_id', function ($row) {
                $seller = ShopInfo::where('seller_id', $row->seller_id)->first()->name;

                return  '<div class="media">
                        <img class="list_img mr-3" src="' . '/' . $row->product->default_image . '" alt="">
                        <div class="media-body">
                            <p class="product_title"><a target="_blank" href="' . env("APP_FRONTEND") . '/product/' . $row->product->slug . '" class="text-dark text-decoration-none">' . $row->product->title . '</a></p>
                            <a href="#" class="seller_name text-decoration-none">' . $seller ?? '-' . '</a>
                        </div>
                    </div>';
            })


            ->editColumn('status', function ($row) {
                return '<label class="badge text-light" style="background-color: ' . $row->statuses->color_code . '"> ' . $row->statuses->title . ' </label>';
            })

            ->addColumn('action', function ($row) {
                $btn = '';

                if (Auth::user()->can('product.restock.request.change.status')) {
                    $btn = $btn . '<a class="text-decoration-none change_status_btn" title="Change Status Restored" href="#" id="' . $row->id . '"><i class="icon_btn text-success mdi mdi-replay"></i></a>';
                }

                if (Auth::user()->can('product.return.request.pushnotification')) {
                    $btn = $btn . '<a class="send_notification" href="" title="Send push notification to user" id="' . $row->id . '"><i class="icon_btn text-warning mdi mdi-bell-ring"></i></a>';
                }

                if (Auth::user()->can('product.return.request.sms')) {
                    $btn = $btn . '<a class="text-success send_sms" href="" title="Send SMS to user" id="' . $row->id . '"><i class="icon_btn text-default mdi mdi-message-processing"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['product_id', 'status', 'action'])->make(true);
    }

    public function restockRequestChangeStatus($id)
    {
        if (is_null($this->user) || !$this->user->can('product.restock.request.change.status')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request = ProductRestockRequest::find($id);
        $request->status = 6;
        $request->save();

        if ($request) {
            $title = $request->product->title;

            if ($request->product->default_image) {
                $image = env('APP_API_URL') . '/' . $request->product->default_image;
                $link = env('APP_FRONTEND') . '/product/' . $request->product->slug;
            } else {
                $image = null;
                $link = env('APP_FRONTEND') . '/product/' . $request->product->slug;
            }

            $message_body = ' আপনি ' . $title . ' রিস্টোরের জন্য রিকোয়েস্ট করেছিলেন। এই প্রোডাক্ট  টি এখন ষ্টোকে যোগ করা হয়েছে। আপনি এখন প্রোডাক্টটি কিনতে পারবেন। ';

            $message = [
                'type' => 'genarel',
                'message' => $message_body,
            ];


            $message = [
                'type' => 'genarel',
                'message' => $message_body,
            ];

            $sms = ' আপনি ' . $title . ' রিস্টোরের জন্য রিকোয়েস্ট করেছিলেন। এই প্রোডাক্ট  টি এখন ষ্টোকে যোগ করা হয়েছে। আপনি এখন প্রোডাক্টটি কিনতে পারবেন। ' . $link;

            // SEND Notification TO  USER 
            Helper::sendPushNotification($request->user->id, 1, $title, $message_body, json_encode($message), $image, $link);

            // SEND SMS 
            if ($request->user->phone) {
                $smsReponse = Helper::sendSms($request->user->phone, $sms);
            }

            $data = 'success';

            return response()->json($data);
        }
    }

    public function restockRequestPushnotification($id)
    {
        if (is_null($this->user) || !$this->user->can('product.return.request.pushnotification')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request = ProductRestockRequest::find($id);

        if ($request) {
            $title = $request->product->title;

            if ($request->product->default_image) {
                $image = env('APP_API_URL') . '/' . $request->product->default_image;
                $link = env('APP_FRONTEND') . '/product/' . $request->product->slug;
            } else {
                $image = null;
                $link = env('APP_FRONTEND') . '/product/' . $request->product->slug;
            }

            $message_body = ' আপনি ' . $title . ' রিস্টোরের জন্য রিকোয়েস্ট করেছিলেন। এই প্রোডাক্ট  টি এখন ষ্টোকে যোগ করা হয়েছে। আপনি এখন প্রোডাক্টটি কিনতে পারবেন। ';

            $message = [
                'type' => 'genarel',
                'message' => $message_body,
            ];


            $message = [
                'type' => 'genarel',
                'message' => $message_body,
            ];

            // SEND Notification TO  USER 
            Helper::sendPushNotification($request->user->id, 1, $title, $message_body, json_encode($message), $image, $link);

            $data = 'success';

            return response()->json($data);
        }
    }


    public function restockRequestSms($id)
    {
        if (is_null($this->user) || !$this->user->can('product.return.request.sms')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request = ProductRestockRequest::find($id);
        if ($request) {

            $title = $request->product->title;

            $link = env('APP_FRONTEND') . '/product/' . $request->product->slug;

            $sms = ' আপনি ' . $title . ' রিস্টোরের জন্য রিকোয়েস্ট করেছিলেন। এই প্রোডাক্ট  টি এখন ষ্টোকে যোগ করা হয়েছে। আপনি এখন প্রোডাক্টটি কিনতে পারবেন। ' . $link;

            // SEND SMS 
            if ($request->user->phone) {
                $smsReponse = Helper::sendSms($request->user->phone, $sms);
            }
            $data = 'success';

            return response()->json($data);
        }
    }

    public function getShippingCost()
    {
        if (is_null($this->user) || !$this->user->can('import.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if (file_exists(public_path() . '/files/sample-csv/shipping_cost.csv')) {
            $csv = public_path() . '/files/sample-csv/shipping_cost.csv';
            $shipping_cost = Helper::csv_to_array($csv);
        } else {
            $shipping_cost = [];
        }

        return view('backend.pages.product.import-shipping-cost')->with('shipping_cost', $shipping_cost);
    }


    public function shippingCostStore(Request $request)
    {
        $request->validate([
            'import_shippingcost_file' => 'required',
        ]);

        if ($request->hasFile('import_shippingcost_file')) {
            $csv = $request->file('import_shippingcost_file');
            $folder_path = 'files/sample-csv/';
            $image_new_name = 'shipping_cost' . '.' . $csv->getClientOriginalExtension();
            $csv->file    = $folder_path . $image_new_name;
            $csv->move(public_path($folder_path), $image_new_name);

            session()->flash('success', 'Shipping cost csv uploaded successfully!');
            return back();
        }
    }


    public function checkShippingCost($type, $weight, $unit)
    {

        $csv = public_path() . '/files/sample-csv/shipping_cost.csv';
        $shipping_cost = Helper::csv_to_array($csv);

        $inside_origin_standard = 0;
        $outside_origin_standard = 0;
        $inside_origin_express = 0;
        $outside_origin_express = 0;

        $prime_unit = '';
        $prime_weight = 0;

        if ($unit == 'gram') {
            $prime_unit = 'kg';
            $prime_weight = ceil(($weight / 1000));
        } elseif ($unit == 'ml') {
            $prime_unit = 'l';
            $prime_weight = ceil(($weight / 1000));
        } else {
            $prime_unit = $unit;
            $prime_weight = $weight;
        }



        foreach ($shipping_cost as $key => $value) {
            if ($type == $value['product_type'] && $prime_weight == $value['weight'] && $prime_unit == $value['weight_unit']) {
                $inside_origin_standard = ($value['inside_origin_standard'] != '') ? $value['inside_origin_standard'] : 0;
                $outside_origin_standard = ($value['outside_origin_standard'] != '') ? $value['outside_origin_standard'] : 0;
                $inside_origin_express = ($value['inside_origin_express'] != '') ? $value['inside_origin_express'] : 0;
                $outside_origin_express = ($value['outside_origin_express'] != '') ? $value['outside_origin_express'] : 0;
            }
        }

        $data['inside_origin_standard'] = $inside_origin_standard;
        $data['outside_origin_standard'] = $outside_origin_standard;
        $data['inside_origin_express'] = $inside_origin_express;
        $data['outside_origin_express'] = $outside_origin_express;

        return response()->json($data);
    }
}