<p class="content_title">Regular Offer</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="regular_offer_status" value="0">
                <input name="regular_offer_status" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('regular_offer_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Section Name<span class="required">*</span></label></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="regular_offer_section_name"
            value="{{ Helper::getsettings('regular_offer_section_name') }}" />
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1420" data-image-height="290" data-input-name="regular_offer_banner"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($banner_image = Helper::getsettings('regular_offer_banner'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $banner_image }}" name="regular_offer_banner">
                    <img src="{{ '/' . $banner_image }}">
                    <b data-file-url="{{ $banner_image }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>

@php  $regular_offer_categoryList = Helper::getSettings('regular_offer_category'); @endphp

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Select Categories<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="regular_offer_category[]" class="selectpicker form-control" data-show-subtext="true"
            data-live-search="true" multiple>
            @foreach ($categories as $category)
                <option data-tokens="{{ $category->title }}"
                    @if ($regular_offer_categoryList) @foreach (explode(',', $regular_offer_categoryList) as $settings)
@if ($category->id == $settings) selected @endif
                    @endforeach
            @endif
            value="{{ $category->id }}">{{ $category->title }}</option>
            @foreach (\DB::table('categories')->where('parent_id', $category->id)->get() as $child)
                <option data-tokens="{{ $child->title }}"
                    @if ($regular_offer_categoryList) @foreach (explode(',', $regular_offer_categoryList) as $settings)
@if ($child->id == $settings) selected @endif
                    @endforeach
            @endif
            value="{{ $child->id }}">{{ '¦–– ' . $child->title }}</option>
            @foreach (\DB::table('categories')->where('parent_id', $child->id)->get() as $child2)
                <option data-tokens="{{ $child2->title }}"
                    @if ($regular_offer_categoryList) @foreach (explode(',', $regular_offer_categoryList) as $settings)
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


<p class="content_title">Promotional Offer</p>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label">
                <input type="hidden" name="promotional_offer_status" value="0">
                <input name="promotional_offer_status" type="checkbox" class="form-check-input" value="1"
                    @if (Helper::getsettings('promotional_offer_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                    class="input-helper"></i></label>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Section Name<span class="required">*</span></label></div>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="promotional_offer_section_name"
            value="{{ Helper::getsettings('promotional_offer_section_name') }}" />
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Banner Image</label>
    <div class="col-sm-9">
        <button type="button" data-image-width="1420" data-image-height="290"
            data-input-name="promotional_offer_banner" data-input-type="single"
            class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($banner_image = Helper::getsettings('promotional_offer_banner'))
            <p class="selected_images_gallery">
                <span>
                    <input type="hidden" value="{{ $banner_image }}" name="promotional_offer_banner">
                    <img src="{{ '/' . $banner_image }}">
                    <b data-file-url="{{ $banner_image }}" class="selected_image_remove">X</b>
                </span>
            </p>
        @endif
    </div>
</div>

@php $promotional_offer_categoryList = Helper::getSettings('promotional_offer_category'); @endphp

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Select Categories<span class="required">*</span></label>
    <div class="col-sm-9">
        <select name="promotional_offer_category[]" class="selectpicker form-control" data-show-subtext="true"
            data-live-search="true" multiple>
            @foreach ($categories as $category)
                <option data-tokens="{{ $category->title }}"
                    @if ($promotional_offer_categoryList) @foreach (explode(',', $promotional_offer_categoryList) as $settings)
@if ($category->id == $settings) selected @endif
                    @endforeach
            @endif
            value="{{ $category->id }}">{{ $category->title }}</option>
            @foreach (\DB::table('categories')->where('parent_id', $category->id)->get() as $child)
                <option data-tokens="{{ $child->title }}"
                    @if ($promotional_offer_categoryList) @foreach (explode(',', $promotional_offer_categoryList) as $settings)
@if ($child->id == $settings) selected @endif
                    @endforeach
            @endif
            value="{{ $child->id }}">{{ '¦–– ' . $child->title }}</option>
            @foreach (\DB::table('categories')->where('parent_id', $child->id)->get() as $child2)
                <option data-tokens="{{ $child2->title }}"
                    @if ($promotional_offer_categoryList) @foreach (explode(',', $promotional_offer_categoryList) as $settings)
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
