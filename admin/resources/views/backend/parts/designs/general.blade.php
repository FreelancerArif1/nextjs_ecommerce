<p class="content_title">General</p>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Site Favicon</label></div>
    <div class="col-sm-9">

        <button type="button" data-image-width="32" data-image-height="32" data-input-name="favicon"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($x = Helper::getsettings('favicon'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $x }}" name="image">
                    <img src="{{ '/' . $x }}">
                    <b data-file-url="{{ $x }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif


    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Payment Method
            Image</label></div>
    <div class="col-sm-9">


        <button type="button" data-image-width="405" data-image-height="82" data-input-name="accepted_payment_image"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($x = Helper::getsettings('accepted_payment_image'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $x }}" name="image">
                    <img src="{{ '/' . $x }}">
                    <b data-file-url="{{ $x }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif

    </div>
</div>

@php $default_currencyList =  Helper::getsettings('default_currency'); @endphp

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Default Currency</label>
    </div>
    <div class="col-sm-9">
        <select class="form-control" name="default_currency">
            @foreach (App\Models\Currency::orderBy('id', 'desc')->get() as $currency)
                <option value="{{ $currency->id }}" @if ($default_currencyList == $currency->id) selected @endif>
                    {{ $currency->title }}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Phone Number</label></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="phone_number"
            value="{{ Helper::getsettings('phone_number') }}">
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Welcome Text</label></div>
    <div class="col-sm-9">
        <input type="text" class="form-control " name="welcome_text"
            value="{{ Helper::getsettings('welcome_text') }}">
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Copyright Text</label>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="copyright_text"
            value="{{ Helper::getsettings('copyright_text') }}">
    </div>
</div>



<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Flagship Banner</label>
    </div>
    <div class="col-sm-9">


        <button type="button" data-image-width="290" data-image-height="45" data-input-name="flagship_banner"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($x = Helper::getsettings('flagship_banner'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $x }}" name="image">
                    <img src="{{ '/' . $x }}">
                    <b data-file-url="{{ $x }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif

    </div>
</div>

@php
$vendors = DB::table('admins')
    ->select('admins.id', 'shop_info.name')
    ->join('shop_info', 'shop_info.seller_id', '=', 'admins.id')
    ->get();

$settings_ids = Helper::getsettings('flagship_ids');
$ids = explode(',', $settings_ids);
@endphp
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Flagship Stores</label>
    <div class="col-sm-9">
        <select name="flagship_ids[]" data-max-options="20" class="selectpicker form-control" data-show-subtext="true"
            data-live-search="true" multiple>
            @foreach ($vendors as $key => $seller)
                <option value="{{ $seller->id }}" @if (in_array($seller->id, $ids)) selected @endif>
                    {{ $seller->name ?? '' }}</option>
            @endforeach
        </select>
    </div>
</div>


@php

$return_policyList = Helper::getsettings('return_policy');
$terms_of_useList = Helper::getsettings('terms_of_use');
$privacy_policyList = Helper::getsettings('privacy_policy');
$warranty_policyList = Helper::getsettings('warranty_policy');
$pages = \DB::table('pages')
    ->where('status', 1)
    ->get();

@endphp


<!-- Pages links -->
<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Return Policy Page</label>
    </div>
    <div class="col-sm-9">
        <select class="form-control" name="return_policy">
            @foreach ($pages as $page)
                <option value="{{ $page->slug }}" @if ($return_policyList == $page->slug) selected @endif>
                    {{ $page->title }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Terms Of Use Page</label>
    </div>
    <div class="col-sm-9">

        <select class="form-control" name="terms_of_use">
            @foreach ($pages as $page)
                <option value="{{ $page->slug }}" @if ($terms_of_useList == $page->slug) selected @endif>
                    {{ $page->title }}</option>
            @endforeach
        </select>

    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Warranty Policy
            Page</label></div>
    <div class="col-sm-9">

        <select class="form-control" name="warranty_policy">
            @foreach ($pages as $page)
                <option value="{{ $page->slug }}" @if ($warranty_policyList == $page->slug) selected @endif>
                    {{ $page->title }}</option>
            @endforeach
        </select>

    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Privacy Policy
            Page</label></div>
    <div class="col-sm-9">

        <select class="form-control" name="privacy_policy">
            @foreach ($pages as $page)
                <option value="{{ $page->slug }}" @if ($privacy_policyList == $page->slug) selected @endif>
                    {{ $page->title }}</option>
            @endforeach
        </select>

    </div>
</div>


<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Meta Title</label></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="site_meta_title"
            value="{{ Helper::getsettings('site_meta_title') }}">
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Meta Keyword</label></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="site_meta_keyword"
            value="{{ Helper::getsettings('site_meta_keyword') }}">
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Meta Description</label>
    </div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="site_meta_description"
            value="{{ Helper::getsettings('site_meta_description') }}">
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Payment/Bank Details </label>
    </div>
    <div class="col-sm-9">
        <textarea type="text" name="company_bank_information" class="textEditor form-control"
                placeholder="Description.."> {{ Helper::getSettings('company_bank_information') }} </textarea>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Loyalty Point Validity (Days) </label>
    </div>
    <div class="col-sm-9">
        <input type="number" min="0" name="loyalty_point_validity_days" class="form-control" value="{{ Helper::getSettings('loyalty_point_validity_days') }}">
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Low Stock Alert</label>
    </div>
    <div class="col-sm-9">
        <input type="number" min="0" name="low_stock_alert" class="form-control" value="{{ Helper::getSettings('low_stock_alert') }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Social Login (ON/OFF)</label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="social_login" value="0">
                <input name="social_login" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('social_login') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>


<!-- Partil Payment start -->
<p class="content_title">Partial Payment</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Enable</label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="partial_payment_enable" value="0">
                <input name="partial_payment_enable" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('partial_payment_enable') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Fixed / Percentage Type</label>
    <div class="col-sm-9">
        <select name="partial_payment_type" class="selectpicker form-control">
            <option data-tokens="fixed" value="fixed" @if (Helper::getSettings('partial_payment_type') == 'fixed') selected @endif> Fixed</option>
            <option data-tokens="percentage" value="percentage" @if (Helper::getSettings('partial_payment_type') == 'percentage') selected @endif> Percentage</option>
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Fixed / Percentage Amount</label>
    <div class="col-sm-9">
        <input type="text" min="0" name="partial_payment_fixed_or_percentage_amount" class="form-control" value="{{ Helper::getSettings('partial_payment_fixed_or_percentage_amount') }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Minimum Amount</label>
    <div class="col-sm-9">
        <input type="text" min="0" name="partial_payment_minimum_amount" class="form-control" value="{{ Helper::getSettings('partial_payment_minimum_amount') }}">
    </div>
</div>
<!-- Partil Payment End -->