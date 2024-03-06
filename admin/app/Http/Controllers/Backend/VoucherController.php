<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Settings;
use App\Models\Currency;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\Admins;
use App\Models\VoucherCategory;
use App\Models\Voucher;
use App\Models\ShippingMethod;
use App\Models\FreeShippingZipList;
use Image, Validator;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('settings.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $voucher_category = VoucherCategory::where('status', 1)->orderBy('title', 'asc')->get();
        $brands = Brand::where('is_active', 1)->orderBy('id', 'desc')->get();
        $users = User::where('status', 1)->orderBy('name', 'asc')->get();
        return view('backend.pages.voucher.voucher', compact('voucher_category', 'brands', 'users'));
    }

    public function getVoucher()
    {
        if (is_null($this->user) || !$this->user->can('settings.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $voucher = Voucher::orderBy('id', 'desc')->where('is_active', 1)->where('is_deleted', 0);

        return DataTables::of($voucher)->addIndexColumn()
            ->editColumn(
                'type',
                function ($row) {
                    if ($row->type == 1) {
                        $type = "Percentage";
                    } else {
                        $type = "Fixed Amount";
                    }
                    return $type;
                }
            )
            ->addColumn(
                'voucher_category',
                function ($row) {
                    $category = $row->voucher_category->title ?? '';
                    return $category;
                }
            )
            ->addColumn(
                'image',
                function ($row) {
                    $image = '<img class="thumb-image" src="' . '/' . $row->banner . '">';
                    return $image;
                }
            )
            ->addColumn(
                'status',
                function ($row) {
                    if ($row->is_active == 1) {
                        $status = '<span class="text-success">Active</span>';
                    } else {
                        $status = '<span class="text-danger">Inactive</span>';
                    }
                    return $status;
                }
            )

            ->addColumn('action', function ($row) {
                $btn = '';

                if (Auth::user()->can('voucher.edit')) {
                    $btn = $btn . '<a class="icon_btn text-info" href="' . route('admin.voucher.edit', $row->id) . '"><i class="mdi mdi-playlist-edit"></i></a>';
                }
                if (Auth::user()->can('voucher.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger"  href="' . route('admin.voucher.delete', $row->id) . '"  ><i class="mdi mdi-delete"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['type', 'image', 'voucher_category', 'status', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('currencies.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'voucher_category_id' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'quantity' => 'required',
            'valid_to' => 'required',
            'valid_from' => 'required',
            'banner' => 'required'
        ]);

        $voucher = new Voucher;
        $voucher->voucher_category_id = $request->voucher_category_id;
        $voucher->banner = $request->banner;
        $voucher->type = $request->type;
        $voucher->title = $request->title;
        $voucher->minimum_amount = $request->minimum_amount;
        $voucher->amount = $request->amount;
        $voucher->quantity = $request->quantity;
        $voucher->max_qty_per_user = $request->max_qty_per_user;
        $voucher->valid_to = $request->valid_to;
        $voucher->valid_from = $request->valid_from;

        $voucher->category_ids = ($request->featured_categories) ? implode(',', $request->featured_categories) : null;

        $voucher->brand_ids =  ($request->brand_id) ? implode(',', $request->brand_id) : null;

        $voucher->product_ids = ($request->products) ? implode(',', $request->products) : null;

        $voucher->seller_ids = ($request->featured_sellers) ? implode(',', $request->featured_sellers) : null;

        $voucher->user_ids = ($request->users) ? implode(',', $request->users) : null;

        $voucher->save();
        session()->flash('success', 'New Voucher Created!');
        return back();
    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('currencies.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $voucher = Voucher::find($id);
        $voucher_category = VoucherCategory::where('status', 1)->orderBy('title', 'asc')->get();
        $brands = Brand::where('is_active', 1)->orderBy('id', 'desc')->get();
        $vendors = Admins::where('status', 1)->orderBy('name', 'asc')->get();
        $vendorArray = [];
        foreach ($vendors as $vendor) {
            if ($vendor->hasRole('seller')) {
                $vendorArray[] = $vendor;
            }
        }

        $users = User::where('status', 1)->orderBy('name', 'asc')->get();

        return view('backend.pages.voucher.voucher-edit', compact('voucher', 'voucher_category', 'brands', 'vendorArray', 'users'));
    }

    public function update(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('currencies.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'voucher_category_id' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'quantity' => 'required',
            'valid_to' => 'required',
            'valid_from' => 'required',
            'banner' => 'required'
        ]);

        $voucher = Voucher::find($request->id);
        $voucher->voucher_category_id = $request->voucher_category_id;
        $voucher->banner = $request->banner;
        $voucher->type = $request->type;
        $voucher->title = $request->title;
        $voucher->minimum_amount = $request->minimum_amount;
        $voucher->amount = $request->amount;
        $voucher->quantity = $request->quantity;
        $voucher->max_qty_per_user = $request->max_qty_per_user;
        $voucher->valid_to = $request->valid_to;
        $voucher->valid_from = $request->valid_from;

        $voucher->category_ids = ($request->featured_categories) ? implode(',', $request->featured_categories) : null;

        $voucher->brand_ids =  ($request->brand_id) ? implode(',', $request->brand_id) : null;

        $voucher->product_ids = ($request->products) ? implode(',', $request->products) : null;

        $voucher->seller_ids = ($request->featured_sellers) ? implode(',', $request->featured_sellers) : null;

        $voucher->user_ids = ($request->users) ? implode(',', $request->users) : null;

        $voucher->is_active = $request->status;
        $voucher->save();
        session()->flash('success', ' Voucher Updated!');
        return back();
    }

    public function delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('currencies.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $voucher = Voucher::find($request->id);
        $voucher->delete();
        return redirect()->route('admin.voucher')->with('success', 'Voucher Successfully Deleted!');
    }

    public function categoryindex()
    {
        if (is_null($this->user) || !$this->user->can('settings.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $category = VoucherCategory::orderBy('title', 'asc')->get();

        return view('backend.pages.voucher.categories', compact('category'));
    }

    public function categorystore(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('currencies.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required'
        ]);

        $category = new VoucherCategory;
        $category->title = $request->title;
        $category->category_banner = $request->category_banner;
        $category->status = $request->status;
        $category->save();
        session()->flash('success', 'New Category Created!');
        return back();
    }


    public function categoryupdate(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('currencies.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required'
        ]);

        $category = VoucherCategory::find($request->editId);
        $category->title = $request->title;
        if ($request->category_banner) {
            $category->category_banner = $request->category_banner;
        }
        $category->status = $request->status;
        $category->update();
        session()->flash('success', 'Category Updated!');
        return back();
    }


    public function categorydelete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('currencies.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $category = VoucherCategory::find($id);
        $category->delete();
        return redirect()->route('admin.voucher.category')->with('success', 'Coupon Successfully Deleted!');
    }
}