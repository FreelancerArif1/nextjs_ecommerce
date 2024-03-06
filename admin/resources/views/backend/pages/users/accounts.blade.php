@extends('backend.layouts.master')

@section('title','Seller Statements - '.config('concave.cnf_appname'))

@section('content')

	<div class="grid-margin stretch-card">
		<div class="card">
			<div class="card-body d-flex">
				<div class="w-50">
					<span class="card-title ">Dashboard > User Management > Customer > Statements</span><br>
		        	<b class="text-uppercase">{{ $user->name }}</b>
				</div>
		        {{-- <p class="w-50 text-right"><button class="btn btn-success">Print statements</button></p> --}}
			</div>
		</div>
	</div>


	<div class="grid-margin">
        <div class="row">

        	<div class="col-md-2">
	            <div class="card bg-c-leaf">
	                <div class="card-body pb-0">
	                    <div class="text-center">
	                        <h5 class="mb-2 text-light">{{ $user->number_of_order($user->id) }}</h5>
	                        <h4 class="card-title mb-2">No of Order</h4>
	                    </div>
	                </div>
	            </div>
	        </div>

	        <div class="col-md-2">
	            <div class="card bg-c-violate">
	                <div class="card-body pb-0">
	                    <div class="text-center">
	                        <h5 class="mb-2 text-light">{{ Helper::getDefaultCurrency()->currency_symbol.' '.$user->order_amount($user->id) }}</h5>
	                        <h4 class="card-title mb-2">Order Amount</h4>
	                    </div>
	                </div>
	            </div>
	        </div>

            <div class="col-md-2">
                <div class="card bg-c-yellow">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">{{ Helper::getDefaultCurrency()->currency_symbol.' '. $user->order_paid_amount($user->id) }}</h5>
                            <h4 class="card-title mb-2">Paid Amount</h4>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-2">
                <div class="card bg-c-blue">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">{{ Helper::getDefaultCurrency()->currency_symbol.' '. $user->order_pending_amount($user->id)}}</h5>
                            <h4 class="card-title mb-2">Pending Amount</h4>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="col-md-2">
                <div class="card bg-c-green">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">{{ Helper::getDefaultCurrency()->currency_symbol.' '. $user->loyalty_point($user->id) }}</h5>
                            <h4 class="card-title mb-2">Loyalty Point</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card bg-c-pink">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">{{  $user->product_return($user->id)}}</h5>
                            <h4 class="card-title mb-2">Product Return</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="grid-margin">
		<div class="row">
	      	<div class="col-md-12">
			    <div class="card">
			    	<div class="card-body">
			    		<div class="d-flex justify-content-between">
		                  	<h2 class="card-title" style="font-size: 20px;"> Latest Orders List</h2>
		               	</div>
				      	<div class="designed_table table-responsive">
					        <table id="dataTable" class="table">
					          	<thead>
					            	<tr>
					                	<th>Order Id</th>
			                            <th>Date</th>
			                            <th>User</th>
			                            <th>Shipping Name</th>
			                            <th>Shipping Phone</th>
			                            <th>Payment Method</th>
			                            <th>Price</th>
			                            <th>Payment Status</th>
			                            <th class="text-center">Action</th>
					            	</tr>
					          	</thead>
					          	<tbody>
					          	</tbody>
					      	</table>
				      	</div>
			    	</div>
			    </div>
			</div>
		</div>
	</div>

	<div class="grid-margin">
		<div class="row">
	      	<div class="col-md-6">
			    <div class="card">
			    	<div class="card-body">
			    		<div class="d-flex justify-content-between">
		                  	<h2 class="card-title" style="font-size: 20px;">Product On Cart</h2>
		               	</div>
				      	<div class="designed_table">
					        <table class="table">
					          	<thead>
					            	<tr>
			                            <th>Product</th>
			                            <th>Type</th>
			                            <th>Price</th>
			                            <th>Quantity</th>
					            	</tr>
					          	</thead>
					          		@foreach($carts as $row)
						          		<tr>
						          			<td>
						          				<div class="media">
						                            <img class="list_img mr-3" src="/{{ $row->product->default_image ?? 'no_image.png' }}" alt="">
						                            <div class="media-body">
						                                <p class="product_title">
						                                	<a target="_blank" href="{{ env("APP_FRONTEND") }}'/product/'{{ $row->product->slug }}" class="text-dark text-decoration-none">{{ $row->product->title }}</a>
						                                </p>
						                                <a target="_blank" href="/admin/seller/edit/{{ $row->product->seller_id }}" class="seller_name text-decoration-none">{{ $row->product->seller->shopinfo->name ?? '-' }}</a>
						                            </div>
						                        </div>
						          			</td>
						          			<td>{{ $row->product_type ?? '-'}}</td>
						          			<td>{{ $row->price ?? 0 }}</td>
						          			<td>{{ $row->qty ?? 0 }}</td>
						          		</tr>
						          	@endforeach
					          	<tbody>
					          	</tbody>
					      	</table>
				      	</div>
			    	</div>
			    </div>
			</div>

			<div class="col-md-6">
			    <div class="card">
			    	<div class="card-body">
			    		<div class="d-flex justify-content-between">
		                  	<h2 class="card-title" style="font-size: 20px;">Product On Wishlist</h2>
		               	</div>
				      	<div class="designed_table">
					        <table class="table">
					          	<thead>
					            	<tr>
			                            <th>Product</th>
			                            <th>Type</th>
			                            <th>Price</th>
					            	</tr>
					          	</thead>
					          		@foreach($wishlists as $row)
						          		<tr>
						          			<td>
						          				<div class="media">
						                            <img class="list_img mr-3" src="/{{ $row->product->default_image ?? 'no_image.png' }}" alt="">
						                            <div class="media-body">
						                                <p class="product_title">
						                                	<a target="_blank" href="{{ env("APP_FRONTEND") }}'/product/'{{ $row->product->slug }}" class="text-dark text-decoration-none">{{ $row->product->title }}</a>
						                                </p>
						                                <a target="_blank" href="/admin/seller/edit/{{ $row->product->seller_id }}" class="seller_name text-decoration-none">{{ $row->product->seller->shopinfo->name ?? '-' }}</a>
						                            </div>
						                        </div>
						          			</td>
						          			<td>{{ $row->product->product_type ?? '-'}}</td>
						          			<td>{{ $row->product->price ?? 0 }}</td>
						          		</tr>
						          	@endforeach
					          	<tbody>
					          	</tbody>
					      	</table>
				      	</div>
			    	</div>
			    </div>
			</div>

		</div>
	</div>

	
	@push('footer')
	   	<script type="text/javascript">
	   		function fetchData(id){
	   			var datatable = jQuery('#dataTable').DataTable({
	   				dom: 'Brftlip',
        			buttons: [ 'csv', 'excel', 'pdf', 'print'],
					responsive: true,
					processing: true,
					serverSide: true,
					autoWidth: true,
					ajax: {
						url: "{{ url('/admin/users/accounts/orders/') }}/"+id,
						type: 'GET',
					},
	                aLengthMenu: [
	                    [25, 50, 100, 500, 5000, -1],
	                    [25, 50, 100, 500, 5000, "All"]
	                ],
	                iDisplayLength: 25,
					"order": [[0, 'desc']],
	                "language": {"processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'},
					columns: [
			         	{data: 'id'},
			            {data: 'order_date', name: 'order_date'},
			            {data: 'user', name: 'user'},
			            {data: 'shipping_name', name: 'shipping_name'},
			            {data: 'shipping_phone', name: 'shipping_phone'},
			            {data: 'payment_method', name: 'payment_method'},
			            {data: 'paid_amount', name: 'paid_amount'},
			            {data: 'status', name: 'status'},
			            {data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
					]
				});
	   		}

	   		fetchData({{$user->id}});
	    </script>

	@endpush
	      
@endsection