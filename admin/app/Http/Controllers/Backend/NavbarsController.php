<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Navbar;
use App\Models\NavbarLocalization;
use Image;
use Auth;

class NavbarsController extends Controller
{

    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    
    public function index(){
        if(is_null($this->user) || !$this->user->can('navbar.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $navbar = Navbar::orderBy('sort_order','desc')->get();
        return view('backend.pages.navbar.list')->with('navbars',$navbar);
    }


    public function create(){
        if(is_null($this->user) || !$this->user->can('navbar.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $navbars = Navbar::orderBy('id','desc')->get();
        return view('backend.pages.navbar.create')->with('navbars',$navbars);
    }

    public function edit($id){
        if(is_null($this->user) || !$this->user->can('navbar.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $navbar = Navbar::find($id);
        $navbars = Navbar::orderBy('id','desc')->get();
        return view('backend.pages.navbar.edit')->with(
            array(
                'navbar'=>$navbar,
                'navbars'=> $navbars
            )
        );
    }


    public function store( Request $request){
        if(is_null($this->user) || !$this->user->can('navbar.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'nullable|max:190',
            'link' => 'nullable',
			'link_type' => 'nullable',
			//'sort_order' =>  'numeric',
			'status' =>  'max:2 | numeric'
        ]);

        $navbar = new Navbar;
        $navbar->title = $request->title;
        $navbar->link = $request->link;
        $navbar->link_type = $request->link_type;
		//$navbar->sort_order = $request->sort_order;
        $navbar->status = $request->status;
        $navbar->save();
        
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
            $navbar_localization = new NavbarLocalization();
            $navbar_localization->navbar_id = $navbar->id;
            $navbar_localization->title = $data['title'];
            $navbar_localization->lang_code = $data['lang_code'];
            $navbar_localization->save();
        }
        return redirect()->route('admin.navbar')->with('success', 'navbar successfully created!');
    }




    public function update( Request $request,$id){

        if(is_null($this->user) || !$this->user->can('navbar.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'nullable|max:190',
            'link' => 'nullable',
			'link_type' => 'nullable',
			'status' =>  'max:2 | numeric'
        ]);

        $navbar = Navbar::find($id);
        $navbar->title = $request->title;
        $navbar->link = $request->link;
        $navbar->link_type = $request->link_type;
        $navbar->status = $request->status;
        $navbar->save();

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

            $navbar_localization = navbarLocalization::where('navbar_id', $navbar->id)->where('lang_code',$data['lang_code'])->first();

            if ($navbar_localization !== null) {
                $navbar_localization->title = $data['title'];
                $navbar_localization->save();
            }else{
                $navbar_localization = new NavbarLocalization();
                $navbar_localization->navbar_id = $navbar->id;
                $navbar_localization->title = $data['title'];
                $navbar_localization->lang_code = $data['lang_code'];
                $navbar_localization->save();
            }
        }
        
        return redirect()->route('admin.navbar',$navbar->id)->with('success', 'navbar successfully updated!');
    }



    public function delete( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('navbar.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $navbar = Navbar::find($id);
		$image = 'uploads/images/navbars/'.$navbar->image;
		if (file_exists($image)) {
			unlink($image);
		}
        $navbar->delete();
        return redirect()->route('admin.navbar')->with('success', 'navbar successfully deleted!');
    }


    public function reorder(Request $request){
        $ids = $request->navbar_ids;
        $sort = count($ids);
        foreach ($ids as $key => $value) {
            $navbar = Navbar::find($value);
            $navbar->sort_order = $sort;
            $navbar->save();
            $sort--;
        }

        return redirect()->back()->with('success', 'navbar successfully re-ordered!');
    }

}
