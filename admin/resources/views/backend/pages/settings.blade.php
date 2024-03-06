@extends('backend.layouts.master')
@section('title', 'Configuration - ' . config('concave.cnf_appname'))
@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Configuration</span>
            </div>
        </div>
    </div>

    <div class="grid-margin">
        <div class="card">
            <div class="card-body">
                <form class="form-sample" action="{{ route('admin.update.settings') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Application Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="cnf_appname" value="{{ config('concave.cnf_appname') }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Application Description</label>
                                <div class="col-sm-9">
                                    <input type="text" name="cnf_appdesc" value="{{ config('concave.cnf_appdesc') }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Company Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="cnf_comname" value="{{ config('concave.cnf_comname') }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">System Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="cnf_email" value="{{ config('concave.cnf_email') }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Phone Number</label>
                                <div class="col-sm-9">
                                    <input type="text" name="cnf_phone" value="{{ config('concave.cnf_phone') }}"
                                        class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Company Address</label>
                                <div class="col-sm-9">
                                    <textarea type="text" name="cnf_address" class="form-control">{{ config('concave.cnf_address') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Recaptcha Status</label>
                                <div class="col-sm-9">
                                    <select name="cnf_recaptcha" class="form-control">
                                        <option value="1" @if (config('concave.cnf_recaptcha') == 1) selected @endif>Active
                                        </option>
                                        <option value="0" @if (config('concave.cnf_recaptcha') == 0) selected @endif>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Recaptcha Public Key</label>
                                <div class="col-sm-9">
                                    <input type="text" name="cnf_recaptchapublickey"
                                        value="{{ config('concave.cnf_recaptchapublickey') }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Recaptcha Private Key</label>
                                <div class="col-sm-9">
                                    <input type="text" name="cnf_recaptchaprivatekey"
                                        value="{{ config('concave.cnf_recaptchaprivatekey') }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">MailChimp Api Key</label>
                                <div class="col-sm-9">
                                    <input type="text" name="cnf_mailchimpapikey"
                                        value="{{ config('concave.cnf_mailchimpapikey') }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">MailChimp Audience ID</label>
                                <div class="col-sm-9">
                                    <input type="text" name="cnf_mailchimplistid"
                                        value="{{ config('concave.cnf_mailchimplistid') }}" class="form-control">
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Admin Logo</label>
                                <div class="col-sm-9">
                                    <div class="logo-preview preview-upload">
                                        @if (file_exists(public_path() . '/uploads/images/' . config('concave.cnf_logo')) &&
                                            config('concave.cnf_logo') != '')
                                            <img src="{{ asset('uploads/images/' . config('concave.cnf_logo')) }}"
                                                width="100" />
                                        @else
                                            <img src="{{ asset('backend/assets/images/logo.png') }}" width="100" />
                                        @endif
                                    </div>
                                    <p> Please use same dimension image minimum resolution (100px X 100px) </p>
                                    <div class="fileUpload btn">
                                        <input type="file" name="cnf_logo" class="upload"
                                            accept="image/x-png,image/gif,image/jpeg" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-right"> <button type="submit" class="btn btn-success mt-2">Submit</button>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
