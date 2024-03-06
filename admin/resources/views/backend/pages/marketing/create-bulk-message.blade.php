@extends('backend.layouts.master')
@section('title', 'Send Bulk Message - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Matrketing > Send Bulk Message</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form class="form-sample" method="post" action="{{ route('admin.marketing.send.bulk.message') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Title<span style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="title" placeholder="Ex: New Year Gretings"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Select Customer<span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="customer[]" class="form-control selectpicker" data-live-search="true"
                                            multiple required>
                                            <option value="-1">All Customer</option>
                                            @foreach ($customers as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name . ' - ' . $item->phone . ' - ' . $item->email }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Channel <span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-check form-check-flat font_small_11">
                                                    <label class="form-check-label">
                                                        <input type="radio" name="channel" value="sms"
                                                            class="form-check-input" required>SMS<i
                                                            class="input-helper"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-check form-check-flat font_small_11">
                                                    <label class="form-check-label">
                                                        <input type="radio" name="channel" value="email"
                                                            class="form-check-input" required>EMAIL<i
                                                            class="input-helper"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 sms_item">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">SMS Gateway</label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-check form-check-flat font_small_11">
                                                    <label class="form-check-label">
                                                        <input type="radio" name="gateway_option"
                                                            class="form-check-input">Musking<i class="input-helper"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-check form-check-flat font_small_11">
                                                    <label class="form-check-label">
                                                        <input type="radio" name="gateway_option" class="form-check-input"
                                                            checked>Non Musking<i class="input-helper"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="row email_item">

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Email Header<span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="email_header" placeholder="Ex: Happy New Year"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 email_item">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Email Body</label>
                                    <div class="col-sm-10">
                                        <textarea type="text" name="email_body" placeholder="Description" class="form-control textEditor"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row sms_item">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Message <br> <small class="text-danger">You have
                                            to send message in Bengali language</small> </label>
                                    <div class="col-sm-10">
                                        <textarea type="text" name="message_body" placeholder="Message Body" class="form-control "></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-right">
                                        <button class="btn btn-primary" name="save" type="submit">Send Bulk
                                            Message</button>
                                    </p>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('footer')
        <script>
            jQuery(document).on('change', 'input[name="channel"]', function() {
                if (jQuery(this).val() == 'sms') {
                    jQuery('.email_item').hide();
                    jQuery('.sms_item').show();
                } else if (jQuery(this).val() == 'email') {
                    jQuery('.sms_item').hide();
                    jQuery('.email_item').show();
                }
            });
        </script>
    @endpush


@endsection
