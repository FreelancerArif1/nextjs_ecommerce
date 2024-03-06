@extends('backend.layouts.master')
@section('title','User Search Keyword - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="card-body">
        <span class="card-title">Dashboard > Matrketing > User Search Keywords</span>
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
                        <th>Date</th>
                        <th>User</th>
                        <th>Keyword</th>
                        <th data-priority="1">No of Times</th>
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
			url: "{{ route('admin.marketing.user.search.keyword.data') }}",
			type: 'GET',
		},
		
		"order": [[4, 'desc']],
      "language": {"processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'},
		columns: [
         {data: 'DT_RowIndex',"className" : "text-center",orderable: false, searchable: false,},
         {data: 'created_at', name: 'created_at'},
         {data: 'user_id', name: 'user_id'},
         {data: 'content', name: 'content'},
         {data: 'add_info1', name: 'add_info1'},
		]
	});
   console.log(table.data);
</script>
@endpush