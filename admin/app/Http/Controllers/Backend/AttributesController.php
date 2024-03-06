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
use Image;
use App\Models\Attribute;
use App\Models\AttributeLocalization;
use Auth;

class AttributesController extends Controller
{

    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }


    public function index(){
        if(is_null($this->user) || !$this->user->can('attributelist.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $attributes = Attribute::where('is_deleted', 0)->orderBy('id','desc')->get();
        return view('backend.pages.attribute.list')->with('attributes',$attributes);
    }


    public function create(){
        if(is_null($this->user) || !$this->user->can('attributelist.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.attribute.create');
    }



    public function edit($id){
        if(is_null($this->user) || !$this->user->can('attributelist.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $attribute = Attribute::find($id);
        return view('backend.pages.attribute.edit')->with(array(
            'attribute'    => $attribute
        ));
    }
	


    public function store(Request $request){

        if(is_null($this->user) || !$this->user->can('attributelist.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'is_active' => 'required',
            'attribute_code' => 'required',
        ],
		[
			'is_active.required' => 'Status is required',
		]);

        $attribute = new Attribute; 
        $attribute->title = $request->title;
        $attribute->attribute_code = $request->attribute_code;
        $attribute->placeholder_text = $request->placeholder_text;
        $attribute->catalog_input_type = $request->catalog_input_type;
		$attribute->description = $request->description;
		$attribute->show_on_specification = $request->show_on_specification == 1?? 0;
		$attribute->show_on_advance_search = $request->show_on_advance_search == 1?? 0;
		$attribute->show_on_filter = $request->show_on_filter == 1?? 0;
		$attribute->is_required = $request->is_required == 1 ?? 0;
		$attribute->is_active = $request->is_active;
        $attribute->save();

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
            $attribute_localization = new AttributeLocalization();
            $attribute_localization->attribute_id = $attribute->id;
            $attribute_localization->title = $data['title'];
            $attribute_localization->lang_code = $data['lang_code'];
            $attribute_localization->description = $data['description'];
            $attribute_localization->save();
        }

        return redirect()->route('admin.attribute-list')->with('success', 'Attribute successfully created !');
    }
	
	

    public function update(Request $request,$id){

        if(is_null($this->user) || !$this->user->can('attributelist.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

		$request->validate([
            'title' => 'required|max:255',
            'is_active' => 'required',
            'attribute_code' => 'required',
        ],
		[
			'is_active.required' => 'Status is required',
		]);

        $attribute = Attribute::find($id);
        $attribute->title = $request->title;
        $attribute->attribute_code = $request->attribute_code;
        $attribute->placeholder_text = $request->placeholder_text;
        $attribute->catalog_input_type = $request->catalog_input_type;
		$attribute->description = $request->description;
		$attribute->show_on_specification = $request->show_on_specification == 1?? 0;
		$attribute->show_on_advance_search = $request->show_on_advance_search == 1?? 0;
		$attribute->show_on_filter = $request->show_on_filter == 1?? 0;
		$attribute->is_required = $request->is_required == 1 ?? 0;
		$attribute->is_active = $request->is_active;
        $attribute->save();

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

            $attribute_localization = AttributeLocalization::where('attribute_id', $attribute->id)->where('lang_code',$data['lang_code'])->first();

            if ($attribute_localization !== null) {
                $attribute_localization->title = $data['title'];
                $attribute_localization->description = $data['description'];
                $attribute_localization->save();
            }else{
                $attribute_localization = new AttributeLocalization();
                $attribute_localization->attribute_id = $attribute->id;
                $attribute_localization->title = $data['title'];
                $attribute_localization->lang_code = $data['lang_code'];
                $attribute_localization->description = $data['description'];
                $attribute_localization->save();
            }
        }
        return redirect()->route('admin.attribute-list')->with('success', 'Attribute successfully updated !');
    }

    public function delete(Request $request,$id){

        if(is_null($this->user) || !$this->user->can('attributelist.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $attribute = Attribute::find($id);
		$attribute->is_deleted = 1;
        $attribute->save();
        return redirect()->route('admin.attribute-list')->with('success', 'Attribute successfully deleted!');
    }

}
