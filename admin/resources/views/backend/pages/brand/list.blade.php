@extends('backend.layouts.master')
@section('title','Brand List - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="card-body">
        <span class="card-title">Dashboard > Catalog > Brands</span>
		@if(Auth::user()->can('brand.create'))
			<a class="btn btn-success float-right" href="{{ route('admin.brand.create')}}">Create New Brand</a>
		@endif
      </div>
   </div>
</div>
<div class="grid-margin">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="table-responsive">
              
               <table id="dataTable" class="table" >
                  <thead>
                     <tr>
                        <th>Serial</th>
                        <th>Brand</th>
                        <th>Slug</th>
                        <th>Meta Title</th>
                        <th>Meta KeyWord</th>
                        <th>Status</th>
                        <th class="text-center" data-priority="1">Action</th>
                     </tr>
                  </thead>
                  <tbody></tbody>
               </table>
            </div>
         </div>
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
            <p>Once you delete this item, you can restore this from trash list!</p>
            <textarea name="reason" id="reason" placeholder="Write reason, why you want to delete this item." class="form-control"></textarea>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <a type="button" href="#" class="btn btn-danger delete_trigger">Delete</a>
         </div>
      </div>
   </div>
</div>
@endsection

@push('footer')
    
<script type="text/javascript">
	var table = jQuery('#dataTable').DataTable({
      dom: 'Brftlip',
      buttons: [ 'csv', 'excel', 'pdf', 'print'],
		responsive: true,
		processing: true,
		serverSide: true,
		autoWidth: false,
		ajax: {
			url: "{{ route('admin.get.brand.list') }}",
			type: 'GET',
		},
		
		"order": [[0, 'desc']],
      "language": {"processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'},
		columns: [
         {data: 'DT_RowIndex',"className" : "text-center",orderable: false, searchable: false,},
         {data: 'brand', name: 'brand'},
         {data: 'slug'},
         {data: 'meta_title'},
         {data: 'meta_keyword', "className" : "white-space-break"},
         {data: 'status', name: 'status'},
         {data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
		]
	});
</script>
@endpush