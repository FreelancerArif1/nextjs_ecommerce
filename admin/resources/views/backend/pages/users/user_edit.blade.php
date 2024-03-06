@extends('backend.layouts.master')
@section('title','Cuatomer Update - '.config('concave.cnf_appname'))
@section('content')
    
  <div class="grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
      <span class="card-title">Dashboard > Accounts > Customers > Update Customer</span>
      <a class="btn btn-success float-right" href="{{ route('admin.user')}}">View Customer List</a>
        </div>
    </div>
  </div>

			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <form class="form-sample" method="post" action="{{ route('admin.user.update',$user->id) }}" >
                      @csrf
                          <p class="content_title">Personal Information</p>
        
                            <div class="row">
        
                            <div class="col-md-6">
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Full Name <span style="color: #f00">*</span></label>
                                <div class="col-sm-8">
                                   <input type="text" name="name" class="form-control" @error('name') is-invalid @enderror value="{{ $user->name }}" required  autocomplete="name" autofocus placeholder="Full Name" >
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
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" autocomplete="email" readonly placeholder="Email Address">
                      
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
                                      <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $user->phone }}" readonly autocomplete="name" autofocus placeholder="Phone Number">
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
                                  <label class="col-sm-4 col-form-label">Status </label>
                                  <div class="col-sm-8">
                                     <div class="form-check form-check-flat">
                                        <label class="switch"><input name="status" type="checkbox"  @if($user->status) checked @endif ><span class="slider round" required></span></label>
                                     </div>
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

                                      @if($user->avatar)
                                        <p class="selected_images_gallery">
                                            <span>
                                              <input type="hidden" value="{{$user->avatar}}" name="avatar_old">
                                              <img src="{{'/'.$user->avatar}}"> 
                                              <b data-file-url="{{$user->avatar}}" class="selected_image_remove">X</b>
                                            </span>
                                        </p>
                                      @endif

                                    </div>
                                </div>
                              </div>
        
                            </div>

                            <div class="form-group col-md-12">
                              <p class="text-right"><button type="submit" class="btn btn-primary submit-btn">Update</button></p>
                            </div>

                    </form>
                  </div>
                </div>
              </div>
      </div>

      <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <p class="content_title">Address</p>
                    <div class="row">
                      <div class="designed_table table-responsive">
                        <table id="listTable" class="table">
                            <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Division</th>
                                  <th>District</th>
                                  <th>Upazila</th>
                                  <th>Union / Area</th>
                                  <th>Address</th>
                                  <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>

                            @foreach ($address as $row)
                              <tr class="row{{$row->id}}">
                                  <td class="pl-3">{{$loop->iteration}}</td>
                                  <td data-division="{{$row->shipping_division}}">{{$row->division->title ?? ''}}</td>
                                  <td data-district="{{$row->shipping_district}}">{{$row->district->title ?? ''}}</td>
                                  <td data-upazila="{{$row->shipping_thana}}">{{$row->upazila->title ?? '' }}</td>
                                  <td data-union="{{$row->shipping_union}}">{{$row->union->title ?? '' }}</td>
                                  <td data-address="{{$row->shipping_address}}">{{$row->shipping_address ?? ''}}</td>
                                  <td class="text-center">
                                      <a class="text-success edit_address" id="{{ $row->id }}" data-toggle="modal" data-target="#SaveModal" href="#"><i class="mdi mdi-pencil-box-outline"></i></a>
                                  </td>
                              </tr>
                            @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
      </div>


      <!--Save Modal -->
    <div class="modal fade" id="SaveModal" tabindex="-1" role="dialog" aria-labelledby="SaveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="SaveModalLabel">Update Address </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form class="form-sample" method="post" action="{{ route('admin.user.address.update')}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Division<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                              <select class=" form-control" @error('division_id') is-invalid @enderror name="division_id" value="{{ old('division_id') }}" data-show-subtext="true" data-live-search="true" name="division_id" id="division_id" required="">
                                <option disabled="" selected="">Select Division</option>
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

                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">District<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                              <select class=" form-control" id="district_id" @error('district_id') is-invalid @enderror name="district_id" value="{{ old('district_id') }}" data-show-subtext="true" data-live-search="true" required="">
                                <option  selected disabled>--Select Division First--</option>
                                
                              </select>
                              
                               @error('district_id')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                            </div>
                         </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Upazila<span style="color: #f00">*</span></label>
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

                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Union<span style="color: #f00">*</span></label>
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
                            <label class="col-sm-4 col-form-label">Address<span style="color: #f00">*</span></label>
                            <div class="col-sm-8">
                               <input type="text" name="street_address" class="form-control" required  autocomplete="name" id="street_address" autofocus placeholder="Address" >
                               @error('street_address')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                            </div>
                         </div>
                        </div>
                    </div>
          
                    <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                              <p class="text-left">
                                  <input type="hidden" name="address_id" class="" id="hidden_address_id">
                                  <button class="btn btn-primary" name="save" type="submit">Update</button>
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

        jQuery(document).on("click", ".edit_address", function(){
            var id = jQuery(this).attr("id");
            $('#hidden_address_id').val(id);

            var division = $('.row'+id).children('td').eq(1).attr('data-division');
            $("#division_id option[value="+division+"]").prop('selected', true);
            // $("#division_id").trigger("change");
            setTimeout(function() {
              $("#division_id").val(division).trigger('change');
            }, 1000);

            setTimeout(function() {
              var district = $('.row'+id).children('td').eq(2).attr('data-district');
              $('#district_id').find('option:contains('+district+')').attr("selected",true);
              $("#district_id").val(district).trigger('change');
            }, 2000);

            setTimeout(function() {
              var upazila = $('.row'+id).children('td').eq(3).attr('data-upazila');
              $('#upazila_id').find('option:contains('+upazila+')').attr("selected",true);
              $("#upazila_id").val(upazila).trigger('change');
            }, 3000);

            setTimeout(function() {
              var union = $('.row'+id).children('td').eq(4).attr('data-union');
              $('#union_id').find('option:contains('+union+')').attr("selected",true);
              $("#union_id").val(union).trigger('change');
            }, 4000);

            var address = $('.row'+id).children('td').eq(5).attr('data-address');
            $('#street_address').val(address);

           

          });

    </script>
  @endpush
@endsection
