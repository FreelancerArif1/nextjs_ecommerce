@extends('backend.layouts.master')
@section('title','Voucher Update - '.config('concave.cnf_appname'))

@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
      		<span class="card-title">Dashboard > Vouchers Edit</span>
      		<a class="btn btn-success float-right" href="{{ route('admin.voucher') }}"> Vouchers</a>
		</div>
	</div>
</div>

<div class="grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
      		<form class="form-sample" method="post" action="{{ route('admin.voucher.update')}}" enctype="multipart/form-data" >
      			@csrf
      			<div class="row">
					<div class="col-md-12">
						<div class="form-group row">
							<label class="col-sm-3 col-form-label">Title</label>
							<div class="col-sm-9">
								<input type="text" name="title" class="form-control" value="{{ $voucher->title }}" />
							</div>
						</div>
					</div>
						
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Voucher Category<span class="required">*</span></label>
              				<div class="col-sm-9">
		                    	<select name="voucher_category_id" class="form-control" required="">
		                      		<option value="">Select</option>
		                      		@foreach($voucher_category as $row)
		                      			<option @if($voucher->voucher_category_id == $row->id) selected="" @endif value="{{$row->id}}">{{$row->title}}</option>
		                      		@endforeach
		                    	</select>
		                  	</div>
            			</div>
          			</div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Banner<span class="required">*</span></label>
              				<div class="col-sm-9">
                				<div class="form-group ">
		                            <button type="button"
		                               data-image-width="1420" 
		                               data-image-height="290"  
		                               data-input-name="banner" 
		                               data-input-type="single" 
		                               class="btn btn-success initConcaveMedia" >Select Image
		                            </button>
		                            @if($voucher->banner)
	                                   	<p class="selected_images_gallery">
	                                        <span>
	                                          	<input type="hidden" value="{{$voucher->banner}}" name="banner">
	                                          	<img src="{{'/'.$voucher->banner}}"> 
	                                          	<b data-file-url="{{$voucher->banner}}" class="selected_image_remove">X</b>
	                                       	</span>
	                                    </p>
                                 	@endif
		                        </div>
              				</div>
            			</div>
          			</div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Type<span class="required">*</span></label>
              				<div class="col-sm-9">
                				<select name="type" id="type" class="form-control">
		                      		<option @if($voucher->type == '1') selected="selected" @endif value="1">Percentage</option>
		                      		<option @if($voucher->type == '2') selected="selected" @endif value="2">Fixed Amount</option>
		                    	</select>
              				</div>
            			</div>
          			</div>
          			<div class="col-md-12 minimum_amount_area">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Minimum Amount</label>
              				<div class="col-sm-9">
                				<input type="text" name="minimum_amount" class="form-control" value="{{ $voucher->minimum_amount }}"/>
              				</div>
            			</div>
          			</div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Amount</label>
              				<div class="col-sm-9">
                				<input type="text" name="amount" id="amount" class="form-control" placeholder="Enter % value" value="{{ $voucher->amount }}" />
              				</div>
            			</div>
          			</div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Quantity<span class="required">*</span></label>
              				<div class="col-sm-9">
                				<input type="number" name="quantity" class="form-control" value="{{ $voucher->quantity }}"/>
              				</div>
            			</div>
          			</div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Max Quantity / User<span class="required">*</span></label>
              				<div class="col-sm-9">
                				<input type="number" name="max_qty_per_user" class="form-control" value="{{ $voucher->max_qty_per_user }}"/>
              				</div>
            			</div>
          			</div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Valid From<span class="required">*</span></label>
              				<div class="col-sm-9">
                				<input type="datetime-local" name="valid_from" class="form-control" required="" value="{{ date('Y-m-d\TH:i:s', strtotime($voucher->valid_from )) }}"/>
              				</div>
            			</div>
          			</div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Valid To<span class="required">*</span></label>
              				<div class="col-sm-9">
                				<input type="datetime-local" name="valid_to" class="form-control" required="" value="{{ date('Y-m-d\TH:i:s', strtotime($voucher->valid_to )) }}"/>
              				</div>
            			</div>
          			</div>

	                <div class="col-md-12">
	                  	<div class="form-group row">
	                      	<label class="col-sm-3 col-form-label">Categories</label>
	                      	<div class="col-sm-9">
	                       		<select name="featured_categories[]" data-size="10" class="selectpicker form-control"  data-show-subtext="true" data-live-search="true" multiple>
	                                @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',0)->get() as $category)
	                                <option data-tokens="{{$category->title}}"
	                                 @if($voucher->category_ids)
	                                    @foreach(explode(',',$voucher->category_ids) as $settings)
	                                       @if($category->id == $settings) selected @endif
	                                    @endforeach
	                                 @endif
	                                 value="{{$category->id}}">{{$category->title}}</option>
	                                   @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',$category->id)->get() as $child)
	                                      <option data-tokens="{{$child->title}}"
	                                       @if($voucher->category_ids)
	                                          @foreach(explode(',',$voucher->category_ids) as $settings)
	                                             @if($child->id == $settings) selected @endif
	                                          @endforeach
	                                       @endif
	                                       value="{{$child->id}}">{{'¦–– '.$child->title}}</option>
	                                      @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',$child->id)->get() as $child2)
	                                         <option data-tokens="{{$child2->title}}"
	                                          @if($voucher->category_ids)
	                                             @foreach(explode(',',$voucher->category_ids) as $settings)
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
	                        <select name="brand_id[]" data-max-options="20" data-size="10" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple="">
	                           @foreach($brands as $brand)
	                           <option 
	                            @if($voucher->brand_ids)
	                                @foreach(explode(',',$voucher->brand_ids) as $settings)
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
	                        <select name="products[]" data-max-options="20" data-size="10" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple>
	                           @foreach(App\Models\Product::orderBy('title','asc')->where('is_active',1)->where('is_deleted',0)->get() as $product)
	                           <option  value="{{$product->id}}"
	                             @if($voucher->product_ids)
	                                @foreach(explode(',',$voucher->product_ids) as $settings)
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
	                        <select name="featured_sellers[]" data-size="10" class="selectpicker form-control"  data-show-subtext="true" data-live-search="true" multiple>
	                            
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

	                             @if($voucher->seller_ids)
	                                @foreach(explode(',',$voucher->seller_ids) as $settings)
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
	                        <select name="users[]" data-size="10" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple="">
	                           @foreach($users as $user)
	                           <option 
	                           	@if($voucher->user_ids)
	                                @foreach(explode(',',$voucher->user_ids) as $settings)
	                                   @if($user->id == $settings) selected @endif
	                                @endforeach
	                            @endif
	                            value="{{$user->id}}" >{{$user->name}}</option>
	                           @endforeach
	                        </select>
	                      </div>
	                  </div>
	                </div>
	                <div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Status</label>
              				<div class="col-sm-9">
                				<select name="status" class="form-control" required="">
		                      		<option @if($voucher->is_active == '1') selected="selected" @endif value="1">Active</option>
		                      		<option @if($voucher->is_active == '0') selected="selected" @endif value="0">Inactive</option>
		                    	</select>
              				</div>
            			</div>
          			</div>
	        	</div>
  
	            <div class="row">
	              	<div class="col-md-12">
	                	<div class="form-group">
	                  		<p class="text-right">
	                  			<input type="hidden" name="id" value="{{ $voucher->id }}">
	                      		<button class="btn btn-primary" name="save" type="submit">Update Voucher</button>
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
          		$('#amount').attr("placeholder", "Enter value");
        	}else{
          		$('#amount').attr("placeholder", "Enter % value");
        	}
      	});
   	</script>
@endpush
      
@endsection