<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\SliderLocalization;
use Image;
use Auth;

class SlidersController extends Controller
{

    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    
    public function index(){
        if(is_null($this->user) || !$this->user->can('slider.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $slider = Slider::orderBy('sort_order','desc')->get();
        return view('backend.pages.slider.list')->with('sliders',$slider);
    }


    public function create(){
        if(is_null($this->user) || !$this->user->can('slider.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $sliders = Slider::orderBy('id','desc')->get();
        return view('backend.pages.slider.create')->with('sliders',$sliders);
    }

    public function edit($id){
        if(is_null($this->user) || !$this->user->can('slider.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $slider = Slider::find($id);
        $sliders = Slider::orderBy('id','desc')->get();
        return view('backend.pages.slider.edit')->with(
            array(
                'slider'=>$slider,
                'sliders'=> $sliders
            )
        );
    }


    public function store( Request $request){
        if(is_null($this->user) || !$this->user->can('slider.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'nullable|max:190',
            'slider_text' => 'nullable',
			'button_title' => 'max:190 | nullable',
			'button_link' =>  'max:190 | nullable',
			'status' =>  'max:2 | numeric'
        ]);

        $slider = new Slider;
        $slider->title = $request->title;
        $slider->slider_text = $request->slider_text;
        $slider->button_title = $request->button_title;
		$slider->button_link = $request->button_link;
        $slider->status = $request->status;
        $slider->is_grocery = $request->is_grocery ? 1 : 0;
        $slider->image = $request->image;
		
        $slider->save();
        
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
            $slider_localization = new SliderLocalization();
            $slider_localization->slider_id = $slider->id;
            $slider_localization->title = $data['title'];
            $slider_localization->lang_code = $data['lang_code'];
            $slider_localization->slider_text = $data['slider_text'];
            $slider_localization->button_title = $data['button_title'];
            $slider_localization->save();
        }

        return redirect()->route('admin.slider')->with('success', 'Slider successfully created!');
    }




    public function update( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('slider.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'nullable|max:190',
            'slider_text' => 'nullable',
			'button_title' => 'max:190 | nullable',
			'button_link' =>  'max:190 | nullable',
			'status' =>  'max:2 | numeric'
        ]);

        $slider = Slider::find($id);
        $slider->title = $request->title;
        $slider->slider_text = $request->slider_text;
        $slider->button_title = $request->button_title;
        $slider->button_link = $request->button_link;
        $slider->status = $request->status;
        $slider->image = $request->image;
        $slider->is_grocery = $request->is_grocery ? 1 : 0;
        $slider->save();

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

            $slider_localization = SliderLocalization::where('slider_id', $slider->id)->where('lang_code',$data['lang_code'])->first();

            if ($slider_localization !== null) {
                $slider_localization->title = $data['title'];
                $slider_localization->slider_text = $data['slider_text'];
                $slider_localization->button_title = $data['button_title'];
                $slider_localization->save();
            }else{
                $slider_localization = new SliderLocalization();
                $slider_localization->slider_id = $slider->id;
                $slider_localization->title = $data['title'];
                $slider_localization->lang_code = $data['lang_code'];
                $slider_localization->slider_text = $data['slider_text'];
                $slider_localization->button_title = $data['button_title'];
                $slider_localization->save();
            }
        }
        
        return redirect()->route('admin.slider',$slider->id)->with('success', 'Slider successfully updated!');
    }



    public function delete( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('slider.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $slider = Slider::find($id);
		$image = 'uploads/images/sliders/'.$slider->image;
		if (file_exists($image)) {
			unlink($image);
		}
        $slider->delete();
        return redirect()->route('admin.slider')->with('success', 'Slider successfully deleted!');
    }


    public function reorder(Request $request){
        $ids = $request->slider_ids;
        $sort = count($ids);
        foreach ($ids as $key => $value) {
            $slider = Slider::find($value);
            $slider->sort_order = $sort;
            $slider->save();
            $sort--;
        }

        return redirect()->back()->with('success', 'Slider successfully re-ordered!');
    }

}
