@extends('backend.layouts.master')
@section('title','Import - '.config('concave.cnf_appname'))
@section('content')
	<div class="grid-margin stretch-card">
	   	<div class="card">
	      	<div class="card-body"> 
	        	<span class="card-title">Dashboard > Import > 
	            <span class="create_product_title">Product Image</span>
	        	</span>
	         	<a href="{{ asset('files') }}/sample-csv/product_image_import_sample.csv" class="btn btn-info float-right" title="Download Sample CSV">Product Image Sample CSV</a>
	      	</div>
	   	</div>
	</div>


   <div class="product_type_selector ">
      <div class="card">
         <div class="card-body">
            <form class="" method="post" action="{{ route('admin.import.product.image.store.csv') }}" enctype="multipart/form-data" >
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