@extends('backend.layouts.master')
@section('title','Administrator Update - '.config('concave.cnf_appname'))
@section('content')
    
    <div class="grid-margin stretch-card">
      <div class="card">
          <div class="card-body">
        <span class="card-title">Dashboard > Accounts > Administrators > Update Account</span>
        <a class="btn btn-success float-right" href="{{ route('admin.administrator')}}">View Administrators List</a>
          </div>
      </div>
    </div>

			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('admin.administrator.update',$administrator->id) }}">
                      @csrf
                          <p class="content_title">Personal Information</p>
        
                            <div class="row">
        
                            <div class="col-md-6">
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Full Name <span style="color: #f00">*</span></label>
                                <div class="col-sm-8">
                                   <input type="text" name="name" class="form-control" @error('name') is-invalid @enderror value="{{ $administrator->name }}" required  autocomplete="name" autofocus placeholder="Seller Name" >
                                   @error('name')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                             </div>
                            </div>
        
        
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label"> Email</label>
                                  <div class="col-sm-8">
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $administrator->email }}" autocomplete="email" readonly placeholder="Email Address">
                      
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                  </div>
                               </div>
                              </div>
            
                              <div class="form-group col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Phone <span style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                      <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $administrator->phone }}" readonly autocomplete="name" autofocus placeholder="Phone Number">
                                      @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                                    </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Role <span style="color: #f00">*</span></label>
                                  <div class="col-sm-8">
                                    <select name="roles[]" class=" form-control text-capitalize">
                                      @foreach($roles as $role)
                                        <option {{ $administrator->hasRole($role->name) ? 'selected' : '' }} value="{{$role->name}}">{{$role->name}}</option>
                                      @endforeach
                                    </select>
                                  </div>
                               </div>
                              </div>
                              
                             
        
                              <div class="form-group col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Profile Image</label>
                                    <div class="col-sm-8">
                                      <button type="button"   
                                        data-image-width="400" 
                                        data-image-height="400"  
                                        data-input-name="avatar"
                                        data-input-type="single" 
                                        class="btn btn-success initConcaveMedia" >Select Image
                                      </button>

                                      @if($administrator->avatar)
                                        <p class="selected_images_gallery">
                                            <span>
                                              <input type="hidden" value="{{$administrator->avatar}}" name="avatar">
                                              <img src="{{'/'.$administrator->avatar}}"> 
                                              <b data-file-url="{{$administrator->avatar}}" class="selected_image_remove">X</b>
                                            </span>
                                        </p>
                                      @endif

                                    </div>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Status </label>
                                  <div class="col-sm-8">
                                     <div class="form-check form-check-flat">
                                        <label class="switch"><input name="status" type="checkbox"  @if($administrator->status) checked @endif ><span class="slider round" required></span></label>
                                     </div>
                                  </div>
                               </div>
                              </div>

                            </div>

                            <div class="form-group col-md-12">
                              <p class="text-right"><button type="submit" class="btn btn-primary submit-btn">Register</button></p>
                            </div>

                    </form>
                  </div>
                </div>
              </div>
      </div>
@endsection
