<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Admins;
use App\Models\Cart;
use App\Models\ShopInfo;
use App\Models\Status;
use App\Models\Wishlist;
use App\Models\CorporateRequests;
use App\Models\ReturnRequest;
use DataTables;
use Auth;
use DB;
use Helper;
use Carbon\Carbon;


class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function categoryWiseProduct()
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.report.category-wise-product-sale', get_defined_vars());
    }


    public function categoryWiseProductSale(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }


        $data = Category::with('products')->where('is_deleted', 0)->where('is_active', 1)->orderBy('title', 'ASC');


        return DataTables::of($data)->addIndexColumn()

            ->addColumn('product_stock_qty', function ($row) use ($request) {
                if (Auth::user()->getRoleNames() == '["seller"]') {
                    $product_stock_qty = Product::whereIn('category_id', [$row->id])->where('seller_id', $request->seller)->sum('qty');
                } else {
                    $product_stock_qty = Product::whereIn('category_id', [$row->id])->sum('qty');
                }
                return $product_stock_qty;
            })

            ->addColumn('no_of_sale', function ($row) use ($request) {
                if ($request->filter_by == 'today') {
                    $today = date("Y-m-d");
                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)->whereDate('order_details.created_at', $today)
                            ->sum('order_details.product_qty');
                    } else {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)->whereDate('order_details.created_at', $today)
                            ->sum('order_details.product_qty');
                    }
                    return $orders;
                } else if ($request->filter_by == 'this month') {
                    $month = date("Y-m-d");
                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)->whereMonth('order_details.created_at', $month)
                            ->sum('order_details.product_qty');
                    } else {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)->whereMonth('order_details.created_at', $month)
                            ->sum('order_details.product_qty');
                    }
                    return $orders;
                } else if ($request->filter_by == 'this year') {
                    $year = date("Y-m-d");
                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)->whereYear('order_details.created_at', $year)
                            ->sum('order_details.product_qty');
                    } else {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)->whereYear('order_details.created_at', $year)
                            ->sum('order_details.product_qty');
                    }
                    return $orders;
                } else if ($request->filter_by == 'date range') {
                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)->whereBetween('order_details.created_at', [$request->start_date, $request->end_date])
                            ->sum('order_details.product_qty');
                    } else {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)->whereBetween('order_details.created_at', [$request->start_date, $request->end_date])
                            ->sum('order_details.product_qty');
                    }
                    return $orders;
                } else {
                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)
                            ->sum('order_details.product_qty');
                    } else {
                        $orders = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)
                            ->sum('order_details.product_qty');
                    }
                    return $orders;
                }
            })
            ->addColumn('sale_amount', function ($row)  use ($request) {
                if ($request->filter_by == 'today') {
                    $today = date("Y-m-d");
                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)->whereDate('order_details.created_at', $today)
                            ->sum('order_details.price');
                    } else {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)->whereDate('order_details.created_at', $today)
                            ->sum('order_details.price');
                    }
                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                } else if ($request->filter_by == 'this month') {
                    $month = date("Y-m-d");
                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)->whereMonth('order_details.created_at', $month)
                            ->sum('order_details.price');
                    } else {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)->whereMonth('order_details.created_at', $month)
                            ->sum('order_details.price');
                    }

                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                } else if ($request->filter_by == 'this year') {
                    $year = date("Y-m-d");

                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)->whereYear('order_details.created_at', $year)
                            ->sum('order_details.price');
                    } else {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)->whereYear('order_details.created_at', $year)
                            ->sum('order_details.price');
                    }
                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                } else if ($request->filter_by == 'date range') {
                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)->whereBetween('order_details.created_at', [$request->start_date, $request->end_date])
                            ->sum('order_details.price');
                    } else {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)->whereBetween('order_details.created_at', [$request->start_date, $request->end_date])
                            ->sum('order_details.price');
                    }
                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                } else {
                    if (Auth::user()->getRoleNames() == '["seller"]') {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')->where('order_details.seller_id', $request->seller_id)
                            ->where('products.category_id', $row->id)
                            ->sum('order_details.price');
                    } else {
                        $sale_amount = OrderDetails::join('products', 'products.id', '=', 'order_details.product_id')
                            ->join('categories', 'categories.id', 'products.category_id')
                            ->where('products.category_id', $row->id)
                            ->sum('order_details.price');
                    }

                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                }
            })
            ->rawColumns(['product_stock_qty', 'no_of_sale'])->make(true);
    }


    public function sellerProductWise()
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.report.seller-product-wise-sale');
    }

    public function getSellerProductWise(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $vendors = Admins::with('shopinfo')->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        $vendorArray = [];
        foreach ($vendors as $vendor) {
            if ($vendor->hasRole('seller')) {
                $vendorArray[] = $vendor;
            }
        }
        $data = $vendorArray;

        return DataTables::of($data)->addIndexColumn()
            ->addColumn('seller_name', function ($row) {
                return $row->name ?? null;
            })
            ->addColumn('shop_name', function ($row) {
                return $row->shopinfo->name ?? null;
            })
            ->addColumn('product_stock_qty', function ($row) {
                $product_stock_qty = Product::where('seller_id', $row->id)->sum('qty');
                return $product_stock_qty;
            })
            ->addColumn('no_of_sale', function ($row) use ($request) {
                if ($request->filter_by == 'today') {
                    $today = date("d");
                    $orders = OrderDetails::where('seller_id', $row->id)->whereDay('order_details.created_at', $today)->sum('product_qty');
                    return $orders;
                } else if ($request->filter_by == 'this month') {
                    $month = date("Y-m-d");
                    $orders = OrderDetails::where('seller_id', $row->id)->whereMonth('order_details.created_at', $month)->sum('product_qty');
                    return $orders;
                } else if ($request->filter_by == 'this year') {
                    $year = date("Y-m-d");
                    $orders = OrderDetails::where('seller_id', $row->id)->whereYear('order_details.created_at', $year)->sum('product_qty');
                    return $orders;
                } else if ($request->filter_by == 'date range') {
                    $orders = OrderDetails::where('seller_id', $row->id)->whereBetween('created_at', [$request->start_date, $request->end_date])->sum('product_qty');
                    return $orders;
                } else {
                    $orders = OrderDetails::where('seller_id', $row->id)->sum('product_qty');
                    return $orders;
                }
            })
            ->addColumn('sale_amount', function ($row) use ($request) {
                if ($request->filter_by == 'today') {
                    $today = date("Y-m-d");
                    $sale_amount = OrderDetails::where('seller_id', $row->id)->whereDate('created_at', $today)->sum('price');
                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                } else if ($request->filter_by == 'this month') {
                    $month = date("Y-m-d");
                    $sale_amount = OrderDetails::where('seller_id', $row->id)->whereMonth('created_at', $month)->sum('price');
                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                } else if ($request->filter_by == 'this year') {
                    $year = date("Y-m-d");
                    $sale_amount = OrderDetails::where('seller_id', $row->id)->whereYear('created_at', $year)->sum('price');
                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                } else if ($request->filter_by == 'date range') {
                    $sale_amount = OrderDetails::where('seller_id', $row->id)->whereBetween('created_at', [$request->start_date, $request->end_date])->sum('price');
                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                } else {
                    $sale_amount = OrderDetails::where('seller_id', $row->id)
                        ->sum('price');
                    return Helper::getDefaultCurrency()->currency_symbol . ' ' . $sale_amount;
                }
            })
            ->rawColumns(['seller_name', 'shop_name', 'product_stock_qty', 'no_of_sale', 'image'])->make(true);
    }


    public function singleProductWise()
    {
        $carts = [];
        $wishlists = [];
        if (Auth::user()->getRoleNames() == '["seller"]') {
            $products = Product::where('is_deleted',0)->where('seller_id', Auth::user()->id)->get();
        }else{
            $products = Product::where('is_deleted',0)->get();
        }
        $total_amount = 0;
        return view('backend.pages.report.single-product-wise-sale', get_defined_vars());
    }


    public function getSingleProductWise(Request $request)
    {
        if (Auth::user()->getRoleNames() == '["seller"]') {
            $products = Product::where('is_deleted',0)->where('seller_id', Auth::user()->id)->get();
        }else{
            $products = Product::where('is_deleted',0)->get();
        }
        

        $filter = Product::where('id', $request->product_id)->first();
        $total_sold = OrderDetails::where('product_id', $request->product_id)->sum('product_qty');
        $total_amount = $total_sold *  $filter->price;
        $wishlists = Wishlist::where('product_id', $request->product_id)->get();
        $carts = Cart::where('product_id', $request->product_id)->get();
        $product_return = DB::table('product_return')->where('product_id', $request->product_id)->count();
        $total_refund = OrderDetails::where('product_id', $request->product_id)->where('status', 8)->sum('product_qty');
        
        return view('backend.pages.report.single-product-wise-sale', get_defined_vars());
    }
    public function saleConfirmStatusWise()
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $statuses = Status::get();
        return view('backend.pages.report.sale-confirm-status-wise', get_defined_vars());
    }


    public function getSaleConfirmStatusWise(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('order.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $data = Order::select('orders.*', 'order_details.seller_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id');

        if ($request->seller_id > 0) {
            $data->where('order_details.seller_id', $request->seller_id);
        }

        if ($request->filter_by == 'today') {
            $today = date('Y-m-d');
            $data->whereDay('orders.created_at', $today);
        } elseif ($request->filter_by == 'this month') {
            $today = date("m");
            $data->whereMonth('orders.created_at', $today);
        } elseif ($request->filter_by == 'this year') {
            $today = date("Y");
            $data->whereYear('orders.created_at', $today);
        } elseif ($request->filter_by == '7 day') {
            $today  = Carbon::today();
            $filter_option = Carbon::today()->subDays(7);

            $data->where('orders.created_at', '>=', $filter_option);
        } elseif ($request->filter_by == 'date range') {
            if ($request->start_date != 0 && $request->end_date != 0) {
                $data->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
            }
        }


        if ($request->status_id > 0) {
            $data->where('orders.status', $request->status_id);
        }

        $data->with('statuses')->with('user')->with('order_details')->orderBy('orders.id', 'desc')->groupBy('orders.id');

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('id', function ($row) use ($request) {
                return 'KB' . date('y', strtotime($row->created_at)) . $row->id;
            })

            ->addColumn('order_date', function ($row) {
                return date('d M, Y h:ia', strtotime($row->created_at));
            })

            ->addColumn('user', function ($row) {
                return $row->user->name;
            })

            ->addColumn('shipping_name', function ($row) {
                if ($row->is_pickpoint == 1) {
                    return $row->pickpoint_address->title ?? '';
                } else {
                    return $row->address->shipping_first_name . ' ' . $row->address->shipping_last_name;
                }
            })

            ->addColumn('shipping_phone', function ($row) {
                if ($row->is_pickpoint == 1) {
                    return $row->pickpoint_address->phone ?? '';
                } else {
                    return $row->address->shipping_phone;
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

            ->rawColumns(['order_date', 'user', 'shipping_name', 'shipping_phone', 'product_qty', 'paid_amount', 'promotion', 'status', 'action'])->make(true);
    }


    public function getSaleStatusWise(Request $request)
    {

        $data = Order::select('orders.*', 'order_details.seller_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id');

        if ($request->seller_id > 0) {
            $data->where('order_details.seller_id', $request->seller_id);
        }

        if ($request->filter_by == 'today') {
            $today = date('Y-m-d');
            $data->whereDay('orders.created_at', $today);
        } elseif ($request->filter_by == 'this month') {
            $today = date("m");
            $data->whereMonth('orders.created_at', $today);
        } elseif ($request->filter_by == 'this year') {
            $today = date("Y");
            $data->whereYear('orders.created_at', $today);
        } elseif ($request->filter_by == '7 day') {
            $today  = Carbon::today();
            $filter_option = Carbon::today()->subDays(7);

            $data->where('orders.created_at', '>=', $filter_option);
        } elseif ($request->filter_by == 'date range') {
            if ($request->start_date != 0 && $request->end_date != 0) {
                $data->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
            }
        }


        if ($request->status_id > 0) {
            $data->where('orders.status', $request->status_id);
        }



        $all_orders = $data->with('statuses')->with('user')->orderBy('orders.id', 'desc')->groupBy('orders.id')->get();

        $total_amount = 0;
        $total_order = 0;

        foreach ($all_orders as $row) {
            $total_amount = $total_amount + $row->total_amount;
            $total_order++;
        }

        $response['total_order_amount'] = \Helper::getDefaultCurrency()->currency_symbol . ' ' . $total_amount;
        $response['total_orders'] = $total_order;
        $response['status'] = Status::find($request->status_id)->title ?? 'Total';

        return response()->json($response, 200);
    }


    public function productWishlist()
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.report.product-wishlist', get_defined_vars());
    }

    public function getProductWishlist(Request $request)
    {


        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        // $data = Wishlist::get();
        if ($request->filter_by == 'today') {
            $today = date("Y-m-d");
            $data = Wishlist::whereDate('created_at', $today);
        } else if ($request->filter_by == 'this month') {
            $month = date("Y-m-d");
            $data = Wishlist::whereMonth('created_at', $month);
        } else if ($request->filter_by == 'this year') {
            $year = date("Y-m-d");
            $data = Wishlist::whereYear('created_at', $year);
        } else if ($request->filter_by == 'date range') {
            $data = Wishlist::whereBetween('created_at', [$request->start_date, $request->end_date]);
        } else {
            $data = Wishlist::all();
        }


        return DataTables::of($data)->addIndexColumn()
            ->addColumn('product_name', function ($row) {
                return $row->product->title ?? null;
            })
            ->addColumn('customer_name', function ($row) {
                return $row->user->name ?? null;
            })
            ->addColumn('customer_number', function ($row) {
                return $row->user->phone ?? null;
            })
            ->addColumn('price', function ($row) {
                $price =  $row->product->price ?? null;
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $price;
            })
            ->addColumn('product_type', function ($row) {
                return $row->product->product_type ?? null;
            })
            ->rawColumns(['product_name', 'customer_name', 'customer_number', 'price', 'product_type'])->make(true);
    }


    public function topSoldProductReport(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if (isset($request->saller)) {
            $products = Product::where('is_deleted', 0)->where('seller_id', $request->saller)->orderby('qty', 'desc')->paginate(20);
        } else {
            if ($this->user->getRoleNames() == '["seller"]') {
                $saller = $this->user->id;
                $products = Product::where('is_deleted', 0)->where('seller_id', $saller)->orderby('qty', 'desc')->paginate(20);
            } else {
                $products = Product::where('is_deleted', 0)->orderby('qty', 'desc')->paginate(20);
            }
        }
        return view('backend.pages.report.top-sold-products-report', get_defined_vars());
    }

    public function getTopSoldProductReport(Request $request)
    {
        // dd('fsfs');
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $data = OrderDetails::with('product')->where('status', 6);
        // dd($data);

        if ($this->user->getRoleNames() == '["seller"]') {
            $seller_id = $this->user->id;
            $data->where('seller_id', $seller_id);
        }

        if ($request->seller_id > 0) {
            $data->where('seller_id', $request->seller_id);
        }

        if ($request->filter_by == 'today') {
            $date = date('d');
            $data->whereDay('created_at', $date);
        } elseif ($request->filter_by == 'this month') {
            $date = date("m");
            $data->whereMonth('created_at', $date);
        } elseif ($request->filter_by == 'this year') {
            $date = date("Y");
            $data->whereYear('created_at', $date);
        } elseif ($request->filter_by == 'date range') {
            if ($request->start_date != 0 && $request->end_date != 0) {
                $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
        }

        $data->groupBy('product_id');

        return DataTables::of($data)->addIndexColumn()
            ->addColumn('image', function ($row) {
                $image = '';
                $imageHtml = '';

                if ($row->product->default_image) {
                    $imageHtml = '<img class="list_img mr-3" src="' . asset('/' . $row->product->default_image) . '">';
                } else {
                    $imageHtml = '<img class="list_img mr-3" src="' . asset('uploads/images/default/no-image.png') . '">';
                }

                $image = '<div class="media">' . $imageHtml . '<div class="media-body"><p class="product_title">' . $row->product->title . '</p></div>';
                return $image;
            })

            ->addColumn('no_of_sale', function ($row) {
                $total_qty = $row->where('product_id', $row->product->id)->where('status', 6)->sum('product_qty');
                return $total_qty;
            })
            ->addColumn('category_title', function ($row) {
                return $row->product->category_title ?? '';
            })
            ->addColumn('brand_title', function ($row) {
                return $row->product->brand_title ?? '';
            })
            ->addColumn('qty', function ($row) {
                return $row->product->qty ?? '';
            })
            ->addColumn('sale_amount', function ($row) {
                $noOfSale = $row->where('product_id', $row->product->id)->where('status', 6)->sum('product_qty');
                $total_price = $noOfSale * $row->product->price ?? 0;
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $total_price;
            })
            ->rawColumns(['no_of_sale', 'category_title', 'brand_title', 'image', 'qty', 'sale_amount'])->make(true);
    }


    public function sendMessage($id)
    {
        if (is_null($this->user) || !$this->user->can('marketing.bulk.message')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $customers = User::orderBy('id', 'desc')->get();
        return view('backend.pages.report.send-message', get_defined_vars());
    }

    public function smsSend(Request $request)
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

    public function sendNotification($id)
    {
        if (is_null($this->user) || !$this->user->can('marketing.push.notification')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $customers = User::orderBy('id', 'desc')->get();
        return view('backend.pages.report.send-push-notification', get_defined_vars());
    }

    public function pushNotificationSend(Request $request)
    {
        $title = $request->notification_title;

        $message = [
            'type' => 'genarel',
            'message' => $request->message_body,
        ];

        if (in_array('-1', $request->customer)) {
            // SEND Notification TO ALL USER
            foreach (User::where('is_deleted', 0)->where('status', 1)->get() as $row) {
                // Helper::sendSms($row->phone,$sms);

                Helper::sendPushNotification($row->id, 1, $title, $request->message_body, json_encode($message));
            }
            return redirect()->back()->with('success', 'Notification successfully sended!');
        } else {
            // SEND Notification TO SELECTED USERS
            foreach (User::whereIn('id', $request->customer)->where('is_deleted', 0)->where('status', 1)->get() as $row) {

                Helper::sendPushNotification($row->id, 1, $title, $request->message_body, json_encode($message));
            }
            return redirect()->back()->with('success', 'Notification successfully sended!');
        }
    }

    public function couponUsesReport()
    {
        if (is_null($this->user) || !$this->user->can('report.coupon.uses')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.report.coupon-uses-report');
    }

    public function couponUsesReportList()
    {

        $data = Order::where('is_deleted', 0)->whereNotNull('coupon_code');

        return DataTables::of($data)->addIndexColumn()

            ->editColumn('order_id', function ($row) {
                return 'KB' . date('y', strtotime($row->created_at)) . $row->id;
            })

            ->editColumn('created_at', function ($row) {
                return date('d M, Y h:ia', strtotime($row->created_at));
            })

            ->addColumn('user_id', function ($row) {
                return $row->user->name;
            })


            ->editColumn('paid_amount', function ($row) {
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->paid_amount;
            })

            ->editColumn('status', function ($row) {
                return  '<label class="badge text-light" style="background-color: ' . $row->statuses->color_code . ';">' . $row->statuses->title . '</label>';
            })

            ->rawColumns(['order_id', 'created_at', 'user_id', 'paid_amount', 'status'])->make(true);
    }

    // corporate report
    public function corporateSalesReport()
    {
        if (is_null($this->user) || !$this->user->can('report.corporate.sale')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.report.corporate-sale-reports');
    }

    public function corporateSalesReportList(Request $request)
    {
        $data = CorporateRequests::where('is_deleted', 0)
            ->orderBy('id', 'desc');

        if ($request->user_id > 0) {
            $data->where('user_id', $request->user_id);
        }
        if ($request->filter_option == 'today') {
            $date = date('Y-m-d');
            $data->whereDate('created_at', $date);
        }
        if ($request->filter_option == '7 day') {
            $date = Carbon::today()->subDays(7);
            $data->where('created_at', '>=', $date);
        }
        if ($request->filter_option == 'this month') {
            $date = date('m');
            $data->whereMonth('created_at', $date);
        }
        if ($request->filter_option == 'this year') {
            $date = date('Y');
            $data->whereYear('created_at', $date);
        }

        if ($request->filter_option == 'date range') {
            if ($request->start_date && $request->end_date) {
                $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
        }
        $data->with('statuses');


        return DataTables::of($data->get())->addIndexColumn()

            ->editColumn('payment_status', function ($row) {
                return  '<label class="badge text-light" style="background-color: ' . $row->statuses->color_code . ';">' . $row->statuses->title . '</label>';
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

            ->rawColumns(['created_at', 'user_id', 'qty', 'amount', 'discount', 'payment_status', 'action'])->make(true);
    }


    public function corporateSalesReportInitialData(Request $request)
    {
        $data = CorporateRequests::where('is_deleted', 0);

        if ($request->user_id > 0) {
            $data->where('user_id', $request->user_id);
        }
        if ($request->filter_option == 'today') {
            $date = date('Y-m-d');
            $data->whereDate('created_at', $date);
        }
        if ($request->filter_option == '7 day') {
            $date = Carbon::today()->subDays(7);
            $data->where('created_at', '>=', $date);
        }
        if ($request->filter_option == 'this month') {
            $date = date('m');
            $data->whereMonth('created_at', $date);
        }
        if ($request->filter_option == 'this year') {
            $date = date('Y');
            $data->whereYear('created_at', $date);
        }

        if ($request->filter_option == 'date range') {
            if ($request->start_date && $request->end_date) {
                $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
        }

        // $data->get();

        // var_dump($data->get());
        // exit();

        $total_sale = 0;
        $product_qty = 0;
        $sale_amount = 0;
        $sale_discount = 0;
        $paid_amount = 0;
        $pending_amount = 0;

        foreach ($data->get() as $row) {
            $total_sale++;
            $product_qty += $row->qty;
            $sale_amount += $row->amount;
            $sale_discount += $row->discount;
            $paid_amount += $row->payment_amount;
        }

        $pending_amount = ($sale_amount - $sale_discount) - $pending_amount;

        $return['total_sale'] = $total_sale;
        $return['product_qty'] = $product_qty;
        $return['sale_amount'] = $sale_amount;
        $return['sale_discount'] = $sale_discount;
        $return['paid_amount'] = $paid_amount;
        $return['pending_amount'] = $pending_amount;
        return response()->json($return, 200);
    }


    // get sales report

    public function sale_report()
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.report.sale-report');
    }

    public function getSalesReport(Request $request)
    {

        $data = Order::select('orders.*', 'order_details.seller_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id');

        if ($request->seller_id > 0) {

            $data->where('order_details.seller_id', $request->seller_id);

            if ($request->filter_by == 'today') {
                $today = date('Y-m-d');
                $data->whereDay('orders.created_at', $today);
            } elseif ($request->filter_by == 'this month') {
                $today = date("m");
                $data->whereMonth('orders.created_at', $today);
            } elseif ($request->filter_by == 'this year') {
                $today = date("Y");
                $data->whereYear('orders.created_at', $today);
            } elseif ($request->filter_by == '7 day') {
                $today  = Carbon::today();
                $filter_option = Carbon::today()->subDays(7);

                $data->where('orders.created_at', '>=', $filter_option);
            } elseif ($request->filter_by == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $data->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
                }
            }
        } else {
            if ($request->filter_by == 'today') {
                $today = date('Y-m-d');
                $data->whereDay('orders.created_at', $today);
            } elseif ($request->filter_by == 'this month') {
                $today = date("m");
                $data->whereMonth('orders.created_at', $today);
            } elseif ($request->filter_by == 'this year') {
                $today = date("Y");
                $data->whereYear('orders.created_at', $today);
            } elseif ($request->filter_by == '7 day') {
                $today  = Carbon::today();
                $filter_option = Carbon::today()->subDays(7);

                $data->where('orders.created_at', '>=', $filter_option);
            } elseif ($request->filter_by == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $data->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
                }
            }
        }

        if ($request->status_id > 0) {
            $data->where('orders.status', $request->status_id);
        }

        $data->with('statuses')->with('user')->with('order_details')->orderBy('orders.id', 'desc')->groupBy('orders.id');

        return DataTables::of($data)->addIndexColumn()
            ->editColumn('id', function ($row) {
                return 'KB' . date('y', strtotime($row->created_at)) . $row->id;
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y-m-d');
            })

            ->addColumn('shipping_name', function ($row) {
                if ($row->is_pickpoint == 1) {
                    $shipping_name = $row->pickpoint_address->title;
                } else {
                    $shipping_name = $row->address->shipping_first_name . '' . $row->address->shipping_last_name;
                }
                return $shipping_name ?? null;
            })

            ->addColumn('billing_phone', function ($row) {
                if ($row->is_pickpoint == 1) {

                    $billing_phone = $row->pickpoint_address->phone;
                } else {
                    $billing_phone = $row->address->shipping_phone;
                }

                return $billing_phone ?? null;
            })

            ->addColumn('quantity', function ($row) {
                return $row->order_details->sum('product_qty') ?? null;
            })

            ->editColumn('total_amount', function ($row) {
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->total_amount;
            })

            ->editColumn('status', function ($row) {
                return '<label class="badge text-light" style="background-color: ' . $row->statuses->color_code . '">' . $row->statuses->title . '</label>';
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('order.view')) {
                    $btn = '<a class="text-success" href="' . route('admin.order.show', $row->id) . '"><i class="mdi mdi-eye"></i></a>';
                }

                // if (Auth::user()->can('order.delete')) {
                //     $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.order.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';;
                // }
                return $btn;
            })

            ->rawColumns(['created_at', 'shipping_name', 'billing_phone', 'quantity', 'total_amount', 'status', 'action'])->make(true);
    }











    public function filter_sale_report(Request $request)
    {

        $all_order_amount = Order::select('orders.*', 'order_details.seller_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id');


        $total_orders = Order::select('orders.*', 'order_details.seller_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id');

        $total_product_returned = ReturnRequest::orderBy('id', 'desc');

        $total_product_ordered = OrderDetails::orderBy('id', 'desc');

        $total_ordered_customer = OrderDetails::select('created_at','seller_id')->groupBy('user_id');

        $total_product_exchanged = DB::table('product_return');
        $total_product_refunded = DB::table('product_return');
        $refunded_amount = DB::table('product_return');

        if ($request->seller_id > 0) {

            $all_order_amount->where('order_details.seller_id', $request->seller_id);
            $total_orders->where('order_details.seller_id', $request->seller_id);
            $total_product_returned->where('seller_id', $request->seller_id);
            $total_product_ordered->where('seller_id', $request->seller_id);
            $total_ordered_customer->where('seller_id', $request->seller_id);

            $total_product_exchanged = DB::table('product_return')->where('seller_id', $request->seller_id);
            $total_product_refunded = DB::table('product_return')->where('seller_id', $request->seller_id);
            $refunded_amount = DB::table('product_return')->where('seller_id', $request->seller_id);

            if ($request->filter_by == 'today') {
                $today = date('Y-m-d');

                $all_order_amount->whereDay('orders.created_at', $today);
                $total_orders->whereDay('orders.created_at', $today);

                $total_product_returned->whereDay('created_at', $today);
                $total_product_ordered->whereDay('created_at', $today);
                $total_ordered_customer->whereDay('created_at', $today);

                $total_product_exchanged = DB::table('product_return')->whereDay('created_at', $today);
                $total_product_refunded = DB::table('product_return')->whereDay('created_at', $today);
                $refunded_amount = DB::table('product_return')->whereDay('created_at', $today);
            } elseif ($request->filter_by == 'this month') {
                $today = date("m");

                $all_order_amount->whereMonth('orders.created_at', $today);
                $total_orders->whereMonth('orders.created_at', $today);

                $total_product_returned->whereMonth('created_at', $today);
                $total_product_ordered->whereMonth('created_at', $today);
                $total_ordered_customer->whereMonth('created_at', $today);

                $total_product_exchanged = DB::table('product_return')->whereMonth('created_at', $today);
                $total_product_refunded = DB::table('product_return')->whereMonth('created_at', $today);
                $refunded_amount = DB::table('product_return')->whereMonth('created_at', $today);
            } elseif ($request->filter_by == 'this year') {
                $today = date("Y");

                $all_order_amount->whereYear('orders.created_at', $today);
                $total_orders->whereYear('orders.created_at', $today);

                $total_product_returned->whereYear('created_at', $today);
                $total_product_ordered->whereYear('created_at', $today);
                $total_ordered_customer->whereYear('created_at', $today);

                $total_product_exchanged = DB::table('product_return')->whereYear('created_at', $today);
                $total_product_refunded = DB::table('product_return')->whereYear('created_at', $today);
                $refunded_amount = DB::table('product_return')->whereYear('created_at', $today);
            } elseif ($request->filter_by == '7 day') {
                $today  = Carbon::today();
                $filter_option = Carbon::today()->subDays(7);

                $all_order_amount->where('orders.created_at', '>=', $filter_option);
                $total_orders->where('orders.created_at', '>=', $filter_option);

                $total_product_returned->where('created_at', '>=', $filter_option);
                $total_product_ordered->where('created_at', '>=', $filter_option);
                $total_ordered_customer->where('created_at', '>=', $filter_option);

                $total_product_exchanged = DB::table('product_return')->where('created_at', '>=', $filter_option);
                $total_product_refunded = DB::table('product_return')->where('created_at', '>=', $filter_option);
                $refunded_amount = DB::table('product_return')->where('created_at', '>=', $filter_option);
            } elseif ($request->filter_by == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {
                    $all_order_amount->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
                    $total_orders->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);

                    $total_product_returned->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $total_product_ordered->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $total_ordered_customer->whereBetween('created_at', [$request->start_date, $request->end_date]);

                    $total_product_exchanged = DB::table('product_return')->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $total_product_refunded = DB::table('product_return')->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $refunded_amount = DB::table('product_return')->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            }
        } else {
            if ($request->filter_by == 'today') {
                $today = date('Y-m-d');

                $all_order_amount->whereDay('orders.created_at', $today);
                $total_orders->whereDay('orders.created_at', $today);

                $total_product_returned->whereDay('created_at', $today);
                $total_product_ordered->whereDay('created_at', $today);
                $total_ordered_customer->whereDay('created_at', $today);

                $total_product_exchanged = DB::table('product_return')->whereDay('created_at', $today);
                $total_product_refunded = DB::table('product_return')->whereDay('created_at', $today);
                $refunded_amount = DB::table('product_return')->whereDay('created_at', $today);
            } elseif ($request->filter_by == 'this month') {
                $today = date("m");

                $all_order_amount->whereMonth('orders.created_at', $today);
                $total_orders->whereMonth('orders.created_at', $today);

                $total_product_returned->whereMonth('created_at', $today);
                $total_product_ordered->whereMonth('created_at', $today);
                $total_ordered_customer->whereMonth('created_at', $today);

                $total_product_exchanged = DB::table('product_return')->whereMonth('created_at', $today);
                $total_product_refunded = DB::table('product_return')->whereMonth('created_at', $today);
                $refunded_amount = DB::table('product_return')->whereMonth('created_at', $today);
            } elseif ($request->filter_by == 'this year') {
                $today = date("Y");

                $all_order_amount->whereYear('orders.created_at', $today);
                $total_orders->whereYear('orders.created_at', $today);

                $total_product_returned->whereYear('created_at', $today);
                $total_product_ordered->whereYear('created_at', $today);
                $total_ordered_customer->whereYear('created_at', $today);

                $total_product_exchanged = DB::table('product_return')->whereYear('created_at', $today);
                $total_product_refunded = DB::table('product_return')->whereYear('created_at', $today);
                $refunded_amount = DB::table('product_return')->whereYear('created_at', $today);
            } elseif ($request->filter_by == '7 day') {
                $today  = Carbon::today();
                $filter_option = Carbon::today()->subDays(7);

                $all_order_amount->where('orders.created_at', '>=', $filter_option);
                $total_orders->where('orders.created_at', '>=', $filter_option);

                $total_product_returned->where('created_at', '>=', $filter_option);
                $total_product_ordered->where('created_at', '>=', $filter_option);
                $total_ordered_customer->where('created_at', '>=', $filter_option);

                $total_product_exchanged = DB::table('product_return')->where('created_at', '>=', $filter_option);
                $total_product_refunded = DB::table('product_return')->where('created_at', '>=', $filter_option);
                $refunded_amount = DB::table('product_return')->where('created_at', '>=', $filter_option);
            } elseif ($request->filter_by == 'date range') {
                if ($request->start_date != 0 && $request->end_date != 0) {

                    $all_order_amount->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
                    $total_orders->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);

                    $total_product_returned->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $total_product_ordered->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $total_ordered_customer->whereBetween('created_at', [$request->start_date, $request->end_date]);

                    $total_product_exchanged = DB::table('product_return')->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $total_product_refunded = DB::table('product_return')->whereBetween('created_at', [$request->start_date, $request->end_date]);
                    $refunded_amount = DB::table('product_return')->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            }
        }

        if ($request->status_id > 0) {
            $all_order_amount->where('orders.status', $request->status_id);
            $total_orders->where('orders.status', $request->status_id);

            $total_product_exchanged = DB::table('product_return')->where('status', $request->status_id);
            $total_product_refunded = DB::table('product_return')->where('status', $request->status_id);
            $refunded_amount = DB::table('product_return')->where('status', $request->status_id);
        }


        $order_amount = $all_order_amount->groupBy('orders.id')->get();
        $amount = 0;
        foreach ($order_amount as $row) {
            $amount = $amount + $row->total_amount;
        }

        $order_item = count($total_orders->groupBy('orders.id')->get());

        $order_porduct_return = $total_product_returned->count();
        $order_items = $total_product_ordered->sum('product_qty');
        $customers = count($total_ordered_customer->get()) ?? 0;

        $total_product_exchanged = $total_product_exchanged->where('return_type', 'exchange')->count();
        $total_product_refunded = $total_product_refunded->where('return_type', 'refund')->count();
        $refunded_amount = $refunded_amount->where('return_type', 'refund')->sum('refund_amount');

        $data['total_order_amount'] = \Helper::getDefaultCurrency()->currency_symbol . ' ' . $amount;
        $data['total_orders'] = $order_item;
        $data['total_product_returned'] = $order_porduct_return;
        $data['total_product_ordered'] = $order_items;
        $data['total_ordered_customer'] = $customers;
        $data['total_product_exchanged'] = $total_product_exchanged;
        $data['total_product_refunded'] = $total_product_refunded;
        $data['refunded_amount'] = \Helper::getDefaultCurrency()->currency_symbol . ' ' . $refunded_amount;

        return response()->json($data, 200);
    }




















    public function product_sale_report()
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }


        return view('backend.pages.report.product-sale-report');
    }

    public function fetch_product_sale_data($id)
    {
        if (isset($id) && $id != 0) {
            $data = Product::where('is_deleted', 0)->where('seller_id', $id);
        } else {
            if ($this->user->getRoleNames() == '["seller"]') {
                $saller = $this->user->id;
                $data = Product::where('is_deleted', 0)->where('seller_id', $saller);
            } else {
                $data = Product::where('is_deleted', 0);
            }
        }

        $data->with('brand');

        return DataTables::of($data)->addIndexColumn()

            ->editColumn('title', function ($row) {
                $image = '';
                $imageHtml = '';

                if ($row->default_image) {
                    $imageHtml = '<img class="list_img mr-3" src="' . asset('/' . $row->default_image) . '">';
                } else {
                    $imageHtml = '<img class="list_img mr-3" src="' . asset('uploads/images/default/no-image.png') . '">';
                }

                $image = '<div class="media">' . $imageHtml . '<div class="media-body"><p class="product_title">' . $row->title . '</p></div>';
                return $image;
            })
            ->editColumn('brand_id', function ($row) {
                return $row->brand->title ?? '';
            })

            ->addColumn('category', function ($row) {

                $categories = [];
                $catHtml = '';
                $badge = ['info', 'primary', 'dark', 'secondary', 'danger'];
                $category_id = $row->category_id;
                if ($category_id) {
                    $category_id = explode(',', $category_id);
                    if ($category_id) {
                        foreach ($category_id  as $cat_id) {
                            $category = \DB::table('categories')->select('title')->where('id', $cat_id)->first();
                            if ($category) {
                                $categories[] = $category->title;
                            }
                        }
                    }
                }

                if ($categories) {
                    foreach ($categories as $cat) {
                        $catHtml .= '<span class="badge badge-' . $badge[rand(0, 4)] . ' mb-1">' . $cat . '</span>';
                    }
                }
                return ($catHtml) ? $catHtml : 'N/A';
            })

            ->addColumn('no_of_sale', function ($row) {
                return $row->orderdetails()->sum('product_qty');
            })
            ->addColumn('sale_amount', function ($row) {
                $amount = $row->orderdetails()->sum('product_qty') * $row->orderdetails()->sum('price') ?? 0;
                return \Helper::getDefaultCurrency()->currency_symbol . ' ' . $amount;
            })

            ->rawColumns(['no_of_sale', 'sale_amount', 'title', 'category'])->make(true);
    }



    public function lowStockItem()
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.report.low-stock-item');
    }


    public function getLowStockItem()
    {
        $alert_qty = \Helper::getSettings('low_stock_alert') ?? 5;
        $data = Product::where('is_deleted', 0);

        if (Auth::user()->getRoleNames() == '["seller"]') {
            $data->where('seller_id', Auth::user()->id);
        }

        $collection = $data->where('qty', '<=', $alert_qty)->with('seller');


        foreach ($collection as $key => $product) {
            if ($product->product_type == "variable") {
                $meta = DB::table('product_metas')->where('product_id', $product->id)->where('meta_key', 'custom_options')->first();
                if ($meta) {
                    if ($meta_value = $meta->meta_value) {
                        $meta_value = unserialize($meta_value);
                        $qtyArray = [];

                        foreach ($meta_value as $single_value) {
                            foreach ($single_value['value'] as $value) {
                                $qtyArray[] = $value['qty'];
                            }
                        }

                        if ($qtyArray) {
                            $minimumQty = min($qtyArray);

                            if ($minimumQty <= $alert_qty) {
                                $product->qty = $minimumQty;
                            } else {
                                $collection->forget($key);
                            }
                        }
                    }
                }
            }
        }


        return Datatables::of($collection)

            ->addIndexColumn()

            ->editColumn('title', function ($row) {

                if ($row->default_image) {
                    $image = '<img class="list_img mr-3" src="' . '/' . $row->default_image . '" alt="">';
                } else {
                    $image = '<img class="list_img mr-3" src="/no_image.png" alt="">';
                }

                return  '<div class="media">
                            ' . $image . '
                            <div class="media-body">
                                <p class="product_title"><a target="_blank" href="' . env("APP_FRONTEND") . '/product/' . $row->slug . '" class="text-dark text-decoration-none">' . $row->title . '</a></p>
                            </div>
                        </div>';
            })

            ->editColumn('product_type', function ($row) {
                return '<span class="badge_' . $row->product_type . ' plb">' . $row->product_type . '</span>';
            })

            ->editColumn('seller_id', function ($row) {
                return $row->shopinfo->name ?? '';
            })

            ->editColumn('price', function ($row) {
                return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->price;
            })

            ->editColumn('qty', function ($row) {
                return $row->qty;
            })

            ->addColumn('status', function ($row) {
                return '<span class="badge badge-danger">Low Stock</span>';
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('product.view')) {
                    $btn = '<a title="Preview Product" class="icon_btn text-success text-decoration-none productViewBtn" id="' . $row->id . '"  href="#"><i class="mdi mdi-eye"></i></a>';
                }

                if (Auth::user()->can('product.edit')) {
                    $btn = $btn . '<a title="Edit Product" class="icon_btn text-success text-decoration-none" href="' . route('admin.product.edit', $row->id) . '"><i class="mdi mdi-pencil-box-outline"></i></a>';
                }

                if (Auth::user()->can('product.delete')) {
                    $btn = $btn . '<a title="Delete Product" class="icon_btn text-danger delete_btn text-decoration-none" data-url="' . route('admin.product.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['title', 'seller_id', 'product_type', 'price', 'status', 'action'])->make(true);
    }

    public function vat()
    {
        if (is_null($this->user) || !$this->user->can('report.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        return view('backend.pages.report.vat');
    }

    public function getVat(Request $request)
    {
        $data = Order::where('is_deleted', 0)->where('status', 6)->where('vat', '>', 0);

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
            $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        return Datatables::of($data)

            ->addIndexColumn()
            ->editColumn('id', function ($row) {
                return 'KB' . date('y', strtotime($row->created_at)) . $row->id;
            })
            ->editColumn('vat', function ($row) {
                return \Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->vat;
            })
            ->editColumn('total_amount', function ($row) {
                return \Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->total_amount;
            })

            ->rawColumns(['id'])->make(true);
    }

    public function vatInitialData(Request $request)
    {
        $data = Order::where('is_deleted', 0)->where('status', 6);

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

            $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } else {
            $data;
        }
        $total_vat = $data->sum('vat');

        $return['total_vat'] = $total_vat;
        return response()->json($return, 200);
    }
}