@extends('backend.layouts.master')
@section('title','Seller Create - '.config('concave.cnf_appname'))
@section('content')
	<div class="grid-margin stretch-card">
		<div class="card">
			  <div class="card-body">
		  <span class="card-title">Dashboard > Accounts > Create Seller</span>
		  <a class="btn btn-primary float-right" href="{{ route('admin.vendor')}}">View Seller List</a>
			  </div>
		</div>
	</div>
  <form class="form-sample" method="post" action="{{ route('admin.vendor.store') }}" >
    @csrf
			<div class="row">
              <div class="col-md-6 grid-margin">
                <div class="card">
                  <div style="min-height: 473px" class="card-body">
                  <p class="content_title">Personal Information</p>

                    <div class="row">

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Full Name <span style="color: #f00">*</span></label>
                        <div class="col-sm-8">
                           <input type="text" name="name" class="form-control" @error('name') is-invalid @enderror value="{{ old('name') }}" required  autocomplete="name" autofocus placeholder="Seller Name" >
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
                          <label class="col-sm-4 col-form-label"> Email <span style="color: #f00">*</span></label>
                          <div class="col-sm-8">
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Email Address" >
              
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

                      <div class="col-md-6"></div>
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

                      <div class="form-group col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">NID Front </label>
                            <div class="col-sm-8">
                              <button type="button"   
                                data-image-width="800"
                                data-image-height="400"  
                                data-input-name="nid_front_side"
                                data-input-type="single" 
                                class="btn btn-success initConcaveMedia" >Select Image
                              </button>
                            </div>
                        </div>
                      </div>

                      <div class="form-group col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">NID Back </label>
                            <div class="col-sm-8">
                              <button type="button"   
                                data-image-width="800"
                                data-image-height="400"  
                                data-input-name="nid_back_side"
                                data-input-type="single" 
                                class="btn btn-success initConcaveMedia" >Select Image
                              </button>
                            </div>
                        </div>
                      </div>


                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Status <span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                               <div class="form-check form-check-flat">
                                  <label class="switch"><input name="status" type="checkbox" checked ><span class="slider round" required></span></label>
                               </div>
                            </div>
                         </div>
                        </div>

                        <div class="form-group col-md-12">
                          <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Password</label>
                              <div class="col-sm-10">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}"  autocomplete="name" autofocus placeholder="Password">
                                <small class="hint_text">If you dont fill up then a random password will be generated!</small>
                                @error('password')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                          </div>
                        </div>

                    </div>
                  </div>
                </div>
              </div>

          

              <div class="col-md-6 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <p class="content_title">Shop Information</p>
                    <div class="row">

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Shop Name <span style="color: #f00">*</span></label>
                        <div class="col-sm-8">
                           <input type="text" name="title" data-slugable-model="seller" class="form-control slug_maker" @error('name') is-invalid @enderror value="{{ old('title') }}" required  autocomplete="name" autofocus placeholder="Shop Name" >
                           @error('title')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                     </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Shop Url </label>
                        <div class="col-sm-8">
                           <input type="text" name="slug" data-slugable-model="seller" class="form-control slug_taker" @error('name') is-invalid @enderror value="{{ old('slug') }}"   autocomplete="name" autofocus placeholder="Shop Url" >
                           @error('slug')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                     </div>
                    </div>

                    
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Shop Phone </label>
                        <div class="col-sm-8">
                           <input type="text" name="shop_phone" class="form-control" @error('name') is-invalid @enderror value="{{ old('shop_phone') }}"   autocomplete="name" autofocus placeholder="Shop Phone" >
                           @error('shop_phone')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                     </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Shop Email</label>
                        <div class="col-sm-8">
                          <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="shop_email" value="{{ old('shop_email') }}"  autocomplete="email" autofocus placeholder="Email Address">
            
                          @error('shop_phone')
                          <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                          </span>
                          @enderror
                        </div>
                     </div>
                    </div>

                    <div class="form-group col-md-6">
                      <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Shop Logo </label>
                          <div class="col-sm-8">
                            <button type="button"   
                              data-image-width="400" 
                              data-image-height="400"  
                              data-input-name="shop_logo"
                              data-input-type="single" 
                              class="btn btn-success initConcaveMedia" >Select Image
                            </button>
                          </div>
                      </div>
                    </div>

                    <div class="form-group col-md-6">
                      <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Shop Banner</label>
                          <div class="col-sm-8">
                            <button type="button"   
                              data-image-width="1920"
                              data-resize="true" 
                              data-image-height="250"  
                              data-input-name="shop_banner"
                              data-input-type="single" 
                              class="btn btn-success initConcaveMedia" >Select Image
                            </button>
                          </div>
                      </div>
                    </div>
                    <div class="form-group col-md-6">
                      <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Trade License </label>
                          <div class="col-sm-8">
                            <button type="button"   
                              data-image-width="3508"
                              data-image-height="2480"  
                              data-input-name="trade_license"
                              data-input-type="single" 
                              class="btn btn-success initConcaveMedia" >Select Image
                            </button>
                          </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-8 col-form-label">Shop Commission (Percent) <span style="color: #f00">*</span></label>
                          <div class="col-sm-4">
                            <input type="text" name="commission_percent" class="form-control" @error('commission_percent') is-invalid @enderror value="{{ old('commission_percent') }}" autocomplete="name" autofocus placeholder="10" required>
                            @error('commission_percent')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                      </div>
                    </div>

                    <div class="col-md-6"></div>

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Division<span style="color: #f00">*</span></label>
                        <div class="col-sm-8">
                          <select class="form-control" @error('shop_url') is-invalid @enderror name="shop_division" value="{{ old('shop_url') }}" data-show-subtext="true" data-live-search="true" name="shop_division" id="division_id">
                            <option>Select Division</option>
                            @foreach(App\Models\Division::orderBy('title','asc')->get() as $division)
                              <option value="{{ $division->id }}">{{ $division->title }}</option>
                            @endforeach
                          </select>
                           {{-- <input type="text" name="shop_division" class="form-control" @error('name') is-invalid @enderror value="{{ old('shop_url') }}" required  autocomplete="name" autofocus placeholder="Division" > --}}
                           @error('shop_url')
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
                          <select class="form-control" id="district_id" @error('shop_district') is-invalid @enderror name="shop_district" value="{{ old('shop_district') }}" data-show-subtext="true" data-live-search="true" >
                            <option value="" selected disabled>--Select Division First--</option>
                            
                          </select>
                          {{--  <input type="text" name="shop_district" class="form-control" @error('shop_district') is-invalid @enderror value="{{ old('shop_district') }}" required  autocomplete="name" autofocus placeholder="District" > --}}
                           @error('shop_district')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                     </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Upazila<span style="color: #f00">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" @error('shop_area') is-invalid @enderror name="shop_area" value="{{ old('shop_area') }}" data-show-subtext="true" data-live-search="true" id="upazila_id">
                            <option value="" selected disabled>--Select District First--</option>
                          </select>
                           {{-- <input type="text" name="shop_area" class="form-control" @error('shop_area') is-invalid @enderror value="{{ old('shop_area') }}" required  autocomplete="name" autofocus placeholder="Area" > --}}
                           @error('shop_area')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                     </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Union<span style="color: #f00">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" @error('shop_union') is-invalid @enderror name="shop_union" value="{{ old('shop_union') }}" data-show-subtext="true" data-live-search="true" id="union_id">
                              <option value="" selected disabled>--Select Upazila First--</option>
                            </select>
                           {{-- <input type="text" name="shop_area" class="form-control" @error('shop_area') is-invalid @enderror value="{{ old('shop_area') }}" required  autocomplete="name" autofocus placeholder="Area" > --}}
                           @error('shop_union')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                     </div>
                    </div>


                    <div class="col-md-12">
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                           <input type="text" name="shop_address" class="form-control" @error('shop_address') is-invalid @enderror value="{{ old('shop_address') }}"  autocomplete="name" autofocus placeholder="Address" >
                           @error('shop_address')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                     </div>
                    </div>

                    @if(\Auth::user()->getRoleNames() != '["seller"]')
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Shop Status</label>
                            <div class="col-sm-8">
                              <div class="form-check form-check-flat">
                                  <label class="switch"><input name="shop_status" type="checkbox" @if(old('shop_status')) checked @endif ><span class="slider round"></span></label>
                              </div>
                            </div>
                        </div>
                        </div>
                    @endif
            
                    </div>
                  </div>
                </div>
              </div>




              <div class="col-md-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <p class="content_title">Bank Account Details</p>
                    <div class="row">

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Bank Name </label>
                        <div class="col-sm-8">
                           <input type="text" name="bank_name" class="form-control" @error('bank_name') is-invalid @enderror value="{{ old('bank_name') }}"  autocomplete="name" autofocus placeholder="Ex: Eastern Bank Ltd" >
                           @error('bank_name')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Account Name </label>
                        <div class="col-sm-8">
                           <input type="text" name="account_name"  class="form-control" @error('account_name') is-invalid @enderror value="{{ old('account_name') }}"  autocomplete="name" autofocus placeholder="Ex: DITS Enterprise" >
                           @error('account_name')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Account Number </label>
                        <div class="col-sm-8">
                           <input type="text" name="account_number"  class="form-control" @error('account_number') is-invalid @enderror value="{{ old('account_number') }}"  autocomplete="name" autofocus placeholder="Ex: 19216800004578" >
                           @error('account_number')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Routing Number</label>
                        <div class="col-sm-8">
                           <input type="text" name="routing_number"  class="form-control" @error('routing_number') is-invalid @enderror value="{{ old('routing_number') }}"   autocomplete="name" autofocus placeholder="Ex: 54578" >
                           @error('routing_number')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                      </div>
                    </div>
                   </div>

                    <p class="content_title">Mobile Financial Service Accounts</p>
                    <div class="row">
                      
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Bkash Number</label>
                          <div class="col-sm-8">
                             <input type="text" name="bkash"  class="form-control" @error('bkash') is-invalid @enderror value="{{ old('bkash') }}"   autocomplete="name" autofocus placeholder="Ex: 01758207025" >
                             @error('bkash')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Rocket Number</label>
                          <div class="col-sm-8">
                             <input type="text" name="rocket"  class="form-control" @error('rocket') is-invalid @enderror value="{{ old('rocket') }}"   autocomplete="name" autofocus placeholder="Ex: 017582070259" >
                             @error('rocket')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Nagad Number</label>
                          <div class="col-sm-8">
                             <input type="text" name="nagad"  class="form-control" @error('nagad') is-invalid @enderror value="{{ old('nagad') }}"   autocomplete="name" autofocus placeholder="Ex: 017582070259" >
                             @error('nagad')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Upay Number</label>
                          <div class="col-sm-8">
                             <input type="text" name="upay"  class="form-control" @error('upay') is-invalid @enderror value="{{ old('upay') }}"   autocomplete="name" autofocus placeholder="Ex: 017582070259" >
                             @error('upay')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                        </div>
                      </div>


                    </div>



                  </div>
                </div>
              </div>

              <div class="col-md-12 grid-margin">
                  <div class="form-group col-md-12">
                    <p class="text-right"><button type="submit" class="btn btn-success submit-btn">Create Seller</button></p>
                  </div>
              </div>
      </div>
  </form>

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