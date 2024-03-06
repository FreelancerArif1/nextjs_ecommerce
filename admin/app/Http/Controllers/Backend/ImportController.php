<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductMeta;
use App\Models\Brand;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\ProductImage;
use App\Models\AttributeSets;
use App\Models\Admins;
use App\Models\ShopInfo;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\User;
use App\Models\ReturnRequest;
use App\Models\Order;
use App\Models\Status;
use App\Models\OrderDetails;
use App\Models\ProductLocalization;
use App\Models\ProductSpecification;
use Yajra\DataTables\DataTables;
use Image;
use DB;
use Auth;
use Hash;
use Response;
use File;
use Helper;
use Illuminate\Support\Str;

use Spatie\Permission\Models\Role;
class ImportController extends Controller
{

    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }


    public function productCsv(){
        if(is_null($this->user) || !$this->user->can('import.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        
        $product = Product::orderBy('id','desc')->where('is_deleted',0)->paginate(10);
        return view('backend.pages.imports.import-products')->with('products',$product);
    }


    public function productImageCsv(){
        if(is_null($this->user) || !$this->user->can('import.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $product = Product::orderBy('id','desc')->where('is_deleted',0)->paginate(10);
        return view('backend.pages.imports.import-product-image')->with('products',$product);
    }


    private function _isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
     }


    public function storeProductCsv(Request $request){
        $request->validate([
            'import_product_file' => 'required',
        ]);

        if($request->hasFile('import_product_file')){
            $csv = $request->file('import_product_file');
            $products = Helper::csv_to_array($csv);

            $notImported = [];
            $variableOptions = '';
            $i = 1;
            foreach ($products as $key => $value) {


                if($value['product_type'] == 'variable'){
                    if($value['variable_options']){
                        if(!$this->_isJson($value['variable_options'])){
                            return redirect()->back()->with('error', 'Variable option is not valid! Please make sure data is valid for import.');
                        }else{
                            $result = (array) json_decode($value['variable_options']);
                            foreach($result as $key =>$data ){
                                if($data->value){
                                    foreach($data->value as $k => $v){
                                        $data->value[$k] = (array) $v ;
                                    }
                                }
                                $result[$key] = (array) $data;
                            }
                            $variableOptions = serialize($result);
                        }
                    }
                }

               
                
                if($value['product_type'] == '' || $value['title'] == ''){
                    $notImported[] = 'Row ID- '.$i.' : Category - '.$value['title'].' could not imported.';
                    
                }else{
                        $brand = []; $category = []; $seller = [];
                        if(isset($value['brand_title'])){
                            $brand = Brand::where('title', $value['brand_title'])->first();
                        }
                        
                        if(isset($value['category_title'])){
                            $category = Category::where('title', $value['category_title'])->first();
                        }
                        
                        if(isset($value['seller_name'])){
                            $seller = ShopInfo::where('name', $value['seller_name'])->first();
                        }
                        
                        $product = new Product();
                        $product->brand_id = ($brand) ? $brand->id : 0;
                        $product->category_id = ($category) ? $category->id : 0;
                        $product->title = $value['title'];
                        $product->product_type = $value['product_type'];
                        $product->barcode = isset($value['barcode']) ? $value['barcode'] : '';
                        $product->short_description = isset($value['short_description']) ? $value['short_description'] : '';
                        $product->description = isset($value['description']) ? Helper::mysql_escape($value['description']) : '';
                        $product->slug = (isset($value['slug']) &&  $value['slug'] != '') ? $value['slug'] : Helper::generateUniqueSlug('product','title',$value['title']);
                        $product->price = isset($value['price']) ? (double) $value['price'] : 0;
                        $product->product_cost = isset($value['product_cost']) ? (double) $value['product_cost'] : 0;
                        $product->packaging_cost = isset($value['packaging_cost'] ) ? (double)  $value['packaging_cost'] : 0;
                        $product->loyalty_point = isset($value['loyalty_point']) ? (double) $value['loyalty_point'] : 0;
                        $product->list_price = isset($value['list_price']) ? (double) $value['list_price'] : 0;
                        $product->tour_price = isset($value['tour_price'] ) ? (double) $value['tour_price'] : 0;
                        $product->vat = isset($value['vat']) ? (double) $value['vat'] : 0;
                        $product->weight = isset($value['weight']) ? (int) $value['weight'] : 0;
                        $product->sku = isset($value['sku']) ? $value['sku'] : '';
                        $product->manage_stock = isset($value['manage_stock']) ? (int)  $value['manage_stock'] : 0;
                        $product->qty = isset($value['qty']) ? (int) $value['qty'] : 0;
                        $product->max_cart_qty = isset($value['max_cart_qty']) ? (int)  $value['max_cart_qty'] : 0;
                        $product->weight_unit = isset($value['weight_unit']) ? $value['weight_unit'] :'';
                        $product->seller_id = ($seller) ? $seller->seller_id : 0;
                        $product->is_active = isset($value['is_active']) ? 1 : 0;
                        $product->old_product_id = isset($value['old_product_id']) ? $value['old_product_id'] : null;
                        $product->shuffle_number = (isset($value['shuffle_number']) && $value['shuffle_number']) ? $value['shuffle_number'] : 0;

                        //new add 
                        $product->special_price = (isset($value['special_price']) && $value['special_price']) ? $value['special_price'] : 0;
                        $product->special_price_type = (isset($value['special_price_type']) && $value['special_price_type']) ? $value['special_price_type'] : '';

                        $product->special_price_start = (isset($value['special_price_start']) && $value['special_price_start'] != '') ? date('Y-m-d h:i:s', strtotime($value['special_price_start']))  : null;

                        $product->special_price_end = (isset($value['special_price_end']) && $value['special_price_end'] != '') ? date('Y-m-d h:i:s', strtotime($value['special_price_end'])) : null;
                        $product->is_grocery = (isset($value['is_grocery']) && $value['is_grocery']) ? $value['is_grocery'] : 'other';

                        $product->tag = (isset($value['tag']) && $value['tag']) ? $value['tag'] : '';
                        $product->allow_review = (isset($value['allow_review']) && $value['allow_review']) ? $value['allow_review'] : 1;
                        $product->require_moderation = (isset($value['require_moderation']) && $value['require_moderation']) ? $value['require_moderation'] : 1;
                        $product->allow_refund = (isset($value['allow_refund']) && $value['allow_refund']) ? $value['allow_refund'] : 1;
                        $product->is_promotion = (isset($value['is_promotion']) && $value['is_promotion']) ? $value['is_promotion'] : 0;
                        $product->is_active = (isset($value['is_active']) && $value['is_active']) ? $value['is_active'] : 0;
                        $product->product_qc = (isset($value['product_qc']) && $value['product_qc']) ? $value['product_qc'] : 0;



                        try{

                            $product->save();

                            if (isset($value['attribute_set']) && $value['attribute_set'] != '') {
                                $attributset = AttributeSets::where('title', $value['attribute_set'])->first();
                                if ($attributset && isset($value['attributes'])) {
                                    $product->attribute_set_id = $attributset->id;
                                    $product->save();
                                    
                                    $attribute_ids_array = explode(',',$attributset->attribute_ids);
                                    

                                    $data = [];
                                    foreach($attribute_ids_array as $attribute_id){
                                        $attribute = \DB::table('attributes')->where('id',$attribute_id)->where('is_active',1)->where('is_deleted',0)->first();
                                        if($attribute){

                                            $csv_attributes = explode(',',$value['attributes']);

                                            $meta_key = '';
                                            $meta_value = '';


                                            if ($csv_attributes) {
                                                foreach ($csv_attributes as $ak => $av) {
                                                    $exployed_attributes = explode(':',$av);
                                                    if ($attribute->title == $exployed_attributes[0]) {
                                                        $meta_key = $attribute->attribute_code;
                                                        $meta_value = $exployed_attributes[1];
                                                    }else{
                                                        $meta_key = $attribute->attribute_code;
                                                    }
                                                }
                                            }else{
                                                $meta_key = $attribute->attribute_code;
                                            }

                                            $data[] = [
                                                'product_id' => $product->id,
                                                'meta_key' => $meta_key,
                                                'meta_value' => $meta_value,
                                            ];
                                        }
                                    }

                                    if ($data) {
                                        ProductSpecification::insert($data);
                                    }
                                    
                                }
                            }


                            if($variableOptions){
                                $options[] = [
                                    'product_id' => $product->id,
                                    'meta_key' => 'custom_options',
                                    'meta_value' => $variableOptions,
                                ];
                                ProductMeta::insert($options);
                            }


                            //SEO SECTION STARTS
                            if (isset($value['meta_title']) && $value['meta_title']) {
                                ProductMeta::updateOrCreate(
                                    ['product_id' =>  $product->id, 'meta_key' =>  'meta_title'],
                                    [
                                        'meta_key' =>  'meta_title',
                                        'meta_value' =>  $value['meta_title']
                                    ]
                                );
                            }

                            if (isset($value['meta_keyword']) && $value['meta_keyword']) {
                                ProductMeta::updateOrCreate(
                                    ['product_id' =>  $product->id, 'meta_key' =>  'meta_keyword'],
                                    [
                                        'meta_key' =>  'meta_keyword',
                                        'meta_value' => $value['meta_keyword']
                                    ]
                                );
                            }

                            if (isset($value['meta_description']) && $value['meta_description']) {
                                ProductMeta::updateOrCreate(
                                    ['product_id' =>  $product->id, 'meta_key' =>  'meta_description'],
                                    [
                                        'meta_key' =>  'meta_description',
                                        'meta_value' => $value['meta_description']
                                    ]
                                );
                            }

                            $miscellaneous_information=[];

                            if (isset($value['allow_cash_on_delivery']) && $value['allow_cash_on_delivery'] != '') {
                                $miscellaneous_information['allow_cash_on_delivery'] = $value['allow_cash_on_delivery'];
                            }

                            if (isset($value['warrenty_period']) && $value['warrenty_period'] != '') {
                                $miscellaneous_information['warrenty_period'] = $value['warrenty_period'];
                            }

                            if (isset($value['allow_change_of_mind']) && $value['allow_change_of_mind'] != '') {
                                $miscellaneous_information['allow_change_of_mind'] = $value['allow_change_of_mind'];
                            }
                            
                            if ($miscellaneous_information) {
                                ProductMeta::updateOrCreate(
                                    ['product_id' =>  $product->id, 'meta_key' =>  'product_miscellaneous_information'],
                                    [
                                        'meta_key' =>  'product_miscellaneous_information',
                                        'meta_value' => serialize($miscellaneous_information)
                                    ]
                                );
                            }

                        }catch(\Exception $e){
                            $notImported[] = 'Row ID '.$i.' was not imported. Error: '.$e;
                        }
                        
                }
                $i++;
            }
            
            if($notImported){
                return redirect()->back()->with('success', implode(',', $notImported));
            }else{
                return redirect()->back()->with('success', 'All products has been imported successfully!');
            }

            // var_dump(implode(',', $notImported));
        }
    }



    public function storeProductImageCsv(Request $request){
        $request->validate([
            'import_product_file' => 'required',
        ]);

        if($request->hasFile('import_product_file')){
            $csv = $request->file('import_product_file');
            $products = Helper::csv_to_array($csv);

            $notImported = [];
            $i = 1;
            foreach ($products as $key => $value) {
                
                if($value['product_id'] == ''){
                    $notImported[] = 'Row ID- '.$i.' : Image - '.$value['product_id'].' could not imported.';
                }else{
                    $product = Product::find($value['product_id']);
                    if ($product) {
                        $product->default_image = ($value['default_image']) ? $value['default_image']: '' ;
                        $product->gallery_images = ($value['gallery_images']) ? $value['gallery_images']: '' ;

                        $data = [
                            'title' => $value['file_name'],
                            'alt_text' => $value['file_name'],
                            'description' => $value['file_name'],
                            'thumbnail_url' => 'media/thumbnail/'.$value['default_image'],
                            'file_url' => $value['default_image'],
                            'uploaded_by' => 1,
                        ];
                        try{
                            $product->save();
                            DB::table('concave_media')->insert($data);
                        }catch(\Exception $e){
                            $notImported[] = 'Row ID '.$i.' was not imported.';
                        }
                    }else{
                        $notImported[] = 'Row ID '.$i.' was not imported. Product not found!';
                    }
                           
                }
                $i++;
            }
            
            if($notImported){
                return redirect()->back()->with('success', implode(',', $notImported));
            }else{
                return redirect()->back()->with('success', 'All product image has been imported successfully!');
            }
        }
    }

    public function productUpdateCsv(){
        if(is_null($this->user) || !$this->user->can('import.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $product = Product::orderBy('id','desc')->where('is_deleted',0)->get();
        return view('backend.pages.imports.product-update')->with('products',$product);
    }


    public function productUpdateCsvDownload(Request $request){

        if($request->product_id){
            $products = \DB::table('products')->whereIn('id', $request->product_id)->get();
        }else{
            $products = Product::all();
        }
       
        
        $headers = array(
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=update_product_sample.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        );
        $filename =  public_path("files/sample-csv/update_product_sample.csv");
        $handle = fopen($filename, 'w');
        fputcsv($handle, [
            "id",
            "brand_title",
            "category_title",
            "product_type",
            "title",
            "short_description",
            "description",
            "price",
            "product_cost",
            "packaging_cost",
            "loyalty_point",
            "vat",
            "max_cart_qty",
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
            "is_active",
            "seller_name",
            "shuffle_number",
            "attribute_set",
            "attributes",
            "variable_options"
        ]);
        foreach ($products as $product) {
            $seller = ShopInfo::where('seller_id',$product->seller_id)->first();
            
            if ($product->attribute_set_id) {
                $attribute_set = AttributeSets::find($product->attribute_set_id);
            }

            $product_specifications = ProductSpecification::where('product_id', $product->id)->get();

            $product_specifications_text = '';

            if ($product_specifications) {
                foreach ($product_specifications as $specification) {
                    $product_specifications_text = $product_specifications_text.$specification->meta_key.':'.$specification->meta_value.',';
                }
            }

            $product_variable_options = '';

            if($product->product_type == 'variable'){
                $variable_options = DB::table('product_metas')->where('product_id',$product->id)->where('meta_key','custom_options')->first();
                if($variable_options){
                    if($meta_value = $variable_options->meta_value){
                        $product_variable_options = json_encode(unserialize($meta_value));
                    }
                }
            }

            fputcsv($handle, [
                $product->id,
                $product->brand_title,
                $product->category_title,
                $product->product_type,
                $product->title,
                $product->short_description,
                $product->description,
                $product->price,
                $product->product_cost,
                $product->packaging_cost,
                $product->loyalty_point,
                $product->vat,
                $product->max_cart_qty,
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
                $product->is_active,
                $seller->name ?? '',
                $product->shuffle_number,
                $attribute_set->title ?? '',
                $product_specifications_text,
                $product_variable_options
            ]);

        }
        fclose($handle);


        return Response::download($filename, "update_product_sample.csv", $headers); 
    }


    public function storeProductUpdateCsv(Request $request){
        $request->validate([
            'import_product_file' => 'required',
        ]);

        if($request->hasFile('import_product_file')){
            $csv = $request->file('import_product_file');
            $products = Helper::csv_to_array($csv);

            $notImported = [];
            $i = 1;
            foreach ($products as $key => $value) {
                $variableOptions = '';
                if($value['product_type'] == 'variable'){
                    if($value['variable_options']){
                        if(!$this->_isJson($value['variable_options'])){
                            return redirect()->back()->with('error', 'Variable option is not valid! Please make sure data is valid for import.');
                        }else{
                            $result = (array) json_decode($value['variable_options']);
                            foreach($result as $key =>$data ){
                                if($data->value){
                                    foreach($data->value as $k => $v){
                                        $data->value[$k] = (array) $v ;
                                    }
                                }
                                $result[$key] = (array) $data;
                            }
                            $variableOptions = serialize($result);
                        }
                    }
                }

                
                if($value['id'] == ''){
                    $notImported[] = 'Required data missing! Row - '.$i;
                }else{
                    $product = Product::find($value['id']);

                    $brand = []; $category = []; $seller = [];
                    if(isset($value['brand_title'])){
                        $brand = Brand::where('title', $value['brand_title'])->first();
                        if($brand){
                            $product->brand_id = $brand->id;
                            $product->brand_title = $brand->title;
                        }
                    }
                    
                    if(isset($value['category_title'])){
                        $category = Category::where('title', $value['category_title'])->first();
                        if($category){
                            $product->category_id = $category->id;
                            $product->category_title = $category->title;
                        }
                    }
                    
                    if(isset($value['seller_name'])){
                        $seller = ShopInfo::where('name', $value['seller_name'])->first();
                        if($seller){
                            $product->seller_id = $seller->seller_id;
                        }
                    }

                    $slug = Helper::generateUniqueSlug('product','slug',$value['slug']);

                    
                    $product->title = $value['title'];
                    $product->product_type = $value['product_type'];
                    $product->barcode = $value['barcode'] ?? '';
                    $product->short_description = $value['short_description'];
                    $product->description = $value['description'];
                    $product->slug = $value['slug'];
                    $product->price = $value['price'];
                    $product->product_cost = $value['product_cost'];
                    $product->packaging_cost = number_format((float)$value['packaging_cost'], 2, '.', '') ?? 0.00;
                    $product->loyalty_point = number_format((float)$value['loyalty_point'], 2, '.', '') ?? 0.00;
                    $product->vat = $value['vat'] ?? '' ;
                    $product->weight = $value['weight'];
                    $product->sku = $value['sku'];
                    $product->manage_stock = $value['manage_stock'] ?? 0;
                    $product->qty = $value['qty'] ?? 0;
                    $product->max_cart_qty = $value['max_cart_qty'] ?? 0;
                    $product->special_price = number_format((float)$value['special_price'], 2, '.', '') ?? 0.00;
                    $product->special_price_type = $value['special_price_type'] ?? '';
                    $product->special_price_start = date('Y-m-d H:i:s', strtotime($value['special_price_start'])) ?? '';
                    $product->special_price_end = date('Y-m-d H:i:s', strtotime($value['special_price_end'])) ?? '';
                    $product->weight_unit = $value['weight_unit'] ?? '';

                    $product->is_active = 1;
                    $product->shuffle_number = (int) ($value['shuffle_number'] == 0) ? Product::orderBy('shuffle_number','desc')->first()->shuffle_number + 1 : $value['shuffle_number'];
                    
                    try{
                        $product->save();

                        if (isset($value['attribute_set']) && $value['attribute_set'] != '') {
                            $attributset = AttributeSets::where('title', $value['attribute_set'])->first();
                                
                            if ($attributset) {

                                $product->attribute_set_id = $attributset->id;
                                $product->save();

                                $previous_specifications = ProductSpecification::where('product_id', $product->id)->get();

                                if ($previous_specifications) {
                                    foreach ($previous_specifications as $spc) {
                                        ProductSpecification::where('product_id', $spc->product_id)->delete();
                                    }
                                }

                                $attribute_ids_array = explode(',',$attributset->attribute_ids);
                                $data = [];
                                foreach($attribute_ids_array as $attribute_id){
                                    $attribute = \DB::table('attributes')->where('id',$attribute_id)->where('is_active',1)->where('is_deleted',0)->first();
                                    if($attribute){

                                        $csv_attributes = explode(',',$value['attributes']);

                                        $meta_key = '';
                                        $meta_value = '';


                                        if ($csv_attributes) {
                                            foreach ($csv_attributes as $ak => $av) {
                                                $exployed_attributes = explode(':',$av);
                                                if ($attribute->title == $exployed_attributes[0]) {
                                                    $meta_key = $attribute->attribute_code;
                                                    $meta_value = $exployed_attributes[1];
                                                }else{
                                                    $meta_key = $attribute->attribute_code;
                                                }
                                            }
                                        }else{
                                            $meta_key = $attribute->attribute_code;
                                        }

                                        $data[] = [
                                            'product_id' => $product->id,
                                            'meta_key' => $meta_key,
                                            'meta_value' => $meta_value,
                                        ];
                                    }
                                }

                                if ($data) {
                                    ProductSpecification::insert($data);
                                }
                            }
                        }


                        if($variableOptions){
                            ProductMeta::updateOrCreate(
                                ['product_id' =>  $product->id, 'meta_key' =>  'custom_options'],
                                [
                                    'meta_key' =>  'custom_options',
                                    'meta_value' =>   $variableOptions
                                ]
                            );
                        }


                    }catch(\Exception $e){
                        $notImported[] = 'Row ID '.$i.' was not updated. Error: '.$e;
                    } 
                }
                $i++;
            }

            if($notImported){
                return redirect()->back()->with('success', implode(',', $notImported));
            }else{
                return redirect()->back()->with('success', 'All products has been updated successfully!');
            }    
        }
    }

    // category inport function 

    public function categoryCsv(){
        if(is_null($this->user) || !$this->user->can('import.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.imports.import-category');
    }

    public function storeCategoryCsv(Request $request){
        $request->validate([
            'import_category_file' => 'required',
        ]);

        if($request->hasFile('import_category_file')){
            $csv = $request->file('import_category_file');
            $categories = Helper::csv_to_array($csv);
            $i = 1;
            $notImported = [];
            foreach ($categories as $key => $value) {
                
                if($value['title'] == ''){
                    $notImported[] = 'Required data missing! Row - '.$i;
                    // return redirect()->back()->with('failed', 'Required data missing! Row - '.$i);
                }else{
                    $parent_category = Category::where('title', trim($value['parent_category_title']))->first();
                    if (!$parent_category) {
                        $notImported[] = 'Row ID- '.$i.' : Category - '.$value['title'].' could not imported.';
                        continue;
                    }
                    $category = Category::where('title', $value['title'])->first();

                    if (!$category) {
                        $slug = Helper::generateUniqueSlug('category','title',$value['title']);

                        $category = new Category();
                        $category->parent_id = $parent_category->id ?? 0;
                        $category->title = trim($value['title']);
                        $category->slug = ($value['slug'] != '') ? trim($value['slug']) : Helper::generateUniqueSlug('category','title',$value['title']);
                        $category->description = $value['description'];
                        $category->show_child_products = $value['show_child_products'] ?? 0;
                        $category->hide_on_menu = $value['hide_on_menu'] ?? 0;
                        $category->meta_title = $value['meta_title'] ?? '';
                        $category->meta_keyword = $value['meta_keyword'] ?? '';
                        $category->meta_description = $value['meta_description'] ?? '';
                        $category->sort_order = $value['sort_order'] ?? 0;
                        $category->is_active = isset($value['is_active']) ? 1 : 0;
                        

                        try{
                            $category->save();
                        }catch(\Exception $e){
                            $notImported[] = 'Row ID '.$i.' was not updated. Error: '.$e;
                        } 
                    }else{
                        $notImported[] = 'Row ID- '.$i.' : Category - '.$value['title'].' could not imported.';
                    }
                }
                $i++;
            }

            if($notImported){
                return redirect()->back()->with('success', implode(',', $notImported));
            }else{
                return redirect()->back()->with('success', 'All category has been imported successfully!');
            }
        }
    }

    // brand inport function 

    public function brandCsv(){
        if(is_null($this->user) || !$this->user->can('import.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.imports.import-brand');
    }

    public function storeBrandCsv(Request $request){
        $request->validate([
            'import_brand_file' => 'required',
        ]);

        if($request->hasFile('import_brand_file')){
            $csv = $request->file('import_brand_file');
            $brands = Helper::csv_to_array($csv);
            $notImported = [];
            $i = 1;
            foreach ($brands as $key => $value) {
                
                if($value['title'] == ''){
                    $notImported[] = 'Required data missing! Row - '.$i;
                    // return redirect()->back()->with('failed', 'Required data missing! Row - '.$i);
                }else{
                    $brand = Brand::where('title', trim($value['title']))->first();

                    if (!$brand) {
                        $slug = Helper::generateUniqueSlug('brand','title', trim($value['title']));

                        $brand = new Brand();
                        $brand->title = trim($value['title']);
                        $brand->slug = ($value['slug'] == '') ? Helper::generateUniqueSlug('brand','title', trim($value['title'])) : $value['slug'];
                        $brand->description = $value['description'] ?? '';
                        $brand->meta_title = $value['meta_title'] ?? '';
                        $brand->meta_keyword = $value['meta_keyword'] ?? '';
                        $brand->meta_description = $value['meta_description'] ?? '';
                        $brand->is_active = $value['is_active'] ?? 0;
                        try{
                            $brand->save();
                        }catch(\Exception $e){
                            $notImported[] = 'Row ID '.$i.' was not updated. Error: '.$e;
                        } 
                    }else{
                        $notImported[] = 'Row ID- '.$i.' : Brand - '.$value['title'].' could not imported.';
                    }
                }
                $i++;
            } 

            if($notImported){
                return redirect()->back()->with('success', implode(',', $notImported));
            }else{
                return redirect()->back()->with('success', 'All brand has been imported successfully!');
            }
        }
    }

    // Seller inport function 

    public function sellerCsv(){
        if(is_null($this->user) || !$this->user->can('import.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.imports.import-seller');
    }

    public function storeSellerCsv(Request $request){
        $request->validate([
            'import_seller_file' => 'required',
        ]);

        if($request->hasFile('import_seller_file')){
            $csv = $request->file('import_seller_file');
            $sellers = Helper::csv_to_array($csv);

            $notImported = [];
            $i = 1;
            foreach ($sellers as $key => $value) {
                
                if($value['seller_name'] == '' || $value['seller_email'] == '' || $value['shop_name'] == '' || $value['shop_email'] == ''){
                    $notImported[] = 'Required data missing! Row - '.$i;
                }else{
                    if(Admins::where('email', $value['seller_email'])->exists()){
                        $notImported[] = 'Row ID- '.$i.' : Seller - '.$value['seller_name'].' could not imported.';
                    }else{

                        $slug = Helper::generateUniqueSlug('seller','title',trim($value['seller_name']));

                        // var_dump($slug);


                        $seller = new Admins();
                        $seller->name = trim($value['seller_name']);
                        $seller->email = $value['seller_email'] ?? null;
                        $seller->password = ($value['password'] == '') ? Hash::make($value['password']) : Hash::make($value['phone']);
                        $seller->phone = ($value['phone']) ? '0'.$value['phone'] : null;
                        $seller->assignRole(['seller']);
                        $seller->status = isset($value['status']) ? 1 : 0;

                        try{
                            $seller->save();

                            $division = Division::where('title', trim($value['division']))->first();
                            $district = District::where('title', trim($value['district']))->first();
                            $area = Upazila::where('title', trim($value['area']))->first();
                            $shop_union = Union::where('title', trim($value['shop_union']))->first();

                            $shopinfo = new ShopInfo();
                            $shopinfo->name = trim($value['shop_name']);
                            $shopinfo->seller_id = $seller->id;
                            $shopinfo->slug = ($value['slug'] == '') ? Helper::generateUniqueSlug('seller','title',trim($value['seller_name'])) : $value['slug'];
                            $shopinfo->phone = ($value['shop_phone']) ? '0'.$value['shop_phone'] : null;
                            $shopinfo->email = ($value['shop_email']) ? $value['shop_email'] : null;
                            $shopinfo->division = $division->id ?? 0;
                            $shopinfo->district = $district->id ?? 0;
                            $shopinfo->area = $area->id ?? 0;
                            $shopinfo->shop_union = $shop_union->id ?? 0;
                            $shopinfo->address = $value['address'];
                            $shopinfo->trade_license = '/images/notfound.png';
                            $shopinfo->bank_name = ($value['bank_name']) ? $value['bank_name'] : null;
                            $shopinfo->account_name =($value['account_name']) ? $value['account_name'] : null;
                            $shopinfo->account_number =($value['account_number']) ? $value['account_number'] : null;
                            $shopinfo->routing_number =($value['routing_number']) ? $value['routing_number'] : null;
                            $shopinfo->bkash = ($value['bkash']) ? $value['bkash'] : null;
                            $shopinfo->rocket = ($value['rocket']) ? $value['rocket'] : null;
                            $shopinfo->nagad = ($value['nagad']) ? $value['nagad'] : null;
                            $shopinfo->upay = ($value['upay']) ? $value['upay'] : null;
                            $shopinfo->commission_percent = (double)($value['commission_percent']) ? $value['commission_percent'] : 0.00;
                            $shopinfo->status = isset($value['status']) ? 1 : 0;
                            $shopinfo->save();
                        }catch(\Exception $e){
                            $notImported[] = 'Row ID '.$i.' was not updated. Error: '.$e;
                        } 
                    }
                }
                $i++;
            }

            if($notImported){
                return redirect()->back()->with('success', implode(',', $notImported));
            }else{
                return redirect()->back()->with('success', 'All seller has been imported successfully!');
            }    
        }
    }


    // Customer import function 

    public function customerCsv(){
        if(is_null($this->user) || !$this->user->can('import.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.imports.import-customer');
    }

    public function storeCustomerCsv(Request $request){

        $request->validate([
            'import_customer_file' => 'required',
        ]);

        if($request->hasFile('import_customer_file')){
            $csv = $request->file('import_customer_file');
            $customers = Helper::csv_to_array($csv);

            $notImported = [];
            // var_dump($customers); exit();
            $i = 1;
            foreach ($customers as $key => $value) {
                
                if($value['name'] == ''){
                    $notImported[] = 'Required data missing! Row - '.$i;
                }else{

                    $user_email = [];  $user_phone = [];

                    if(isset($value['email']) && $value['email']){
                        $user_email = User::where('email', $value['email'])->first();
                    }

                    if(isset($value['phone']) && $value['phone']){
                        $user_phone = User::Where('phone', '0'.$value['phone'])->first();
                    }

                    if(!$user_email && !$user_phone){
                        $customer = new User();
                        $customer->name = $value['name'];
                        $customer->email = ($value['email']) ? $value['email'] : null;
                        $customer->password = ($value['password'] == '') ? $value['password'] : Hash::make('0'.$value['phone']);
                        $customer->phone = ($value['phone']) ? '0'.$value['phone'] : null;
                        $customer->status = isset($value['status']) ? 1 : 0;
                        $customer->balance = (double) $value['balance'] ?? 0;
                        try{
                            $customer->save();
                        }catch(\Exception $e){
                            $notImported[] = 'Row ID '.$i.' was not updated. Error: '.$e;
                        } 
                    }else{
                        $notImported[] = 'Row ID- '.$i.' : Customer - '.$value['name'].' could not imported.';
                    }
                }
                $i++;
            }

            if($notImported){
                return redirect()->back()->with('success', implode(',', $notImported));
            }else{
                return redirect()->back()->with('success', 'All customer has been imported successfully!');
            }    
        }
    }
}
