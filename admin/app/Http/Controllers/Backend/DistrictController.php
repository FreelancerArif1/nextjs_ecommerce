<?php

namespace App\Http\Controllers\Backend;

use App\Models\District;
use App\Models\Division;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Image;
use Auth;

class DistrictController extends Controller
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
        if (is_null($this->user) || !$this->user->can('location.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $data = District::where('is_deleted', 0)->get();
        return view('backend.pages.location.district.list', compact('data'));
    }




    // public function getBlogList(){

    //     if(is_null($this->user) || !$this->user->can('blog.view')){
    //         return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
    //     }

    //     $data = Blog::where('is_deleted',0);

    //     return Datatables::of($data)
    //     ->addIndexColumn()
    //     ->editColumn('title', function($row){
    //         $html = '';
    //         $imageHtml = '';

    //         if($row->image){
    //             $imageHtml = '<img class="list_img mr-3" src="'.asset('/'.$row->image).'">';
    //         }else{
    //             $imageHtml = '<img class="list_img mr-3" src="'.asset('uploads/images/default/no-image.png').'">';
    //         }

    //         $html = '<div class="media">'.$imageHtml.'<div class="media-body"><p class="product_title">'.$row->title.'</p></div>';
    //         return $html;
    //     })

    //     ->editColumn('created_at', function($row){
    //         return  date('d M, Y h:ia', strtotime($row->created_at));
    //     })

    //     ->editColumn('is_active', function($row){
    //         return  '<label class="badge badge_'.strtolower(\Helper::getStatusName('default',$row->is_active)).'">'.\Helper::getStatusName('default',$row->is_active).'</label>';
    //     })

    //     ->editColumn('category_id', function($row){
    //         return $row->category->title ?? '';
    //     })

    //     ->addColumn('action', function($row){
    //         $btn = '';
    //         if(Auth::user()->can('blog.edit')){
    //             $btn = '<a class="icon_btn text-success" href="'.route('admin.blog.edit',$row->id).'"><i class="mdi mdi-pencil-box-outline"></i></a>';
    //         }

    //         if(Auth::user()->can('blog.delete')){
    //             $btn = $btn.'<a class="icon_btn text-danger delete_btn" data-url="'.route('admin.blog.delete',$row->id).'" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
    //         }

    //         return $btn;
    //     })

    //     ->rawColumns(['title','category_id','created_at','is_active','action'])->make(true);
    // }



    public function create()
    {
        if (is_null($this->user) || !$this->user->can('location.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $data = Division::where('is_deleted', 0)->get();

        return view('backend.pages.location.district.create', compact('data'));
    }


    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('location.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'division_id' => 'required'
            // 'is_active' => 'required',
            // 'slug'  => 'required | unique:blogs'
        ]);

        $location = new District();
        $location->title = $request->title;
        $location->division_id = $request->division_id;


        $location->save();


        return redirect()->route('admin.location.district')->with('success', 'District successfully created!');
    }


    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('location.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $data = District::find($id);
        return view('backend.pages.location.district.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('location.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'division_id' => 'required'
        ]);

        $location = District::find($id);
        $location->title = $request->title;
        $location->division_id = $request->division_id;

        $location->save();

        return redirect()->route('admin.location.district')->with('success', 'District successfully updated!');
    }

    public function delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('location.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $location = District::find($id);

        //Insert Trash Data
        $type = 'location';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $location;
        \Helper::setTrashInfo($type, $type_id, $reason, $data);

        $location->is_deleted = 1;
        $location->save();

        return redirect()->route('admin.location.district')->with('success', 'Division successfully deleted!');
    }

    public function getDistrict()
    {
        $data = District::where('is_deleted', 0);
        return DataTables::of($data)->addIndexColumn()
            ->addColumn('division_title', function ($row) {
                $division = Division::where('id', $row->division_id)
                    ->first();
                return $division->title ?? null;
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                // if (Auth::user()->can('location.view')) {
                //     $btn = '<a class="icon_btn text-success" href="' . route('admin.location.district', $row->id) . '"><i class="mdi mdi-eye"></i></a>';
                // }
                if (Auth::user()->can('location.edit')) {
                    $btn = $btn . '<a class="icon_btn text-info" href="' . route('admin.location.district.edit', $row->id) . '"><i class="mdi mdi-playlist-edit"></i></a>';
                }
                if (Auth::user()->can('location.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.location.district.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['division_title', 'action'])->make(true);
    }
}