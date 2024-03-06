@extends('backend.layouts.master')
@section('title','Administrator Create - '.config('concave.cnf_appname'))
@section('content')
	<div class="grid-margin stretch-card">
		<div class="card">
			  <div class="card-body">
		  <span class="card-title">Dashboard > Accounts > Users > Create Administrator</span>
		  <a class="btn btn-success float-right" href="{{ route('admin.administrator')}}">View Administrator List</a>
			  </div>
		</div>
	</div>
			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('admin.administrator.store') }}" enctype="multipart/form-data" >
                      @csrf
                          <p class="content_title">Personal Information</p>
        
                            <div class="row">
        
                            <div class="col-md-6">
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Full Name <span style="color: #f00">*</span></label>
                                <div class="col-sm-8">
                                   <input type="text" name="name" class="form-control" @error('name') is-invalid @enderror value="{{ old('name') }}" required  autocomplete="name" autofocus placeholder="Full Name" >
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
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Email Address">
                      
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
                                      <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="name" autofocus placeholder="Phone Number">
                                      @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                                    </div>
                                </div>
                              </div>
        
                              
                              <div class="form-group col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Role</label>
                                    <div class="col-sm-8">
                                      <select name="roles[]" class="selectpicker form-control text-capitalize">
                                        @foreach($roles as $role)
                                          <option  value="{{$role->name}}">{{$role->name}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                </div>
                              </div>

                              <div class="form-group col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label ">Password <span style="color: #f00">*</span></label>
                                    <div class="col-sm-8">
                                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="name" autofocus placeholder="Password">
                                      @error('password')
                                        <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
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
                                    </div>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Status </label>
                                  <div class="col-sm-8">
                                     <div class="form-check form-check-flat">
                                        <label class="switch"><input name="status" type="checkbox" checked ><span class="slider round" required></span></label>
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
