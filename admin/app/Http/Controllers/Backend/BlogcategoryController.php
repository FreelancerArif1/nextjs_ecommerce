<?php

namespace App\Http\Controllers\Backend;

use App\Models\BlogCategory;
use App\Models\BlogCategoryLocalization;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Image;
use Auth;
use Illuminate\Support\Str;


class BlogcategoryController extends Controller
{
    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }
   
    public function index()
    {
        if(is_null($this->user) || !$this->user->can('blog.category.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $categories = BlogCategory::latest()->get();
        return view('backend.pages.blog.category.list', compact('categories'));
    }



    public function getBlogCategoryList(){

        if(is_null($this->user) || !$this->user->can('blog.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $data = BlogCategory::latest()->get();

        return Datatables::of($data)->addIndexColumn()
        ->addColumn('category', function($row){
            $html = '';
            $imageHtml = '';

            if($row->image){
                $imageHtml = '<img class="list_img mr-3" src="'.asset('/'.$row->image).'">';
            }else{
                $imageHtml = '<img class="list_img mr-3" src="'.asset('uploads/images/default/no-image.png').'">';
            }
            
            $html = '<div class="media">'.$imageHtml.'<div class="media-body"><p class="product_title">'.$row->title.'</p></div>';
            return $html;
        })
        ->addColumn('icon', function($row){
            $html = '';
            if($row->icon){
                $html .= '<img class="list_img mr-3" src="/'.$row->icon.'">';
            }else{
                $html .= '<img class="list_img mr-3" src="'.asset('uploads/images/default/no-image.png').'">';
            }
                     
            return $html;
        })
        ->addColumn('status', function($row){
            return  '<label class="badge badge_'.strtolower(\Helper::getStatusName('default',$row->is_active)).'">'.\Helper::getStatusName('default',$row->is_active).'</label>';
        })

        ->addColumn('action', function($row){
            $btn = '';
            if(Auth::user()->can('blog.edit')){
                $btn = '<a class="icon_btn text-success" href="'.route('admin.blog.category.edit',$row->id).'"><i class="mdi mdi-pencil-box-outline"></i></a>';
            }
            
            if(Auth::user()->can('blog.delete')){
                $btn = $btn.'<a class="icon_btn text-danger delete_btn" data-url="'.route('admin.blog.category.delete',$row->id).'" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
            }

            return $btn;
        })

        ->rawColumns(['category','icon','status','action'])->make(true);
    }

    public function create()
    {
        if(is_null($this->user) || !$this->user->can('blog.category.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.blog.category.create');
    }

    public function store( Request $request){
        if(is_null($this->user) || !$this->user->can('blog.category.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'slug'  => 'required|unique:blog_categories',
            'is_active' => 'required',
        ]);

        $blogCategory = new BlogCategory;
        $blogCategory->title = $request->title;

        //$blogCategory->slug = $request->slug;

        $blogCategory->slug  = str_replace(' ', '-', Str::slug(Str::lower($request->slug)));


        $blogCategory->image = $request->image;
        $blogCategory->description = $request->description;
        $blogCategory->icon = $request->icon;
        $blogCategory->meta_title = $request->meta_title;
        $blogCategory->meta_keyword = $request->meta_keyword;
        $blogCategory->meta_description = $request->meta_description;
        $blogCategory->sort_order = $request->sort_order;
        $blogCategory->is_active = $request->is_active ? 1 : 0;
        $blogCategory->save();





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
            $category_localization = new BlogCategoryLocalization();
            $category_localization->blog_category_id = $blogCategory->id;
            $category_localization->title = $data['title'];
            $category_localization->lang_code = $data['lang_code'];
            $category_localization->description = $data['description'];
            $category_localization->save();
        }







        return redirect()->route('admin.blog.category')->with('success', 'Blog category successfully created!');
    }

    public function edit($id)
    {
        if(is_null($this->user) || !$this->user->can('blog.category.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $category = BlogCategory::findOrFail($id);

        return view('backend.pages.blog.category.edit', compact('category'));
    }

    public function update( Request $request,$id){

        if(is_null($this->user) || !$this->user->can('blog.category.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'is_active' => 'required',
        ]);

        $blogCategory = BlogCategory::find($id);
        $blogCategory->title = $request->title;
        $blogCategory->image = $request->image;
        $blogCategory->icon = $request->icon;
        $blogCategory->description = $request->description;
        $blogCategory->meta_title = $request->meta_title;
        $blogCategory->meta_keyword = $request->meta_keyword;
        $blogCategory->meta_description = $request->meta_description;
        $blogCategory->sort_order = $request->sort_order;
        $blogCategory->is_active = $request->is_active ? 1 : 0;
        $blogCategory->save();
        $blogCategory->save();


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
            $category_localization = new BlogCategoryLocalization();
            $category_localization->blog_category_id = $blogCategory->id;
            $category_localization->title = $data['title'];
            $category_localization->lang_code = $data['lang_code'];
            $category_localization->description = $data['description'];
            $category_localization->save();
        }



        return redirect()->route('admin.blog.category',$blogCategory->id)->with('success', 'Blog category successfully updated!');
    }

    public function delete( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('blog.category.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $BlogCategory = BlogCategory::find($id);
        $BlogCategory->delete();
        return redirect()->route('admin.blog')->with('success', 'Blog successfully deleted!');
    }
}
