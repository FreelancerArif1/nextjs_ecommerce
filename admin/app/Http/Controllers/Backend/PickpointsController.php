<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pickpoints;
use Image;
use Auth;

class PickpointsController extends Controller
{

    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    
    public function index(){
        if(is_null($this->user) || !$this->user->can('pick_point.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $pick_point = Pickpoints::orderBy('id','desc')->get();
        return view('backend.pages.pick_point.list')->with('pick_points',$pick_point);
    }


    public function create(){
        if(is_null($this->user) || !$this->user->can('pick_point.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.pick_point.create');
    }

    public function edit($id){
        if(is_null($this->user) || !$this->user->can('pick_point.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $pick_point = Pickpoints::find($id);
        return view('backend.pages.pick_point.edit',compact('pick_point'));
    }


    public function store( Request $request){
        if(is_null($this->user) || !$this->user->can('pick_point.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required',
            'division_id' => 'required',
            'district_id' => 'required',
            'upazila_id' => 'required',
            'union_id' => 'required',
            'address' => 'required',
            'discount' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $pick_point = new Pickpoints;
        $pick_point->title = $request->title;
        $pick_point->address = $request->address;
        $pick_point->division_id = $request->division_id;
        $pick_point->district_id = $request->district_id;
        $pick_point->upazila_id = $request->upazila_id;
        $pick_point->union_id = $request->union_id;
        $pick_point->post_code = $request->post_code;
        $pick_point->discount = $request->discount;
        $pick_point->phone = $request->phone;
        $pick_point->email = $request->email;
        $pick_point->status = $request->status ? 1 : 0;
        $pick_point->save();
        
        return redirect()->route('admin.pick_points')->with('success', 'Pick point successfully created!');
    }




    public function update( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('pick_point.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $request->validate([
            'title' => 'required',
            'division_id' => 'required',
            'district_id' => 'required',
            'upazila_id' => 'required',
            'union_id' => 'required',
            'address' => 'required',
            'discount' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $pick_point = Pickpoints::find($id);
        $pick_point->title = $request->title;
        $pick_point->address = $request->address;
        $pick_point->division_id = $request->division_id;
        $pick_point->district_id = $request->district_id;
        $pick_point->upazila_id = $request->upazila_id;
        $pick_point->union_id = $request->union_id;
        $pick_point->post_code = $request->post_code;
        $pick_point->discount = $request->discount;
        $pick_point->phone = $request->phone;
        $pick_point->email = $request->email;
        $pick_point->status = $request->status ? 1 : 0;
        $pick_point->save();

        return redirect()->route('admin.pick_points',$pick_point->id)->with('success', 'Pick point successfully updated!');
    }



    public function delete( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('pick_points.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $pick_point = Pickpoints::find($id);
        $pick_point->delete();
        return redirect()->route('admin.pick_points')->with('success', 'Pick point successfully deleted!');
    }


}
