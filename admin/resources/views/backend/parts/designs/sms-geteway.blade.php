<p class="content_title">SMS Gateway</p>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Default SMS
            Provider</label></div>
    <div class="col-sm-9">
        <select class="form-control sms_gateway_default_provider" name="sms_gateway_default_provider">
            <option @if (Helper::getsettings('sms_gateway_default_provider') == 'mimsms') selected="" @endif value="mimsms">Mim SMS</option>
            <option @if (Helper::getsettings('sms_gateway_default_provider') == 'sslwireless') selected="" @endif value="sslwireless">SSL Wireless</option>
            <option @if (Helper::getsettings('sms_gateway_default_provider') == 'icombd') selected="" @endif value="icombd">Icombd</option>
            <option @if (Helper::getsettings('sms_gateway_default_provider') == 'metrotel') selected="" @endif value="metrotel">Metrotel</option>
        </select>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3"><label class="col-form-label">Default SMS Sender</label>
    </div>
    <div class="col-sm-9">
        <div class="form-check form-check-flat">
            <label class="form-check-label" style="text-transform: capitalize;">
                <input name="sms_gateway_default_sender" type="radio" @if (Helper::getsettings('sms_gateway_default_sender') == 'musking') checked @endif
                    class="form-check-input" value="musking">Musking SMS<i class="input-helper"></i>
            </label>
        </div>

        <div class="form-check form-check-flat">
            <label class="form-check-label" style="text-transform: capitalize;">
                <input name="sms_gateway_default_sender" type="radio" @if (Helper::getsettings('sms_gateway_default_sender') == 'non_musking') checked @endif
                    class="form-check-input" value="non_musking">Non Musking SMS<i class="input-helper"></i>
            </label>
        </div>

    </div>
</div>

<div class="musking_api_key_area">
    <div class="form-group row ">
        <div class="col-sm-3"><label class="col-form-label">Musking Api Key</label>
        </div>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="sms_gateway_musking_api_key"
                value="{{ Helper::getsettings('sms_gateway_musking_api_key') }}">
        </div>
    </div>
</div>

<div class="sender_id_area_musking">
    <div class="form-group row ">
        <div class="col-sm-3"><label class="col-form-label">Musking Sender ID</label>
        </div>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="sms_gateway_musking_sender_id"
                value="{{ Helper::getsettings('sms_gateway_musking_sender_id') }}">
        </div>
    </div>
</div>


<div class="nonmusking_api_key_area">
    <div class="form-group row ">
        <div class="col-sm-3"><label class="col-form-label">Non Musking Api
                Key</label></div>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="sms_gateway_non_musking_api_key"
                value="{{ Helper::getsettings('sms_gateway_non_musking_api_key') }}">
        </div>
    </div>
</div>

<div class="nonmusking_sender_id_area">
    <div class="form-group row">
        <div class="col-sm-3"><label class="col-form-label">Non Musking Sender
                ID</label></div>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="sms_gateway_non_musking_sender_id"
                value="{{ Helper::getsettings('sms_gateway_non_musking_sender_id') }}">
        </div>
    </div>
</div>

<div class="sms_gateway_username_area @if (Helper::getsettings('sms_gateway_default_provider') != 'icombd') d-none @endif">
    <div class="form-group row">
        <div class="col-sm-3">
            <label class="col-form-label">User Name</label>
        </div>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="sms_gateway_username"
                value="{{ Helper::getsettings('sms_gateway_username') }}">
        </div>
    </div>
</div>

<div class="sms_gateway_password_area @if (Helper::getsettings('sms_gateway_default_provider') != 'icombd') d-none @endif">
    <div class="form-group row ">
        <div class="col-sm-3"><label class="col-form-label">Password</label></div>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="sms_gateway_password"
                value="{{ Helper::getsettings('sms_gateway_password') }}">
        </div>
    </div>
</div>

