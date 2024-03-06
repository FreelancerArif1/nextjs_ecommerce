<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryLocalization;
use App\Models\CategoryImage;
use Image;
use Auth;
use DB;
use Validator;

class CategoriesController extends Controller
{

    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    
    public function index(){

        if(is_null($this->user) || !$this->user->can('category.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

    
        $category = [
           'allcategories'=>Category::where('is_deleted',0)->orderBy('sort_order', 'ASC')->get(),
            'categories' => Category::where(['parent_id' => 0])->where('is_deleted',0)->orderBy('sort_order', 'ASC')->get(),
        ];

        return view('backend.pages.category.list', $category);

    }

    public function create(){
        if(is_null($this->user) || !$this->user->can('category.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $category = [
            'allcategories'=>Category::where('is_deleted',0)->orderBy('sort_order', 'ASC')->get(),
            'categories' => Category::where(['parent_id' => 0])->where('is_deleted',0)->orderBy('sort_order', 'ASC')->get(),
        ];
        
        return view('backend.pages.category.create', $category);
    }

    public function edit($id){
        if(is_null($this->user) || !$this->user->can('category.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $category = Category::find($id);

        return view('backend.pages.category.edit')->with(
            array(
                'allcategories'=>Category::where('is_deleted',0)->orderBy('sort_order', 'ASC')->get(),
                'category'=>$category,
                'categories'=> Category::where(['parent_id' => 0])->where('is_deleted',0)->orderBy('sort_order', 'ASC')->get()
            )
        );
    }


    public function store( Request $request){
        if(is_null($this->user) || !$this->user->can('category.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'slug' => 'required | unique:categories'
        ]);

        $category = new Category;
        $category->title = $request->title;
        $category->slug = $request->slug;
        $category->parent_id = $request->parent_id;
        $category->image = $request->image;
        $category->banner = $request->banner;
        $category->description = $request->description;
        $category->meta_title = $request->meta_title;
        $category->meta_keyword = $request->meta_keyword;
        $category->meta_description = $request->meta_description;
        $category->is_active = $request->is_active ? 1 : 0 ;
        $category->hide_on_menu = $request->hide_on_menu ? 1 : 0 ;
        $category->show_child_products = $request->show_child_products ? 1 : 0 ;

        $category->save();

        if (!empty($request->products)) {
           foreach($request->products as $pid){
                $product = Product::find($pid);
                if ($product) {
                    $product->category_id = $product->category_id.','.$category->id;
                    $product->category_title = $category->title;
                    $product->update();
                }
            }
        }

        
        //Localization Data
        $lanData = [];
        foreach($request->all() as $key => $val){
            if(strpos($key,"__") > 0){
                $paramKey = explode('__',$key)[0];
                $lang = explode('__',$key)[1];
                $lanData[$lang][$paramKey] = $val;
                $lanData[$lang]['lang_code'] = $lang;
            }
        }

        foreach($lanData as $data){
            $category_localization = new CategoryLocalization();
            $category_localization->category_id = $category->id;
            $category_localization->title = $data['title'];
            $category_localization->lang_code = $data['lang_code'];
            $category_localization->description = $data['description'];
            $category_localization->save();
        }


        return redirect()->route('admin.category')->with('success', 'Category successfully created!');
    }


    public function update( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('category.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
        ]);

        $category = Category::find($id);
        $category->title = $request->title;
        $category->parent_id = $request->parent_id;
        $category->image = $request->image;
        $category->banner = $request->banner;
        $category->description = $request->description;
        $category->meta_title = $request->meta_title;
        $category->meta_keyword = $request->meta_keyword;
        $category->meta_description = $request->meta_description;
        $category->is_active = $request->is_active ? 1 : 0 ;
        $category->hide_on_menu = $request->hide_on_menu ? 1 : 0 ;
        $category->show_child_products = $request->show_child_products ? 1 : 0 ;
        $category->save();

        if (!empty($request->products)) {

            $reqArray = $request->products;
            $current_category_id = $id;
            $newvalues = [];

            $categoryInProducts = explode(',',$request->remove_cat_product);

            foreach($reqArray as $rpid){
                $product = Product::find($rpid);
                if($product){
                    $db_categories = explode(',',$product->category_id);
                    array_push($db_categories,$current_category_id);
                    $db_categories = array_unique($db_categories);
                    $product->category_id = implode(',',$db_categories);
                    $product->update(); 
                }

                if (($unseted = array_search($rpid, $categoryInProducts)) !== false) {
                    unset($categoryInProducts[$unseted]);
                }
                
            }

            //Remove Section
            foreach($categoryInProducts as $rmpid){
                $dProduct = Product::find($rmpid);
                
                if($dProduct){
                    $rm_db_categories = explode(',',$dProduct->category_id);
                    
                    if (($unseted = array_search($current_category_id, $rm_db_categories)) !== false) {
                        unset($rm_db_categories[$unseted]);
                    }

                    $dProduct->category_id = implode(',',$rm_db_categories);
                    $dProduct->update(); 
                }
            }
        }else{
            $main_category = $category->id;
            $product = Product::whereRaw("FIND_IN_SET($main_category,category_id)")->get();
            foreach ($product as $row) {
                $dProduct = Product::find($row->id);
                
                if($dProduct){
                    $rm_db_categories = explode(',',$dProduct->category_id);
                    
                    if (($unseted = array_search($main_category, $rm_db_categories)) !== false) {
                        unset($rm_db_categories[$unseted]);
                    }

                    $dProduct->category_id = implode(',',$rm_db_categories);
                    $dProduct->update(); 
                }
            }
            
        }
        
        //Localization Data
        $lanData = [];
        foreach($request->all() as $key => $val){
            if(strpos($key,"__") > 0){
                $paramKey = explode('__',$key)[0];
                $lang = explode('__',$key)[1];
                $lanData[$lang][$paramKey] = $val;
                $lanData[$lang]['lang_code'] = $lang;
            }
        }

        foreach($lanData as $data){

            $category_localization = CategoryLocalization::where('category_id', $category->id)->where('lang_code',$data['lang_code'])->first();

            if ($category_localization !== null) {
                $category_localization->title = $data['title'];
                $category_localization->description = $data['description'];
                $category_localization->save();
            }else{
                $category_localization = new CategoryLocalization();
                $category_localization->category_id = $category->id;
                $category_localization->title = $data['title'];
                $category_localization->lang_code = $data['lang_code'];
                $category_localization->description = $data['description'];
                $category_localization->save();
            }
        }


        return redirect()->route('admin.category')->with('success', 'Category successfully updated!');
    }

    public function delete( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('category.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $category = Category::find($id);
        $category->is_deleted = 1;
        //Insert Trash Data
        $type = 'category'; $type_id = $id; $reason = $request->reason ?? ''; $data = $category;
        \Helper::setTrashInfo($type,$type_id,$reason,$data);
        $category->save();

        return redirect()->route('admin.category')->with('success', 'Category successfully deleted!');
    }

    //Nested Categories
    public function saveNestedCategories(Request $request){
        
        // $json = $request->nested_category_array;
        $rules = array(
            'nested_category_array'   => 'required'
        );

        $validator = Validator::make($request->all(), $rules);  

        if ($validator->passes()) {
            $menus = json_decode($request->input('nested_category_array'),true);

            $child = array();
            $a=0;
            foreach($menus as $m)
            {           
                if(isset($m['children']))
                {
                    $b=0;
                    foreach($m['children'] as $l)                   
                    {
                        if(isset($l['children']))
                        {
                            $c=0;
                            foreach($l['children'] as $l2)
                            {
                                $level3[] = $l2['id'];
                                \DB::table('categories')->where('id','=',$l2['id'])
                                    ->update(array('parent_id'=> $l['id'],'sort_order'=>$c));
                                $c++;   
                            }       
                        }
                        \DB::table('categories')->where('id','=', $l['id'])
                            ->update(array('parent_id'=> $m['id'],'sort_order'=>$b)); 
                        $b++;
                    }                           
                }
                \DB::table('categories')->where('id','=', $m['id'])
                    ->update(array('parent_id'=>'0','sort_order'=>$a));
                $a++;       
            }

            return redirect()->back()->with('success', 'Data Has Been Save Successfull')->with('status','success');
        } else {
            return redirect()->back()->with('error', 'The following errors occurred')->with('status','error');
        }   
        // $decoded_json = json_decode($json, TRUE);

        // $simplified_list = [];
        // $this->recur1($decoded_json, $simplified_list);


        // //var_dump($simplified_list); exit;

        // try {
           
        //     foreach($simplified_list as $k => $v){
        //         $category = Category::find($v['id']);
        //         $category->parent_id = $v['parent_id'];
        //         $category->sort_order = $v['sort_order'];
        //         $category->save();
        //     }
        //     $info['success'] = TRUE;

        // } catch (\Exception $e) {
        //     $info['success'] = FALSE;
        // }

        // if($info['success']){
        //     $request->session()->flash('success', "All Categories updated.");
        // }else{
        //     $request->session()->flash('error', "Something went wrong while updating...");
        // }

        // return redirect(route('admin.category'));
    }

    public function recur1($nested_array=[], &$simplified_list=[]){
        
        static $counter = 0;
        
        foreach($nested_array as $k => $v){

            $sort_order = $k+1;

            $simplified_list[] = [
                "id" => $v['id'], 
                "parent_id" => 0, 
                "sort_order" => $sort_order
            ];


            if(!empty($v["children"])){
                $counter+=1;
                $this->recur2($v['children'], $simplified_list, $v['id']);
            }else{
                $simplified_list[] = [
                    "id" => $v['id'], 
                    "parent_id" => 0, 
                    "sort_order" => $sort_order
                ];
            }

        }
    }

    public function recur2($sub_nested_array=[], &$simplified_list=[], $parent_id = NULL){
        
        static $counter = 0;

        foreach($sub_nested_array as $k => $v){
            
            $sort_order = $k+1;
            $simplified_list[] = [
                "id" => $v['id'], 
                "parent_id" => $parent_id, 
                "sort_order" => $sort_order
            ];
            
            if(!empty($v["children"])){
                $counter+=1;
                return $this->recur2($v['children'], $simplified_list, $v['id']);
            }
        }
    }
}