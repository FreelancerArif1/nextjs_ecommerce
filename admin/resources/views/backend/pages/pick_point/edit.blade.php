@extends('backend.layouts.master')
@section('title','Update Pick Point - '.config('concave.cnf_appname'))

@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Settings > Update New Pick Point</span>
      <a class="btn btn-success float-right" href="{{ route('admin.pick_points')}}">View Pick Points</a>
		  </div>
	</div>
</div>
			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('admin.pick_points.store')}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">


                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Point Name<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                              <input type="text" name="title" value="{{ $pick_point->title }}" placeholder="Title" class="form-control" required/>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Address<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                              <textarea type="text" name="address" placeholder="Address" class="form-control" required >{{ $pick_point->address }}</textarea>
                            </div>
                          </div>
                        </div>

                        
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Division<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                              <select class="form-control" @error('division_id') is-invalid @enderror name="division_id"  data-show-subtext="true" data-live-search="true" name="shop_division" id="division_id">
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
                              <select class="form-control" id="district_id" @error('district_id') is-invalid @enderror name="district_id"  data-show-subtext="true" data-live-search="true" >
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
                            <label class="col-sm-4 col-form-label">Upazila<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" @error('upazila_id') is-invalid @enderror name="upazila_id" data-show-subtext="true" data-live-search="true" id="upazila_id">
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
                            <label class="col-sm-4 col-form-label">Union<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" @error('union_id') is-invalid @enderror name="union_id" data-show-subtext="true" data-live-search="true" id="union_id">
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

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Postal code<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                              <input type="text" name="post_code" value="{{ $pick_point->post_code }}" placeholder="Postal Code" class="form-control" required/>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Shipping Amount<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                              <input type="text" name="discount" value="{{ $pick_point->discount }}" placeholder="Shipping Amount" class="form-control" required/>
                            </div>
                          </div>
                        </div>


                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Mobile Number<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                              <input type="text" name="phone" value="{{ $pick_point->phone }}" placeholder="Mobile Number" class="form-control" required/>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Email<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                              <input type="text" name="email" value="{{ $pick_point->email }}" placeholder="Email address" class="form-control" required/>
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
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <p class="text-right">
                                <button class="btn btn-primary" name="save" type="submit">Update Pick Point</button>
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