@extends('backend.layouts.master')
@section('title','Coupon List - '.config('concave.cnf_appname'))
@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
      		<span class="card-title">Dashboard > Coupons</span>
      		<a class="btn btn-success float-right" data-toggle="modal" data-target="#SaveModal" href="#">Create New Coupons</a>
		</div>
	</div>
</div>


<div class="grid-margin stretch-card">
    <div class="card">
      	<div class="designed_table table-responsive">
        	<table id="dataTable" class="table">
          		<thead>
            		<tr>
		              	<th>Coupon Code</th>
		              	<th>Type</th>
		              	<th>Minimum Amount</th>
		              	<th>Amount</th>
		              	<th>Quantity</th>
						        <th>Max Quantity / User</th>
		              	<th>Expired Date</th>
		              	<th class="text-center" data-priority="1">Action</th>
		            </tr>
          		</thead>
          		<tbody>
          		</tbody>
        	</table>
      	</div>
    </div>
</div>



    <!--Save Modal -->
<div class="modal fade" id="SaveModal" tabindex="-1" role="dialog" aria-labelledby="SaveModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-md" role="document">
    	<div class="modal-content">
	      	<div class="modal-header">
	        	<h5 class="modal-title" id="SaveModalLabel">Add New Coupons </h5>
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          		<span aria-hidden="true">&times;</span>
	        	</button>
	      	</div>
      		<div class="modal-body">
        		<form class="form-sample" method="post" action="{{ route('admin.coupons.store')}}" enctype="multipart/form-data" >
          			@csrf
          			<div class="row">
              			<div class="col-md-12">
                			<div class="form-group row">
                  				<label class="col-sm-3 col-form-label">Coupons Code <span class="text-danger">*</span></label>
                  				<div class="col-sm-9">
			                    	<input type="text" name="code" class="form-control" required />
			                  	</div>
                			</div>
              			</div>

                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Use Type</label>
                          <div class="col-sm-9">
                            <select name="use_type" id="use_type" class="form-control">
                                <option value="1">Global</option>
                                <option  value="2">Loyalty Point</option>
                            </select>
                          </div>
                      </div>
                    </div>

                    <div class="col-md-12 deduction_points_area d-none">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Deduction Points</label>
                          <div class="col-sm-9">
                            <input type="text" name="deduction_points" id="deduction_points" class="form-control" />
                          </div>
                      </div>
                    </div>

              			<div class="col-md-12">
                			<div class="form-group row">
                  				<label class="col-sm-3 col-form-label">Type</label>
                  				<div class="col-sm-9">
                    				<select name="type" id="type" class="form-control">
			                      		<option value="1">Percentage</option>
			                      		<option  value="2">Fixed Amount</option>
			                    	</select>
                  				</div>
                			</div>
              			</div>

              			<div class="col-md-12 minimum_amount_area">
                			<div class="form-group row">
                  				<label class="col-sm-3 col-form-label">Minimum Cart Amount </label>
                  				<div class="col-sm-9">
                    				<input type="text" name="minimum_amount" class="form-control" />
                  				</div>
                			</div>
              			</div>

              			<div class="col-md-12">
                			<div class="form-group row">
                  				<label class="col-sm-3 col-form-label">Discount Amount <span class="text-danger">*</span></label>
                  				<div class="col-sm-9">
                    				<input type="text" name="amount" id="amount" class="form-control" placeholder="Enter % value" required />
                  				</div>
                			</div>
              			</div>
              			<div class="col-md-12">
                			<div class="form-group row">
                  				<label class="col-sm-3 col-form-label">Available Quantity<span class="text-danger">*</span></label>
                  				<div class="col-sm-9">
                    				<input type="number" name="quantity" class="form-control" required/>
                  				</div>
                			</div>
              			</div>
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Max Quantity / User</label>
                          <div class="col-sm-9">
                            <input type="number" name="max_qty_per_user" class="form-control"/>
                          </div>
                      </div>
                    </div>
              			<div class="col-md-12">
                			<div class="form-group row">
                  				<label class="col-sm-3 col-form-label">Expired Date<span class="text-danger">*</span></label>
                  				<div class="col-sm-9">
                    				<input type="datetime-local" name="expire" class="form-control" required/>
                  				</div>
                			</div>
              			</div>
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Categories</label>
                          <div class="col-sm-9">
                           <select name="featured_categories[]" class="selectpicker form-control"  data-show-subtext="true" data-live-search="true" multiple>
                                @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',0)->get() as $category)
                                <option value="{{$category->id}}">{{$category->title}}</option>
                                   @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',$category->id)->get() as $child)
                                      <option data-tokens="{{$child->title}}" value="{{$child->id}}">{{'¦–– '.$child->title}}</option>
                                      @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',$child->id)->get() as $child2)
                                         <option data-tokens="{{$child2->title}}" value="{{$child2->id}}">{{'¦––––'.$child2->title}}</option>
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
                               <option data-tokens="{{$brand->title}}" value="{{$brand->id}}" >{{$brand->title}}</option>
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
                             @foreach(App\Models\Product::orderBy('title','asc')->where('is_active',1)->get() as $product)
                             <option  value="{{$product->id}}"
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
                                 <option value="{{$seller->id}}">{{$seller->shopinfo->name ?? ''}}</option>
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
                               <option data-tokens="{{$user->name}}" value="{{$user->id}}" >{{$user->name}}</option>
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
		                      		<button class="btn btn-primary" name="save" type="submit">Add Coupon</button>
		                  		</p>
		                	</div>
		              	</div>
		            </div>
          		</form>
      		</div>
    	</div>
  	</div>
