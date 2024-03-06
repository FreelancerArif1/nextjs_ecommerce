@extends('backend.layouts.master')
@section('title','Voucher Categories - '.config('concave.cnf_appname'))
@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		<div class="card-body">
      		<span class="card-title">Dashboard > Voucher Category</span>
      		<a class="btn btn-success float-right" data-toggle="modal" data-target="#SaveModal" href="#">Create New Voucher Category</a>
		</div>
	</div>
</div>


<div class="grid-margin stretch-card">
    <div class="card">
      	<div class="designed_table table-responsive">
        	<table id="dataTable" class="table">
          		<thead>
            		<tr>
		              	<th>No</th>
		              	<th>Title</th>
                    	<th>Banner</th>
		              	<th>Status</th>
		              	<th class="text-center">Action</th>
		            </tr>
          		</thead>
          		<tbody>

        			@foreach ($category as $row)
            		<tr>
                		<td class="pl-3">{{$loop->iteration}}</td>
                		<td id="title{{$row->id}}">{{$row->title}}</td>
                    <td id="category_banner{{$row->id}}" data-banner="{{$row->category_banner}}"><img class="thumb-image" src="{{'/'.$row->category_banner}}"></td>
                		<td id="status{{$row->id}}" data-status="{{$row->status}}">
                			@if($row->status == 1) 
                				<span class="text-success">Active</span> 
                			@else 
                				<span class="text-danger">Inactive</span>
                			@endif
                		</td>
                		<td class="text-center">
                  			@if(Auth::user()->can('brand.delete'))
                          		<a class="text-success edit_btn"  id="{{$row->id}}" ><i class="mdi mdi-pencil-box-outline"></i></a>

                    			<a class="text-danger delete_btn" data-url="{{ route('admin.voucher.category.delete',$row->id)}}" data-toggle="modal" data-target="#deleteModal" href="#"><i class="mdi mdi-delete"></i></a>
                  			@endif
                		</td>
            		</tr>
        			@endforeach
          		</tbody>
        	</table>
      	</div>
    </div>
</div>

    <!--Save Modal -->
<div class="modal fade" id="SaveModal" tabindex="-1" role="dialog" aria-labelledby="SaveModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
	      	<div class="modal-header">
	        	<h5 class="modal-title" id="SaveModalLabel">Add New Voucher Category </h5>
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          		<span aria-hidden="true">&times;</span>
	        	</button>
	      	</div>
      		<div class="modal-body">
        		<form class="form-sample" method="post" action="{{ route('admin.voucher.category.store')}}" enctype="multipart/form-data" >
          			@csrf
          			<div class="row">
              			<div class="col-md-12">
                			<div class="form-group row">
                  				<label class="col-sm-3 col-form-label">Title</label>
                  				<div class="col-sm-9">
			                    	<input type="text" name="title" class="form-control" required="" />
			                  	</div>
                			</div>
              			</div>
                    <div class="col-md-12">
                      <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Banner</label>
                          <div class="col-sm-9">
                            <div class="form-group ">
                                  <button type="button"
                                     data-image-width="1420" 
                                     data-image-height="290"  
                                     data-input-name="category_banner" 
                                     data-input-type="single" 
                                     class="btn btn-success initConcaveMedia" >Select Image
                                  </button>
                              </div>
                          </div>
                      </div>
                    </div>
              			<div class="col-md-12">
                			<div class="form-group row">
                  				<label class="col-sm-3 col-form-label">Status</label>
                  				<div class="col-sm-9">
                    				<select name="status" class="form-control" required="">
			                      		<option value="1">Active</option>
			                      		<option  value="0">Inactive</option>
			                    	</select>
                  				</div>
                			</div>
              			</div>
              		</div>
		            <div class="row">
		              	<div class="col-md-12">
		                	<div class="form-group">
		                  		<p class="text-right">
		                      		<button class="btn btn-primary" name="save" type="submit">Add Category</button>
		                  		</p>
		                	</div>
		              	</div>
		            </div>
          		</form>
      		</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="UpdateModal" tabindex="-1" role="dialog" aria-labelledby="UpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="SaveModalLabel">Edit Voucher Category </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="form-sample" method="post" action="{{ route('admin.voucher.category.update')}}" enctype="multipart/form-data" >
                @csrf
                <div class="row">
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Title</label>
              				<div class="col-sm-9">
		                    	<input type="text" name="title" class="form-control" id="editTitle" required="" />
		                  	</div>
            			</div>
          			</div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Banner</label>
                      <div class="col-sm-9">
                        <div class="form-group ">
                              <button type="button"
                                 data-image-width="1420" 
                                 data-image-height="290"  
                                 data-input-name="category_banner" 
                                 data-input-type="single" 
                                 class="btn btn-success initConcaveMedia" >Select Image
                              </button>

                              <p class="selected_images_gallery">
                                  <span>
                                    <input type="hidden" value="" name="previous_category_banner" id="category_banner">
                                    <img src="" class="view_image"> 
                                    <b data-file-url="" class="selected_image_remove">X</b>
                                 </span>
                              </p>
                          </div>
                      </div>
                  </div>
                </div>
          			<div class="col-md-12">
            			<div class="form-group row">
              				<label class="col-sm-3 col-form-label">Status</label>
              				<div class="col-sm-9">
                				<select name="status" class="form-control" id="editStatus" required="">
		                      		<option value="1">Active</option>
		                      		<option  value="0">Inactive</option>
		                    	</select>
              				</div>
            			</div>
          			</div>
          		</div>
      
                <div class="row">
                    <div class="col-md-12">
                      	<div class="form-group">
                          	<p class="text-right">
                          		<input type="hidden" name="editId" id="editId">
                              	<button class="btn btn-primary" name="save" type="submit">Update Category</button>
                          	</p>
                      	</div>
                    </div>
                </div>
              </form>
          </div>
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

@push('footer')
   	<script type="text/javascript">

      	jQuery(document).on('change','#type',function(e){
        	e.preventDefault();
        	var val = $(this).val();
        	// alert(val);
        	if(val == 'Fixed Amount') {
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

      	jQuery(document).on('click','.edit_btn',function(e){
        	e.preventDefault();
        	var id = jQuery(this).attr('id');

        	jQuery('#editStatus option[value='+$('#status'+id).attr("data-status")+ ']')
            .attr('selected',true);

            jQuery('#editTitle').val($('#title'+id).html());
            jQuery('#category_banner').val($('#category_banner'+id).attr("data-banner"));
            jQuery('.view_image').attr("src", '/'+$('#category_banner'+id).attr("data-banner"));

        	jQuery("#editId").val(id);
        	$('#UpdateModal').modal('show');
        });

   	</script>
@endpush
      
@endsection