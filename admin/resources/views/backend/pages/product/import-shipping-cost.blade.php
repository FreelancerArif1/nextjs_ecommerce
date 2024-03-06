@extends('backend.layouts.master')
@section('title','Import Shipping Cost- '.config('concave.cnf_appname'))
@section('content')
	<div class="grid-margin stretch-card">
	   	<div class="card">
	      	<div class="card-body"> 
	        	<span class="card-title">Dashboard > Product > 
	            <span class="create_product_title">Shipping Cost</span>
	        	</span>
	         	<a href="{{ asset('files') }}/sample-csv/shipping_cost_sample.csv" class="btn btn-info float-right" title="Shipping Cost Sample CSV">Shipping Cost Sample CSV</a>
	      	</div>
	   	</div>
	</div>


   	<div class="product_type_selector ">
      	<div class="card">
         	<div class="card-body">
	            <form class="" method="post" action="{{ route('admin.product.shipping.cost.store') }}" enctype="multipart/form-data" >
	              	@csrf
	              	<div class="row">
		                <div class="col-md-12">
		                  	<div class="form-group row">
		                      	<label class="col-sm-4 col-form-label text-right">Import File (CSV) <span style="color: #f00">*</span></label>
		                      	<div class="col-sm-4">
		                        	<input type="file" name="import_shippingcost_file" class="form-control" accept=".csv" required="" />
		                      	</div>
		                      	<div class="col-sm-4">
		                      		<p class="text-left">
			                            <button class="btn btn-primary" type="submit">Import</button>
			                        </p>
		                      	</div>
		                  	</div>
		                </div>  
	              	</div>
	          	</form>


	          	<div class="table-responsive">
	          		<p class="content_title text-capitalize">Shipping cost list</p>
	          		<table id="shipping_cost_table" class="table table-striped table-bordered" style="width:100%">
				        <thead>
				            <tr>
				                <th>S.N</th>
				                <th>Type</th>
				                <th>Weight</th>
				                <th>Unit</th>
				                <th>Inside Origin (Standard)</th>
				                <th>Outside Origin (Standard)</th>
				                <th>Inside Origin (Express)</th>
				                <th>Outside Origin (Express)</th>
				            </tr>
				        </thead>
				        <tbody>
				        	@foreach($shipping_cost as $row)
					            <tr>
					                <td>{{ $loop->iteration }}</td>
					                <td class="text-capitalize">{{ $row['product_type'] ?? '' }}</td>
					                <td>{{ $row['weight'] }}</td>
					                <td class="text-uppercase">{{ $row['weight_unit'] }}</td>
					                <td>BDT {{ $row['inside_origin_standard'] }}</td>
					                <td>BDT {{ $row['outside_origin_standard'] }}</td>
					                <td>BDT {{ $row['inside_origin_express'] }}</td>
					                <td>BDT {{ $row['outside_origin_express'] }}</td>
					            </tr>
					        @endforeach
				        </tbody>
				    </table>
	          	</div>
         	</div>
      	</div>
   	</div>

   	@push('footer')
   		<script type="text/javascript">
   			$(document).ready(function () {
			    $('#shipping_cost_table').DataTable();
			});
   		</script>
   	@endpush
@endsection