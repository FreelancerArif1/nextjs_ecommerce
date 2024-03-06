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
use App\Models\AttributeSets;
use Auth;

class AttributesetsController extends Controller
{

    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }


    public function index(){
        
        if(is_null($this->user) || !$this->user->can('attributeset.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $attributeset = AttributeSets::where('is_deleted', 0)->orderBy('id','desc')->get();
        return view('backend.pages.attribute-set.list')->with('attributeset',$attributeset);
    }


    public function create(){

        if(is_null($this->user) || !$this->user->can('attributeset.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.attribute-set.create');
    }



    public function edit($id){

        if(is_null($this->user) || !$this->user->can('attributeset.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $attributeset = AttributeSets::find($id);
        return view('backend.pages.attribute-set.edit')->with(array(
            'attributeset'    => $attributeset
        ));
    }
	


    public function store(Request $request){
        
        if(is_null($this->user) || !$this->user->can('attributeset.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'is_active' => 'required',
        ],
		[
			'is_active.required' => 'Status is required',
		]);

        $attributeset = new AttributeSets; 
        $attributeset->title = $request->title;
		$attributeset->description = $request->description;
		$attributeset->attribute_set_code = $request->attribute_set_code;
		$attributeset->is_active = $request->is_active;
		$attributeset->attribute_ids = implode(',',$request->attribute_ids);
        $attributeset->save();
        return redirect()->route('admin.attribute-set')->with('success', 'Attribute Sets successfully created !');
    }
	
	

    public function update(Request $request,$id){
        
        if(is_null($this->user) || !$this->user->can('attributeset.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'is_active' => 'required',
        ],
		[
			'is_active.required' => 'Status is required',
		]);

        $attributeset = AttributeSets::find($id);
        $attributeset->title = $request->title;
		$attributeset->description = $request->description;
		$attributeset->attribute_set_code = $request->attribute_set_code;
		$attributeset->is_active = $request->is_active;
		$attributeset->attribute_ids = implode(',',$request->attribute_ids);
		
        $attributeset->save();
        return redirect()->route('admin.attribute-set')->with('success', 'Attribute Sets successfully updated !');
    }

    public function delete(Request $request,$id){

        if(is_null($this->user) || !$this->user->can('attributeset.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $attributeset = AttributeSets::find($id);
		$attributeset->is_deleted = 1;
        $attributeset->save();
        return redirect()->route('admin.attribute-set')->with('success', 'Attribute Sets successfully deleted!');
    }
	
	
	public function get_attribute_set_details($attributeSetId){
        $attributset = AttributeSets::where('id',$attributeSetId)->where('is_active',1)->where('is_deleted',0)->first();
        $html = '';
        if($attributset){
            $attribute_ids_array = explode(',',$attributset->attribute_ids);
            foreach($attribute_ids_array as $attribute_id){

                $attribute = \DB::table('attributes')->where('id',$attribute_id)->where('is_active',1)->where('is_deleted',0)->first();

                if($attribute){

                    $catalog_input_type = $attribute->catalog_input_type;
                    $is_required = $attribute->is_required;
                    if($is_required == 1){
                        $requredText = '<span class="required">*</span>';
                        $requiredValidate = 'required';
                    }else{
                        $requredText = '';
                        $requiredValidate = '';
                    }

                    if ($catalog_input_type == 'radio') {

                            $attribute_values = unserialize($attribute->attribute_values);
                                $html .= '<div class="form-group row">
                                <label class="col-sm-3 col-form-label">'.$attribute->title. $requredText.'</label>
                                <div class="col-sm-9">';
                                        foreach( $attribute_values as $av){
                                            $html.='<input type="radio" name="specification['.$attribute->attribute_code.']" value="'.$av['value'].'"'.$requiredValidate.' > &nbsp;'.$av['label'].'&nbsp;&nbsp;&nbsp;';
                                        }
                                $html.= '</div>
                                </div>';

                    }elseif($catalog_input_type == 'checkbox'){
                        $attribute_values = unserialize($attribute->attribute_values);
                        $html .= '<div class="form-group row">
                        <label class="col-sm-3 col-form-label">'.$attribute->title.$requredText.'</label>
                        <div class="col-sm-9">';
                                foreach( $attribute_values as $av){
                                    $html.='<input class="form-control" type="checkboox" name="specification['.$attribute->attribute_code.']" value="'.$av['value'].'"'.$requiredValidate.' > &nbsp;&nbsp;'.$av['label'];
                                }
                        $html.= '</div>
                        </div>';

                    }elseif($catalog_input_type == 'dropdown'){
                        $attribute_values = unserialize($attribute->attribute_values);
                        $html .= '<div class="form-group row">
                                <label class="col-sm-3 col-form-label">'.$attribute->title.$requredText.'</label>
                                <div class="col-sm-9">';
                                    $html.='<select class="form-control" name="specification['.$attribute->attribute_code.']"'.$requiredValidate.'>';
                                            foreach( $attribute_values as $av){
                                                $html.= '<option value="'.$av['value'].'">'.$av['label'].'</option>';
                                            }
                                    $html.='</select>
                                </div>
                            </div>';
                    }elseif($catalog_input_type == 'textfield'){
                        $html .= '<div class="form-group row">
                        <label class="col-sm-3 col-form-label">'.$attribute->title.$requredText.'</label>
                        <div class="col-sm-9">';
                            $html.= '<input class="form-control" type="text" name="specification['.$attribute->attribute_code.']"'.$requiredValidate.'>
                            </div>
                        </div>';
                    }elseif($catalog_input_type == 'textarea'){
                        $html .= '<div class="form-group row">
                        <label class="col-sm-3 col-form-label">'.$attribute->title.$requredText.'</label>
                        <div class="col-sm-9">';
                                $html.= '<textarea class="form-control" name="specification['.$attribute->attribute_code.']"'.$requiredValidate.'></textarea>
                            </div>
                        </div>';
                    }elseif($catalog_input_type == 'textareawitheditor'){
                        $html .= '<div class="form-group row">
                        <label class="col-sm-3 col-form-label">'.$attribute->title.$requredText.'</label>
                        <div class="col-sm-9">';
                                $html.= '<textarea class="form-control textEditor" name="specification['.$attribute->attribute_code.']"'.$requiredValidate.'></textarea>
                            </div>
                        </div>';
                    }

                }  

            }
        }
        return $html;
    }




}
