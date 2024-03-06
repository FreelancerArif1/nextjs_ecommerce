<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payments;
use App\Models\UserSearch;
use App\Models\NewsletterSubscribtion;
use Auth;
use Helper;
use Yajra\DataTables\DataTables;

class MarketingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }


    public function bulk_message()
    {
        if (is_null($this->user) || !$this->user->can('marketing.bulk.message')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $customers = User::orderBy('id', 'desc')->get();
        return view('backend.pages.marketing.create-bulk-message', compact('customers'));
    }

    public function push_notification()
    {
        if (is_null($this->user) || !$this->user->can('marketing.push.notification')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $customers = User::orderBy('id', 'desc')->get();
        return view('backend.pages.marketing.create-push-notification', compact('customers'));
    }


    public function sendSms(Request $request)
    {
        if ($request->channel == 'sms') {
            $sms = $request->message_body;
            if (in_array('-1', $request->customer)) {
                // SEND SMS TO ALL USER 
                foreach (User::where('is_deleted', 0)->where('status', 1)->get() as $row) {
                    $smsReponse = Helper::sendSms($row->phone, $sms);
                }
                return redirect()->back()->with('success', 'SMS successfully sended!');
            } else {
                // SEND SMS TO SELECTED USERS 
                foreach (User::whereIn('id', $request->customer)->where('is_deleted', 0)->where('status', 1)->get() as $row) {
                    $smsReponse = Helper::sendSms($row->phone, $sms);
                }
                return redirect()->back()->with('success', 'SMS successfully sended!');
            }
        } else {
            $data = $request->email_body;
            $subject = $request->email_header;
            if (in_array('-1', $request->customer)) {
                // SEND Email TO ALL USER 
                foreach (User::where('is_deleted', 0)->where('status', 1)->get() as $row) {
                    $smsReponse = Helper::sendEmail($row->email, $subject, $data);
                }
                return redirect()->back()->with('success', 'Email successfully sended!');
            } else {
                // SEND Email TO SELECTED USERS 
                foreach (User::whereIn('id', $request->customer)->where('is_deleted', 0)->where('status', 1)->get() as $row) {
                    $smsReponse = Helper::sendEmail($row->email, $subject, $data);
                }
                return redirect()->back()->with('success', 'Email successfully sended!');
            }



            return redirect()->back()->with('success', 'email');
        }
    }



    public function sendPushNotification(Request $request){
        
        $title = $request->notification_title;
        if($request->image){
            $image = env('APP_API_URL').'/'.$request->image;
            $link = $request->link;
        }else{
            $image = null;
            $link = env('APP_API_URL');
        }

        $message = [
            'type' =>'genarel',
            'message' =>$request->message_body,
            ];

        $title = $request->notification_title;

        $message = [
            'type' => 'genarel',
            'message' => $request->message_body,
        ];

        if (in_array('-1', $request->customer)) {
            // SEND Notification TO ALL USER 
            foreach (User::where('is_deleted', 0)->where('status',1)->get() as $row) {
                Helper::sendPushNotification($row->id,1,$title,$request->message_body,json_encode($message),$image,$link);
            }
            return redirect()->back()->with('success', 'Notification successfully sended!');
        } else {
            // SEND Notification TO SELECTED USERS 
            foreach (User::whereIn('id',$request->customer)->where('is_deleted', 0)->where('status',1)->get() as $row) {
                Helper::sendPushNotification($row->id,1,$title,$request->message_body,json_encode($message),$image,$link);
            }
            return redirect()->back()->with('success', 'Notification successfully sended!');
        }
    }

    public function userSearchKeyword()
    {
        if (is_null($this->user) || !$this->user->can('marketing.user.search')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.marketing.user-search-keyword');
    }

    public function userSearchKeywordData()
    {

        $data = UserSearch::orderBy('created_at', 'desc');

        return Datatables::of($data->get())->addIndexColumn()

            ->editColumn('created_at', function ($row) {
                return date('d M, Y h:ia', strtotime($row->created_at));
            })

            ->editColumn('user_id', function ($row) {
                return ($row->user_id) ? $row->user_id : 'Geust';
            })

            ->editColumn('add_info1', function ($row) {
                return UserSearch::where('content', $row->content)->count();
            })

            ->rawColumns(['user_id', 'created_at', 'add_info1'])->make(true);
    }

    public function subscribers(){
        if (is_null($this->user) || !$this->user->can('marketing.subscriber')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.marketing.subscribers');
    }

    public function subscriberList(){

        $data = NewsletterSubscribtion::orderBy('created_at', 'desc');

        return Datatables::of($data->get())->addIndexColumn()

            ->editColumn('created_at', function ($row) {
                return date('d M, Y h:ia', strtotime($row->created_at));
            })

            ->editColumn('email', function ($row) {
                return $row->email ?? '';
            })

            ->addColumn('action', function ($row) {
                $btn = '';

                $btn = '<a class="icon_btn text-success"  href="#"><i class="mdi mdi-eye"></i></a>';
                return $btn;
            })

            ->rawColumns(['created_at', 'email', 'action'])->make(true);
    }
}