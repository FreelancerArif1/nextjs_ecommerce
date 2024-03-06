<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trash;
use App\Models\Admins;
use App\Models\Product;
use Yajra\DataTables\DataTables;
use Auth;

class TrashController extends Controller
{

    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    
    public function index(){
        if(is_null($this->user) || !$this->user->can('trash.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.others.trash');
    }


    public function getTrashList($start_date, $end_date){

        if(is_null($this->user) || !$this->user->can('blog.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if($start_date != 0 && $end_date != 0){
            $data = Trash::whereBetween('created_at', [$start_date, $end_date])->with('admin');
        }else{
            $data = Trash::orderBy('id', 'desc')->with('admin');
        }

        return Datatables::of($data)
        ->addIndexColumn()
       
        ->editColumn('created_at', function($row){
            return  date('d M, Y h:ia', strtotime($row->created_at));
        })

        ->addColumn('checkbox', function($row){
            return '<div class="form-check form-check-flat">
                        <label class="form-check-label">
                            <input name="select_all[]" type="checkbox"  class="form-check-input checkbox_single_select" value="'.$row->id.'"><i class="input-helper"></i>
                        </label>
                    </div>';
        })

        ->editColumn('deleted_by', function($row){
            $html = '';
            if($row->admin){
                $html.=  '<small><b>User:</b>'.$row->admin->name.'<br><b>Email:</b>'. $row->admin->email.'<br><b>Phone:</b>'.$row->admin->phone.'</small>';
            }

           if(Auth::user()->can('trash.view')){
                $dataHtml = '<table class="table"><tr><td>Field Name</td><td>Field Value</td></tr>';
                if($row->data){
                    foreach( json_decode($row->data) as $key=>$val){
                        
                        if(gettype($val) == 'array'){
                            $val = json_encode($val);
                        }

                        //Show Images

                        if($key == 'default_image' || $key == 'file_url' ){
                            $val = '<a href="/'.$val.'" target="_blank"><img style="width: 100px;border-radius: 0;height: 100px;object-fit: contain;" src="/'.$val.'"></a>';
                        }

                        if($key == 'gallery_images'){
                            $imageArray = explode(',',$val);
                            $val = '';
                            foreach($imageArray  as $img){
                                $val .= '<a href="/'.$img.'" target="_blank"><img style="width: 100px;border-radius: 0;height: 100px;object-fit: contain;" src="/'.$img.'"></a>';
                            }
                        }


                        $dataHtml.= '<tr><td>'.$key.'</td><td>'.$val.'</td></tr>';
                    }
                }

                $dataHtml .= '</table>';

                $html .= '<div class="modal fade trashModal" id="modal'.$row->id.'" tabindex="-1" aria-labelledby="modalLabel'.$row->id.'"  aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel'.$row->id.'" >Deleted Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">'.$dataHtml.'</div>
                    </div>
                </div>
                </div>';
            }

           return $html;
        })

    
        ->addColumn('action', function($row){
            $btn = '';
            
            if(Auth::user()->can('trash.edit')){
                $btn .= '<a title="Undo" class="icon_btn text-warning" href="'.route('admin.trash.undo.delete',$row->id).'"><i class="mdi mdi-undo-variant"></i></a>';
            }

            if(Auth::user()->can('trash.view')){
                $btn .= '<a title="Quick View" data-toggle="modal" data-target="#modal'.$row->id.'" class="icon_btn text-success" href="javascript:void(0)"><i class="mdi mdi-eye"></i></a>';
            }
            
            if(Auth::user()->can('trash.delete')){
                $btn = $btn.'<a title="Permanent Delete" class="icon_btn text-danger delete_btn" data-url="'.route('admin.trash.delete',$row->id).'" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
            }

            return $btn;
        })

        ->rawColumns(['title','checkbox','deleted_by','created_at','action'])->make(true);
    }


    public function undoDelete($id){
        if(is_null($this->user) || !$this->user->can('trash.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $trash = Trash::find($id);
        $modelArray = [
            'product' =>  'App\Models\Product',
            'category' => 'App\Models\Category',
            'brand'    => 'App\Models\Brand',
            'order'    => 'App\Models\Order',
            'admin'    => 'App\Models\Admins',
            'seller'   => 'App\Models\Admins',
            'customer' => 'App\Models\User',
            'blog'     => 'App\Models\Blog',
            'withdraw' => 'App\Models\WithdrawalRequest',
            'ticket' => 'App\Models\Ticket'
        ];

        if($trash){

            if($trash->type == 'media'){
                $update = \DB::table('concave_media')->where('id',$trash->type_id)->update(['is_deleted'=>0]);
            }else{
                $model = $modelArray[$trash->type];
                $update = $model::where('id',$trash->type_id)->update(['is_deleted'=>0]);
            }
            
            if($update){
                $trash->delete();
                return redirect()->route('admin.trash')->with('success', 'Trash item has been restored successfully!');
            }else{
                return redirect()->route('admin.trash')->with('failed', 'Unable to restore this item!');
            }
        }else{
            return redirect()->route('admin.trash')->with('failed', 'Trash item not found!');
        }

    }

    

    public function delete($id){
        if(is_null($this->user) || !$this->user->can('trash.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $trash = Trash::find($id);

        if($trash){
            $validateTrash = \Helper::validateAndDelete($trash->type,$trash->type_id);
            if($validateTrash['status']){
                $trash->delete();
                return redirect()->route('admin.trash')->with('success', 'Trash item has been deleted successfully!');
            }else{
                $message = $validateTrash['message'] ?? 'Unable to delete this item!';
                return redirect()->route('admin.trash')->with('failed', $message);
            }
        }else{
            return redirect()->route('admin.trash')->with('failed', 'Trash item not found!');
        }

    }


    public function bulkAction(Request $request){

            if(is_null($this->user) || !$this->user->can('trash.delete')){
                return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
            }

            if (empty($request->select_all)) {
                session()->flash('success', 'You have to select trash items!');
                return back();
            }


            if($request->action ==  "undo"){
                $i = 0;
                $modelArray = [
                    'product' =>  'App\Models\Product',
                    'category' => 'App\Models\Category',
                    'brand'    => 'App\Models\Brand',
                    'order'    => 'App\Models\Order',
                    'admin'    => 'App\Models\Admins',
                    'seller'   => 'App\Models\Admins',
                    'customer' => 'App\Models\User',
                    'blog'     => 'App\Models\Blog',
                    'withdraw' => 'App\Models\WithdrawalRequest'
                ];
                foreach($request->select_all as $id){
                    $trash = Trash::find($id);
                    if($trash){
                        if($trash->type == 'media'){
                            $update = \DB::table('concave_media')->where('id',$trash->type_id)->update(['is_deleted'=>0]);
                        }else{
                            $model = $modelArray[$trash->type];
                            $update = $model::where('id',$trash->type_id)->update(['is_deleted'=>0]);
                        }
                        if($update){
                            $res = $trash->delete();
                            if($res) $i++;
                        }
                    }
                }
                session()->flash('success', $i.' Trash Item(s) successfully restored !');
                return back();
            }

       
            if($request->action ==  "delete"){
                $i = 0;
                $notDeleted = [];
                foreach($request->select_all as $id){
                    $trash = Trash::find($id);
                    if($trash){
                        $validateTrash = \Helper::validateAndDelete($trash->type,$trash->type_id);
                        if($validateTrash['status']){
                            $res = $trash->delete();
                            if($res) $i++;
                        }else{
                            $notDeleted[] = $validateTrash['message'];
                        }
                    }
                }

                if($notDeleted){
                    $message = $i.' Trash item(s) successfully deleted !';
                    $message.= '<br> Following Error Occurred: '.implode('<br>',$notDeleted);
                    session()->flash('failed',$message);
                }else{
                    session()->flash('success', $i.' Trash item(s) successfully deleted !');
                }

                return back();
            }

    }


}
