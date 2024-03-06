<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\BrandLocalization;
use Yajra\DataTables\DataTables;
use Image;
use Auth;

class BrandsController extends Controller
{
    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index(){

        if(is_null($this->user) || !$this->user->can('brand.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $brand = Brand::orderBy('id','desc')->where('is_deleted',0)->get();
        return view('backend.pages.brand.list')->with('brands',$brand);
    }


    public function getBrandList(){

        if(is_null($this->user) || !$this->user->can('brand.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        
        $data = Brand::orderBy('id','desc')->where('is_deleted',0)->get();

        return Datatables::of($data)->addIndexColumn()
        ->editColumn('brand', function($row){
            $brandHtml = '';
            $imageHtml = '';

            if($row->image){
                $imageHtml = '<img class="list_img mr-3" src="'.asset('/'.$row->image).'">';
            }else{
                $imageHtml = '<img class="list_img mr-3" src="'.asset('uploads/images/default/no-image.png').'">';
            }
            
            $brandHtml = '<div class="media">'.$imageHtml.'<div class="media-body"><p class="product_title">'.$row->title.'</p></div>';
            return $brandHtml;
        })
        
        ->editColumn('status', function($row){
            return  '<label class="badge badge_'.strtolower(\Helper::getStatusName('default',$row->is_active)).'">'.\Helper::getStatusName('default',$row->is_active).'</label>';
        })
        
        ->editColumn('meta_keyword', function($row){
            $meta_keyword = '';
            if($row->meta_keyword){
                foreach(explode(",",$row->meta_keyword) as $k => $val){
                    $meta_keyword .= '<span class="badge badge_theme text-light m-1">'.$val.'</span>';
                }
            }
            
            return  $meta_keyword;
        })
        
        ->editColumn('action', function($row){
            $btn = '';
            if(Auth::user()->can('brand.edit')){
                $btn = '<a class="icon_btn text-success" href="'.route('admin.brand.edit',$row->id).'"><i class="mdi mdi-pencil-box-outline"></i></a>';
            }
            
            if(Auth::user()->can('brand.delete')){
                $btn = $btn.'<a class="icon_btn text-danger delete_btn" data-url="'.route('admin.brand.delete',$row->id).'" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
            }

            return $btn;
        })

        ->rawColumns(['brand','status','action','meta_keyword'])->make(true);
    }


    
    public function create(){
        if(is_null($this->user) || !$this->user->can('brand.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.brand.create');
    }

    public function edit($id){
        if(is_null($this->user) || !$this->user->can('brand.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $brand = Brand::find($id);
        return view('backend.pages.brand.edit')->with(
            array(
                'brand'=>$brand
            )
        );
    }


    public function store( Request $request){
        if(is_null($this->user) || !$this->user->can('brand.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $request->validate([
            'title' => 'required|max:255',
            'is_active' => 'required',
            'slug'  => 'required | unique:brands',
        ]);

        $brand = new Brand;
        $brand->title = $request->title;
        $brand->slug = $request->slug;
        $brand->image = $request->image;
        $brand->description = $request->description;
        $brand->meta_title = $request->meta_title;
        $brand->meta_keyword = $request->meta_keyword;
        $brand->meta_description = $request->meta_description;
        $brand->is_active = $request->is_active ? 1 : 0 ;
        $brand->save();


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
            $brand_localization = new BrandLocalization();
            $brand_localization->brand_id = $brand->id;
            $brand_localization->title = $data['title'];
            $brand_localization->lang_code = $data['lang_code'];
            $brand_localization->description = $data['description'];
            $brand_localization->save();
        }


        return redirect()->route('admin.brand')->with('success', 'Brand successfully created!');
    }




    public function update( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('brand.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'is_active' => 'required'
        ]);

        $brand = Brand::find($id);
        $brand->title = $request->title;
        $brand->image = $request->image;
        $brand->description = $request->description;
        $brand->meta_title = $request->meta_title;
        $brand->meta_keyword = $request->meta_keyword;
        $brand->meta_description = $request->meta_description;
        $brand->is_active = $request->is_active ? 1 : 0 ;
        $brand->save();

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

            $brand_localization = BrandLocalization::where('brand_id', $brand->id)->where('lang_code',$data['lang_code'])->first();

            if ($brand_localization !== null) {
                $brand_localization->title = $data['title'];
                $brand_localization->description = $data['description'];
                $brand_localization->save();
            }else{
                $brand_localization = new BrandLocalization();
                $brand_localization->brand_id = $brand->id;
                $brand_localization->title = $data['title'];
                $brand_localization->lang_code = $data['lang_code'];
                $brand_localization->description = $data['description'];
                $brand_localization->save();
            }
        }

        return redirect()->route('admin.brand',$brand->id)->with('success', 'Brand successfully updated!');
    }



    public function delete( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('brand.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $brand = Brand::find($id);
    
        //Insert Trash Data
        $type = 'brand'; $type_id = $id; $reason = $request->reason ?? ''; $data = $brand;
        \Helper::setTrashInfo($type,$type_id,$reason,$data);

        $brand->is_deleted = 1;
        $brand->save();

        return redirect()->route('admin.brand')->with('success', 'Brand successfully deleted!');
    }


}
