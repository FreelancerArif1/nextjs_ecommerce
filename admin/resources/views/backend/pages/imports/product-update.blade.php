@extends('backend.layouts.master')
@section('title','Import - '.config('concave.cnf_appname'))
@section('content')
	<div class="grid-margin stretch-card">
	   	<div class="card">
	      	<div class="card-body"> 
	        	<span class="card-title">Dashboard > Import > 
	            <span class="create_product_title">Update Products</span>
	        	</span>
	      	</div>
	   	</div>
	</div>


   	<div class="product_type_selector ">
      	<div class="card mb-4">
         	<div class="card-body">
         		<p class="content_title">Export sample CSV for update products</p>
	            <form class="" method="post" action="{{ route('admin.import.product.update.csv.download') }}" enctype="multipart/form-data" >
	              	@csrf
	              	<div class="row">
		                <div class="col-md-12">
		                  	<div class="form-group row">
		                      	<label class="col-sm-4 col-form-label text-right">Select products for export CSV <span style="color: #f00">*</span></label>
		                      	<div class="col-sm-4">
		                        	<select name="product_id[]" data-max-options="20" data-size="10" class="selectpicker form-control" data-show-subtext="true" data-live-search="true" multiple>
	                                 	@foreach($products as $row)
	                                      <option value="{{$row->id}}" >{{$row->title}}</option>
	                                 	@endforeach
	                                </select>
		                      	</div>
		                      	<div class="col-sm-4">
		                      		<p class="text-left">
			                            <button class="btn btn-primary" type="submit">Export sample CSV</button>
			                        </p>
		                      	</div>
		                  	</div>
		                </div>  
	              	</div>
	          	</form>
         	</div>
      	</div>

      	<div class="card">
         	<div class="card-body">
         		<p class="content_title">Import CSV for update products</p>
	            <form class="" method="post" action="{{ route('admin.import.product.update.csv.store') }}" enctype="multipart/form-data" >
	              	@csrf
	              	<div class="row">
		                <div class="col-md-12">
		                  	<div class="form-group row">
		                      	<label class="col-sm-4 col-form-label text-right">Import File (CSV) <span style="color: #f00">*</span></label>
		                      	<div class="col-sm-4">
		                        	<input type="file" name="import_product_file" class="form-control" accept=".csv" required="" />
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
         	</div>
      	</div>
   	</div>


@endsection