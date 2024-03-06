<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Settings;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\Admins;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\FreeShippingZipList;
use Image, Validator;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
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

        $product_collection = DB::table('products')->select('id', 'title')->orderBy('title', 'asc')->where('is_active', 1)->where('is_deleted', 0)->get();
        $categories = DB::table('categories')->orderBy('title', 'asc')->where('parent_id', 0)->get();
        $allSettings = \Helper::getAllSettings();
        return view('backend.pages.design', compact('product_collection', 'categories', 'allSettings'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('settings.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $data = [];

        // foreach($request->file() as $key => $val){
        //     if ($request->hasFile($key)) {
        //         $image = $request->file($key);
        //         $imageName = Str::random(32).'.'.$image->getClientOriginalExtension();
        //         $location = public_path('uploads/images/settings/'.$imageName);
        //         Image::make($image)->save($location);
        //         $data[$key] = $imageName;
        //     }
        // }

        foreach ($request->input() as $key => $val) {
            if (!is_array($val)) {
                $request->validate([
                    $val => 'nullable | string'
                ]);
                $data[$key] = $val;
            } else {
                $data[$key] = implode(',', $val);
            }
        }
        unset($data['_token']);

        foreach ($data as $key => $val) {
            $settings = Settings::updateOrCreate(
                ['key' =>  $key],
                ['value' => $val]
            );
        }
        session()->flash('success', 'Settings Successfully Updated!');
        return back();
    }


    //Shipping Methods


    public function settings(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('settings.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.pages.settings');
    }

    public function settingsSave(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('settings.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $rules = array(
            'cnf_appname' => 'required|min:2',
            'cnf_appdesc' => 'required|min:2 |string',
            'cnf_comname' => 'required|min:2',
            'cnf_email' => 'required|email',
        );
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {

            $logo = '';
            if ($request->hasFile('cnf_logo')) {
                $image = $request->file('cnf_logo');
                $imageName = 'backend-logo.' . $image->getClientOriginalExtension();

                $logo = public_path('uploads/images/' . $imageName);
                Image::make($image)->save($logo);
            }
            $paymentMethods = '';



            $val  =        "<?php \n";
            $val  .=     "return [\n";
            $val  .=     "'cnf_appname' 			=> '" . $request->input('cnf_appname') . "',\n";
            $val  .=     "'cnf_appdesc' 			=> '" . $request->input('cnf_appdesc') . "',\n";
            $val  .=     "'cnf_comname' 			=> '" . $request->input('cnf_comname') . "',\n";
            $val  .=     "'cnf_email' 			=> '" . $request->input('cnf_email') . "',\n";
            $val  .=     "'cnf_phone' 			=> '" . $request->input('cnf_phone') . "',\n";
            $val  .=     "'cnf_metakey' 			=> '" . $request->input('cnf_metakey') . "',\n";
            $val  .=     "'cnf_address' 			=> '" . $request->input('cnf_address') . "',\n";
            $val  .=     "'cnf_metadesc' 		=> '" . $request->input('cnf_metadesc') . "',\n";
            $val  .=     "'cnf_recaptcha' 		=> '" . $request->input('cnf_recaptcha') . "',\n";
            $val  .=     "'cnf_recaptchapublickey' => '" . $request->input('cnf_recaptchapublickey') . "',\n";
            $val  .=     "'cnf_recaptchaprivatekey' => '" . $request->input('cnf_recaptchaprivatekey') . "',\n";
            $val  .=     "'cnf_mailchimpapikey' => '" . $request->input('cnf_mailchimpapikey') . "',\n";
            $val  .=     "'cnf_mailchimplistid' => '" . $request->input('cnf_mailchimplistid') . "',\n";
            $val  .=     "'cnf_logo' 			=> '" . ($logo != ''  ? $imageName : config('concave.cnf_logo')) . "',\n";
            $val  .=     "];\n";
            $filename = base_path() . '/config/concave.php';
            $fp = fopen($filename, "w+");
            fwrite($fp, $val);
            fclose($fp);
            return redirect()->route('admin.settings')->with('success', 'Setting has been saved successfully.');
        } else {
            return redirect()->route('admin.settings')->with('errors', 'The following errors occurred');
        }
    }


    //currency
    public function currency()
    {
        if (is_null($this->user) || !$this->user->can('currencies.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $currency = Currency::orderBy('id', 'desc')->get();
        return view('backend.pages.others.currency', compact('currency'));
    }
    public function currency_store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('currencies.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:64 | unique:currencies',
            'currency_symbol' => 'required|max:8',
            'exchange_rate' => 'required | numeric',
            'status' => 'numeric'
        ]);

        $currency = new Currency;
        $currency->title = $request->title;
        $currency->currency_symbol = $request->currency_symbol;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->status = $request->status;
        $currency->save();
        session()->flash('success', 'New Currency Created!');
        return back();
    }

    public function currency_delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('currencies.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $currency = Currency::find($id);
        $currency->delete();
        return redirect()->route('admin.currency')->with('success', 'Currency Successfully Deleted!');
    }





    //Language

    public function language()
    {
        if (is_null($this->user) || !$this->user->can('language.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $language = Language::orderBy('id', 'desc')->get();
        return view('backend.pages.others.language', compact('language'));
    }
    public function language_store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('language.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'required|max:64 | unique:currencies',
            'language_code' => 'required|max:2',
            'is_active' => 'numeric'
        ]);

        $language = new Language;
        $language->title = $request->title;
        $language->lang_code = $request->language_code;
        $language->is_active = $request->is_active;
        $language->save();
        session()->flash('success', 'New Language Created!');
        return back();
    }

    public function language_delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('language.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $language = Language::find($id);
        $language->delete();
        return redirect()->route('admin.language')->with('success', 'Language Successfully Deleted!');
    }


    public function coupons()
    {
        if (is_null($this->user) || !$this->user->can('currencies.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        // $coupons = Coupon::orderBy('id', 'desc')->get();
        $brands = Brand::where('is_active', 1)->orderBy('id', 'desc')->get();
        // $vendors = Admins::where('status', 1)->orderBy('name', 'asc')->get();
        // $vendorArray = [];
        // foreach ($vendors as $vendor) {
        //     if ($vendor->hasRole('seller')) {
        //         $vendorArray[] = $vendor;
        //     }
        // }

        $users = User::where('status', 1)->orderBy('name', 'asc')->get();
        return view('backend.pages.coupons', compact('brands', 'users'));
    }

    public function getCoupons()
    {
        if (is_null($this->user) || !$this->user->can('currencies.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $coupons = Coupon::orderBy('id', 'desc');
        return DataTables::of($coupons)->addIndexColumn()
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

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Auth::user()->can('brand.edit')) {
                    $btn = $btn . '<a class="icon_btn text-info" href="' . route('admin.coupons.edit', $row->id) . '"><i class="mdi mdi-playlist-edit"></i></a>';
                }
                if (Auth::user()->can('brand.delete')) {
                    $btn = $btn . '<a class="icon_btn text-danger" href="' . route('admin.coupons.delete', $row->id) . '"><i class="mdi mdi-delete"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['type', 'action'])->make(true);
    }


    public function coupons_store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('currencies.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'quantity' => 'required',
            'expire' => 'required'
        ]);

        $coupon = new Coupon;
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->use_type = $request->use_type;
        $coupon->minimum_amount = $request->minimum_amount;
        $coupon->amount = $request->amount;
        $coupon->quantity = $request->quantity;
        $coupon->max_qty_per_user = $request->max_qty_per_user;
        $coupon->expire = $request->expire;
        if (!empty($request->featured_categories)) {
            $category = '';
            foreach ($request->featured_categories as $key => $val) {
                $category .= $val;
                $category .= ',';
            }
            $coupon->category_ids = $category;
        }

        if (!empty($request->brand_id)) {
            $brand = '';
            foreach ($request->brand_id as $key => $val) {
                $brand .= $val;
                $brand .= ',';
            }
            $coupon->brand_ids =  $brand;
        }

        if (!empty($request->products)) {
            $product = '';
            foreach ($request->products as $key => $val) {
                $product .= $val;
                $product .= ',';
            }
            $coupon->product_ids =  $product;
        }

        if (!empty($request->featured_sellers)) {
            $seller = '';
            foreach ($request->featured_sellers as $key => $val) {
                $seller .= $val;
                $seller .= ',';
            }
            $coupon->seller_ids = $seller;
        }

        if (!empty($request->users)) {
            $user = '';
            foreach ($request->users as $key => $val) {
                $user .= $val;
                $user .= ',';
            }
            $coupon->user_ids = $user;
        }

        $coupon->save();
        session()->flash('success', 'New Coupon Created!');
        return back();
    }

    public function coupons_edit($id)
    {
        if (is_null($this->user) || !$this->user->can('currencies.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $coupon = Coupon::find($id);

        $brands = Brand::where('is_active', 1)->orderBy('id', 'desc')->get();
        $vendors = Admins::where('status', 1)->orderBy('name', 'asc')->get();
        $vendorArray = [];
        foreach ($vendors as $vendor) {
            if ($vendor->hasRole('seller')) {
                $vendorArray[] = $vendor;
            }
        }

        $users = User::where('status', 1)->orderBy('name', 'asc')->get();

        return view('backend.pages.coupon-edit', compact('coupon', 'brands', 'vendorArray', 'users'));
    }

    public function coupons_update(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('currencies.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'quantity' => 'required',
            'expire' => 'required'
        ]);

        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->use_type = $request->use_type;
        $coupon->minimum_amount = $request->minimum_amount;
        $coupon->amount = $request->amount;
        $coupon->quantity = $request->quantity;
        $coupon->max_qty_per_user = $request->max_qty_per_user;
        $coupon->expire = $request->expire;

        if (!empty($request->featured_categories)) {
            $category = '';
            foreach ($request->featured_categories as $key => $val) {
                $category .= $val;
                $category .= ',';
            }
            $coupon->category_ids = $category;
        }else{
            $coupon->category_ids = '';
        }

        if (!empty($request->brand_id)) {
            $brand = '';
            foreach ($request->brand_id as $key => $val) {
                $brand .= $val;
                $brand .= ',';
            }
            $coupon->brand_ids =  $brand;
        }else{
            $coupon->brand_ids = '';
        }

        if (!empty($request->products)) {
            $product = '';
            foreach ($request->products as $key => $val) {
                $product .= $val;
                $product .= ',';
            }
            $coupon->product_ids =  $product;
        }else{
            $coupon->product_ids = '';
        }

        if (!empty($request->featured_sellers)) {
            $seller = '';
            foreach ($request->featured_sellers as $key => $val) {
                $seller .= $val;
                $seller .= ',';
            }
            $coupon->seller_ids = $seller;
        }else{
            $coupon->seller_ids = '';
        }

        if (!empty($request->users)) {
            $user = '';
            foreach ($request->users as $key => $val) {
                $user .= $val;
                $user .= ',';
            }
            $coupon->user_ids = $user;
        }else{
            $coupon->user_ids = '';
        }

        $coupon->save();
        session()->flash('success', 'Coupon Updated!');
        return back();
    }

    public function coupons_delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('currencies.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('success', 'Coupon Successfully Deleted!');
    }


    public function changePassword()
    {
        return view('backend.pages.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if (Hash::check($request->current_password, Auth::user()->password)) {
            $user = Admins::find(Auth::user()->id);
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Password successfully changed!');
        } else {
            return redirect()->back()->with('failed', 'Current password not match!');
        }
    }

    public function environment()
    {
        if (is_null($this->user) || !$this->user->can('environment.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        return view('backend.parts.designs.environment');
    }

    public function environmentStore(Request $request)
    {

        file_put_contents(app()->environmentFilePath(), str_replace(
            'MAIL_HOST' . '=' . env('MAIL_HOST'),
            'MAIL_HOST' . '=' . $request->host,
            file_get_contents(app()->environmentFilePath())
        ));

        file_put_contents(app()->environmentFilePath(), str_replace(
            'MAIL_PORT' . '=' . env('MAIL_PORT'),
            'MAIL_PORT' . '=' . $request->port,
            file_get_contents(app()->environmentFilePath())
        ));

        file_put_contents(app()->environmentFilePath(), str_replace(
            'MAIL_USERNAME' . '=' . env('MAIL_USERNAME'),
            'MAIL_USERNAME' . '=' . $request->user_name,
            file_get_contents(app()->environmentFilePath())
        ));

        file_put_contents(app()->environmentFilePath(), str_replace(
            'MAIL_PASSWORD' . '=' . env('MAIL_PASSWORD'),
            'MAIL_PASSWORD' . '=' . $request->password,
            file_get_contents(app()->environmentFilePath())
        ));

        file_put_contents(app()->environmentFilePath(), str_replace(
            'MAIL_ENCRYPTION' . '=' . env('MAIL_ENCRYPTION'),
            'MAIL_ENCRYPTION' . '=' . $request->encryption,
            file_get_contents(app()->environmentFilePath())
        ));

        file_put_contents(app()->environmentFilePath(), str_replace(
            'MAIL_FROM_ADDRESS' . '=' . env('MAIL_FROM_ADDRESS'),
            'MAIL_FROM_ADDRESS' . '=' . $request->from_address,
            file_get_contents(app()->environmentFilePath())
        ));

        return redirect()->back()->with('success', 'Email configuration changed successfully!');
    }
}