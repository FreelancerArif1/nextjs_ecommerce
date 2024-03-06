@extends('backend.layouts.master')
@section('title','Update Coupon - '.config('concave.cnf_appname'))

@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
      		<span class="card-title">Dashboard > Coupons > Edit Coupon</span>
		</div>
	</div>
</div>

<div class="grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
      		<form class="form-sample" method="post" action="{{ route('admin.coupons.update')}}" enctype="multipart/form-data" >
      			@csrf
      			<div class="row">
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Coupons Code<span class="text-danger">*</span></label>
              				<div class="col-sm-9">
		                    	<input type="text" name="code" class="form-control" value="{{ $coupon->code }}" required />
		                  	</div>
            			</div>
          			</div>

                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Use Type</label>
                      <div class="col-sm-9">
                        <select name="use_type" id="use_type" class="form-control">
                            <option @if($coupon->use_type == '1') selected="selected" @endif value="1">Global</option>
                            <option @if($coupon->use_type == '2') selected="selected" @endif value="2">Loyalty Point</option>
                        </select>
                      </div>
                  </div>
                </div>

                <div class="col-md-12 deduction_points_area d-none">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Deduction Points</label>
                      <div class="col-sm-9">
                        <input type="text" name="deduction_points" id="deduction_points" class="form-control"  value="{{ $coupon->deduction_points }}"/>
                      </div>
                  </div>
                </div>

          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Type</label>
              				<div class="col-sm-9">
                				<select name="type" id="type" class="form-control">
		                      		<option @if($coupon->type == '1') selected="selected" @endif value="1">Percentage</option>
		                      		<option @if($coupon->type == '2') selected="selected" @endif value="2">Fixed Amount</option>
		                    	</select>
              				</div>
            			</div>
          			</div>


          			<div class="col-md-12 minimum_amount_area">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Minimum Amount</label>
              				<div class="col-sm-9">
                				<input type="text" name="minimum_amount" class="form-control"  value="{{ $coupon->minimum_amount }}"/>
              				</div>
            			</div>
          			</div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Discount Amount <span class="text-danger">*</span></label>
              				<div class="col-sm-9">
                				<input type="text" name="amount" id="amount" class="form-control" placeholder="Enter % value"  value="{{ $coupon->amount }}" required/>
              				</div>
            			</div>
          			</div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Available Quantity<span class="text-danger">*</span></label>
              				<div class="col-sm-9">
                				<input type="number" name="quantity" class="form-control"  value="{{ $coupon->quantity }}" required/>
              				</div>
            			</div>
          			</div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Max Quantity / User</label>
                      <div class="col-sm-9">
                        <input type="number" name="max_qty_per_user" class="form-control" value="{{ $coupon->max_qty_per_user }}"/>
                      </div>
                  </div>
                </div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Expired Date<span class="text-danger">*</span></label>
              				<div class="col-sm-9">

                				<input required type="datetime-local" name="expire" class="form-control" id="expire_date" value="{{ date('Y-m-d\TH:i:s', strtotime($coupon->expire)) }}"  />

              				</div>
            			</div>
          			</div>
                <div class="col-md-12">
                  	<div class="form-group row">
                      	<label class="col-sm-3 col-form-label">Categories</label>
                      	<div class="col-sm-9">
                       		<select name="featured_categories[]" class="selectpicker form-control"  data-show-subtext="true" data-live-search="true" multiple>
                                @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',0)->get() as $category)
                                <option data-tokens="{{$category->title}}"
                                 @if($coupon->category_ids)
                                    @foreach(explode(',',$coupon->category_ids) as $settings)
                                       @if($category->id == $settings) selected @endif
                                    @endforeach
                                 @endif
                                 value="{{$category->id}}">{{$category->title}}</option>
                                   @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',$category->id)->get() as $child)
                                      <option data-tokens="{{$child->title}}"
                                       @if($coupon->category_ids)
                                          @foreach(explode(',',$coupon->category_ids) as $settings)
                                             @if($child->id == $settings) selected @endif
                                          @endforeach
                                       @endif
                                       value="{{$child->id}}">{{'¦–– '.$child->title}}</option>
                                      @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',$child->id)->get() as $child2)
                                         <option data-tokens="{{$child2->title}}"
                                          @if($coupon->category_ids)
                                             @foreach(explode(',',$coupon->category_ids) as $settings)
                                                @if($child2->id == $settings) selected @endif
                                             @endforeach
                                          @endif
                                          value="{{$child2->id}}">{{'¦––––'.$child2->title}}</option>
                                      @endforeach
                                   @endforeach
                                @endforeach
                               </select>
                      	</div>
                  	</div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Brands</label>
                      <div class="col-sm-9">
                        <select name="brand_id[]" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple="">
                           <option value="0">Select Brand</option>
                           @foreach($brands as $brand)
                           <option 
                            @if($coupon->brand_ids)
                                @foreach(explode(',',$coupon->brand_ids) as $settings)
                                   @if($brand->id == $settings) selected @endif
                                @endforeach
                             @endif
                           value="{{$brand->id}}" >{{$brand->title}}</option>
                           @endforeach
                        </select>
                      </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Products</label>
                      <div class="col-sm-9">
                        <select name="products[]" data-max-options="20" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple>
                           @foreach(App\Models\Product::orderBy('title','asc')->where('is_active',1)->where('is_deleted',0)->get() as $product)
                           <option  value="{{$product->id}}"
                             @if($coupon->product_ids)
                                @foreach(explode(',',$coupon->product_ids) as $settings)
                                   @if($product->id == $settings) selected @endif
                                @endforeach
                             @endif
                             >{{$product->title}}</option>
                           @endforeach
                          </select>
                      </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Sellers</label>
                      <div class="col-sm-9">
                        <select name="featured_sellers[]" class="selectpicker form-control"  data-show-subtext="true" data-live-search="true" multiple>
                            
                          @php 
                             $vendors = App\Models\Admins::orderBy('name','asc')->with('shopinfo')->get();
                             $vendorArray = [];
                             foreach($vendors as $vendor){
                                if($vendor->hasRole('seller')){
                                   $vendorArray[] = $vendor;
                                }
                             }
                          @endphp

                          @foreach($vendorArray as $seller)
                             <option 

                             @if($coupon->seller_ids)
                                @foreach(explode(',',$coupon->seller_ids) as $settings)
                                   @if($seller->id == $settings) selected @endif
                                @endforeach
                             @endif

                             value="{{$seller->id}}" >{{$seller->shopinfo->name ?? ''}}</option>
                          @endforeach

                         </select>
                      </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Users</label>
                      <div class="col-sm-9">
                        <select name="users[]" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple="">
                           <option value="0">Select Users</option>
                           @foreach($users as $user)
                           <option 
                           	@if($coupon->user_ids)
                                @foreach(explode(',',$coupon->user_ids) as $settings)
                                   @if($user->id == $settings) selected @endif
                                @endforeach
                            @endif
                            value="{{$user->id}}" >{{$user->name}}</option>
                           @endforeach
                        </select>
                      </div>
                  </div>
                </div>
        		</div>
  
	            <div class="row">
	              	<div class="col-md-12">
	                	<div class="form-group">
	                  		<p class="text-right">
	                  			<input type="hidden" name="id" value="{{ $coupon->id }}">
	                      		<button class="btn btn-primary" name="save" type="submit">Update Coupon</button>
	                  		</p>
	                	</div>
	              	</div>
	            </div>
      		</form>
		</div>
	</div>
</div>


@push('footer')
   	<script type="text/javascript">



      	jQuery(document).on('change','#type',function(e){
        	e.preventDefault();
        	var val = $(this).val();
        	// alert(val);
        	if(val == '2') {
        		// alert(val);
          		$('.minimum_amount_area').removeClass('d-none');
          		$('.minimum_amount_area').addClass('d-block');
          		$('#amount').attr("placeholder", "Enter value");
        	}else{
          		$('.minimum_amount_area').removeClass('d-block');
          		$('.minimum_amount_area').addClass('d-none');
          		$('#amount').attr("placeholder", "Enter % value");
        	}
      	});

        jQuery(document).on('change','#use_type',function(e){
          e.preventDefault();
          var val = $(this).val();
          // alert(val);
          if(val != 1) {
            // alert(val);
              $('.deduction_points_area').removeClass('d-none');
              $('.deduction_points_area').addClass('d-block');
              $('#deduction_points').attr("placeholder", "Enter value");
          }else{
              $('.deduction_points_area').removeClass('d-block');
              $('.deduction_points_area').addClass('d-none');
          }
        });
   	</script>
@endpush
      
@endsection