@extends('backend.layouts.master')
@section('title','Seller Update - '.config('concave.cnf_appname'))

@section('content')
<div class="grid-margin stretch-card">
  <div class="card">
      <div class="card-body">
          <span class="card-title">Dashboard > Accounts > Update Account</span>
          @if(Auth::user()->getRoleNames() != '["seller"]')
           <a class="btn btn-success float-right" href="{{ route('admin.vendor')}}">View Sellers List</a>
          @endif
      </div>
  </div>
</div>

  <form class="form-sample" method="post" action="{{ route('admin.vendor.update',$vendor->id) }}" enctype="multipart/form-data" >
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
                           <input type="text" name="name" class="form-control" @error('name') is-invalid @enderror value="{{ $vendor->name }}" required  autocomplete="name" autofocus placeholder="Seller Name" >
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
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $vendor->email }}" @if(\Auth::user()->getRoleNames() == '["seller"]') disabled @endif autocomplete="email" autofocus placeholder="Email Address">
              
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
                              <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $vendor->phone }}" @if(\Auth::user()->getRoleNames() == '["seller"]') disabled @endif autocomplete="name" autofocus placeholder="Phone Number">
                              @error('phone')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                        </div>
                      </div>

                      
                    <div class="col-md-6"></div>

                @if(\Auth::user()->getRoleNames() != '["seller"]')

                      <div class="form-group col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">NID Front</label>
                            <div class="col-sm-8">
                              <button type="button"   
                                data-image-width="800"
                                data-image-height="400"  
                                data-input-name="nid_front_side"
                                data-input-type="single" 
                                class="btn btn-success initConcaveMedia" >Select Image
                              </button>

                              @if($vendor->nid_front_side)
                                <p class="selected_images_gallery">
                                    <span>
                                      <input type="hidden" value="{{$vendor->nid_front_side}}" name="nid_front_side">
                                      <img src="{{'/'.$vendor->nid_front_side}}"> 
                                      <b data-file-url="{{$vendor->nid_front_side}}" class="selected_image_remove">X</b>
                                    </span>
                                </p>
                              @endif


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

                              @if($vendor->nid_back_side)
                                <p class="selected_images_gallery">
                                    <span>
                                      <input type="hidden" value="{{$vendor->nid_back_side}}" name="nid_back_side">
                                      <img src="{{'/'.$vendor->nid_back_side}}"> 
                                      <b data-file-url="{{$vendor->nid_back_side}}" class="selected_image_remove">X</b>
                                    </span>
                                </p>
                              @endif

                            </div>
                        </div>
                      </div>

                  @else
                  <div class="col-md-6 form-group ">
                    <div class="row">
                        <label class="col-sm-4 col-form-label">NID Front Side</label>
                        <div class="col-sm-8">
                          @if($vendor->nid_front_side)
                            <p class="selected_images_gallery">
                              <span>
                                <a href="{{'/'.$vendor->nid_front_side}}" target="_blank">
                                  <img src="{{'/'.$vendor->nid_front_side}}"> 
                                </a>
                              </span>
                            </p>
                          @endif
                        </div>
                    </div>
                  </div>

                  <div class="col-md-6 form-group ">
                    <div class="row">
                        <label class="col-sm-4 col-form-label">NID Back Side</label>
                        <div class="col-sm-8">
                          @if($vendor->nid_back_side)
                            <p class="selected_images_gallery">
                              <span>
                                <a href="{{'/'.$vendor->nid_back_side}}" target="_blank">
                                  <img src="{{'/'.$vendor->nid_back_side}}"> 
                                </a>
                              </span>
                            </p>
                          @endif
                        </div>
                    </div>
                  </div>

                  @endif



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
                             
                              @if($vendor->avatar)
                                <p class="selected_images_gallery">
                                     <span>
                                       <input type="hidden" value="{{$vendor->avatar}}" name="avatar">
                                       <img src="{{'/'.$vendor->avatar}}"> 
                                       <b data-file-url="{{$vendor->avatar}}" class="selected_image_remove">X</b>
                                    </span>
                                 </p>
                              @endif

                            </div>
                        </div>
                      </div>

                      @if(\Auth::user()->getRoleNames() != '["seller"]')
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Status</label>
                            <div class="col-sm-8">
                               <div class="form-check form-check-flat">
                                  <label class="switch"><input name="status" type="checkbox" @if($vendor->status) checked @endif ><span class="slider round"></span></label>
                               </div>
                            </div>
                         </div>
                        </div>
                      @else
                        <label class="col-md-6 col-form-label">
                          @if($vendor->status == 1)
                            <p><b>Account Status:</b> <span style="color:green">Active</span></p>
                          @else
                          <p><b>Account Status:</b> <span style="color:red">Inactive</span></p>
                          @endif
                      </label>
                      @endif

                      <div class="form-group col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-12 col-form-label">Agreement Paper</label>
                          <div class="col-sm-12">
                            <input type="file" class="form-control @error('agreement_file') is-invalid @enderror" name="agreement_file" value="{{ old('agreement_file') }}"  autocomplete="name" autofocus>
                            @error('agreement_file')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                            @if($vendor->shopinfo->agreement_file != '')
                              <a href="{{ asset('uploads/agreementpapers/'.$vendor->shopinfo->agreement_file)}}" class="badge badge-primary text-small" target="_blank">View File</a>
                            @endif
                          </div>
                        </div>
                      </div>

                      <div class="form-group col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-12 col-form-label">Password</label>
                            <div class="col-sm-12">
                              <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}"  autocomplete="name" autofocus placeholder="Password">
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
                           <input type="text" name="title" class="form-control" @error('name') is-invalid @enderror value="{{ $vendor->shopinfo->name??'' }}" required  autocomplete="name" autofocus placeholder="Shop Name" >
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
                        <label class="col-sm-4 col-form-label">Shop Url <span style="color: #f00">*</span></label>
                        <div class="col-sm-8">
                           <input type="text" disabled class="form-control" @error('name') is-invalid @enderror value="{{ $vendor->shopinfo->slug??'' }}" required  autocomplete="slug" autofocus placeholder="Shop Url" >
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
                           <input type="text" name="shop_phone" class="form-control" @error('name') is-invalid @enderror value="{{ $vendor->shopinfo->phone??'' }}"  autocomplete="name" autofocus placeholder="Shop Phone" >
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
                          <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="shop_email" value="{{ $vendor->shopinfo->email??'' }}"  autocomplete="email" autofocus placeholder="Email Address">
            
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

                            @if($vendor->shopinfo->logo??'')
                              <p class="selected_images_gallery">
                                  <span>
                                    <input type="hidden" value="{{$vendor->shopinfo->logo}}" name="shop_logo">
                                    <img src="{{'/'.$vendor->shopinfo->logo??''}}"> 
                                    <b data-file-url="{{$vendor->shopinfo->logo??''}}" class="selected_image_remove">X</b>
                                  </span>
                              </p>
                            @endif
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
                          
                            @if($vendor->shopinfo->banner??'')
                            <p class="selected_images_gallery">
                                <span>
                                  <input type="hidden" value="{{$vendor->shopinfo->banner??''}}" name="shop_banner">
                                  <img src="{{'/'.$vendor->shopinfo->banner??''}}"> 
                                  <b data-file-url="{{$vendor->shopinfo->banner??''}}" class="selected_image_remove">X</b>
                                </span>
                            </p>
                          @endif

                          </div>
                      </div>
                    </div>

                @if(\Auth::user()->getRoleNames() != '["seller"]')          

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

                            @if($vendor->shopinfo->trade_license??'')
                              <p class="selected_images_gallery">
                                  <span>
                                    <input type="hidden" value="{{$vendor->shopinfo->trade_license??''}}" name="trade_license">
                                    <img src="{{'/'.$vendor->shopinfo->trade_license??''}}"> 
                                    <b data-file-url="{{$vendor->shopinfo->trade_license??''}}" class="selected_image_remove">X</b>
                                  </span>
                              </p>
                            @endif
                          </div>
                      </div>
                    </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-8 col-form-label">Shop Commission (Percent) <span style="color: #f00">*</span></label>
                          <div class="col-sm-4">
                            <input type="text" name="commission_percent" class="form-control" @error('commission_percent') is-invalid @enderror value="{{ $vendor->shopinfo->commission_percent??''}}" autocomplete="name" autofocus placeholder="10" required>
                            @error('commission_percent')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>
                      </div>
                      </div>
                  @else


                  <div class="col-md-6 form-group">
                    <div class="row">
                        <label class="col-sm-4 col-form-label">Trade License</label>
                        <div class="col-sm-8">
                          @if($vendor->shopinfo->trade_license??'')
                            <p class="selected_images_gallery">
                              <span>
                                <a href="{{'/'.$vendor->shopinfo->trade_license??''}}" target="_blank">
                                  <img src="{{'/'.$vendor->shopinfo->trade_license??''}}"> 
                                </a>
                              </span>
                            </p>
                          @endif
                        </div>
                    </div>
                  </div>
                 
                 @endif


                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Division<span style="color: #f00">*</span></label>
                        <div class="col-sm-8">
                          <select class="form-control" @error('shop_url') is-invalid @enderror name="shop_division" value="{{ old('shop_url') }}" data-show-subtext="true" data-live-search="true" name="shop_division" id="division_id">
                            <option>Select Division</option>
                            @foreach(App\Models\Division::orderBy('title','asc')->get() as $division)
                            @if($vendor->shopinfo)
                              <option @if($division->id == $vendor->shopinfo->division) selected="" @endif value="{{ $division->id }}">{{ $division->title }}</option>
                            @else
                              <option value="{{ $division->id }}">{{ $division->title }}</option>
                            @endif
                            @endforeach
                          </select>
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
                            @foreach(App\Models\District::orderBy('title','asc')->get() as $district)
                            @if($vendor->shopinfo)
                              <option @if($district->id == $vendor->shopinfo->district??'') selected="" @endif value="{{ $district->id }}">{{ $district->title }}</option>
                            @else
                              <option value="{{ $district->id }}">{{ $district->title }}</option>
                            @endif
                            
                            @endforeach
                          </select>
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
                            @foreach(App\Models\Upazila::orderBy('title','asc')->get() as $area)
                            @if($vendor->shopinfo)
                              <option @if($area->id == $vendor->shopinfo->area??'') selected="" @endif value="{{ $area->id }}">{{ $area->title }}</option>
                            @else
                              <option value="{{ $area->id }}">{{ $area->title }}</option>
                            @endif
                            @endforeach
                          </select>
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
                              @foreach(App\Models\Union::orderBy('title','asc')->get() as $union)
                              @if($vendor->shopinfo)
                                <option @if($union->id == $vendor->shopinfo->shop_union??'') selected="" @endif value="{{ $union->id }}">{{ $union->title }}</option>
                                @else
                                  <option value="{{ $union->id }}">{{ $union->title }}</option>
                                @endif
                            @endforeach
                             
                            </select>
                            @error('shop_union')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>
                     </div>
                    </div>


                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Address</label>
                        <div class="col-sm-8">
                           <textarea type="text" name="shop_address" class="form-control" @error('shop_address') is-invalid @enderror autocomplete="name" autofocus placeholder="Address" > {{ $vendor->shopinfo->address??''}}</textarea>
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
                                  <label class="switch"><input name="shop_status" type="checkbox" @if($vendor->shopinfo->status??'') checked @endif ><span class="slider round"></span></label>
                              </div>
                            </div>
                        </div>
                        </div>
                    @else
                      <label class="col-md-6 col-form-label">
                          @if($vendor->shopinfo->status??'')
                            <p><b>Shop Status:</b> <span style="color:green">Active</span></p>
                          @else
                          <p><b>Shop Status:</b> <span style="color:red">Inactive</span></p>
                          @endif
                      </label>
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
                           <input type="text" name="bank_name" class="form-control" @error('bank_name') is-invalid @enderror value="{{ $vendor->shopinfo->bank_name??'' }}"   autocomplete="name" autofocus placeholder="Ex: Eastern Bank Ltd" >
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
                           <input type="text" name="account_name"  class="form-control" @error('account_name') is-invalid @enderror value="{{ $vendor->shopinfo->account_name??'' }}"   autocomplete="name" autofocus placeholder="Ex: Biponi Enterprise" >
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
                           <input type="text" name="account_number"  class="form-control" @error('account_number') is-invalid @enderror value="{{ $vendor->shopinfo->account_number??'' }}"   autocomplete="name" autofocus placeholder="Ex: 19216800004578" >
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
                           <input type="text" name="routing_number"  class="form-control" @error('routing_number') is-invalid @enderror value="{{ $vendor->shopinfo->routing_number??'' }}"   autocomplete="name" autofocus placeholder="Ex: 54578" >
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
                             <input type="text" name="bkash"  class="form-control" @error('bkash') is-invalid @enderror value="{{ $vendor->shopinfo->bkash??'' }}"   autocomplete="name" autofocus placeholder="Ex: 01758207025" >
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
                             <input type="text" name="rocket"  class="form-control" @error('rocket') is-invalid @enderror value="{{ $vendor->shopinfo->rocket??'' }}"   autocomplete="name" autofocus placeholder="Ex: 017582070259" >
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
                             <input type="text" name="nagad"  class="form-control" @error('nagad') is-invalid @enderror value="{{ $vendor->shopinfo->nagad??'' }}"   autocomplete="name" autofocus placeholder="Ex: 017582070259" >
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
                             <input type="text" name="upay"  class="form-control" @error('upay') is-invalid @enderror value="{{ $vendor->shopinfo->upay??'' }}"   autocomplete="name" autofocus placeholder="Ex: 017582070259" >
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
                    <p class="text-right"><button type="submit" class="btn btn-success submit-btn">Update Information</button></p>
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