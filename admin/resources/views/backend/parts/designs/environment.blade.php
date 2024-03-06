@extends('backend.layouts.master')
@section('page_title', 'Environments')
@section('content')


    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Settings > Environments</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="content_title">Email Configurations</p>
                            <form class="form-sample row" method="post" action="{{ route('admin.designs.environment.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Host <span class="required">*</span></label>
                                        <input type="text" name="host" class="form-control" placeholder="mi3-ts11.a2hosting.com" required="" value="{{ env('MAIL_HOST') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Port <span class="required">*</span></label>
                                        <input type="text" name="port" class="form-control" placeholder="465" required="" value="{{ env('MAIL_PORT') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Username <span class="required">*</span></label>
                                        <input type="text" name="user_name" class="form-control" placeholder="info@xyz.com" required="" value="{{ env('MAIL_USERNAME') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password <span class="required">*</span></label>
                                        <input type="text" name="password" class="form-control" placeholder="12345678" required="" value="{{ env('MAIL_PASSWORD') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Encryption <span class="required">*</span></label>
                                        <input type="text" name="encryption" class="form-control" placeholder="ssl" required="" value="{{ env('MAIL_ENCRYPTION') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From Address<span class="required">*</span></label>
                                        <input type="text" name="from_address" class="form-control" placeholder="info@xyz.com" required="" value="{{ env('MAIL_FROM_ADDRESS') }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                	<p class="text-right">
                                		<button type="submit" class="btn btn-primary">Apply Changes</button>
	                                </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
