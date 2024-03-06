@extends('backend.layouts.master')
@section('title','Change Password - '.config('concave.cnf_appname'))
@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Account > Change Password</span>
		  </div>
	</div>
</div>

<div class="grid-margin">
    <div class="card">
      <div class="card-body">
      <form class="form-sample" action="{{ route('admin.update.password')}}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Current Password <span style="color: #f00">*</span></label>
                <div class="col-sm-9">
                  <input type="text" name="current_password"  class="form-control" required="">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">New Password <span style="color: #f00">*</span></label>
                <div class="col-sm-9">
                  <input type="password" name="password"  class="form-control" required="">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Confirm Password <span style="color: #f00">*</span></label>
                <div class="col-sm-9">
                  <input type="password" name="password_confirmation" class="form-control" required="">
                </div>
              </div>
            </div>
            
            <div class="col-md-12">
              <p class="text-right"> <button type="submit" class="btn btn-success mt-2">Submit</button></p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  @endsection