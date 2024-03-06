<p class="content_title">PopUp Banner</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="popup_banner_status" value="0">
                <input name="popup_banner_status" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('popup_banner_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>


<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1" data-image-height="1" data-input-name="popup_banner_image"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($popup_banner_image = Helper::getsettings('popup_banner_image'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $popup_banner_image }}" name="popup_banner_image">
                    <img src="{{ '/' . $popup_banner_image }}">
                    <b data-file-url="{{ $popup_banner_image }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>
<p class="content_title">Header Offer Banner</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="header_offer_banner_status" value="0">
                <input name="header_offer_banner_status" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('header_offer_banner_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>


<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="240" data-image-height="66" data-input-name="header_offer_banner_image"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($header_offer_banner_image = Helper::getsettings('header_offer_banner_image'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $header_offer_banner_image }}" name="header_offer_banner_image">
                    <img src="{{ '/' . $header_offer_banner_image }}">
                    <b data-file-url="{{ $header_offer_banner_image }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>


<div class="form-group row">
    <label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="header_offer_banner_linktype" class="selectpicker form-control">
            <option data-tokens="internal_url" value="internal_url" @if (Helper::getSettings('header_offer_banner_linktype') == 'internal_url') selected @endif>
                Internal URL</option>
            <option data-tokens="external_url" value="external_url" @if (Helper::getSettings('header_offer_banner_linktype') == 'external_url') selected @endif>
                External URL</option>
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
    <div class="col-sm-9">
        <input type="text" name="header_offer_banner_link" class="form-control"
            value="{{ Helper::getSettings('header_offer_banner_link') }}">
    </div>
</div>

<p class="content_title">Vouchers</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="homepage_voucher_status" value="0">
                <input name="homepage_voucher_status" @if ($allSettings['homepage_voucher_status'] == 1) checked @endif type="checkbox"
                    class="form-check-input" value="1">Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>

@php
    $voucherList = Helper::getSettings('homepage_voucher_list');
@endphp

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Select Vouchers<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="homepage_voucher_list[]" data-max-options="20" class="selectpicker form-control"
            data-show-subtext="true" data-live-search="true" multiple>
            @foreach (App\Models\Voucher::where('is_active', 1)->where('is_deleted', 0)->get() as $voucher)
                <option data-tokens="{{ $voucher->id }}" value="{{ $voucher->id }}"
                    @if ($voucherList) @foreach (explode(',', $voucherList) as $settings)
			               		@if ($voucher->id == $settings) selected @endif
                    @endforeach
            @endif
            >{{ $voucher->title }}</option>
            @endforeach
        </select>
    </div>
</div>
<p class="content_title">Featured Sellers</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Title <span class="required">*</span></label>
    <div class="col-sm-9 title_textarea">
        <div class="form-check form-check-flat">
            <input type="text" name="featured_seller_title" class="textEditor form-control"
                value="{{ Helper::getSettings('featured_seller_title') }}" placeholder="Title..">
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="featured_sellers_status" value="0">
                <input name="featured_sellers_status" @if (Helper::getSettings('featured_sellers_status') == 1) checked @endif type="checkbox"
                    class="form-check-input" value="1">Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Select Sellers<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="featured_sellers[]" data-size="10" class="selectpicker form-control" data-show-subtext="true"
            data-live-search="true" multiple>
            @php
                $vendors = DB::table('admins')
                    ->select('admins.id', 'shop_info.name')
                    ->join('shop_info', 'shop_info.seller_id', '=', 'admins.id')
                    ->get();
                $featured_sellersList = Helper::getSettings('featured_sellers');
            @endphp

            @foreach ($vendors as $seller)
                <option
                    @if ($featured_sellersList) @foreach (explode(',', $featured_sellersList) as $settings)
			                   	@if ($seller->id == $settings) selected @endif
                    @endforeach
            @endif
            value="{{ $seller->id }}">{{ $seller->name ?? '' }}</option>
            @endforeach
        </select>
    </div>
</div>

<p class="content_title">Featured Categories</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="featured_categories_status" value="0">
                <input name="featured_categories_status" @if (Helper::getSettings('featured_categories_status') == 1) checked @endif
                    type="checkbox" class="form-check-input" value="1">Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i>
            </label>
        </div>
    </div>
</div>

@php $featured_categoriesList = Helper::getSettings('featured_categories'); @endphp

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Select Categories<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="featured_categories[]" data-size="10" class="selectpicker form-control"
            data-show-subtext="true" data-live-search="true" multiple>
            @foreach ($categories as $category)
                <option data-tokens="{{ $category->title }}"
                    @if ($featured_categoriesList) @foreach (explode(',', $featured_categoriesList) as $settings)
				               	@if ($category->id == $settings) selected @endif
                    @endforeach
            @endif
            value="{{ $category->id }}">{{ $category->title }}</option>

            @foreach (\DB::table('categories')->where('parent_id', $category->id)->get() as $child)
                <option data-tokens="{{ $child->title }}"
                    @if ($featured_categoriesList) @foreach (explode(',', $featured_categoriesList) as $settings)
			                     	@if ($child->id == $settings) selected @endif
                    @endforeach
            @endif
            value="{{ $child->id }}">{{ '¦–– ' . $child->title }}</option>

            @foreach (\DB::table('categories')->where('parent_id', $child->id)->get() as $child2)
                <option data-tokens="{{ $child2->title }}"
                    @if ($featured_categoriesList) @foreach (explode(',', $featured_categoriesList) as $settings)
			                        	@if ($child2->id == $settings) selected @endif
                    @endforeach
            @endif
            value="{{ $child2->id }}">{{ '¦––––' . $child2->title }}</option>
            @endforeach
            @endforeach
            @endforeach
        </select>
    </div>
</div>

<p class="content_title">Bestselling Products</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Title <span class="required">*</span></label>
    <div class="col-sm-9 title_textarea">
        <div class="form-check form-check-flat">
            <input type="text" name="bestselling_products_title" class="textEditor form-control"
                value="{{ Helper::getSettings('bestselling_products_title') }}" placeholder="Title..">
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="bestselling_products_status" value="0">
                <input name="bestselling_products_status" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('bestselling_products_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i>
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1420" data-image-height="290"
            data-input-name="bestselling_products_banner" data-input-type="single"
            class="btn btn-success initConcaveMedia">Select Image
        </button>
        @if ($banner_image = Helper::getsettings('bestselling_products_banner'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $banner_image }}" name="bestselling_products_banner">
                    <img src="{{ '/' . $banner_image }}">
                    <b data-file-url="{{ $banner_image }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>

@php $bestSellingList = Helper::getSettings('bestselling_products'); @endphp
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Select Products<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="bestselling_products[]" data-max-options="20" data-size="10" class="selectpicker form-control"
            data-show-subtext="true" data-live-search="true" multiple>
            @foreach ($product_collection as $product)
                <option data-tokens="{{ $product->title }}" value="{{ $product->id }}"
                    @if ($bestSellingList) @foreach (explode(',', $bestSellingList) as $settings)
	                   			@if ($product->id == $settings) selected @endif
                    @endforeach
            @endif
            >{{ $product->title }}</option>
            @endforeach
        </select>
    </div>
</div>

<p class="content_title">Featured Products</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Title <span class="required">*</span></label>
    <div class="col-sm-9 title_textarea">
        <div class="form-check form-check-flat">
            <input type="text" name="featured_products_title" class="textEditor form-control"
                value="{{ Helper::getSettings('featured_products_title') }}" placeholder="Title..">
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="featured_products_status" value="0">
                <input name="featured_products_status" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('featured_products_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i>
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1420" data-image-height="290"
            data-input-name="featured_products_banner" data-input-type="single"
            class="btn btn-success initConcaveMedia">Select Image
        </button>
        @if ($banner_image = Helper::getsettings('featured_products_banner'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $banner_image }}" name="featured_products_banner">
                    <img src="{{ '/' . $banner_image }}">
                    <b data-file-url="{{ $banner_image }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>

@php $featured_productsList = Helper::getSettings('featured_products'); @endphp

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Featured Products<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="featured_products[]" data-max-options="20" data-size="10" class="selectpicker form-control"
            data-show-subtext="true" data-live-search="true" multiple>
            @foreach ($product_collection as $product)
                <option data-tokens="{{ $product->title }}" value="{{ $product->id }}"
                    @if ($featured_productsList) @foreach (explode(',', $featured_productsList) as $settings)
			                   	@if ($product->id == $settings) selected @endif
                    @endforeach
            @endif
            >{{ $product->title }}</option>
            @endforeach
        </select>
    </div>
</div>

<p class="content_title">Flash Sale Products</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Title <span class="required">*</span></label>
    <div class="col-sm-9 title_textarea">
        <div class="form-check form-check-flat">
            <input type="text" name="flashsale_products_title" class="textEditor form-control"
                value="{{ Helper::getSettings('flashsale_products_title') }}" placeholder="Title..">
        </div>
    </div>
</div>


<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="flash_sale_products_status" value="0">
                <input name="flash_sale_products_status" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('flash_sale_products_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i>
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1420" data-image-height="290"
            data-input-name="flashsale_products_banner" data-input-type="single"
            class="btn btn-success initConcaveMedia">Select Image
        </button>
        @if ($banner_image = Helper::getsettings('flashsale_products_banner'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $banner_image }}" name="flashsale_products_banner">
                    <img src="{{ '/' . $banner_image }}">
                    <b data-file-url="{{ $banner_image }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>

@php $flash_sale_productsList = Helper::getSettings('flash_sale_products'); @endphp

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Expired Date<span class="required">*</span></label>
    <div class="col-sm-9">
        <input type="datetime-local" name="expired_date" value="{{ Helper::getsettings('expired_date') }}"
            class="form-control" />
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Flash Sale Products<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="flash_sale_products[]" data-max-options="20" data-size="10" class="selectpicker form-control"
            data-show-subtext="true" data-live-search="true" multiple>
            @foreach ($product_collection as $product)
                <option data-tokens="{{ $product->title }}" value="{{ $product->id }}"
                    @if ($flash_sale_productsList) @foreach (explode(',', $flash_sale_productsList) as $settings)
			                   	@if ($product->id == $settings) selected @endif
                    @endforeach
            @endif
            >{{ $product->title }}</option>
            @endforeach
        </select>
    </div>
</div>

<p class="content_title">On Sale Products</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Title <span class="required">*</span></label>
    <div class="col-sm-9 title_textarea">
        <div class="form-check form-check-flat">
            <input type="text" name="onsale_products_title" class="textEditor form-control"
                value="{{ Helper::getSettings('onsale_products_title') }}" placeholder="Title..">
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="on_sale_products_status" value="0">
                <input name="on_sale_products_status" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('on_sale_products_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1420" data-image-height="290"
            data-input-name="onsalesale_products_banner" data-input-type="single"
            class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($banner_image = Helper::getsettings('onsalesale_products_banner'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $banner_image }}" name="onsalesale_products_banner">
                    <img src="{{ '/' . $banner_image }}">
                    <b data-file-url="{{ $banner_image }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>

@php $on_sale_productsList = Helper::getSettings('on_sale_products'); @endphp

<div class="form-group row">
    <label class="col-sm-3 col-form-label">On Sale Products<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="on_sale_products[]" data-max-options="20" data-size="10" class="selectpicker form-control"
            data-show-subtext="true" data-live-search="true" multiple>
            @foreach ($product_collection as $product)
                <option data-tokens="{{ $product->title }}" value="{{ $product->id }}"
                    @if ($on_sale_productsList) @foreach (explode(',', $on_sale_productsList) as $settings)
@if ($product->id == $settings) selected @endif
                    @endforeach
            @endif
            >{{ $product->title }}</option>
            @endforeach
        </select>
    </div>
</div>


<p class="content_title">Most Viewed Products</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Title <span class="required">*</span></label>
    <div class="col-sm-9 title_textarea">
        <div class="form-check form-check-flat">
            <input type="text" name="mostvied_products_title" class="textEditor form-control"
                value="{{ Helper::getSettings('mostvied_products_title') }}" placeholder="Title..">
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="most_viewed_products_status" value="0">
                <input name="most_viewed_products_status" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('most_viewed_products_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1420" data-image-height="290"
            data-input-name="most_viewed_products_banner" data-input-type="single"
            class="btn btn-success initConcaveMedia">Select Image
        </button>
        @if ($banner_image = Helper::getsettings('most_viewed_products_banner'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $banner_image }}" name="most_viewed_products_banner">
                    <img src="{{ '/' . $banner_image }}">
                    <b data-file-url="{{ $banner_image }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>

@php $most_viewed_productsList = Helper::getSettings('most_viewed_products'); @endphp
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Most Viewed Products<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="most_viewed_products[]" data-max-options="20" data-size="10" class="selectpicker form-control"
            data-show-subtext="true" data-live-search="true" multiple>
            @foreach ($product_collection as $product)
                <option data-tokens="{{ $product->title }}" value="{{ $product->id }}"
                    @if ($most_viewed_productsList) @foreach (explode(',', $most_viewed_productsList) as $settings)
@if ($product->id == $settings) selected @endif
                    @endforeach
            @endif
            >{{ $product->title }}</option>
            @endforeach
        </select>
    </div>
</div>


{{-- homepage banner 1 --}}
<p class="content_title">Homepage Banner Ad - 1</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="home_banner_status_1" value="0">
                <input name="home_banner_status_1" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('home_banner_status_1') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1420" data-image-height="290" data-input-name="homepage_banner_1"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>
        @if ($homepage_banner_1 = Helper::getsettings('homepage_banner_1'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $homepage_banner_1 }}" name="homepage_banner_1">
                    <img src="{{ '/' . $homepage_banner_1 }}">
                    <b data-file-url="{{ $homepage_banner_1 }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>



<div class="form-group row">
    <label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="homepage_add_banner_linktype_1" class="selectpicker form-control">
            <option data-tokens="internal_url" value="internal_url"
                @if (Helper::getSettings('homepage_add_banner_linktype_1') == 'internal_url') selected @endif>Internal URL</option>
            <option data-tokens="external_url" value="external_url"
                @if (Helper::getSettings('homepage_add_banner_linktype_1') == 'external_url') selected @endif>External URL</option>
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
    <div class="col-sm-9">
        <input type="text" name="homepage_add_banner_link_1" class="form-control"
            value="{{ Helper::getSettings('homepage_add_banner_link_1') }}">
    </div>
</div>


{{-- homepage banner 2 --}}
<p class="content_title">Homepage Banner Ad - 2</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="home_banner_status_2" value="0">
                <input name="home_banner_status_2" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('home_banner_status_2') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>


<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1420" data-image-height="290" data-input-name="homepage_banner_2"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($homepage_banner_2 = Helper::getsettings('homepage_banner_2'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $homepage_banner_2 }}" name="homepage_banner_2">
                    <img src="{{ '/' . $homepage_banner_2 }}">
                    <b data-file-url="{{ $homepage_banner_2 }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>



<div class="form-group row">
    <label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="homepage_add_banner_linktype_2" class="selectpicker form-control">
            <option data-tokens="internal_url" value="internal_url"
                @if (Helper::getSettings('homepage_add_banner_linktype_2') == 'internal_url') selected @endif>Internal URL</option>
            <option data-tokens="external_url" value="external_url"
                @if (Helper::getSettings('homepage_add_banner_linktype_2') == 'external_url') selected @endif>External URL</option>
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
    <div class="col-sm-9">
        <input type="text" name="homepage_add_banner_link_2" class="form-control"
            value="{{ Helper::getSettings('homepage_add_banner_link_2') }}">
    </div>
</div>






{{-- Order From Website --}}
<p class="content_title">How to Order From Website</p>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="930" data-image-height="320"
            data-input-name="how_to_order_from_website_image" data-input-type="multiple"
            class="btn btn-success initConcaveMedia">Select Image
        </button>

        <p class="selected_images_gallery">
            @foreach (explode(',', Helper::getsettings('how_to_order_from_website_image')) as $img)
                @if ($img)
                    <span>
                        <input type="hidden" value="{{ $img }}"
                            name="gallery_images[]">
                        <img src="{{ '/' . $img }}"> <b
                            data-file-url="{{ $img }}"
                            class="selected_image_remove">X</b>
                    </span>
                @endif
            @endforeach
        </p>


    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Description</label>
    <div class="col-sm-9 title_textarea">
        <div class="form-check form-check-flat">
            <textarea type="text" name="how_to_order_from_website_description" class="textEditor form-control"
                placeholder="Description.."> {{ Helper::getSettings('how_to_order_from_website_description') }} </textarea>
        </div>
    </div>
</div>

