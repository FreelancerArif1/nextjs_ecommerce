<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payments;
use App\Models\Status;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\WithdrawalRequest;
use App\Models\ShopInfo;
use App\Models\User;
use App\Models\Addresses;
use App\Models\ProductMeta;
use App\Models\ReturnRequest;
use App\Models\SellerAccountHistory;
use Yajra\DataTables\DataTables;
use App\Models\Category;
use App\Models\CourierCompany;
use App\Models\Pickpoints;
use Auth;
use DB;
use Helper;

use Carbon\Carbon;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }


    public function index()
    {
        if (is_null($this->user) || !$this->user->can('order.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        // $orders = Order::orderBy('id','desc')->with('statuses')->paginate(20);
        return view('backend.pages.order.list');
        // return view('backend.pages.order.saler-order-list');
    }

    public function promotional()
    {
        if (is_null($this->user) || !$this->user->can('order.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        // $orders = Order::orderBy('id','desc')->with('statuses')->paginate(20);
        return view('backend.pages.order.promotional-order');
        // return view('backend.pages.order.saler-order-list');
    }

    public function getOrderList($start_date, $end_date)
    {

        if (is_null($this->user) || !$this->user->can('order.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if (Auth::user()->getRoleNames() == '["seller"]') {
            $data = Order::select('orders.*', 'order_details.seller_id', 'order_details.is_promotion')->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.seller_id', Auth::user()->id)->with('statuses')->where('orders.is_deleted', 0)->orderBy('orders.id', 'desc');

            if ($start_date != 0 && $end_date != 0) {
                $data->whereBetween('orders.created_at', [$start_date, $end_date]);
            }
            $data->groupBy('orders.id')->with('statuses');
        } else {

            $data = Order::select('orders.*', 'order_details.seller_id', 'order_details.is_promotion')->join('order_details', 'orders.id', '=', 'order_details.order_id')->where('orders.is_deleted', 0);
            if ($start_date != 0 && $end_date != 0) {
                $data->whereBetween('orders.created_at', [$start_date, $end_date]);
            }
            $data->groupBy('orders.id')->with('statuses');
        }


        return Datatables::of($data)->addIndexColumn()

            ->setRowClass(function ($row) {
                if ($row->order_seen_status == 0) {
                    return 'bg-yellow-light';
                }
            })

            // ->setTransformer(function($row){
            //     return 'DR' . date('y', strtotime($row->created_at)) . $row->id;
            // })

            ->editColumn('id', function ($row) {
                return 'DR' . date('y', strtotime($row->created_at)) . $row->id;
                return $row->id;
            })

            ->editColumn('parent_order_id', function ($row) {
                if ($row->parent_order_id) {
                    return 'DR' . date('y', strtotime($row->created_at)) . $row->parent_order_id;
                } else {
                    return 'ONE TIME';
                }
            })

            ->editColumn('created_at', function ($row) {
                return date('d M, Y h:ia', strtotime($row->created_at));
            })

            ->addColumn('user_id', function ($row) {
                return $row->user->name;
            })


            ->addColumn('shipping_name', function ($row) {
                if ($row->is_pickpoint == 1) {
                    return optional($row->pickpoint_address)->title.''.optional($row->pickpoint_address)->phone;
                } else {
                    return optional($row->address)->shipping_first_name . ' ' . optional($row->address)->shipping_last_name.'<br>'.optional($row->address)->shipping_phone;
                }
            })

            ->addColumn('shipping_phone', function ($row) {
                if ($row->is_pickpoint == 1) {
                    return optional($row->pickpoint_address)->phone;
                } else {
                    return optional($row->address)->shipping_phone;
                }
            })


            ->addColumn('product_qty', function ($row) {
                if (Auth::user()->getRoleNames() == '["seller"]') {
                    $product_qty = 0;
                    foreach (OrderDetails::where('order_id', $row->id)->get() as $odtls) {
                        if ($odtls->seller_id == Auth::user()->id) {
                            $product_qty += $odtls->product_qty;
                        }
                    }
                    return $product_qty;
                } else {
                    return $row->order_details->sum('product_qty');
                }
            })

            ->editColumn('paid_amount', function ($row) {
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->paid_amount;
            })

            ->editColumn('total_amount', function ($row) {
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->total_amount;
            })

            ->editColumn('status', function ($row) {
                return  '<label class="badge text-light" style="background-color: ' . $row->statuses->color_code . ';">' . $row->statuses->title . '</label>';
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('order.view')) {
                    $btn = '<a class="icon_btn text-success" href="' . route('admin.order.show', $row->id) . '"><i class="mdi mdi-eye"></i></a>';
                }
                if (Auth::user()->can('order.edit')) {
                    $btn = $btn . '<a class="icon_btn text-info" href="' . route('admin.order.edit', $row->id) . '"><i class="mdi mdi-playlist-edit"></i></a>';
                }
                if (Auth::user()->can('order.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.order.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['created_at', 'user_id', 'shipping_name', 'shipping_phone', 'product_qty', 'paid_amount', 'total_amount', 'status', 'action'])->make(true);
    }

    public function getOrderPromotionalList($start_date, $end_date)
    {
        if (is_null($this->user) || !$this->user->can('order.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if (Auth::user()->getRoleNames() == '["seller"]') {
            $data = Order::select('orders.*', 'order_details.seller_id', 'order_details.is_promotion')->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.seller_id', Auth::user()->id)->with('statuses')->where('order_details.is_promotion', 1)->orderBy('orders.id', 'desc');

            if ($start_date != 0 && $end_date != 0) {
                $data->whereBetween('orders.created_at', [$start_date, $end_date]);
            }
            $data->groupBy('orders.id');
        } else {
            $data = Order::select('orders.*', 'order_details.seller_id', 'order_details.is_promotion')->join('order_details', 'orders.id', '=', 'order_details.order_id')->where('order_details.is_promotion', 1)->orderBy('id', 'desc');
            if ($start_date != 0 && $end_date != 0) {
                $data->whereBetween('orders.created_at', [$start_date, $end_date]);
            }

            $data->groupBy('orders.id')->with('statuses');
        }

        return Datatables::of($data->get())->addIndexColumn()

            ->addColumn('order_date', function ($row) {
                return $row->created_at->toDateString();
            })

            ->addColumn('user_name', function ($row) {
                return $row->user->name;
            })


            ->addColumn('shipping_name', function ($row) {
                return $row->address->shipping_first_name . ' ' . $row->address->shipping_last_name;
            })

            ->addColumn('shipping_phone', function ($row) {
                return $row->address->shipping_phone;
            })


            ->addColumn('product_qty', function ($row) {
                if (Auth::user()->getRoleNames() == '["seller"]') {
                    $product_qty = 0;
                    foreach (OrderDetails::where('order_id', $row->id)->get() as $odtls) {
                        if ($odtls->seller_id == Auth::user()->id) {
                            $product_qty += $odtls->product_qty;
                        }
                    }
                    return $product_qty;
                } else {
                    return $row->order_details->sum('product_qty');
                }
            })

            ->addColumn('paid_amount', function ($row) {
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->paid_amount;
            })

            ->addColumn('promotion', function ($row) {
                return ($row->is_promotion == 1) ? '<span class="badge badge-success text-light">Promotional</span>' : '<span class="badge badge-warning text-dark">Regular</span>';;
            })

            ->addColumn('status', function ($row) {
                return  '<label class="badge text-light" style="background-color: ' . $row->statuses->color_code . ';">' . $row->statuses->title . '</label>';
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('order.view')) {
                    $btn = '<a class="icon_btn text-success" href="' . route('admin.order.show', $row->id) . '"><i class="mdi mdi-eye"></i></a>';
                }
                if (Auth::user()->can('order.edit')) {
                    $btn = $btn . '<a class="icon_btn text-info" href="' . route('admin.order.edit', $row->id) . '"><i class="mdi mdi-playlist-edit"></i></a>';
                }
                if (Auth::user()->can('order.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.order.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['order_date', 'user_name', 'shipping_name', 'shipping_phone', 'product_qty', 'paid_amount', 'promotion', 'status', 'action'])->make(true);
    }



    function fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('query');
            // var_dump($search);
            if ($search) {
                $orders =  Order::orderBy('id', 'desc')
                    ->join('addresses', 'addresses.id', '=', 'orders.address_id')
                    ->where('orders.payment_id', $search)
                    ->orWhere('addresses.shipping_phone', 'LIKE', "%{$search}%")
                    ->orWhere('addresses.shipping_first_name', 'LIKE', "%{$search}%")
                    ->orWhere('orders.payment_method', 'LIKE', "%{$search}%")
                    ->orWhereDate('orders.created_at', '=', $search)
                    ->select('orders.*', 'addresses.shipping_first_name', 'addresses.shipping_phone')
                    ->with('statuses')->paginate(10);
            } else {
                $query = Order::orderBy('id', 'desc')->with('statuses');
                if ($request->pagination == 'All') {
                    $orders =  $query->get();
                } else {
                    $orders =  $query->paginate($request->pagination);
                }
            }
            return view('backend.pages.order.include.order-list-table')->with('orders', $orders);
        }
    }

    public function show($id)
    {
        if (is_null($this->user) || !$this->user->can('order.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $order = Order::find($id);
        $orderUpdate = Order::find($id);
        if ($orderUpdate) {
            $orderUpdate->order_seen_status = 1;
            $orderUpdate->save();
        }

        $order->refunded = optional($order->product_return_by_order_id)->sum('refund_amount');

        $order_log = DB::table('order_log')->where('order_id', $id)->get();
        return view('backend.pages.order.show', compact('order', 'order_log'));
    }



    public function update(Request $request)
    {
        $order_details = OrderDetails::find($request->id);
        $status = $request->status;

        $oldStatus = Helper::get_status_by_status_id($order_details->status)->title;
        $newStatus = Helper::get_status_by_status_id($status)->title;


        if (Auth::user()->getRoleNames() == '["seller"]' && $status == 6) {
            $status = 10;
        }


        $product = Product::find($order_details->product_id);

        $product_title = '';
        if ($product) {
            $product_title = $product->title;
        }


        $order_details->status = $status;
        $order_details->shipping_company_id = $request->shipping_company_id ?? null;
        $order_details->tracking_id = $request->tracking_id ?? null;

        $order_details->save();

        //Insert Order Log
        $additionalText = '';
        if ($request->shipping_company_id && $request->tracking_id) {
            $shippingCompany = CourierCompany::find($request->shipping_company_id);
            $additionalText = '(Courier: ' . $shippingCompany->title . ' , Tracking ID: ' . $request->tracking_id . ') ';
        }

        $generated_text = 'Order delivery status for product ' . $product_title . ' (ID: ' . $order_details->product_id . ') has been changed from ' . $oldStatus . ' to ' . $newStatus . ' ' . $additionalText . 'by ' . Auth::user()->name;
        Helper::setOrderLog($order_details->order_id, $order_details->id, $generated_text, Auth::id(), null);

        //Confirm Seller Balance and Send Notification to user
        if (Auth::user()->getRoleNames() != '["seller"]' && $status == 6) {

            $checkPendingMaturation = SellerAccountHistory::where('order_details_id', $order_details->id)->where('status', 1)->first();

            if ($checkPendingMaturation) {
                $checkPendingMaturation->status = 2;
                $checkPendingMaturation->save();
            }

            //Send Notification to user

            $user = User::where('id', $order_details->user_id)->first();
            if ($user) {
                $mobile = $user->phone;
                $msg = 'আপনার আর্ডার আইডি #'. 'DR' . date('y', strtotime($order_details->created_at)) . ' এর একটি পন্য সফলভাবে বিতরণ করা হয়েছে । অনুগ্রহ করে পন্যটির বিষয়ে আপনার মতামত দিন !';
                if ($mobile) {
                    \Helper::sendSms($mobile, $msg);
                }

                $message = [
                    'order_id' => 'DR'. date('y', strtotime($order_details->created_at)) . $order_details->order_id,
                    'type' => 'order',
                    'message' => $msg,
                ];

                \Helper::sendPushNotification($order_details->user_id, 1, 'Product Delivered Please Review', $msg, json_encode($message), null, null);
            }
        } elseif (Auth::user()->getRoleNames() != '["seller"]' && $status != 6) { //Matured to Pending Maturation
            $checkPendingMaturation = SellerAccountHistory::where('order_details_id', $order_details->id)->where('status', 2)->first();
            if ($checkPendingMaturation) {
                $checkPendingMaturation->status = 1;
                $checkPendingMaturation->save();
            }


            //Send Push Notification to User
            $user_id = Order::find($order_details->order_id)->user_id;
            $user = User::find($user_id);
            $message = [
                'order_id' => 'DR'. date('y', strtotime($order_details->created_at)) .$order_details->order_id,
                'type' => 'order',
                'message' => 'আপনার অর্ডার সফলভাবে আপডেট করা হয়েছে!',
            ];

            Helper::sendPushNotification($user_id, 1, 'Order Status', 'Order Status Changed!', json_encode($message), null, null);
            Helper::sendSms($user->phone, 'আপনার অর্ডার সফলভাবে আপডেট করা হয়েছে! অর্ডার আইডি '.'DR' . date('y', strtotime($order_details->created_at)) . $order_details->order_id);

            //Send Push Notification to Seller
            Helper::sendPushNotification($order_details->seller_id, 2, 'Order Status', 'Order Status Changed!', json_encode($message), null, null);
        }


        $order = Order::find($order_details->order_id);

        if ($order->payment_method == 'online_payment' && ($order->status == 2 || $order->status == 6) && $request->status == 5) {
            $returnrequest = new ReturnRequest();
            $returnrequest->user_id = $order_details->user_id;
            $returnrequest->order_id = $order_details->order_id;
            $returnrequest->order_details_id = $order_details->id;
            $returnrequest->product_id = $order_details->product_id;
            $returnrequest->seller_id = $order_details->seller_id;
            $returnrequest->return_type = 'refund';
            $returnrequest->refund_amount = 0;
            $returnrequest->description = 'Canceled';
            $returnrequest->save();
        } else {
            $returnrequest = ReturnRequest::where('user_id', $order_details->user_id)
                ->where('order_id', $order_details->order_id)
                ->where('order_details_id', $order_details->id)
                ->where('product_id', $order_details->product_id)
                ->where('seller_id', $order_details->seller_id)
                ->where('return_type', 'refund')
                ->first();
            if ($returnrequest) $returnrequest->delete();
        }

        //Product Increment 
        if (($order->payment_method == 'online_payment' && $order->status == 2) && ($request->status == 5 || $request->status == 8)) {
            \Helper::update_product_quantity($order_details->product_id, $order_details->product_qty, $order_details->product_options, 'addition');
        } else if (($order->payment_method == 'cash_on_delivery' && $order->status == 1) && ($request->status == 5 || $request->status == 8)) {
            \Helper::update_product_quantity($order_details->product_id, $order_details->product_qty, $order_details->product_options, 'addition');
        }

        return redirect()->back()->with('success', 'Order status successfully updated!');
    }


    public function destroy(Request $request, $id)
    {

        if (is_null($this->user) || !$this->user->can('order.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $order = Order::find($id);

        //Insert Trash Data
        $type = 'order';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $order;
        Helper::setTrashInfo($type, $type_id, $reason, $data);

        $order->is_deleted = 1;
        $order->save();


        return redirect()->route('admin.order')->with('success', 'Order successfully deleted!');
    }

    // ALL REPORTS

    public function product_refund_report(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if (isset($request->saller)) {
            $refunds = OrderDetails::where('status', 8)->where('seller_id', $request->saller)->paginate(10);
        } else {
            if ($this->user->getRoleNames() == '["seller"]') {
                $saller = $this->user->id;
                $refunds = OrderDetails::where('status', 8)->where('seller_id', $saller)->paginate(10);
            } else {
                $refunds = OrderDetails::where('status', 8)->paginate(10);
            }
        }

        return view('backend.pages.report.product-refund-request')->with('refunds', $refunds);
    }


    public function vendor_withdrawal_request(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.report.vendor-withdrawal-request');
    }

    public function get_vendor_withdrawal_request(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $data = WithdrawalRequest::where('is_deleted', 0);

        if ($this->user->getRoleNames() == '["seller"]') {
            $data->where('seller_id', $this->user->id);
        } else {
            if (($request->seller_id)) {
                $data->where('seller_id', $request->seller_id);
            }
            if ($request->filter_by == 'today') {
                $date = date('d');
                $data->whereDay('created_at', $date);
            } else if ($request->filter_by == 'this month') {
                $date = date('m');
                $data->whereMonth('created_at', $date);
            } else if ($request->filter_by == 'this year') {
                $date = date('Y');
                $data->whereYear('created_at', $date);
            } else if ($request->filter_by == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            }
            if ($request->status_id > 0) {
                $data->where('status', $request->status_id);
            }
        }


        return DataTables::of($data)->addIndexColumn()
            ->addColumn('seller_name', function ($row) {
                return $row->seller->name ?? null;
            })
            ->editColumn('requested_amount', function ($row) {
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->requested_amount ?? null;
            })
            ->editColumn('amount_to_pay', function ($row) {

                if ($row->amount_to_pay > 0) {
                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->amount_to_pay ?? 0;
                } else {
                    return $row->amount_to_pay ?? 0;
                }
            })
            ->editColumn('status', function ($row) {
                return '<span class="badge text-light p-1"
                                        style="background-color: ' . $row->statuses->color_code . ';">' . $row->statuses->title . '</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if ($row->status != 6) {
                    if (Auth::user()->can('report.edit')) {
                        $btn = '<a title="Preview " class="icon_btn text-success text-decoration-none apprived_btn" id=" ' . $row->id . '" href="#"><i class="mdi mdi-cash"></i></a>';
                    }
                    if (Auth::user()->can('report.delete')) {
                        $btn = $btn . '<a title="Delete Request" class="icon_btn text-danger delete_btn text-decoration-none" data-url="' . route('admin.withdrawal_request.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                    }
                }
                return $btn;
            })

            ->rawColumns(['seller_name', 'status', 'action'])->make(true);
    }


    public function vendor_withdrawal_request_destroy(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('report.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $withdrawalRequest = WithdrawalRequest::find($id);

        //Insert Trash Data
        $type = 'withdrawal';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $withdrawalRequest;
        Helper::setTrashInfo($type, $type_id, $reason, $data);

        $withdrawalRequest->is_deleted = 1;
        $withdrawalRequest->save();

        return redirect()->route('admin.vendor.withdrawal.request')->with('success', 'Withdrawal request successfully deleted!');
    }


    public function vendor_withdrawal_request_send(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $request->validate([
            'payment_method' => 'required',
            'requested_amount' => 'required | numeric | min:500',
        ]);

        if (Helper::get_seller_balance(Auth::user()->id) < $request->requested_amount) {
            return redirect()->back()->with('failed', 'You don\'t have enough balance to withdrawal!');
        } else {
            $date = date('Y-m-d');
            $withdrawal = new WithdrawalRequest();
            $withdrawal->date = $date;
            $withdrawal->seller_id = Auth::user()->id;
            $withdrawal->payment_method = $request->payment_method;
            $withdrawal->requested_amount = $request->requested_amount;
            $withdrawal->message = $request->message;
            $withdrawal->save();

            return redirect()->back()->with('success', 'Withdrawal request has been initiated successfully. Please wait for approval!');
        }
    }

    public function vendor_withdrawal_request_approved(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('currencies.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $withdrawal = WithdrawalRequest::find($request->id);
        if ($request->status == 6) {
            $withdrawal->amount_to_pay = $request->amount_to_pay;
        } else {
            $withdrawal->amount_to_pay = 0;
        }
        $withdrawal->status = $request->status;
        $withdrawal->accept_reject_msg = $request->accept_reject_msg;
        $withdrawal->save();
        return redirect()->route('admin.vendor.withdrawal.request')->with('success', 'Request Successfully Approved!');
    }

    public function sellerAccountHistory()
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        // $seller_pending_maturation_balance = \DB::table('seller_account_history')->where('status', 1)->sum('seller_amount');
        return view('backend.pages.report.seller-account-history', get_defined_vars());
    }


    public function getSellerAccountHistory($id)
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if (isset($id) && $id != 0) {
            $data = SellerAccountHistory::where('status', '!=', 0)->where('seller_id', $id)->orderby('id', 'desc')->get();
        } else {
            if ($this->user->getRoleNames() == '["seller"]') {
                $seller_id = $this->user->id;
                $data = SellerAccountHistory::where('status', '!=', 0)->where('seller_id', $seller_id)->orderby('id', 'desc')->get();
            } else {
                $data = SellerAccountHistory::where('status', '!=', 0)->orderby('id', 'desc')->get();
            }
        }


        return DataTables::of($data)->addIndexColumn()
            ->editColumn('order_id', function ($row) {
                return 'DR' . date('y', strtotime($row->created_at)) . $row->order_id;
            })
            ->addColumn('seller_pending_maturation_balance', function ($row) {
                $seller_pending_maturation_balance = $row->sum('seller_amount');
                return $seller_pending_maturation_balance;
            })
            ->addColumn('product', function ($row) {

                $product =  \DB::table('products')->where('id', $row->product_id)->first();
                $shop =  \DB::table('shop_info')->where('seller_id', $row->seller_id)->first();
                $result = '';
                $imageHtml = '';

                if ($product->default_image) {
                    $imageHtml = '<img class="list_img mr-3" src="' . asset('/' . $product->default_image) . '">';
                } else {
                    $imageHtml = '<img class="list_img mr-3" src="' . asset('uploads/images/default/no-image.png') . '">';
                }

                $result = '<div class="media">' . $imageHtml . '<div class="media-body"><p class="product_title"><a class="text-dark" target="_blank" href="' . env('APP_FRONTEND') . '/product/' . $product->slug . '">' . $product->title . '</p> <small><a target="_blank" href="/admin/seller/edit/' . $row->seller_id . '">' . $shop->name . '</a></small> </div>';
                return $result;
            })


            ->addColumn('commission', function ($row) {
                return 'BDT ' . $row->commission_amount . ' (<span style="color:#f00">' . $row->commission_percent . '%</span>)';
            })

            ->addColumn('price', function ($row) {
                return 'BDT ' . $row->price;
            })

            ->addColumn('seller_amount', function ($row) {
                return 'BDT ' . $row->seller_amount;
            })
            ->addColumn('updated_at', function ($row) {
                return date('dM, Y h:ia', strtotime($row->updated_at));
            })

            ->addColumn('status', function ($row) {
                $status = '';
                if ($row->status == 1) {
                    $status = '<span class="badge bg-warning">Pending Maturation</span>';
                } elseif ($row->status == 2) {
                    $status = '<span class="badge bg-success">Matured</span>';
                }
                return $status;
            })

            ->rawColumns(['seller_pending_maturation_balance', 'product', 'commission', 'status'])->make(true);
    }


    public function accountHistoryInitialData(Request $request)
    {
        $data = SellerAccountHistory::where('status', 1)->orderby('id', 'desc');

        if ($request->seller_id > 0) {
            $seller_pending_maturation_balance =  $data->where('seller_id', $request->seller_id)->sum('seller_amount');

            $refund_amount = \DB::table('product_return')->where('seller_id', $request->seller_id)->where('return_type', 'refund')->where('status',6)->sum('refund_amount');
            $seller_amount = \DB::table('seller_account_history')->where('seller_id', $request->seller_id)->where('status', 2)->sum('seller_amount');
            $seller_revenue = $seller_amount - $refund_amount;

            $seller_withdrawal_amount =  \DB::table('withdrawal_requests')->where('seller_id', $request->seller_id)->where('status', '!=', 5)->sum('requested_amount');

            // $sellerRevenue  = \DB::table('seller_account_history')->where('seller_id', $request->seller_id)->where('status', 2)->sum('seller_amount');
            // $withdrawalBalance = \DB::table('withdrawal_requests')->where('seller_id', $request->seller_id)->where('status', '=', 6)->sum('requested_amount');
            $seller_balance =  $seller_revenue - $seller_withdrawal_amount;
        } else {
            $seller_pending_maturation_balance = $data->sum('seller_amount');

            $refund_amount = \DB::table('product_return')->where('return_type', 'refund')->where('status',6)->sum('refund_amount');
            $seller_amount = \DB::table('seller_account_history')->where('status', 2)->sum('seller_amount');
            $seller_revenue = $seller_amount - $refund_amount;

            $seller_withdrawal_amount = \DB::table('withdrawal_requests')->where('status', '!=', 5)->sum('requested_amount');

            // $sellerRevenue  = \DB::table('seller_account_history')->where('status', 2)->sum('seller_amount');

            // $withdrawalBalance = \DB::table('withdrawal_requests')->where('status', '=', 6)->sum('requested_amount');
            $seller_balance =  $seller_revenue - $seller_withdrawal_amount;
        }

        $return['seller_pending_maturation_balance'] = $seller_pending_maturation_balance;
        $return['seller_revenue'] = $seller_revenue;
        $return['seller_withdrawal_amount'] = $seller_withdrawal_amount;
        $return['seller_balance'] = $seller_balance;
        return response()->json($return, 200);
    }



    public function editOrder($id)
    {
        if (is_null($this->user) || !$this->user->can('order.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $order = Order::find($id);


        $pickpoint_address = Pickpoints::where('status', 1)->with('division', 'district', 'upazila', 'union')->get();
        $user_address = Addresses::where('user_id', $order->user_id)->with('division', 'district', 'upazila', 'union')->get();

        $orderUpdate = Order::find($id);
        if ($orderUpdate) {
            $orderUpdate->order_seen_status = 1;
            $orderUpdate->save();
        }

        $products = Product::orderBy('id', 'desc')->where('is_deleted', 0)->get();
        return view('backend.pages.order.edit', compact('order', 'products', 'pickpoint_address', 'user_address'));
    }

    public function getOrderShippingCost($address_type,$address_id, $subtotal){
        $data = [];
        if ($address_type == 'pickpoint') {
            $pickpoint =  Pickpoints::select('discount_type', 'discount')->where('id', $address_id)->first();
            if ($pickpoint) {
                if ($pickpoint->discount_type == 1) { //Fixed
                    $data['shipping_cost'] = $pickpoint->discount;
                } else {
                    $data['shipping_cost'] =  ($subtotal * $pickpoint->discount) / 100;
                }
            }
            $data['status'] = 1;
        }

        return response()->json($data, 200);
    }

    public function productDetails($id, $customer_id, $address_id)
    {
        $product = Product::find($id);
        $data['product'] = $product;

        $data['product_price'] = \Helper::price_after_offer($product->id);

        $address = Addresses::find($address_id);

        $shipping_html = '';
        $variant_html = '';

        

        if ($product->product_type != 'digital' && $product->product_type != 'service' && $product->is_grocery != 'grocery'){
            $availableShippingOptions = \Helper::get_shipping_cost($customer_id, $product->seller_id, $product->id, $address->district_id);

            if ($availableShippingOptions['free_shipping'] == 'on') {
                $shipping_html .= '<label class="labl">
                    <input type="radio" name="shipping_option' . $product->id . '"  data-previous-value="0" class="shipping_option_list " value="0"  />
                    <small>BDT 0  </small>
                    <small>Free Shipping</small>
                </label><br>';
            }
            $shipping_html .= '<label class="labl">
                <input type="radio" name="shipping_option' . $product->id . '" class="shipping_option_list "  value="' . $availableShippingOptions['standard_shipping'] . '" data-previous-value="'. $availableShippingOptions['standard_shipping'].'" data-shippingmethod="standard_shipping" />
                <small>BDT ' . $availableShippingOptions['standard_shipping'] . '</small>
                <small>Standerd Shipping</small>
            </label><br>
            <label class="labl">
                <input type="radio" name="shipping_option' . $product->id . '" class="shipping_option_list" value="' . $availableShippingOptions['express_shipping'] . '" data-previous-value="'. $availableShippingOptions['express_shipping'] .'" data-shippingmethod="express_shipping"  />
                <small>BDT ' . $availableShippingOptions['express_shipping'] . '</small>
                <small>Express Shipping</small>
            </label>';
        }
        
        if ($product->product_type == 'variable') {

            $product_meta = ProductMeta::where('product_id', $product->id)->where('meta_key', 'custom_options')->first();
            $meta_value = unserialize($product_meta->meta_value);

            // var_dump($meta_value);exit();

            foreach ($meta_value as $key => $value) {
                if ($value['type'] == 'radio') {
                    $variant_html .= '
                        <div class="">
                            <h6 class=" mb-1">' . $value['title'] . ':
                            </h6>';
                    foreach ($value['value'] as $row) {
                        $variant_html .= '
                                    <label>

                                        <input type="radio" name="variable_option_' . $product->id . '[]" value="' . $value['title'] . ':' . $row['title'] . '"  data-aditionalprice="' . $row['price'] . '" class="variable_option" required>
                                        <span>' . $row['title'] . '</span>
                                    </label>';
                    }
                    $variant_html .= '
                        </div>';
                }

                if ($value['type'] == 'dropdown') {
                    $variant_html .= '
                        <div class="">
                            <h6 class="mb-1">
                                ' . $value['title'] . ':
                            </h6>
                            <select class="form-control variable_option" name="variable_option_' . $product->id . '[]" id="" required>
                                <option value="0">Select</option>';
                    foreach ($value['value'] as $row) {
                        $variant_html .= '<option data-aditionalprice="' . $row['price'] . '"  value="' . $value['title'] . ':' . $row['title'] . '">' . $row['title'] . '</option>';
                    }
                    $variant_html .= '
                            </select>
                        </div>';
                }
            }
        }

        $data['shipping_option'] = $shipping_html;
        $data['variable_option'] = $variant_html;

        return response()->json($data, 200);
    }


    public function updateOrder(Request $request)
    {

        $order = Order::find($request->order_id);

        // var_dump($request->product_id);
        if ($order) {
            $order->address_id = $request->address_id;
            $order->note = $request->order_note;
            $order->total_amount = $request->order_grand_total;
            $order->is_pickpoint = $request->is_pickpoint;
            $order->discount_amount = $request->order_discount;
            $order->payment_method = $request->payment_method;
            $order->shipping_cost = $request->order_shipping_cost;
            $order->grocery_shipping_cost = $request->order_grocery_shipping_cost;
            $order->status = $request->payment_status;
            $order->save();

            for ($j = 0; $j < count($request->qty); $j++) {
                if (!empty($request->order_details_id[$j])) {
                    $order_details_delete = OrderDetails::find($request->order_details_id[$j]);
                    // $order_details_delete->delete();
                }
            }
            for ($i = 0; $i < count($request->qty); $i++) {
                // var_dump($request->shipping_method[$i]);
                if ($request->product_id[$i]) {
                    $product = Product::find($request->product_id[$i]);

                    if (!empty($request->order_details_id[$i])) {
                        $order_details = OrderDetails::find($request->order_details_id[$i]);
                    } else {
                        $order_details = new OrderDetails();
                    }

                    $order_details->user_id = $order->user_id;
                    $order_details->order_id = $order->id;
                    $order_details->product_id = $request->product_id[$i];
                    $order_details->product_sku = $product->sku ?? null;
                    $order_details->product_qty = $request->qty[$i];

                    if ($product->product_type == 'variable') {
                        $variable = 'variable_option_' . $product->id;
                        $options = $request->$variable;
                        $string = array();
                        foreach ($options as $key => $value) {
                            $option = explode(':', $value);
                            $string[$option[0]] = $option[1];
                            // array_push($string,$value);
                        }
                        // echo (json_encode($arr = array('a' => 1, 'b' => 2)));
                        // echo (json_encode($string));

                        // $x = (object)$options;

                        $order_details->product_options = json_encode($string);
                    }
                    $order_details->shipping_method = $request->shipping_method[$i] ?? null;
                    $order_details->shipping_cost = $request->shipping_cost[$i];
                    $order_details->packaging_cost = $request->packaging_cost[$i];
                    $order_details->security_charge = $request->security_charge[$i];
                    $order_details->price = $request->price[$i];
                    $order_details->seller_id = $product->seller_id;
                    $order_details->loyalty_point = $product->loyalty_point ?? 0;
                    $order_details->save();
                }
            }
            // exit();
            return redirect()->route('admin.order')->with('success', 'Order updated!');
        } else {
            return redirect()->route('admin.order')->with('error', 'Order number not found!');
        }
    }
}