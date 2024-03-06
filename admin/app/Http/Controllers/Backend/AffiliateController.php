<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductMeta;
use App\Models\ProductSpecification;
use App\Models\Brand;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\ProductImage;
use App\Models\AttributeSets;
use App\Models\Admins;
use App\Models\ShopInfo;
use App\Models\ReturnRequest;
use App\Models\ProductRestockRequest;
use App\Models\Order;
use App\Models\Status;
use App\Models\OrderDetails;
use App\Models\ProductLocalization;
use App\Models\Affiliate;
use Yajra\DataTables\DataTables;
use Image;
use DB;
use Auth;
use Response;
use File;
use Helper;
use Artisan;
use Illuminate\Support\Str;

use App\Jobs\ProductFeedGenerat;
use App\Models\AffiliateWithdrawal;

class AffiliateController extends Controller
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
        if (is_null($this->user) || !$this->user->can('affiliate.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $affiliate = Product::orderBy('id', 'desc')->where('is_deleted', 0)->paginate(10);
        return view('backend.pages.affiliate.index')->with('affiliate', $affiliate);
    }


    public function affiliateList(Request $request)
    {

        $data = Affiliate::latest();
        if ($request->user_id > 0) {
            $data->where('user_id', $request->user_id);

            if ($request->filter_option == 'today') {
                $today = date("d");
                $data->whereDay('created_at', $today);
            } elseif ($request->filter_option == 'this month') {
                $today = date("m");
                $data->whereMonth('created_at', $today);
            } elseif ($request->filter_option == 'this year') {
                $today = date("Y");
                $data->whereYear('created_at', $today);
            } elseif ($request->filter_option == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            }
        } else {
            if ($request->filter_option == 'today') {
                $today = date("d");
                $data->whereDay('created_at', $today);
            } elseif ($request->filter_option == 'this month') {
                $today = date("m");
                $data->whereMonth('created_at', $today);
            } elseif ($request->filter_option == 'this year') {
                $today = date("Y");
                $data->whereYear('created_at', $today);
            } elseif ($request->filter_option == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            }
        }
        return Datatables::of($data)
            ->addIndexColumn()

            ->editColumn('created_at', function ($row) {
                return  date('d M, Y h:ia', strtotime($row->created_at));
            })

            ->editColumn('user_id', function ($row) {
                return  $row->user->name ?? '';
            })

            ->editColumn('buyer_id', function ($row) {
                return  $row->buyer->name ?? '';
            })

            ->editColumn('product_ids', function ($row) {
                $products = Product::select('title', 'slug')->whereIn('id', explode(',', $row->product_ids))->get();
                $product_html = '';
                foreach ($products as $product) {
                    $product_html .= $product->title . '<br>';
                }
                return $product_html;
            })

            ->editColumn('commission_amount', function ($row) {
                return  \Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->commission_amount ?? 0;
            })

            ->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    return  '<span class="badge badge-warning text-light">Pending</span>';
                } elseif ($row->status == 6) {
                    return  '<span class="badge badge-success text-light">Matured</span>';
                }
            })

            ->rawColumns(['created_at', 'user_id', 'buyer_id', 'product_ids', 'status'])->make(true);
    }

    public function affiliateChangeStatus($id)
    {
        $data = Affiliate::find($id);
        $data->status = 6;
        $data->save();

        return 'Successfully changed!';
    }

    public function affiliateWithdrawal()
    {
        return view('backend.pages.affiliate.withdrawalRequest');
    }

    public function getAffiliateWithdrawal(Request $request)
    {
        // dd($request->all());
        $data = AffiliateWithdrawal::latest();

        if ($request->user_id > 0) {
            $data->where('user_id', $request->user_id);

            if ($request->filter_option == 'today') {
                $today = date('Y-m-d');
                $data->whereDay('created_at', $today);
            } elseif ($request->filter_option == 'this month') {
                $today = date("m");
                $data->whereMonth('created_at', $today);
            } elseif ($request->filter_option == 'this year') {
                $today = date("Y");
                $data->whereYear('created_at', $today);
            } elseif ($request->filter_option == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            }
        } else {
            if ($request->filter_option == 'today') {
                $today = date('Y-m-d');
                $data->whereDay('created_at', $today);
            } elseif ($request->filter_option == 'this month') {
                $today = date("m");
                $data->whereMonth('created_at', $today);
            } elseif ($request->filter_option == 'this year') {
                $today = date("Y");
                $data->whereYear('created_at', $today);
            } elseif ($request->filter_option == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            }
        }


        return Datatables::of($data)->addIndexColumn()
            ->editColumn('amount', function ($row) {
                $amount = $row->amount;
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $amount;
            })
            ->editColumn('status', function ($row) {
                $btn_status = '';
                if ($row->status == 1) {
                    $btn_status = '<span class="badge badge-warning text-white">Pending</span>';
                    return $btn_status;
                } else if ($row->status == 6) {
                    $btn_status = '<span class="badge badge-success text-white">Settled</span>';
                    return $btn_status;
                }
            })
            ->editColumn('created_at', function ($row) {
                return  date('d M, Y h:ia', strtotime($row->created_at));
            })
            ->addColumn('username', function ($row) {
                return  $row->username->name ?? null;
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if ($row->status == 1) {
                    if (Auth::user()->can('affiliate.view')) {
                        $btn .= '<a title="Undo" class="btn btn-warning makeMaturedBtn" href="' . route('admin.affiliate.withdrawal.approve', $row->id) . '">Settle</a>';

                        // $btn = $btn . '<a class="btn btn-warning delete_btn" data-url="' . route('admin.affiliate.withdrawal.approve', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#">Settle</a>';

                        // $btn = $btn . '<a class="btn btn-warning" data-url="' . route('admin.affiliate.withdrawal.approve', $row->id) . '" data-toggle="modal" data-target="#changeModal" href="#">Settle</a>';
                    }
                }

                return $btn;
            })
            ->rawColumns(['amount', 'username', 'created_at', 'status', 'action'])->make(true);
    }

    public function withdrawalApprove(Request $request, $id)
    {
        $withdrawal = AffiliateWithdrawal::find($id);
        $withdrawal->note = $request->note ?? '';
        $withdrawal->status = 6;
        $withdrawal->save();
        return 'Successfully changed!';
    }
}