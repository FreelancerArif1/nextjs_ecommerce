<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Image;
use Auth;

class TestimonialsController extends Controller
{

    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    
    public function index(){
        if(is_null($this->user) || !$this->user->can('testimonials.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $testimonial = Testimonial::orderBy('id','desc')->get();
        return view('backend.pages.testimonial.list')->with('testimonials',$testimonial);
    }


    public function create(){
        if(is_null($this->user) || !$this->user->can('testimonials.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $testimonials = Testimonial::orderBy('id','desc')->get();
        return view('backend.pages.testimonial.create')->with('testimonials',$testimonials);
    }

    public function edit($id){
        if(is_null($this->user) || !$this->user->can('testimonials.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $testimonial = Testimonial::find($id);
        $testimonials = Testimonial::orderBy('id','desc')->get();
        return view('backend.pages.testimonial.edit')->with(
            array(
                'testimonial'=>$testimonial,
                'testimonials'=> $testimonials
            )
        );
    }


    public function store( Request $request){

        if(is_null($this->user) || !$this->user->can('testimonials.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'name' => 'required',
            'profession' => 'required',
			'dialuge' => 'required| string',
			'status' =>  'max:2 | numeric',
            'image' => 'nullable | image'
        ]);

        $testimonial = new Testimonial;
        $testimonial->name = $request->name;
        $testimonial->profession = $request->profession;
        $testimonial->dialuge = $request->dialuge;
        $testimonial->status = $request->status;
		
        if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = round(microtime(true)).'.'.$image->getClientOriginalExtension();
                $location = public_path('uploads/images/testimonials/'.$imageName);
                Image::make($image)->save($location);
                $testimonial->image = $imageName;
        }

        $testimonial->save();
        
        return redirect()->route('admin.testimonial')->with('success', 'Testimonial successfully created!');
    }




    public function update( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('testimonials.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'name' => 'required',
            'profession' => 'required',
			'dialuge' => 'required| string',
			'status' =>  'max:2 | numeric',
            'image' => 'nullable | image'
        ]);

        $testimonial = Testimonial::find($id);
        $testimonial->name = $request->name;
        $testimonial->profession = $request->profession;
        $testimonial->dialuge = $request->dialuge;
        $testimonial->status = $request->status;
        
        if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = round(microtime(true)).'.'.$image->getClientOriginalExtension();
                $location = public_path('uploads/images/testimonials/'.$imageName);
                Image::make($image)->save($location);
                $testimonial->image = $imageName;
        }

        $testimonial->save();
        
        return redirect()->route('admin.testimonial',$testimonial->id)->with('success', 'Testimonial successfully updated!');
    }



    public function delete( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('testimonials.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $testimonial = Testimonial::find($id);
		$image = 'uploads/images/testimonials/'.$testimonial->image;
		if (file_exists($image)) {
			unlink($image);
		}
        $testimonial->delete();
        return redirect()->route('admin.testimonial')->with('success', 'Testimonial successfully deleted!');
    }


}
