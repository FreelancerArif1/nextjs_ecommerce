<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\TicketPriority;
use App\Models\Admins;
use App\Models\User;
use App\Models\TicketDepartment;
use App\Models\TicketComments;

use Image;
use Auth;

class TicketsController extends Controller
{
    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }
   
    public function index(){
        if(is_null($this->user) || !$this->user->can('ticket.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.ticket.list');
    }

    public function getTicketList(){

        if(is_null($this->user) || !$this->user->can('ticket.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        if(Auth::user()->getRoleNames() == '["seller"]'){
            $data = Ticket::where('user_type', 'seller')->where('user_id', Auth::user()->id)->where('is_deleted',0);
        }else{
            $data = Ticket::where('is_deleted',0);
        }
        

        return Datatables::of($data)
        ->addIndexColumn()

        ->addColumn('checkbox', function($row){
            return '<div class="form-check form-check-flat">
                        <label class="form-check-label">
                            <input name="select_all[]" type="checkbox"  class="form-check-input checkbox_single_select" value="'.$row->id.'"><i class="input-helper"></i>
                        </label>
                    </div>';
        })

        ->editColumn('created_at', function($row){
            return  date('d M, Y h:ia', strtotime($row->created_at));
        })
       
        ->editColumn('status', function($row){
            return  '<span class="badge badge_'.$row->status.'">'.$row->status.'</span>';
        })

        ->editColumn('department_id', function($row){

            return  '<span class="badge badge_default text-white" style="background:'.$row->department->color.'">'.$row->department->title.'</span>';
        })

        ->editColumn('user_id', function($row){
            if($row->user_type == 'seller'){
                return  $row->seller->name.' - '.$row->seller->phone;
            }elseif($row->user_type == 'customer'){
                return  $row->customer->name.' - '.$row->customer->phone;
            }else{
                return '';
            }
            
        })


        ->editColumn('priority_id', function($row){
            return  '<span class="badge badge_default text-white" style="background:'.$row->priority->color.'">'.$row->priority->title.'</span>';
        })

        ->addColumn('action', function($row){
            $btn = '';

            if(Auth::user()->can('ticket.replay')){
                $btn = $btn.'<a class="icon_btn text-dark " href="'.route('admin.ticket.replay', $row->id).'"><i class="mdi mdi-reply"></i></a>';
            }

            // if(Auth::user()->can('ticket.view')){
            //     $btn = $btn.'<a class="icon_btn text-info ticket_quick_view_btn" href="" data-id="'.$row->id.'" ><i class="mdi mdi-eye"></i></a>';
            // }

            if(Auth::user()->can('ticket.edit')){
                $btn = $btn.'<a class="icon_btn text-success" href="'.route('admin.ticket.edit',$row->id).'"><i class="mdi mdi-pencil-box-outline"></i></a>';
            }
            
            if(Auth::user()->can('ticket.delete')){
                $btn = $btn.'<a class="icon_btn text-danger delete_btn" data-url="'.route('admin.ticket.delete',$row->id).'" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
            }

            return $btn;
        })

        ->rawColumns(['checkbox','title','department_id','created_at','priority_id','status','action'])->make(true);
    }

    

    public function create(){
        if(is_null($this->user) || !$this->user->can('ticket.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $priority = TicketPriority::where('status',1)->get();
        $department = TicketDepartment::where('status',1)->get();

        $vendors = Admins::select('id','name')->where('is_deleted',0)->where('status',1)->orderBy('name','asc')->get();
        $vendorArray = [];
        $adminArray = [];
        foreach($vendors as $vendor){
            if($vendor->hasRole('seller')){
                $vendorArray[] = $vendor;
            }else{
                $adminArray[] = $vendor;
            }
        }
        $customers = User::select('id','name')->where('is_deleted',0)->where('status',1)->where('is_deleted',0)->orderBy('name','asc')->get();


        return view('backend.pages.ticket.create',compact('priority','department','vendorArray','customers','adminArray'));
    }

    public function store( Request $request){
        if(is_null($this->user) || !$this->user->can('ticket.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'subject' => 'required|max:255',
            'priority_id' => 'required',
            'department_id' => 'required',
        ]);

        
        $ticket = new Ticket;
        $ticket->subject = $request->subject;
        $ticket->message = $request->message;
        $ticket->priority_id = $request->priority_id;

        if (Auth::user()->hasRole('seller')) {
            $ticket->user_type = 'seller';
            $ticket->user_id = Auth::user()->id;
            $ticket->status = 0;
        }else{
            if ($request->user_type == 'customer') {
                $ticket->user_id = $request->customer_id;
            }else{
                $ticket->user_id = $request->seller_id;
            }
            $ticket->status = $request->status;
        }
        
        $ticket->department_id = $request->department_id;
        $ticket->last_replay = 'user';
        
        $ticket->admin_ids = isset($request->admin_ids) ? implode(',', $request->admin_ids) : null;

        if($request->hasFile('attachment')){
            $uploadtedFiles = [];
            $allowedfileExtension=['pdf','jpg','JPG','jpeg','png','PNG','docx','doc','csv'];
            $files = $request->file('attachment');
            
            foreach($files as $file){

                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);
        
                if($check){
                    $fileName = round(microtime(true)).rand(1111,9999).'.'.$file->getClientOriginalExtension();
                    $location = public_path('uploads/tickets/');
                    $file->move($location, $fileName);
                    $uploadtedFiles [] = $fileName;
                }else{
                    return redirect()->back()->with('faild', 'Allowed file extentions are'.implode(',', $allowedfileExtension));
                }
            }

            $ticket->attachment = implode(',',$uploadtedFiles);
        }

        $ticket->save();

        return redirect()->route('admin.ticket')->with('success', 'Ticket successfully created!');
    }



    public function edit($id){
        if(is_null($this->user) || !$this->user->can('ticket.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        
        $priority = TicketPriority::where('status',1)->get();
        $department = TicketDepartment::where('status',1)->get();

        $vendors = Admins::select('id','name')->where('is_deleted',0)->where('status',1)->orderBy('name','asc')->get();
        $vendorArray = [];
        $adminArray = [];
        foreach($vendors as $vendor){
            if($vendor->hasRole('seller')){
                $vendorArray[] = $vendor;
            }else{
                $adminArray[] = $vendor;
            }
        }
        $customers = User::select('id','name')->where('is_deleted',0)->where('status',1)->where('is_deleted',0)->orderBy('name','asc')->get();


        $data = Ticket::findOrFail($id);
        return view('backend.pages.ticket.edit', compact('data','priority','department','vendorArray','customers','adminArray'));
    }



    public function update( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('ticket.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'subject' => 'required|max:255',
            'priority_id' => 'required',
            'department_id' => 'required',
        ]);

        
        $ticket = Ticket::findOrFail($id);
        $ticket->subject = $request->subject;
        $ticket->message = $request->message;
        $ticket->priority_id = $request->priority_id;

        if (Auth::user()->hasRole('seller')) {
            $ticket->user_type = 'seller';
            $ticket->user_id = Auth::user()->id;
            $ticket->status = 0;
        }else{
            if ($request->user_type == 'customer') {
                $ticket->user_id = $request->customer_id;
            }else{
                $ticket->user_id = $request->seller_id;
            }
            $ticket->status = $request->status;
        }

        $ticket->department_id = $request->department_id;
        $ticket->last_replay = 'user';

        $ticket->admin_ids = isset($request->admin_ids) ? implode(',', $request->admin_ids) : null;

        if($request->hasFile('attachment')){
            $uploadtedFiles = [];
            $allowedfileExtension=['pdf','jpg','JPG','jpeg','png','PNG','docx','doc','csv'];
            $files = $request->file('attachment');
            
            foreach($files as $file){

                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);
        
                if($check){
                    $fileName = round(microtime(true)).rand(1111,9999).'.'.$file->getClientOriginalExtension();
                    $location = public_path('uploads/tickets/');
                    $file->move($location, $fileName);
                    $uploadtedFiles [] = $fileName;
                    
                }else{
                    return redirect()->back()->with('faild', 'Allowed file extentions are'.implode(',', $allowedfileExtension));
                }
            }
            $ticket->attachment = implode(',',$uploadtedFiles);
        }

        $ticket->save();

        return redirect()->route('admin.ticket')->with('success', 'Ticket successfully updated!');
    }

    public function delete( Request $request,$id){
        if(is_null($this->user) || !$this->user->can('ticket.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $ticket = Ticket::find($id);

        //Insert Trash Data
        $type = 'ticket'; $type_id = $id; $reason = $request->reason ?? ''; $data = $ticket;
        \Helper::setTrashInfo($type,$type_id,$reason,$data);

        $ticket->is_deleted = 1;
        $ticket->save();

        return redirect()->route('admin.ticket')->with('success', 'Ticket successfully deleted!');
    }

    public function replay($id){

        $ticket = Ticket::find($id);
        return view('backend.pages.ticket.replay', compact('ticket'));
    }

    public function action(Request $request){

        if (empty($request->select_all)) {
            session()->flash('success', 'You have to select ticket!');
            return back();
        }

        if($request->action ==  "open"){
            foreach($request->select_all as $id){
                Ticket::where('id', $id)->update(['status' => 'open']);
            }
            session()->flash('success', 'Ticket successfully activated !');
            return back();
        }

        if($request->action ==  "close"){
            foreach($request->select_all as $id){
              Ticket::where('id', $id)->update(['status' => 'closed']);
            }
            session()->flash('success', 'Ticket successfully inctivated !');
            return back();
        }

        if($request->action ==  "delete"){
            foreach($request->select_all as $id){
                Ticket::where('id', $id)->update(['is_deleted' => 1]);
                $ticket = Ticket::find($id);
                //Insert Trash Data
                $type = 'ticket'; $type_id = $id; $reason = $request->reason ?? 'Bulk Delete'; $data = $ticket;
                \Helper::setTrashInfo($type,$type_id,$reason,$data);
            }
            session()->flash('success', 'Ticket successfully deleted !');
            return back();
        }
    }


    public function ticket_replay_store(Request $request){

        $ticket = Ticket::find($request->ticket_id);

        $allComments = TicketComments::where('ticket_id', $ticket->id)->where('status', 0)->get();
        if ($allComments) {
            foreach ($allComments as $row) {
                $row->status = 1;
                $row->save();
            }
        }

        $ticket_comment = new TicketComments();
        $ticket_comment->message = $request->message;
        $ticket_comment->user_type = $ticket->user_type;

        if($this->user->hasRole('seller')){
            $ticket_comment->user_id = $this->user->id;
        }else{
            $ticket_comment->admin_id = $this->user->id;
        }

        $ticket_comment->ticket_id = $request->ticket_id;
        $ticket_comment->status = 0;

        if($request->hasFile('image')){
            $uploadtedFiles = [];
            $allowedfileExtension=['pdf','jpg','JPG','jpeg','png','PNG','docx','doc','csv'];
            $files = $request->file('image');
            
            foreach($files as $file){
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension,$allowedfileExtension);
        
                if($check){
                    $fileName = round(microtime(true)).rand(1111,9999).'.'.$file->getClientOriginalExtension();
                    $location = public_path('uploads/tickets/');
                    $file->move($location, $fileName);
                    $uploadtedFiles [] = $fileName;
                }else{
                    $data['status'] = 0;
                    $data['message'] = 'Allowed file extentions are'.implode(',', $allowedfileExtension);
                    return response()->json($data, 200);
                }
            }

            $ticket_comment->attachment = implode(',',$uploadtedFiles);
        }

        $ticket_comment->save();

        $data['status'] = 1;
        $data['message'] = 'Replay submited!';
        return response()->json($data, 200);
    }


    public function getAllReplay($id){
        $ticket = Ticket::find($id);
        $ticket_replay = TicketComments::where('ticket_id', $id)->get();

        return view('backend.pages.ticket.message')->with(
            array(
                'ticket'=>$ticket,
                'ticket_replay'=>$ticket_replay,
            )
        );
    }

}