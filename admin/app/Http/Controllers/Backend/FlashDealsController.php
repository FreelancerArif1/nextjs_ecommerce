<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashDeal;
use App\Models\FlashDealLocalization;
use App\Models\User;
use App\Models\Product;
use App\Models\FlashDealProducts;
use Image;
use Auth;
use Helper;

class FlashDealsController extends Controller
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
        if (is_null($this->user) || !$this->user->can('flash_deals.view')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $flash_deal = FlashDeal::orderBy('sort_order', 'desc')->get();
        //dd($flash_deal );
        return view('backend.pages.flash_deal.list')->with('flash_deals', $flash_deal);
    }


    public function create()
    {
        if (is_null($this->user) || !$this->user->can('flash_deals.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $flash_deals = FlashDeal::orderBy('id', 'desc')->get();
        return view('backend.pages.flash_deal.create')->with('flash_deals', $flash_deals);
    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('flash_deals.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $flash_deal = FlashDeal::find($id);
        
        $flash_deals = FlashDeal::orderBy('id', 'desc')->get();
        return view('backend.pages.flash_deal.edit')->with(
            array(
                'flash_deal' => $flash_deal,
                'flash_deals' => $flash_deals
            )
        );
    }


    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('flash_deals.create')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'nullable|max:190',
            'slug' => 'required | unique:flash_deals',
            'button_title' => 'max:190 | nullable',
            'button_link' =>  'max:190 | nullable',
        ]);

        $flash_deal = new FlashDeal();
        $flash_deal->title = $request->title;
        $flash_deal->slug = $request->slug;
        $flash_deal->button_title = $request->button_title;
        $flash_deal->button_link = $request->button_link;
        $flash_deal->background_color = $request->background_color;
        $flash_deal->text_color = $request->text_color;
        $flash_deal->banner = $request->banner;
        $flash_deal->start_date = $request->from_date;
        $flash_deal->end_date = $request->end_date;
        $flash_deal->meta_title = $request->meta_title;
        $flash_deal->meta_keyword = $request->meta_keyword;
        $flash_deal->meta_description = $request->meta_description;
        $flash_deal->show_category_wise = $request->show_category_wise ? 1 : 0;
        $flash_deal->status = $request->status ? 1 : 0;
        $flash_deal->is_grocery = $request->is_grocery ? 1 : 0;
        $flash_deal->product_ids = implode(',', $request->selected_products);
        $flash_deal->save();

        if ($request->product_ids) {
            for ($i = 0; $i < count($request->selected_products); $i++) {
                $product = Product::find($request->selected_products[$i]);
                $product->special_price = $request->product_discount[$i];
                $product->special_price_type = $request->discount_type[$i];
                $product->special_price_start = $request->from_date;
                $product->special_price_end = $request->end_date;
                $product->save();
            }
        }

        // if ($request->selected_products) {
        //     $sort = count($request->selected_products);
        //     for ($i = 0; $i < count($request->selected_products); $i++) {
        //         $flash_deal_product = new FlashDealProducts();
        //         $flash_deal_product->flash_deal_id = $flash_deal->id;
        //         $flash_deal_product->product_id = $request->selected_products[$i];
        //         $flash_deal_product->start_date = $request->from_date;
        //         $flash_deal_product->end_date = $request->end_date;
        //         $flash_deal_product->special_price_type = $request->discount_type[$i];
        //         $flash_deal_product->special_price = $request->product_discount[$i];
        //         $flash_deal_product->short_order = $sort;
        //         $flash_deal_product->save();
        //         $sort--;
        //     }
        // }

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
            $flash_deal_localization = new FlashDealLocalization();
            $flash_deal_localization->flash_deal_id = $flash_deal->id;
            $flash_deal_localization->title = $data['title'];
            $flash_deal_localization->lang_code = $data['lang_code'];
            // $flash_deal_localization->flash_deal_text = $data['flash_deal_text'];
            // $flash_deal_localization->button_title = $data['button_title'];
            $flash_deal_localization->save();
        }

        return redirect()->route('admin.flash_deal')->with('success', 'FlashDeal successfully created!');
    }




    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('flash_deals.edit')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $request->validate([
            'title' => 'nullable|max:190',
            'button_title' => 'max:190 | nullable',
            'button_link' =>  'max:190 | nullable',
        ]);

        $flash_deal = FlashDeal::find($id);
        $flash_deal->title = $request->title;
        $flash_deal->button_title = $request->button_title;
        $flash_deal->button_link = $request->button_link;
        $flash_deal->background_color = $request->background_color;
        $flash_deal->text_color = $request->text_color;
        $flash_deal->banner = $request->banner;
        $flash_deal->start_date = $request->from_date;
        $flash_deal->end_date = $request->end_date;
        $flash_deal->meta_title = $request->meta_title;
        $flash_deal->meta_keyword = $request->meta_keyword;
        $flash_deal->meta_description = $request->meta_description;
        $flash_deal->show_category_wise = $request->show_category_wise ? 1 : 0;
        $flash_deal->status = $request->status ? 1 : 0;
        $flash_deal->is_grocery = $request->is_grocery ? 1 : 0;
        $flash_deal->product_ids = implode(',', $request->selected_products);
        $flash_deal->save();

        if ($request->product_ids) {
            for ($i = 0; $i < count($request->selected_products); $i++) {
                $product = Product::find($request->selected_products[$i]);
                $product->special_price = $request->product_discount[$i];
                $product->special_price_type = $request->discount_type[$i];
                $product->special_price_start = $request->from_date;
                $product->special_price_end = $request->end_date;
                $product->save();
            }
        }

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

            $flash_deal_localization = FlashDealLocalization::where('flash_deal_id', $flash_deal->id)->where('lang_code', $data['lang_code'])->first();

            if ($flash_deal_localization !== null) {
                $flash_deal_localization->flash_deal_id = $flash_deal->id;
                $flash_deal_localization->title = $data['title'];
                $flash_deal_localization->lang_code = $data['lang_code'];
                $flash_deal_localization->save();
            } else {
                $flash_deal_localization = new FlashDealLocalization();
                $flash_deal_localization->flash_deal_id = $flash_deal->id;
                $flash_deal_localization->title = $data['title'];
                $flash_deal_localization->lang_code = $data['lang_code'];
                $flash_deal_localization->save();
            }
        }

        return redirect()->route('admin.flash_deal', $flash_deal->id)->with('success', 'FlashDeal successfully updated!');
    }



    public function delete(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('flash_deals.delete')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $flash_deal = FlashDeal::find($id);
        $image = 'uploads/images/flash_deals/' . $flash_deal->image;
        if (file_exists($image)) {
            unlink($image);
        }
        $flash_deal->delete();
        return redirect()->route('admin.flash_deal')->with('success', 'FlashDeal successfully deleted!');
    }


    public function reorder(Request $request)
    {
        $ids = $request->flash_deal_ids;
        $sort = count($ids);
        foreach ($ids as $key => $value) {
            $flash_deal = FlashDeal::find($value);
            $flash_deal->sort_order = $sort;
            $flash_deal->save();
            $sort--;
        }
        return redirect()->back()->with('success', 'FlashDeal successfully re-ordered!');
    }

    public function getProducts($ids, Request $request)
    {

        $flas_deal = null;
        if (isset($request->id)) {
            $flash_deal_id = $request->id;
            $flas_deal = FlashDeal::find($flash_deal_id);
        }

        $product_ids = explode(',', $ids);


        $products = Product::whereIn('id', $product_ids)->orderByRaw("FIELD(id, $ids)")->get();

        return view('backend.pages.flash_deal.load-products', compact('products', 'flas_deal'));
    }


    public function sendPushNotification($id){
        if (is_null($this->user) || !$this->user->can('flash_deals.pushnotification')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $deal = FlashDeal::find($id);
        if ($deal) {
            $title = $deal->title;
            $link = env('APP_FRONTEND') . '/flash-deal/' . $deal->slug;
            if($deal->banner){
                $image = env('APP_API_URL').'/'.$deal->banner;
            }else{
                $image = null;
            }

            $message_body = ' ডিল সম্পর্কে আরো জানতে এখনই ভিজিট করুন। ';

            $message = [
                'type' =>'deal',
                'message' => $message_body,
                'deal_slug'  => $deal->slug
            ];

            $title = $deal->title;


            // SEND Notification TO ALL USER 
            foreach (User::where('is_deleted', 0)->where('status',1)->get() as $row) {
                Helper::sendPushNotification($row->id,1,$title,$message_body,json_encode($message),$image,$link);
            }

            return redirect()->back()->with('success', 'Notification successfully sended!');

        }else{
            return redirect()->back()->with('failed', 'No deals found!');
        }
    }

    public function sendSms($id){
        if (is_null($this->user) || !$this->user->can('flash_deals.sms')) {
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }
        $deal = FlashDeal::find($id);

        if ($deal) {
            $link = env('APP_FRONTEND') . '/flash-deal/' . $deal->slug;

            $sms = $deal->title.' ডিল সম্পর্কে আরো জানতে এখনই ভিজিট করুন। '.$link;

            foreach (User::where('is_deleted', 0)->where('status', 1)->get() as $row) {
                $smsReponse = Helper::sendSms($row->phone, $sms);
            }
            return redirect()->back()->with('success', 'SMS successfully sended!');
        }else{
            return redirect()->back()->with('failed', 'No deals found!');
        }
    }
}