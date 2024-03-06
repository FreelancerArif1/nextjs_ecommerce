@extends('backend.layouts.master')
@section('title', 'Send Push Notification - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Report > Send Push Notification</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form class="form-sample" method="post" action="{{ route('admin.report.push.notification.send') }}"
                        enctype="multipart/form-data">
                        @csrf


                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Notification Title<span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="notification_title" placeholder="Ex: Happy New Year"
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
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $id ? 'selected' : '' }}>
                                                    {{ $item->name . ' - ' . $item->phone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Notification Body</label>
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
                                        <button class="btn btn-primary" name="save" type="submit">Send Push
                                            Notification</button>
                                    </p>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
