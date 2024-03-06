<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageLocalization;
use App\Models\Order;
use App\Models\Admins;
use App\Models\Notifications;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Payments;
use App\Models\User;
use App\Models\Status;
use App\Models\SearchDashboard;
use Yajra\DataTables\DataTables;
use DB;
use Auth;

class PagesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if ($this->user->status != 1) {
                Auth::logout();
                $request->session()->invalidate();
                session()->flash('failed', 'Your account is disabled by admin. Please contact with customer support!');
                return redirect()->route('login');
            }

            return $next($request);
        });
    }


    //Initialize admin panel
    public function admin()
    {

        if (Auth::user()->getRoleNames() == '["seller"]') {
            $data = $this->sellerReport();
            return view('backend.index', compact('data'));
        } else {
            $data = $this->adminReport();
            return view('backend.index', compact('data'));
        }
    }


    public function sellerReport()
    {
        $seller = $this->user->id;
        $data = [];
        $data['order'] = count(DB::select("SELECT orders.id
                            FROM orders, order_details
                            WHERE orders.id = order_details.order_id
                            AND order_details.seller_id = '$seller'"));

        $data['product'] = Product::where('seller_id', $this->user->id)->count();
        $data['users'] = [];
        // $data['orders'] = DB::SELECT("SELECT orders.*
        //                         FROM orders, order_details
        //                         WHERE orders.id = order_details.order_id
        //                         AND order_details.seller_id = '$seller'
        //                         ORDER BY id
        //                         LIMIT 10;");


        $data['orders'] = DB::table('order_details')
                    ->join('orders', 'orders.id', '=', 'order_details.order_id')
                    ->select('orders.*', 'order_details.seller_id')
                    ->where('order_details.seller_id', $seller)
                    ->where('orders.is_deleted', 0)
                    ->orderBy('orders.id', 'DESC')
                    ->groupBy('orders.id')
                    ->get();






           /* $data['completed_orders'] = DB::SELECT("SELECT orders.*

                                FROM orders, order_details
                                WHERE orders.id = order_details.order_id
                                AND order_details.seller_id = '$seller'
                                AND orders.status = 6;"); */
                                
            $data['completed_orders'] = DB::SELECT("SELECT *
                                FROM order_details
                                WHERE status = 6 AND seller_id = '$seller' ;");

        $data['all_orders'] = DB::SELECT("SELECT orders.*
                                FROM orders, order_details
                                WHERE orders.id = order_details.order_id
                                AND order_details.seller_id = '$seller';");

        $data['status'] = Status::all();

        $data['top_category_sale'] = DB::select("SELECT product_id, (SELECT products.category_title FROM products WHERE products.id = order_details.product_id) as 'category',  SUM(price * product_qty) as 'total_sale' FROM order_details 
                WHERE seller_id = '$seller'
                GROUP by product_id
                order by SUM(price * product_qty) desc LIMIT 10;");

        $data['top_sale_product'] = DB::SELECT("SELECT products.id, admins.name, products.title
                                FROM order_details, admins,products
                                WHERE order_details.product_id = products.id
                                AND order_details.seller_id = admins.id
                                AND order_details.seller_id = '$seller'
                                GROUP BY order_details.product_id
                                ORDER BY SUM(order_details.price * order_details.product_qty) desc
                                LIMIT 10;");

        $data['top_seller'] = [];

        $data['top_customer'] = [];

        $query = DB::SELECT("SELECT *
                                FROM order_details
                                WHERE seller_id = '$seller'
                                AND status = 6;");
        $data['revenue'] = 0;
        foreach ($query as $row) {
            $data['revenue'] += ($row->price * $row->product_qty) + $row->shipping_cost;
        }


        return $data;
    }


    public function adminReport()
    {
        $data = [];
        $data['order'] = Order::count();
        $data['product'] = Product::count();
        $data['users'] = User::count();
        $data['orders'] = Order::orderBy('id', 'desc')->where('is_deleted', 0)->limit(10)->get();

        $data['completed_orders'] = DB::SELECT("SELECT *
                                FROM order_details
                                WHERE status = 6;");

        $data['all_orders'] = Order::all();
        $data['status'] = Status::where('delivery', 1)->get();
        $data['top_category_sale'] = DB::select("SELECT product_id, (SELECT categories.title FROM products, categories WHERE products.category_id = categories.id and products.id = order_details.product_id) as 'category',  SUM(price * product_qty) as 'total_sale' FROM order_details
                        GROUP by product_id
                        order by SUM(price * product_qty) desc LIMIT 10;");

        $data['top_sale_product'] = DB::SELECT("SELECT products.id, admins.name, products.title
                                FROM order_details, admins,products
                                WHERE order_details.product_id = products.id
                                AND order_details.seller_id = admins.id
                                GROUP BY order_details.product_id
                                ORDER BY SUM(order_details.price * order_details.product_qty) desc
                                LIMIT 10;");

        $data['top_seller'] = DB::SELECT("SELECT admins.id, admins.name, SUM(order_details.price * order_details.product_qty) as 'total_earn'
                                FROM order_details, admins
                                where order_details.seller_id = admins.id
                                GROUP BY order_details.seller_id
                                ORDER BY SUM(order_details.price * order_details.product_qty) desc
                                LIMIT 10;");

        $data['top_customer'] = DB::table('orders')
            ->select('orders.user_id', DB::raw('SUM(orders.paid_amount) as total_buy_amount'), DB::raw('count(orders.id) as total_buy'), 'users.name')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->groupBy('orders.user_id')
            ->orderByRaw('SUM(orders.paid_amount) DESC')
            ->limit(10)
            ->get();

        // var_dump($top_sale_product);

        $query = DB::SELECT("SELECT *
                                FROM order_details
                                WHERE status = 6;");
        $data['revenue'] = 0;
        foreach ($query as $row) {
            $data['revenue'] += ($row->price * $row->product_qty) + $row->shipping_cost;
        }

        return $data;
    }

    /**
     * Static Pages for Frontend
     * 
     */

    public function index()
    {
        if (is_null($this->user) || !$this->user->can('pages.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $page = Page::orderBy('id', 'desc')->get();
        return view('backend.pages.page.list')->with('pages', $page);
    }

    public function preview($id)
    {
        if (is_null($this->user) || !$this->user->can('pages.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $page = Page::where('id', $id)->first();
        return view('backend.pages.page.preview')->with('page', $page);
    }

    public function create()
    {
        if (is_null($this->user) || !$this->user->can('pages.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.page.create');
    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('pages.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $page = Page::find($id);
        return view('backend.pages.page.edit', compact('page'));
    }


    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('pages.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|string | max:191',
            'description' => 'required',
            'meta_description' => 'nullable | string',
            'status' => 'required',
        ]);

        $page = new Page;
        $page->title = $request->title;
        if (!$request->slug) {
            $page->slug = str_replace(' ', '-', trim(strtolower($request->title)));
        } else {
            $page->slug = $request->slug;
        }
        $page->description = $request->description;
        $page->meta_keyword = $request->meta_keyword;
        $page->meta_description = $request->meta_description;
        $page->static_template = $request->static_template;
        $page->meta_title = $request->meta_title;
        $page->meta_title = $request->meta_description;
        $page->status = $request->status;
        $page->save();

        //Localization Data
        $lanData = [];
        foreach ($request->all() as $key => $val) {
            if (strpos($key, "__") > 0) {
                $paramKey = explode('__', $key)[0];
                $lang = explode('__', $key)[1];
                $lanData[$lang][$paramKey] = $val;
                $lanData[$lang]['lang_code'] = $lang;
            }
        }

        foreach ($lanData as $data) {
            $page_localization = new PageLocalization();
            $page_localization->page_id = $page->id;
            $page_localization->title = $data['title'];
            $page_localization->lang_code = $data['lang_code'];
            $page_localization->description = $data['description'];
            $page_localization->save();
        }

        return redirect()->route('admin.page')->with('success', 'Page successfully created!');
    }




    public function update(Request $request, $id)
    {

        if (is_null($this->user) || !$this->user->can('pages.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|string | max:191',
            'description' => 'required',
            'meta_description' => 'nullable | string',
            'status' => 'required',
        ]);

        $page = Page::find($id);
        $page->title = $request->title;
        if (!$request->slug) {
            $page->slug = str_replace(' ', '-', trim(strtolower($request->title)));
        } else {
            $page->slug = $request->slug;
        }
        $page->description = $request->description;
        $page->meta_keyword = $request->meta_keyword;
        $page->meta_description = $request->meta_description;
        $page->static_template = $request->static_template;
        $page->meta_title = $request->meta_title;
        $page->meta_title = $request->meta_description;
        $page->status = $request->status;
        $page->save();

        //Localization Data
        $lanData = [];
        foreach ($request->all() as $key => $val) {
            if (strpos($key, "__") > 0) {
                $paramKey = explode('__', $key)[0];
                $lang = explode('__', $key)[1];
                $lanData[$lang][$paramKey] = $val;
                $lanData[$lang]['lang_code'] = $lang;
            }
        }

        foreach ($lanData as $data) {

            $page_localization = PageLocalization::where('page_id', $page->id)->where('lang_code', $data['lang_code'])->first();

            if ($page_localization !== null) {
                $page_localization->title = $data['title'];
                $page_localization->description = $data['description'];
                $page_localization->save();
            } else {
                $page_localization = new PageLocalization();
                $page_localization->page_id = $page->id;
                $page_localization->title = $data['title'];
                $page_localization->lang_code = $data['lang_code'];
                $page_localization->description = $data['description'];
                $page_localization->save();
            }
        }


        return redirect()->route('admin.page', $page->id)->with('success', 'Page successfully updated!');
    }



    public function delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('pages.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $page = Page::find($id);
        $page->delete();
        return redirect()->route('admin.page')->with('success', 'Page successfully deleted!');
    }


    public function notifications()
    {
        return view('backend.pages.others.notifications');
    }

    public function mediaGallery()
    {
        return view('backend.pages.others.mediagallery');
    }




    public function notificationsDelete($id)
    {
        if (is_null($this->user) || !$this->user->can('notification.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        DB::table('notifications')->where('id', $id)->update(['status' => 0]);

        return redirect()->route('admin.notifications')->with('success', 'Notifications successfully deleted!');
    }

    
    public function searchDashboardIndex(){
        return view('backend.pages.search_dashboard.search');
    }

    public function getSearchDashboardIndex(){
        $data = SearchDashboard::latest();
        return DataTables::of($data)->addIndexColumn()
            ->editColumn('status', function ($row) {
                $text = '';
                if ($row->status == 1) {
                    $text = '<span class="badge badge-success">Active</span>';
                } else if ($row->status == 2) {
                    $text = '<span class="badge badge-danger">Inactive</span>';
                }

                return $text;
            })
            ->editColumn('permission', function ($row) {
                $text = '';
                if ($row->permission == 1) {
                    $text = '<span class="badge bg-success">Admin</span>';
                } else if ($row->permission == 2) {
                    $text = '<span class="badge bg-warning text-dark">Seller</span>';
                } else if ($row->permission == 3){
                    $text = '<span class="badge bg-info text-dark">both</span>';
                }

                return $text;
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('search.edit')) {
                    $btn = $btn . '<a class="icon_btn text-info" href="' . route('admin.edit.search.dashboard', $row->id) . '"><i class="mdi mdi-playlist-edit"></i></a>';
                }
                if (Auth::user()->can('search.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger" href="' . route('admin.delete.search.dashboard', $row->id) . '"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['status','permission', 'action'])->make(true);
    }

    public function createSearchDashboard(){
        return view('backend.pages.search_dashboard.create');
    }

    public function storeSearchDashboard(Request $request){
        if (is_null($this->user) || !$this->user->can('pages.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required',
            'link' => 'required',
            'permission' => 'required',
            'status' => 'required',
        ]);

        $search_dashboard = new SearchDashboard;
        $search_dashboard->title = $request->title;
        $search_dashboard->link = $request->link;
        $search_dashboard->permission = $request->permission;
        $search_dashboard->status = $request->status;
        $search_dashboard->save();

        return redirect()->back()->with('success', 'Search successfully created!');
    }

    public function editSearchDashboard($id){
        $search = SearchDashboard::find($id);
        return view('backend.pages.search_dashboard.edit',get_defined_vars());
    }

    public function updateSearchDashboard($id, Request $request){
        if (is_null($this->user) || !$this->user->can('pages.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required',
            'link' => 'required',
            'permission' => 'required',
            'status' => 'required',
        ]);

        SearchDashboard::find($id)->update([
           'title'=>$request->title,
           'link'=>$request->link,
           'permission'=>$request->permission,
           'status'=>$request->status,
        ]);
        return redirect()->route('admin.search.dashboard.index')->with('success', 'Search successfully updated!');
    }

    public function deleteSearchDashboard($id){
        SearchDashboard::find($id)->delete();
        return redirect()->back()->with('success', 'Search successfully deleted!');

    }

    public function searchDashboard(Request $request)
    {

        $keyword = $request->keyword;
        if(Auth::user()->getRoleNames() == '["admin"]' || Auth::user()->getRoleNames() == '["superadmin"]'){
            $results = SearchDashboard::where('title', 'LIKE', "%{$keyword}%")->where('status', 1)->get();
        }
        else if(Auth::user()->getRoleNames() == '["seller"]'){
            $results = SearchDashboard::where('title', 'LIKE', "%{$keyword}%")->where('permission',2)->where('status', 1)->get();
        }
        
        $html = '';
        if (count($results) > 0) {
            $html .= '<ul class="pl-0">';
            foreach ($results as $result) {
                $html .= '<li><a href="' . env('APP_URL') . '/' . $result->link . '"><i class="mdi mdi-link-variant"></i>' . $result->title . '</a></li>';
            }
            $html .= '</ul>';
        } else {
            $html .= '<p class="text-center">No result found!</p>';
        }




        return $html;
    }



    public function notificationsList()
    {

        if (is_null($this->user) || !$this->user->can('notification.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        if (Auth::user()->hasRole('seller')) {
            $data =  $data = \DB::table('notifications')
            ->where('user_type', 2)
            ->where('user_id', Auth::id())
            ->where('status','!=', 0)
            ->orderBy('id', 'desc')
            ->get();
        } else {
            $data =  $data = \DB::table('notifications')
            ->where('user_type','!=',1)
            ->where('status','!=', 0)
            ->orderBy('id', 'desc')
            ->get();
        }



        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function ($row) {
                if ($row->user_type == 2 || $row->user_type == 3) {
                    //Admin or Seller
                    $user = Admins::where('id', $row->user_id)->with('shopinfo')->get();
                    foreach ($user as $key => $value) {
                        // return 'Seller Name: '.$value->name.'<br><br> Shop Name : '.$value->shopinfo->name;
                        return $value->name ?? ' - ';
                    }
                }
            })

            ->addColumn('user_type', function ($row) {
                if ($row->user_type == 2) {
                    return 'Seller';
                } elseif ($row->user_type == 3) {
                    return 'Admin';
                }
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 1 || $row->status == 2) {
                    return  '<label  data-status-id="' . $row->id . '" data-status="3"  class="badge nowStatus1">Unseen</label>';
                } elseif ($row->status == 3) {
                    return  '<label  data-status-id="' . $row->id . '" data-status="1"  class="badge nowStatus3">Seen</label>';
                }
            })

            ->addColumn('created_at', function ($row) {
                return  date('d M, Y h:ia', strtotime($row->created_at));
            })

            ->addColumn('description', function ($row) {
                $html = '';
                if ($row->description) {
                    $data = json_decode($row->description);
                    foreach ($data as $key => $val) {
                        $html .= strtoupper(str_replace('_', ' ', $key)) . ': ' . $val . '<br>';
                    }
                }
                return $html;
            })

            ->addColumn('action', function ($row) {
                $btn = '';

                if (Auth::user()->can('notification.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.notification.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
                }

                return $btn;
            })

            ->rawColumns(['user', 'description', 'created_at', 'status', 'action'])->make(true);
    }
}