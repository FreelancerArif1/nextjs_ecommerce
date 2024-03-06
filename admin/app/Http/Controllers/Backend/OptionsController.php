<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\OptionValue;
use Auth;


class OptionsController extends Controller
{


    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    
    public function index(){
        if(is_null($this->user) || !$this->user->can('customoption.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $options = Option::orderBy('id','desc')->get();
        return view('backend.pages.option.list')->with('options',$options);
    }

    public function create(){
        if(is_null($this->user) || !$this->user->can('customoption.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $options = Option::orderBy('id','desc')->get();
        return view('backend.pages.option.create')->with('options',$options);
    }
	
	public function edit($id){
        if(is_null($this->user) || !$this->user->can('customoption.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $option = Option::find($id);
        $values = OptionValue::where('option_id',$id)->get();
        return view('backend.pages.option.edit')->with(
            array(
                'option'=> $option,
                'values'=> $values
            )
        );
    }
	
	public function store( Request $request){

        if(is_null($this->user) || !$this->user->can('customoption.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:191 | string',
            'type' => 'required | string',
        ]);
        $option = new Option;
        $option->title = $request->title; 
        $option->type = $request->type;
        $option->is_required = $request->is_required;
        $option->save();
		
		$i = 0;
		foreach($request->value as $value ){
			$i++;
			$option_value = new OptionValue;
			$option_value->title = $value['title'];
			$option_value->sku = $value['sku'];
			$option_value->price = $value['price'];
			$option_value->price_type = $value['price_type'];
			$option_value->option_id = $option->id;
			$option_value->position = $i;
			$option_value->save();
		}
		

        return redirect()->route('admin.option')->with('success', 'Option successfully created!');
    }
	
	
	public function update( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('customoption.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

		$res = [];
        $request->validate([
            'title' => 'required|max:191 | string',
            'type' => 'required | string',
        ]);
        $option = Option::find($id);
        $option->title = $request->title; 
        $option->type = $request->type;
        $option->is_required = $request->is_required;
        $option->save();
		OptionValue::where('option_id',$id)->delete();
		
		$i = 0;
		foreach($request->value as $value ){
			$i++;
			$option_value = new OptionValue;
			$option_value->title = $value['title'];
			$option_value->sku = $value['sku'];
			$option_value->price = $value['price'];
			$option_value->price_type = $value['price_type'];
			$option_value->option_id = $option->id;
			$option_value->position = $i;
			$option_value->save();
		}

        return redirect()->route('admin.option')->with('success', 'Option successfully updated!');
    }
	
	
	public function delete( Request $request,$id){
        
        if(is_null($this->user) || !$this->user->can('customoption.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $option = Option::find($id);
		$option_value = OptionValue::where('option_id',$id);
		$option_value->delete();
        $option->delete();
        return redirect()->route('admin.option')->with('success', 'Option successfully deleted!');
    }
	
	public function ajax( Request $request){
		$id = $request->id;
		$option = Option::find($id);
		$otionValue = OptionValue::where('option_id',$id)->get();
		$result = [
			'option' => $option,
			'values'	=> $otionValue
		];
		return json_encode($result);
	}

    
}
