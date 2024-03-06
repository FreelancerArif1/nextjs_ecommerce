<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admins;
use App\Models\UsersMeta;
use App\Models\ShopInfo;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Product;
use App\Models\Order;
use App\Models\WithdrawalRequest;
use Yajra\DataTables\DataTables;
use Helper;

use Carbon\Carbon;


use Hash, Image, Auth, DB;
use Spatie\Permission\Models\Role;

class VendorsController extends Controller
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
      return $next($request);
    });
  }


  /**
   * 
   * Vendor management section for admin panel
   * 
   */

  public function vendor()
  {
    if (is_null($this->user) || !$this->user->can('vendor.view')) {
      return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
    }

    $vendors = Admins::where('is_deleted', 0)->orderBy('id', 'desc')->with('shopinfo')->get();
    $vendorArray = [];
    foreach ($vendors as $vendor) {
      if ($vendor->hasRole('seller')) {
        $vendorArray[] = $vendor;
      }
    }
    $vendors = $vendorArray;
    return view('backend.pages.vendors.list', compact('vendors'));
  }

  public function getSellerList()
  {

    if (is_null($this->user) || !$this->user->can('vendor.view')) {
      return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
    }

    $vendors = Admins::where('is_deleted', 0)->orderBy('id', 'desc')->with('shopinfo')->get();
    $vendorArray = [];
    foreach ($vendors as $vendor) {
      if ($vendor->hasRole('seller')) {
        $vendorArray[] = $vendor;
      }
    }
    $data = $vendorArray;

    return Datatables::of($data)->addIndexColumn()

      ->addColumn('checkbox', function ($row) {
        return '<div class="form-check form-check-flat">
                        <label class="form-check-label">
                            <input name="select_all[]" type="checkbox"  class="form-check-input checkbox_single_select" value="' . $row->id . '"><i class="input-helper"></i>
                        </label>
                    </div>';
      })

      ->addColumn('seller', function ($row) {
        $html = '';
        $imageHtml = '';


        if ($row->shopinfo->logo ?? '') {
          $imageHtml = '<img class="list_img mr-3" src="' . '/' . $row->shopinfo->logo . '">';
        } else {
          $imageHtml = '<img class="list_img mr-3" src="' . asset('uploads/images/default/no-image.png') . '">';
        }



        $html = '<div class="media">' . $imageHtml . '<div class="media-body"><p class="product_title text-capitalize">' . $row->name . '</p></div>';
        return $html;
      })

      ->addColumn('shop', function ($row) {
        return  $row->shopinfo->name ?? '';
      })

      ->addColumn('commission', function ($row) {
        $commission =  $row->shopinfo->commission_percent ?? "";
        if ($commission > 0) {
          $commission .= '%';
        }
        return $commission;
      })

      ->addColumn('role', function ($row) {
        $html = '';
        foreach ($row->roles as $role) {
          $html .= '<span class=" badge badge-primary mr-2 mb-1 text-small">' . $role->name . '</span>';
        }
        return $html;
      })

      ->addColumn('status', function ($row) {
        return  '<label class="badge badge_' . strtolower(\Helper::getStatusName('default', $row->status)) . '">' . \Helper::getStatusName('default', $row->status) . '</label>';
      })

      ->addColumn('shop_status', function ($row) {
        return  '<label class="badge badge_' . strtolower(\Helper::getStatusName('default', $row->shopinfo->status ?? '')) . '">' . \Helper::getStatusName('default', $row->shopinfo->status ?? '') . '</label>';
      })




      ->addColumn('action', function ($row) {
        $btn = '';
        if (Auth::user()->can('vendor.accounts')) {
          $btn = '<a class="icon_btn text-default" href="' . route('admin.vendor.accounts', $row->id) . '"><i class="mdi mdi-chart-bar"></i></a>';
        }
        if (Auth::user()->can('vendor.view')) {
          $btn = $btn . '<a class="icon_btn text-info seller_quick_view_btn" href="" data-id="' . $row->id . '" ><i class="mdi mdi-eye"></i></a>';
        }
        if (Auth::user()->can('vendor.edit')) {
          $btn = $btn . '<a class="icon_btn text-success" href="' . route('admin.vendor.edit', $row->id) . '"><i class="mdi mdi-pencil-box-outline"></i></a>';
        }

        if (Auth::user()->can('vendor.delete')) {
          $btn = $btn . '<a class="icon_btn text-danger delete_btn" data-url="' . route('admin.vendor.delete', $row->id) . '" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>';
        }

        return $btn;
      })

      ->rawColumns(['checkbox', 'seller', 'role', 'status', 'shop_status', 'action'])->make(true);
  }



  public function edit($id)
  {
    if (is_null($this->user) || !$this->user->can('vendor.edit')) {
      return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
    }

    $vendor = Admins::where('id', $id)->with('shopinfo')->first();
    $roles = Role::all();
    return view('backend.pages.vendors.edit', compact('vendor', 'roles'));
  }

  public function view($id)
  {

    $vendor = Admins::where('id', $id)->with('shopinfo')->first();

    $division = Division::find($vendor->shopinfo->division)->title ?? '';
    $district = District::find($vendor->shopinfo->district)->title ?? '';
    $upazila = Upazila::find($vendor->shopinfo->area)->title ?? '';
    $union = Union::find($vendor->shopinfo->shop_union)->title ?? '';

    $account_status = ($vendor->status == 1) ? 'Active' : 'Inactive';
    $seller_status = ($vendor->shopinfo->status == 1) ? 'Active' : 'Inactive';

    $request = WithdrawalRequest::where('seller_id', $vendor->id)->where('is_deleted', 0)->get();

    return view('backend.pages.vendors.view', compact('vendor', 'division', 'district', 'upazila', 'union', 'account_status', 'seller_status','request'));
  }


  public function create()
  {
    if (is_null($this->user) || !$this->user->can('vendor.create')) {
      return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
    }
    return view('backend.pages.vendors.create');
  }

  // SELLER DISTRICT PAZILA AND UNION GET STARTED

  public function get_district()
  {
    $html = '';
    $division_id = $_REQUEST['division_id'];
    $districts = District::where('division_id', $division_id)->orderBy('title', 'asc')->get();
    foreach ($districts as $district) {
      $html .= '<option value="' . $district->id . '">' . $district->title . '</option>';
    }
    return $html;
  }


  public function get_upazila()
  {
    $html = '';
    $district_id = $_REQUEST['district_id'];
    $upazilas = Upazila::where('district_id', $district_id)->orderBy('title', 'asc')->get();
    foreach ($upazilas as $upazila) {
      $html .= '<option value="' . $upazila->id . '">' . $upazila->title . '</option>';
    }
    return $html;
  }

  public function get_union()
  {
    $html = '';
    $upazila_id = $_REQUEST['upazila_id'];
    $unions = Union::where('upazila_id', $upazila_id)->orderBy('title', 'asc')->get();
    foreach ($unions as $union) {
      $html .= '<option value="' . $union->id . '">' . $union->title . '</option>';
    }
    return $html;
  }
  // SELLER DISTRICT PAZILA AND UNION GET END

  public function store(Request $request)
  {
    if (is_null($this->user) || !$this->user->can('vendor.create')) {
      return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
    }

    $request->validate([
      'name' => 'required | max:191 | string',
      'email' => 'nullable | max:50 | email | unique:admins',
      'phone' => 'required | max:15 | unique:admins',
      'slug' => 'required | unique:shop_info',
      'status' => 'required',
      'commission_percent'  => 'required',
    ], [
      'email.unique' => 'This email has already been taken!',
      'phone.unique' => 'This phone number has already been taken!',
    ]);

    $vendor = new Admins();
    $vendor->name = $request->name;
    $vendor->email = $request->email;
    $vendor->phone = $request->phone;
    $vendor->avatar = $request->avatar;
    $vendor->nid_front_side = $request->nid_front_side;
    $vendor->nid_back_side = $request->nid_back_side;
    $vendor->avatar = $request->avatar;

    $tempPass = rand(111111, 99999);
    $vendor->password = $request->password ? Hash::make($request->password) : Hash::make($tempPass);

    $vendor->status = $request->status ? 1 : 0;
    $vendor->assignRole(['seller']);
    $vendor->save();

    $shopInfo = new ShopInfo;
    $shopInfo->seller_id = $vendor->id;
    $shopInfo->name = $request->title;
    $shopInfo->slug = $request->slug;
    $shopInfo->phone = $request->shop_phone;
    $shopInfo->email = $request->shop_email;
    $shopInfo->logo = $request->shop_logo;
    $shopInfo->banner = $request->shop_banner;
    $shopInfo->trade_license = $request->trade_license;
    $shopInfo->division = $request->shop_division;
    $shopInfo->district = $request->shop_district;
    $shopInfo->area = $request->shop_area;
    $shopInfo->shop_union = $request->shop_union;
    $shopInfo->address = $request->shop_address;
    $shopInfo->commission_percent = $request->commission_percent;

    $shopInfo->status = $request->shop_status ? 1 : 0;


    //Bank Account
    $shopInfo->bank_name = $request->bank_name;
    $shopInfo->account_name = $request->account_name;
    $shopInfo->account_number = $request->account_number;
    $shopInfo->routing_number = $request->routing_number;
    $shopInfo->bkash = $request->bkash;
    $shopInfo->rocket = $request->rocket;
    $shopInfo->nagad = $request->nagad;
    $shopInfo->upay = $request->upay;
    $shopInfo->save();

    $vendor_password = $request->password ? $request->password : $tempPass;

    $msg = 'আপনার মোবাইল নাম্বার দিয়ে একটি সেলার একাউন্ট খোলা হয়েছে। আপনার পাসওয়ার্ড #' . $vendor_password . ' । অনুগ্রহ করে কারো সাথে আপনার পাসওয়ার্ড শেয়ার করবেন না। ধন্যবাদ।';
    $mobile = $request->phone;

    if ($mobile) {
      \Helper::sendSms($mobile, $msg);
    }

    return redirect()->route('admin.vendor')->with('success', 'Account Successfully Created!');
  }

  public function update(Request $request, $id)
  {

    if (is_null($this->user) || !$this->user->can('vendor.edit')) {
      return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
    }

    if (\Auth::user()->getRoleNames() == '["seller"]') {
      $id = \Auth::id();
      $request->validate([
        'name' => 'required | max:191 | string',
        'shop_division'  => 'required',
        'shop_district'  => 'required',
      ]);
    } else {
      $request->validate([
        'name' => 'required | max:191 | string',
        'title'  => 'required',
        'commission_percent'  => 'required',
        'shop_division'  => 'required',
        'shop_district'  => 'required',
        'shop_area'  => 'required',
        'shop_union'  => 'required',
        'agreement_file'  => 'nullable|mimes:pdf|max:10000',
      ]);
    }



    $vendor = Admins::find($id);
    $vendor->name = $request->name;
    $vendor->avatar = $request->avatar;
    $vendor->nid_front_side = $request->nid_front_side;
    $vendor->nid_back_side = $request->nid_back_side;
    $vendor->avatar = $request->avatar;

    if (\Auth::user()->getRoleNames() != '["seller"]') {
      $vendor->status = $request->status ? 1 : 0;
      $vendor->email = $request->email;
      $vendor->phone = $request->phone;
    }
    
    if ($request->password) {
      $vendor->password = Hash::make($request->password);
    }

    $vendor->assignRole(['seller']);

    $vendor->save();


    $shopInfo = ShopInfo::where('seller_id', $id)->first();
    if ($shopInfo) {
      $shopInfo->name = $request->title;
      $shopInfo->phone = $request->shop_phone;
      $shopInfo->email = $request->shop_email;
      $shopInfo->logo = $request->shop_logo;
      $shopInfo->banner = $request->shop_banner;

      if ($request->hasfile('agreement_file')) {
        $file = $request->file('agreement_file');
        $name = time() . rand(1, 100) . '.' . $file->extension();
        $file->move(public_path('uploads/agreementpapers'), $name);

        $shopInfo->agreement_file = $name;
      }
      
      $shopInfo->trade_license = $request->trade_license;
      $shopInfo->division = $request->shop_division;
      $shopInfo->district = $request->shop_district;
      $shopInfo->area = $request->shop_area;
      $shopInfo->shop_union = $request->shop_union;
      $shopInfo->address = $request->shop_address;

      if (\Auth::user()->getRoleNames() != '["seller"]') {
        $shopInfo->commission_percent = $request->commission_percent;
      }


      //Bank Account
      $shopInfo->bank_name = $request->bank_name;
      $shopInfo->account_name = $request->account_name;
      $shopInfo->account_number = $request->account_number;
      $shopInfo->routing_number = $request->routing_number;
      $shopInfo->bkash = $request->bkash;
      $shopInfo->rocket = $request->rocket;
      $shopInfo->nagad = $request->nagad;
      $shopInfo->upay = $request->upay;

      if (\Auth::user()->getRoleNames() != '["seller"]') {
        $shopInfo->status = $request->shop_status ? 1 : 0;
      }
      $shopInfo->save();
    }

    if (!$request->shop_status) {
      //Deactivate all products
      $products = Product::where('seller_id', $id)->where('is_active', 1)->where('is_deleted', 0)->get();
      foreach ($products as $product) {
        Product::where('id', $product->id)->update(['is_active' => 0]);
      }
    } else {
      //Deactivate all products
      $products = Product::where('seller_id', $id)->where('is_active', 0)->where('is_deleted', 0)->get();
      foreach ($products as $product) {
        Product::where('id', $product->id)->update(['is_active' => 1]);
      }
    }


    if (Auth::user()->getRoleNames() != '["seller"]') {
      return redirect()->route('admin.vendor')->with('success', 'Account Updated Successfully!');
    } else {
      return redirect()->back()->with('success', 'Account Updated Successfully!');
    }
  }

  public function destroy(Request $request, $id)
  {

    if (is_null($this->user) || !$this->user->can('vendor.delete')) {
      return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
    }

    if (Auth::id() == $id) {
      session()->flash('failed', 'You can not delete yourself!');
      return back();
    }

    $vendor = Admins::find($id);

    //Insert Trash Data
    $type = 'seller';
    $type_id = $id;
    $reason = $request->reason ?? '';
    $data = $vendor;
    \Helper::setTrashInfo($type, $type_id, $reason, $data);


    //finaly delete the admin
    $vendor->is_deleted = 1;
    $vendor->save();

    return redirect()->route('admin.vendor')->with('success', 'Account successfully deleted!');
  }


  public function editProfile()
  {
    $vendor = Admins::where('id', $this->user->id)->with('shopinfo')->first();
    $roles = Role::all();
    return view('backend.pages.vendors.edit', compact('vendor', 'roles'));
  }

  public function accounts($id)
  {
    if (is_null($this->user) || !$this->user->can('vendor.accounts')) {
      return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
    }
    $data = [];

    $vendor = Admins::where('id', $id)->with('shopinfo')->first();

    $completed_orders = DB::SELECT("SELECT orders.*
                                FROM orders, order_details
                                WHERE orders.id = order_details.order_id
                                AND order_details.seller_id = '$vendor->id';");

    $all_orders = DB::SELECT("SELECT orders.*
                                FROM orders, order_details
                                WHERE orders.id = order_details.order_id
                                AND order_details.seller_id = '$vendor->id';");

    return view('backend.pages.vendors.accounts', compact('vendor', 'completed_orders', 'all_orders'));
  }

  public function accountsOrders($seller_id)
  {

    $data = Order::select('orders.*', 'order_details.seller_id')
      ->join('order_details', 'orders.id', '=', 'order_details.order_id')
      ->where('order_details.seller_id', $seller_id)
      ->with('statuses')
      ->orderBy('orders.id', 'desc');

    return Datatables::of($data->get())->addIndexColumn()
      ->addColumn('id', function ($row) {
        return 'KB' . date('y', strtotime($row->created_at)) . $row->id;
      })

      ->addColumn('order_date', function ($row) {
        return date('d M, Y h:ia', strtotime($row->created_at));
      })

      ->addColumn('user', function ($row) {
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


  public function accountProducts($seller_id)
  {
    $data = Product::where('seller_id', $seller_id)->where('is_deleted', 0)->orderBy('id', 'DESC');

    return Datatables::of($data)

      //->addIndexColumn()


      ->editColumn('created_at', function ($row) {
        return $row->created_at->toDateString();
      })



      ->editColumn('title', function ($row) {
        $seller = ShopInfo::where('seller_id', $row->seller_id)->first();
        $seller = ($seller) ? ShopInfo::where('seller_id', $row->seller_id)->first()->name : '';

        if ($row->default_image) {
          $image = '<img class="list_img mr-3" src="' . '/' . $row->default_image . '" alt="">';
        } else {
          $image = '<img class="list_img mr-3" src="/no_image.png" alt="">';
        }


        return  '<div class="media">
                        ' . $image . '
                        <div class="media-body">
                            <p class="product_title"><a target="_blank" href="' . env("APP_FRONTEND") . '/product/' . $row->slug . '" class="text-dark text-decoration-none">' . $row->title . '</a></p>
                            <a target="_blank" href="' . '/admin/seller/edit/' . $row->seller_id . '" class="seller_name text-decoration-none">' . $seller ?? '-' . '</a>
                        </div>
                    </div>';
      })

      ->editColumn('sku', function ($row) {
        return ($row->sku) ? $row->sku : 'N/A';
      })

      ->editColumn('category_id', function ($row) {
        $categories = [];
        $catHtml = '';
        $badge = ['info', 'primary', 'dark', 'secondary', 'danger'];
        $category_id = $row->category_id;
        if ($category_id) {
          $category_id = explode(',', $category_id);
          if ($category_id) {
            foreach ($category_id  as $cat_id) {
              $category = DB::table('categories')->select('title')->where('id', $cat_id)->first();
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

      ->editColumn('brand_title', function ($row) {
        return ($row->brand) ? $row->brand->title : 'N/A';
      })

      ->editColumn('product_type', function ($row) {
        return '<span class="badge_' . $row->product_type . ' plb">' . $row->product_type . '</span>';
      })

      ->editColumn('price', function ($row) {
        return Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->price;
      })

      ->editColumn('weight', function ($row) {
        return ($row->weight) ? $row->weight . $row->weight_unit : 'N/A';
      })

      ->editColumn('qty', function ($row) {
        return ($row->qty != 0) ? $row->qty : '-';
      })

      ->addColumn('status', function ($row) {
        return  '<label class="badge badge_' . strtolower(Helper::getStatusName('default', $row->is_active)) . '">
                       ' . Helper::getStatusName('default', $row->is_active) . '
                       </label>';
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

      ->rawColumns(['checkbox', 'title', 'sku', 'category_id', 'product_type', 'price', 'weight', 'status', 'action'])->make(true);
  }

  public function action(Request $request)
  {
    if (empty($request->select_all)) {
      session()->flash('success', 'You have to select seller!');
      return back();
    }

    if ($request->action ==  "active") {
      foreach ($request->select_all as $id) {
        Admins::where('id', $id)->update(['status' => 1]);
        ShopInfo::where('seller_id', $id)->update(['status' => 1]);
      }
      session()->flash('success', 'Seller successfully activated !');
      return back();
    }
    if ($request->action ==  "inactive") {
      foreach ($request->select_all as $id) {
        Admins::where('id', $id)->update(['status' => 0]);
        ShopInfo::where('seller_id', $id)->update(['status' => 0]);
      }
      session()->flash('success', 'Seller successfully inctivated !');
      return back();
    }
    //commission
    if ($request->action ==  "commission") {
      $commission_rate = $request->commission_rate;
      foreach ($request->select_all as $id) {
        ShopInfo::where('seller_id', $id)->update(['commission_percent' => $commission_rate]);
      }
      session()->flash('success', 'Commission Rate successfully updated!');
      return back();
    }

    if ($request->action ==  "delete") {
      foreach ($request->select_all as $id) {
        Admins::where('id', $id)->update(['is_deleted' => 1]);
        $vendor = Admins::find($id);
        //Insert Trash Data
        $type = 'seller';
        $type_id = $id;
        $reason = $request->reason ?? 'Bulk Delete';
        $data = $vendor;
        \Helper::setTrashInfo($type, $type_id, $reason, $data);
      }
      session()->flash('success', 'Seller successfully deleted !');
      return back();
    }
  }
}