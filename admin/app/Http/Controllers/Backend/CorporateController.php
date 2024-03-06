<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CorporateRequestDetails;
use App\Models\CorporateRequests;
use App\Models\CorporateRequestNegotiations;
use App\Models\Product;
use App\Models\ShopInfo;
use App\Models\Status;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Auth;
use App\Models\User;

class CorporateController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    //Corporate Request Start
    public function corporateRequestIndex()
    {
        if (is_null($this->user) || !$this->user->can('corporate.request.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.corporate.corporate_request.index');
    }

    public function getCorporateRequestList()
    {
        $data = CorporateRequests::where('is_deleted', 0)
            ->orderBy('id','desc')
            ->with('statuses');

        return DataTables::of($data)->addIndexColumn()

            ->editColumn('deal_status', function ($row) {
                return  '<span class="badge text-light" style="background-color: ' . $row->statuses->color_code . ';">' . $row->statuses->title . '</span>';
            })

            ->editColumn('created_at', function ($row) {
                return date('d M, Y h:ia', strtotime($row->created_at));
            })

            ->editColumn('user_id', function ($row) {
                return $row->username->name ?? null;
            })

            ->editColumn('amount', function ($row) {
                
                return  \Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->amount;
            })

            ->editColumn('discount', function ($row) {
                return  \Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->discount;
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('corporate.request.view')) {
                    $btn = '<a class="icon_btn text-info" href="' . route('admin.corporate.request.view', $row->id) . '" ><i class="mdi mdi-eye"></i></a>';
                }

                if (Auth::user()->can('corporate.request.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.corporate.request.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['created_at','user_id','qty','amount','discount','deal_status', 'action'])->make(true);
    }

    public function corporateRequestView($request_id)
    {

        if (is_null($this->user) || !$this->user->can('corporate.request.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request = CorporateRequests::find($request_id);

        $products = CorporateRequestDetails::where('corporate_request_id',$request_id)->get();
       
        foreach ($products as $item) {
            $item->product = Product::find($item->product_id);
            $item->seller = ShopInfo::find($item->seller_id);
        }
        $negotiations = CorporateRequestNegotiations::where('corporate_request_id', $request_id)->with('username')->with('adminname')->get();

        $statuses = Status::where('delivery', 1)->get();

        return view('backend.pages.corporate.corporate_request.view', compact('request','products','statuses','negotiations'));
    }

    public function corporateRequestUpdate(Request $request)
    {
        $total_discount = 0;
        foreach ($request->request_id as $key => $value) {
            $total_discount += $request->discount[$key];
            $corporate_request = CorporateRequestDetails::find($value);
            $corporate_request->discount = $request->discount[$key];
            $corporate_request->save();
        }

        $quotation = CorporateRequests::find($request->quotation_id);
        $quotation->deal_status = $request->status;
        $quotation->preferable_date_for_delivery = $request->preferable_date_for_delivery;
        $quotation->delivery_date = $request->delivery_date;
        $quotation->shipping_cost = $request->shipping_cost;
        $quotation->payment_details = $request->payment_details;
        $quotation->discount = $total_discount;
        $quotation->payment_status = $request->payment_status;
        $quotation->save();

        if ($request->note) {
            $negotiations = new CorporateRequestNegotiations();
            $negotiations->corporate_request_id = $quotation->id;
            $negotiations->admin_id = Auth::user()->id;
            $negotiations->note = $request->note;
            $negotiations->save();
        }


        return redirect()->back()->with('success', 'Quatation updated successfully!');

    }

    public function corporateRequestDelete(Request $request, $id)
    {
        $corporate_request = CorporateRequests::find($id);

        //Insert Trash Data
        $type = 'Corporate request';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $corporate_request;
        \Helper::setTrashInfo($type, $type_id, $reason, $data);

        $corporate_request->is_deleted = 1;
        $corporate_request->save();

        return redirect()->back()->with('success', 'Corporate request successfully deleted!');
    }

    //Corporate Request End




    //Corporate Deal Start
    public function corporateDealIndex()
    {
        return view('backend.pages.corporate.corporate_deal.index');
    }

    public function getCorporateDealList()
    {
        $data = CorporateDealList::where('is_deleted', 0);
        return DataTables::of($data)->addIndexColumn()
            // ->editColumn('status', function ($row) {
            //     $text = '';
            //     if ($row->status == 1) {
            //         $text = '<p>Active</p>';
            //     } else if ($row->status == 2) {
            //         $text = '<p>Inactive</p>';
            //     }
            //     return $text;
            // })
            ->editColumn('user_name', function ($row) {
                $username = User::where('id', $row->user_id)->first();
                return $username->name ?? null;
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('corporate.deal.view')) {
                    $btn = '<a class="icon_btn text-info corporate_deal_quick_view_btn" href="" data-id="' . $row->id . '" ><i class="mdi mdi-eye"></i></a>';
                }
                // if (Auth::user()->can('career.edit')) {
                //     $btn = $btn . '<a class="icon_btn text-info" href="' . route('admin.corporate.request.edit', $row->id) . '"><i class="mdi mdi-playlist-edit"></i></a>';
                // }
                if (Auth::user()->can('corporate.deal.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.corporate.deal.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['user_name', 'action'])->make(true);
    }

    public function corporateDealView($id)
    {
        $corporate_deal = CorporateDealList::find($id);

        return view('backend.pages.corporate.corporate_deal.modal')->with(
            array(
                'corporate_deal' => $corporate_deal,
            )
        );
    }

    public function corporateDealDelete(Request $request, $id)
    {
        $corporate_deal = CorporateDealList::find($id);

        //Insert Trash Data
        $type = 'Corporate Deal';
        $type_id = $id;
        $reason = $request->reason ?? '';
        $data = $corporate_deal;
        \Helper::setTrashInfo($type, $type_id, $reason, $data);

        $corporate_deal->is_deleted = 1;
        $corporate_deal->save();

        return redirect()->back()->with('success', 'Corporate deal successfully deleted!');
    }




    //Corporate Deal End
}