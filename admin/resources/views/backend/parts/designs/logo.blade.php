<p class="content_title">Logo</p>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Header Logo</label></div>
    <div class="col-sm-9">

        <button type="button" data-image-width="260" data-image-height="96" data-input-name="header_logo"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($x = Helper::getsettings('header_logo'))
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
    <div class="col-sm-3"><label class="col-form-label">Footer Logo</label></div>
    <div class="col-sm-9">

        <button type="button" data-image-width="260" data-image-height="96" data-input-name="footer_logo"
            data-input-type="single" class="btn btn-success initConcaveMedia">Select Image
        </button>

        @if ($x = Helper::getsettings('footer_logo'))
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
