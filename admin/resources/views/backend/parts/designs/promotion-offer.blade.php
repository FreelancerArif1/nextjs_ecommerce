<!-- Promotional banner 1 start -->
<div class="promotional_banner promotional_banner_1">
    <p class="content_title">Promotional banner - 1</p>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
        <div class="col-sm-9">
            <div class="form-check form-check-flat">
                <label class="form-check-label">
                    <input type="hidden" name="promotional_1_status" value="0">
                    <input name="promotional_1_status" type="checkbox" class="form-check-input" value="1"
                        @if (Helper::getsettings('promotional_1_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                        class="input-helper"></i></label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Banner Image</label>
        <div class="col-sm-9">
            <button type="button" data-image-width="330" data-image-height="425" data-input-name="promotional_1_banner"
                data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
            </button>
            @if ($promotional_1_banner = Helper::getsettings('promotional_1_banner'))
                <p class="selected_images_gallery">
                    <span>
                        <input type="hidden" value="{{ $promotional_1_banner }}" name="promotional_1_banner">
                        <img src="{{ '/' . $promotional_1_banner }}">
                        <b data-file-url="{{ $promotional_1_banner }}" class="selected_image_remove">X</b>
                    </span>
                </p>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
        <div class="col-sm-9">
            <select name="promotional_1_url_type" class="selectpicker form-control">
                <option data-tokens="internal_url" value="internal_url"
                    @if (Helper::getSettings('promotional_1_url_type') == 'internal_url') selected @endif>Internal URL
                </option>
                <option data-tokens="external_url" value="external_url"
                    @if (Helper::getSettings('promotional_1_url_type') == 'external_url') selected @endif>External URL
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
        <div class="col-sm-9">
            <input type="text" name="promotional_1_url" class="form-control"
                value="{{ Helper::getSettings('promotional_1_url') }}">
        </div>
    </div>
</div>
<!-- Promotional banner 1 End -->

<!-- Promotional banner 2 start -->
<div class="promotional_banner promotional_banner_2">
    <p class="content_title">Promotional banner - 2</p>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
        <div class="col-sm-9">
            <div class="form-check form-check-flat">
                <label class="form-check-label">
                    <input type="hidden" name="promotional_2_status" value="0">
                    <input name="promotional_2_status" type="checkbox" class="form-check-input" value="1"
                        @if (Helper::getsettings('promotional_2_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                        class="input-helper"></i></label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Banner Image</label>
        <div class="col-sm-9">
            <button type="button" data-image-width="330" data-image-height="425" data-input-name="promotional_2_banner"
                data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
            </button>
            @if ($promotional_2_banner = Helper::getsettings('promotional_2_banner'))
                <p class="selected_images_gallery">
                    <span>
                        <input type="hidden" value="{{ $promotional_2_banner }}" name="promotional_2_banner">
                        <img src="{{ '/' . $promotional_2_banner }}">
                        <b data-file-url="{{ $promotional_2_banner }}" class="selected_image_remove">X</b>
                    </span>
                </p>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
        <div class="col-sm-9">
            <select name="promotional_2_url_type" class="selectpicker form-control">
                <option data-tokens="internal_url" value="internal_url"
                    @if (Helper::getSettings('promotional_2_url_type') == 'internal_url') selected @endif>Internal URL
                </option>
                <option data-tokens="external_url" value="external_url"
                    @if (Helper::getSettings('promotional_2_url_type') == 'external_url') selected @endif>External URL
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
        <div class="col-sm-9">
            <input type="text" name="promotional_2_url" class="form-control"
                value="{{ Helper::getSettings('promotional_2_url') }}">
        </div>
    </div>
</div>
<!-- Promotional banner 2 End -->


<!-- Promotional banner 3 start -->
<div class="promotional_banner promotional_banner_3">
    <p class="content_title">Promotional banner - 3</p>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
        <div class="col-sm-9">
            <div class="form-check form-check-flat">
                <label class="form-check-label">
                    <input type="hidden" name="promotional_3_status" value="0">
                    <input name="promotional_3_status" type="checkbox" class="form-check-input" value="1"
                        @if (Helper::getsettings('promotional_3_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                        class="input-helper"></i></label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Banner Image</label>
        <div class="col-sm-9">
            <button type="button" data-image-width="330" data-image-height="212"
                data-input-name="promotional_3_banner" data-input-type="single"
                class="btn btn-success initConcaveMedia">Select Image
            </button>
            @if ($promotional_3_banner = Helper::getsettings('promotional_3_banner'))
                <p class="selected_images_gallery">
                    <span>
                        <input type="hidden" value="{{ $promotional_3_banner }}" name="promotional_3_banner">
                        <img src="{{ '/' . $promotional_3_banner }}">
                        <b data-file-url="{{ $promotional_3_banner }}" class="selected_image_remove">X</b>
                    </span>
                </p>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
        <div class="col-sm-9">
            <select name="promotional_3_url_type" class="selectpicker form-control">
                <option data-tokens="internal_url" value="internal_url"
                    @if (Helper::getSettings('promotional_3_url_type') == 'internal_url') selected @endif>Internal URL
                </option>
                <option data-tokens="external_url" value="external_url"
                    @if (Helper::getSettings('promotional_3_url_type') == 'external_url') selected @endif>External URL
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
        <div class="col-sm-9">
            <input type="text" name="promotional_3_url" class="form-control"
                value="{{ Helper::getSettings('promotional_3_url') }}">
        </div>
    </div>
</div>
<!-- Promotional banner 3 End -->


<!-- Promotional banner 4 start -->
<div class="promotional_banner promotional_banner_4">
    <p class="content_title">Promotional banner - 4</p>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
        <div class="col-sm-9">
            <div class="form-check form-check-flat">
                <label class="form-check-label">
                    <input type="hidden" name="promotional_4_status" value="0">
                    <input name="promotional_4_status" type="checkbox" class="form-check-input" value="1"
                        @if (Helper::getsettings('promotional_4_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                        class="input-helper"></i></label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Banner Image</label>
        <div class="col-sm-9">
            <button type="button" data-image-width="330" data-image-height="212"
                data-input-name="promotional_4_banner" data-input-type="single"
                class="btn btn-success initConcaveMedia">Select Image
            </button>
            @if ($promotional_4_banner = Helper::getsettings('promotional_4_banner'))
                <p class="selected_images_gallery">
                    <span>
                        <input type="hidden" value="{{ $promotional_4_banner }}" name="promotional_4_banner">
                        <img src="{{ '/' . $promotional_4_banner }}">
                        <b data-file-url="{{ $promotional_4_banner }}" class="selected_image_remove">X</b>
                    </span>
                </p>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
        <div class="col-sm-9">
            <select name="promotional_4_url_type" class="selectpicker form-control">
                <option data-tokens="internal_url" value="internal_url"
                    @if (Helper::getSettings('promotional_4_url_type') == 'internal_url') selected @endif>Internal URL
                </option>
                <option data-tokens="external_url" value="external_url"
                    @if (Helper::getSettings('promotional_4_url_type') == 'external_url') selected @endif>External URL
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
        <div class="col-sm-9">
            <input type="text" name="promotional_4_url" class="form-control"
                value="{{ Helper::getSettings('promotional_4_url') }}">
        </div>
    </div>
</div>
<!-- Promotional banner 4 End -->

<!-- Promotional banner 5 start -->
<div class="promotional_banner promotional_banner_5">
    <p class="content_title">Promotional banner - 5</p>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
        <div class="col-sm-9">
            <div class="form-check form-check-flat">
                <label class="form-check-label">
                    <input type="hidden" name="promotional_5_status" value="0">
                    <input name="promotional_5_status" type="checkbox" class="form-check-input" value="1"
                        @if (Helper::getsettings('promotional_5_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                        class="input-helper"></i></label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Banner Image</label>
        <div class="col-sm-9">
            <button type="button" data-image-width="330" data-image-height="212"
                data-input-name="promotional_5_banner" data-input-type="single"
                class="btn btn-success initConcaveMedia">Select Image
            </button>
            @if ($promotional_5_banner = Helper::getsettings('promotional_5_banner'))
                <p class="selected_images_gallery">
                    <span>
                        <input type="hidden" value="{{ $promotional_5_banner }}" name="promotional_5_banner">
                        <img src="{{ '/' . $promotional_5_banner }}">
                        <b data-file-url="{{ $promotional_5_banner }}" class="selected_image_remove">X</b>
                    </span>
                </p>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
        <div class="col-sm-9">
            <select name="promotional_5_url_type" class="selectpicker form-control">
                <option data-tokens="internal_url" value="internal_url"
                    @if (Helper::getSettings('promotional_5_url_type') == 'internal_url') selected @endif>Internal URL
                </option>
                <option data-tokens="external_url" value="external_url"
                    @if (Helper::getSettings('promotional_5_url_type') == 'external_url') selected @endif>External URL
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
        <div class="col-sm-9">
            <input type="text" name="promotional_5_url" class="form-control"
                value="{{ Helper::getSettings('promotional_5_url') }}">
        </div>
    </div>
</div>
<!-- Promotional banner 4 End -->

<!-- Promotional banner 6 start -->
<div class="promotional_banner promotional_banner_6">
    <p class="content_title">Promotional banner - 6</p>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Status<span class="required">*</span></label>
        <div class="col-sm-9">
            <div class="form-check form-check-flat">
                <label class="form-check-label">
                    <input type="hidden" name="promotional_6_status" value="0">
                    <input name="promotional_6_status" type="checkbox" class="form-check-input" value="1"
                        @if (Helper::getsettings('promotional_6_status') == 1) checked @endif>Eanbled<i class="input-helper"></i><i
                        class="input-helper"></i></label>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Banner Image</label>
        <div class="col-sm-9">
            <button type="button" data-image-width="330" data-image-height="212"
                data-input-name="promotional_6_banner" data-input-type="single"
                class="btn btn-success initConcaveMedia">Select Image
            </button>
            @if ($promotional_6_banner = Helper::getsettings('promotional_6_banner'))
                <p class="selected_images_gallery">
                    <span>
                        <input type="hidden" value="{{ $promotional_6_banner }}" name="promotional_6_banner">
                        <img src="{{ '/' . $promotional_6_banner }}">
                        <b data-file-url="{{ $promotional_6_banner }}" class="selected_image_remove">X</b>
                    </span>
                </p>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link Type<span class="required">*</span></label>
        <div class="col-sm-9">
            <select name="promotional_6_url_type" class="selectpicker form-control">
                <option data-tokens="internal_url" value="internal_url"
                    @if (Helper::getSettings('promotional_6_url_type') == 'internal_url') selected @endif>Internal URL
                </option>
                <option data-tokens="external_url" value="external_url"
                    @if (Helper::getSettings('promotional_6_url_type') == 'external_url') selected @endif>External URL
                </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link <span class="required">*</span></label>
        <div class="col-sm-9">
            <input type="text" name="promotional_6_url" class="form-control"
                value="{{ Helper::getSettings('promotional_6_url') }}">
        </div>
    </div>
</div>
<!-- Promotional banner 4 End -->