</div>
{{-- 
<div class="modal fade" id="UpdateModal" tabindex="-1" role="dialog" aria-labelledby="UpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="SaveModalLabel">Edit Coupons </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="form-sample" method="post" action="{{ route('admin.coupons.store')}}" enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Coupons Code</label>
                          <div class="col-sm-9">
                            <input type="text" name="code" class="form-control" />
                          </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Type</label>
                          <div class="col-sm-9">
                            <select name="type" id="type" class="form-control">
                                <option value="Percentage">Percentage</option>
                                <option  value="Fixed Amount">Fixed Amount</option>
                            </select>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-12 minimum_amount_area d-none">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Minimum Amount</label>
                          <div class="col-sm-9">
                            <input type="text" name="minimum_amount" class="form-control" />
                          </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Amount</label>
                          <div class="col-sm-9">
                            <input type="text" name="amount" id="amount" class="form-control" placeholder="Enter % value" />
                          </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Quantity</label>
                          <div class="col-sm-9">
                            <input type="number" name="quantity" class="form-control" />
                          </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Expired Date</label>
                          <div class="col-sm-9">
                            <input type="date" name="expire" class="form-control" />
                          </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Categories</label>
                          <div class="col-sm-9">
                           <select name="featured_categories[]" class="selectpicker form-control"  data-show-subtext="true" data-live-search="true" multiple>
                                @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',0)->get() as $category)
                                <option value="{{$category->id}}">{{$category->title}}</option>
                                   @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',$category->id)->get() as $child)
                                      <option data-tokens="{{$child->title}}" value="{{$child->id}}">{{'¦–– '.$child->title}}</option>
                                      @foreach(App\Models\Category::orderBy('title','asc')->where('parent_id',$child->id)->get() as $child2)
                                         <option data-tokens="{{$child2->title}}" value="{{$child2->id}}">{{'¦––––'.$child2->title}}</option>
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
                               <option data-tokens="{{$brand->title}}" value="{{$brand->id}}" >{{$brand->title}}</option>
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
                                 <option value="{{$seller->id}}">{{$seller->shopinfo->name}}</option>
                              @endforeach

                             </select>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Users</label>
                          <div class="col-sm-9">
                            <select name="brand_id[]" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple="">
                               <option value="0">Select Users</option>
                               @foreach($users as $user)
                               <option data-tokens="{{$user->name}}" value="{{$user->id}}" >{{$user->name}}</option>
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
                              <button class="btn btn-primary" name="save" type="submit">Add Currency</button>
                          </p>
                      </div>
                    </div>
                </div>
              </form>
          </div>
      </div>
    </div>
</div>
 --}}
  <!--Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="exampleModalLabel">Are you sure to delete this item? </h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<p>Once you delete this item. You can not restore this item again!</p>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        		<a type="button" href="#" class="btn btn-danger delete_trigger">Delete</a>
      		</div>
    	</div>
  	</div>
</div>

@push('footer')
   	<script type="text/javascript">

 function fetchData() {
            // alert(filter_by);
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,

                ajax: {
                    url: "{{ url('admin/get-coupons') }}",
                    type: 'GET',
                    // data: {
                    //     'filter_by': filter_by,
                    //     'start_date': start_date,
                    //     'end_date': end_date,
                    // }
                },
                aLengthMenu: [
                    [25, 50, 100, 500, 5000, -1],
                    [25, 50, 100, 500, 5000, "All"]
                ],
                iDisplayLength: 25,
                "language": {
                    "processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'
                },
                "order": [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'code',
                        name: 'code',
                        "className": "text-center",
                        orderable: false,
                        searchable: true,
                    },
                    {
                        data: 'type',
                        name: 'type',
                      
                    },
                    {
                        data: 'minimum_amount',
                        name: 'minimum_amount',
                      
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                      
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                      
                    },
                    {
                        data: 'max_qty_per_user',
                        name: 'max_qty_per_user',
                      
                    },
                    {
                        data: 'expire',
                        name: 'expire',
                      
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        "className": "text-center"
                    },

                ]
            });
        }
        fetchData();




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