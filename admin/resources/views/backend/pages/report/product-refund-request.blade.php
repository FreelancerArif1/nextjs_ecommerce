@extends('backend.layouts.master')
@section('title','Product Refund Report - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		 <div class="card-body">
	        <span class="card-title">Dashboard > Reports > Product Refund Report</span>
	        @if(Auth::user()->getRoleNames() != '["seller"]')
	        <div class="row">
	          	<div class="col-md-12">
	            	<form action="{{ route('admin.product.refund.report')}}" method="POST" >
	              		@csrf
		              	<div class="form-group row">
		                  	<div class="col-sm-2">
		                    	<div class="input-group">
		                       		<select name="saller" id="seller_id" class="selectpicker form-control" data-show-subtext="true" data-live-search="true">
		                          		<option value="">--Select Shop First--</option>
		                          		@foreach(App\Models\ShopInfo::all() as $row)
		                            		<option value="{{$row->seller_id}}">{{$row->name}}</option>
		                          		@endforeach
		                       		</select>
		                    	</div>
		                  	</div>
		                 	<label class="col-sm-1"><button class="btn btn-dark" type="submit">Filter</button></label>
		              	</div>
	            	</form>
	          	</div>
	        </div>
	        @endif
		</div>
	</div>
</div>
<div class="grid-margin stretch-card">
    <div class="card">
      	<div class="designed_table table-responsive" id="orderTable">
	        <table id="listTable" class="table dataTable">
	          	<thead>
	            	<tr>
	                	<th>#</th>
	                	<th>Order Id</th>
	                	<th>User</th>
	                	<th>User Phone</th>
	                	<th>Seller</th>
	                	<th>Product Name</th>
	                	<th>Quantity</th>
	                	<th>Price</th>
	                	<th>Status</th>
	                	<th class="text-center"  data-priority="1">Action</th>
	            	</tr>
	          	</thead>
	          	<tbody>
		          	@foreach ($refunds as $row)
		              	<tr>
		                  	<td>#{{$row->id}}</td>
		                  	<td>{{$row->order_id }}</td>
		                  	<td>{{$row->user->firstname.' '.$row->user->lastname}}</td>
		                  	<td>{{$row->user->phone}}</td> 
		                  	<td>{{$row->seller->name}}</td> 
		                  	<td>{{$row->product->title}}</td> 
		                  	<td>{{$row->product_qty}}</td> 
		                  	<td>{{Helper::getDefaultCurrency()->currency_symbol . ' ' . $row->price}}</td> 
		                  	<td>
		                  		<label class="badge text-light" style="background-color: {{$row->statuses->color_code}};">{{$row->statuses->title}}</label>
		                  	</td> 
		                  	<td>
		                  		@if(Auth::user()->can('report.edit'))
                    				<a class="btn btn-success delete_btn" data-url="" data-toggle="modal" data-target="#deleteModal" href="#">Approved</a>
                  				@endif
		                  	</td> 
		              	</tr>
		          	@endforeach
	          	</tbody>
	      	</table>
      	</div>
      	
    </div>
</div>

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


      
@endsection