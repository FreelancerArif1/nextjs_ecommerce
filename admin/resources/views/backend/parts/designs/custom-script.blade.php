<p class="content_title">Header & Footer Scripts</p>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Header Script</label>
    </div>
    <div class="col-sm-9">
        <textarea type="text" class="form-control" style="min-height:350px;" name="website_header_script">{{ Helper::getsettings('website_header_script') }}</textarea>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Footer Script</label>
    </div>
    <div class="col-sm-9">
        <textarea type="text" class="form-control" style="min-height:350px;" name="website_footer_script">{{ Helper::getsettings('website_footer_script') }}</textarea>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Body Script</label>
    </div>
    <div class="col-sm-9">
        <textarea type="text" class="form-control" style="min-height:350px;" name="website_body_script">{{ Helper::getsettings('website_body_script') }}</textarea>
    </div>
</div>
