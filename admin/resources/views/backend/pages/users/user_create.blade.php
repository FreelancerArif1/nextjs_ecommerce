@extends('backend.layouts.master')
@section('title','Create Cuatomer - '.config('concave.cnf_appname'))
@section('content')
  <div class="grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
      <span class="card-title">Dashboard > Accounts > Customer > Create Customer</span>
      <a class="btn btn-success float-right" href="{{ route('admin.user')}}">View User List</a>
        </div>
    </div>
  </div>

			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                   <form class="form-sample" method="post" action="{{ route('admin.user.store') }}" >
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
                                        <label class="switch"><input name="status" type="checkbox" checked ><span class="slider round" ></span></label>
                                     </div>
                                  </div>
                               </div>
                              </div>
                              

                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Division<span style="color: #f00">*</span></label>
                                  <div class="col-sm-8">
                                    <select class=" form-control" @error('division_id') is-invalid @enderror name="division_id" value="{{ old('division_id') }}" data-show-subtext="true" data-live-search="true" name="division_id" id="division_id" required="">
                                      <option>Select Division</option>
                                      @foreach(App\Models\Division::orderBy('title','asc')->get() as $division)
                                        <option value="{{ $division->id }}">{{ $division->title }}</option>
                                      @endforeach
                                    </select>
                                     @error('division_id')
                                        <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                                  </div>
                               </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">District<span style="color: #f00">*</span></label>
                                  <div class="col-sm-8">
                                    <select class=" form-control" id="district_id" @error('district_id') is-invalid @enderror name="district_id" value="{{ old('district_id') }}" data-show-subtext="true" data-live-search="true" required="">
                                      <option value="" selected disabled>--Select Division First--</option>
                                      
                                    </select>
                                    
                                     @error('district_id')
                                        <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                                  </div>
                               </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Upazila / Thana<span style="color: #f00">*</span></label>
                                  <div class="col-sm-8">
                                      <select class=" form-control" @error('upazila_id') is-invalid @enderror name="upazila_id" value="{{ old('upazila_id') }}" data-show-subtext="true" data-live-search="true" id="upazila_id" required="">
                                      <option value="" selected disabled>--Select District First--</option>
                                    </select>
                                     @error('upazila_id')
                                        <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                                  </div>
                               </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Union / Area<span style="color: #f00">*</span></label>
                                  <div class="col-sm-8">
                                      <select class=" form-control" @error('union_id') is-invalid @enderror name="union_id" value="{{ old('union_id') }}" data-show-subtext="true" data-live-search="true" id="union_id" required="">
                                        <option value="" selected disabled>--Select Upazila First--</option>
                                      </select>
                                     @error('union_id')
                                        <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                                  </div>
                               </div>
                              </div>


                              <div class="col-md-12">
                                <div class="form-group row">
                                  <label class="col-sm-2 col-form-label">Address<span style="color: #f00">*</span></label>
                                  <div class="col-sm-10">
                                     <input type="text" name="street_address" class="form-control" required  autocomplete="name" autofocus placeholder="Address" >
                                     @error('street_address')
                                        <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
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

  <!--   
  @push('footer')
    <script type="text/javascript">
       //Get vendor district
       jQuery(document).on("change", "#division_id", function(){
         // jQuery('.ajax_loader').show();
            var nonce = jQuery(this).attr("data-nonce");
            var division_id = jQuery(this).find('option:selected').val();
            if(division_id != -1){
              jQuery.ajax({
                type : "POST",
                url : "/admin/seller/get-district",
                data : {division_id: division_id},
                success: function(response) {
                    // alert(response);
                    jQuery('#district_id').html(response);
                    $('.selectpicker').selectpicker('refresh');
                }
              });
            }
        });

        //Get vendor upazila
        jQuery(document).on("change", "#district_id", function(){
         jQuery('.ajax_loader').show();
            var nonce = jQuery(this).attr("data-nonce");
            var district_id = jQuery(this).find('option:selected').val();
            if(division_id != -1){
                    jQuery.ajax({
                    type : "POST",
                    url : "/admin/seller/get-upazila",
                    data : {district_id: district_id},
                    success: function(response) {
                        jQuery('#upazila_id').html(response);
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }
        });

        //Get vendor union
        jQuery(document).on("change", "#upazila_id", function(){
         jQuery('.ajax_loader').show();
            var nonce = jQuery(this).attr("data-nonce");
            var upazila_id = jQuery(this).find('option:selected').val();
            if(division_id != -1){
                    jQuery.ajax({
                    type : "POST",
                    url : "/admin/seller/get-union",
                    data : {upazila_id: upazila_id},
                    success: function(response) {
                        jQuery('#union_id').html(response); 
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }
        });

    </script>
  @endpush
  -->
  
    @push('footer')
    <script type="text/javascript">
       //Get vendor district
       jQuery(document).on("change", "#division_id", function(){
         // jQuery('.ajax_loader').show();
            var nonce = jQuery(this).attr("data-nonce");
            var division_id = jQuery(this).find('option:selected').val();
            if(division_id != -1){
              jQuery.ajax({
                type : "POST",
                url : "/admin/seller/get-district",
                data : {division_id: division_id},
                success: function(response) {
                    // alert(response);
                    jQuery('#district_id').empty(); 
                    jQuery('#district_id').append('<option value="-1">-- Select --</option>'+response);
                }
              });
            }
        });

        //Get vendor upazila
        jQuery(document).on("change", "#district_id", function(){
         jQuery('.ajax_loader').show();
            var nonce = jQuery(this).attr("data-nonce");
            var district_id = jQuery(this).find('option:selected').val();
            if(division_id != -1){
                    jQuery.ajax({
                    type : "POST",
                    url : "/admin/seller/get-upazila",
                    data : {district_id: district_id},
                    success: function(response) {
                        jQuery('#upazila_id').empty(); 
                        jQuery('#upazila_id').append('<option value="-1">-- Select --</option>'+response);
                    }
                });
            }
        });

        //Get vendor union
        jQuery(document).on("change", "#upazila_id", function(){
         jQuery('.ajax_loader').show();
            var nonce = jQuery(this).attr("data-nonce");
            var upazila_id = jQuery(this).find('option:selected').val();
            if(division_id != -1){
                    jQuery.ajax({
                    type : "POST",
                    url : "/admin/seller/get-union",
                    data : {upazila_id: upazila_id},
                    success: function(response) {
                        jQuery('#union_id').empty(); 
                        jQuery('#union_id').append('<option value="-1">-- Select --</option>'+response); 
                    }
                });
            }
        });

    </script>
  @endpush
  
  
@endsection
