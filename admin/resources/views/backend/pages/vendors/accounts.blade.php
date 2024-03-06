@extends('backend.layouts.master')

@section('title','Seller Statements - '.config('concave.cnf_appname'))

@section('content')

	<div class="grid-margin stretch-card">
		<div class="card">
			<div class="card-body d-flex">
				<div class="w-50">
					<span class="card-title ">Dashboard > User Management > Sellers > Statements</span><br>
		        	<b class="text-uppercase">{{ $vendor->shopinfo->name }}</b>
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
	                        <h5 class="mb-2 text-light">{{ Helper::get_seller_products($vendor->id) }}</h5>
	                        <h4 class="card-title mb-2">No of Products</h4>
	                    </div>
	                </div>
	            </div>
	        </div>

	        <div class="col-md-2">
	            <div class="card bg-c-violate">
	                <div class="card-body pb-0">
	                    <div class="text-center">
	                        <h5 class="mb-2 text-light">৳ {{ Helper::get_seller_sale_amount($vendor->id) }}</h5>
	                        <h4 class="card-title mb-2">Order Amount</h4>
	                    </div>
	                </div>
	            </div>
	        </div>

            <div class="col-md-2">
                <div class="card bg-c-yellow">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">{{ Helper::getDefaultCurrency()->currency_symbol.' '. Helper::getSellerPendingMaturationBalance($vendor->id) }}</h5>
                            <h4 class="card-title mb-2">Pending Maturation</h4>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-2">
                <div class="card bg-c-blue">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">{{ Helper::getDefaultCurrency()->currency_symbol.' '. Helper::get_seller_revenue($vendor->id)}}</h5>
                            <h4 class="card-title mb-2">Total Revenue</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card bg-c-pink">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">{{ Helper::getDefaultCurrency()->currency_symbol.' '. Helper::getSellerWithdrawalAmount($vendor->id)}}</h5>
                            <h4 class="card-title mb-2">Amount Withdrawn</h4>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-2">
                <div class="card bg-c-green">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <h5 class="mb-2 text-light">{{ Helper::getDefaultCurrency()->currency_symbol.' '. Helper::get_seller_balance($vendor->id)}}</h5>
                            <h4 class="card-title mb-2">Available Balance</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    	<div class="col-md-6 grid-margin">
	      	<div class="card">
	         	<div class="card-body">
	               	<div>
	                  	<canvas id="order_summary"></canvas>
	               	</div>
	         	</div>
	      	</div>
	   	</div>

	   	<div class="col-md-6 grid-margin">
	      	<div class="card">
	         	<div class="card-body">
	               	<div>
	                  	<canvas id="statistics"></canvas>
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
		                  	<h2 class="card-title" style="font-size: 20px; margin-bottom: 18px !important;"> Orders List</h2>
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
	      	<div class="col-md-12">
			    <div class="card">
			    	<div class="card-body">
			    		<div class="d-flex justify-content-between">
		                  	<h2 class="card-title" style="font-size: 20px; margin-bottom: 18px !important;"> Orders List</h2>
		               	</div>
				      	<div class="designed_table table-responsive">
					        <table id="productDatatable" class="table">
					          	<thead>
					            	<tr>
					                	<th>Id</th>
			                            <th>Product</th>
					                    <th>SKU</th>
					                    <th>Brand</th>
					                    <th>Category</th>
					                    <th>Type</th>
					                    <th>Price</th>
					                    <th>Weight</th>
					                    <th>Qty</th>
					                    <th>Viewed</th>
					                    <th>Status</th>
					                    <th>Action</th>
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
						url: "{{ url('/admin/seller/accounts/orders/') }}/"+id,
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


	   		function fetchProductData(id){
	   			var datatable = jQuery('#productDatatable').DataTable({
	   				dom: 'Brftlip',
        			buttons: [ 'csv', 'excel', 'pdf', 'print'],
					responsive: true,
					processing: true,
					serverSide: true,
					autoWidth: true,
					ajax: {
						url: "{{ url('/admin/seller/accounts/products/') }}/"+id,
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
			         	{data: 'id',"className" : "text-center",orderable: false, searchable: false,},
			            {data: 'title','width': '20%'},
			            {data: 'sku', "className" : "text-uppercase"},
			            {data: 'brand_title'},
			            {data: 'category_id','width': '10%', "className" : "white-space-break"},
			            {data: 'product_type'},
			            {data: 'price'},
			            {data: 'weight'},
			            {data: 'qty',"className" : "text-center"},
			            {data: 'viewed',"className" : "text-center"},
			            {data: 'status', name: 'status'},
			            {data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
					]
				});
	   		}

	   		fetchData({{$vendor->id}});
	   		fetchProductData({{$vendor->id}});
	    </script>

	    <script>
	       //SETUP
	       	const ordersummaryLabels = [
		        'January',
		        'February',
		        'March',
		        'April',
		        'May',
		        'June',
		        'July',
		        'August',
		        'September',
		        'October',
		        'November',
		        'December',
		    ];
	        const ordersummaryData = {
	         	labels: ordersummaryLabels,
	         	datasets: [{
	            	label: 'Number of Sales',
	            	backgroundColor: 'rgb(34, 245, 200)',
	            	data: [
		               	@for ($i = 1; $i <= 12; $i++)
		                  	@php
		                     	$total_sale = 0;
		                     	foreach ($completed_orders as $row) {
		                        	$date = DateTime::createFromFormat("Y-m-d", $row->created_at);
		                        	if ($i == date('m',$date)) {
		                           		$total_sale++;
		                        	}
		                     	}
		                  	@endphp
		                  	{{ $total_sale }},
		               	@endfor 
	            	],
	         	}]
	        };
	      	//Config
	      	const ordersummaryConfig = {
	         	type: 'bar',
	         	data: ordersummaryData,
	         	options: {}
	        };


	      	const ordersummaryChart = new Chart(
	         	document.getElementById('order_summary'),
	         	ordersummaryConfig
	      	);


	      	const labels = [
	         	'January',
	         	'February',
	         	'March',
	         	'April',
	         	'May',
	         	'July',
	         	'August',
	         	'September',
	         	'October',
	         	'November',
	         	'December',
	        ];

	        const data = {
	         	labels: labels,
	         	datasets: [{
		            label: 'Monthly Sale',
		            backgroundColor: 'rgb(255, 99, 132)',
		            borderColor: 'rgb(255, 99, 132)',
		            data: [
			            @for ($i = 1; $i <= 12; $i++)
			               	@php
			                  	$total_revenue = 0;
			                  	foreach ($all_orders as $row) {
			                     	$date = DateTime::createFromFormat("Y-m-d", $row->created_at);
			                     	if ($i == date('m',$date)) {
			                        	$total_revenue = $total_revenue + $row->paid_amount;
			                     	}
			                  	}
			               	@endphp
			               	{{ $total_revenue }},
			            @endfor
	            	],
	        	}]
	      	};
	      	//Config
	     	const config = {
	         	type: 'line',
	         	data: data,
	         	options: {}
	        };


	      	const salesFigureChart = new Chart(
	         	document.getElementById('statistics'),
	         	config
	      	);
	   	</script>
	@endpush
	      
@endsection