@extends('backend.layouts.master')
@section('title','Notifications - '.config('concave.cnf_appname'))
@section('content')

<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body"><span class="card-title">Dashboard > Notifications</span></div>
	</div>
</div>


<div class="grid-margin stretch-card">
    <div class="card">
      <div class="table-responsive">
        <table id="dataTable" class="table">
          <thead>
            <tr>
              <th>Serial No.</th>
              <th>User Name</th>
              <th>User Type</th>
              <th>Title</th>
              <th>Description</th>
              <th>Date/Time</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <!--Delete Modal -->
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
@push('footer')
    
<script type="text/javascript">



  $(document).on('click', '.nowStatus1', function(){
    var status = $(this).attr('data-status');
    var status_id = $(this).attr('data-status-id');
    var that = this;
    $.ajax({
        url: "/admin/change-notification-status",
        type: "post",
        data: { status: status, status_id: status_id},
        success: function(response) {
            if(response != 0){
                $(that).removeClass('nowStatus1');
                 $(that).addClass('nowStatus3');
                 $(that).attr('data-status', 1);
                 $(that).text('Seen');
                 $('.count').text(response.total);
            }else{
                alert('Something went wrong.');
            }
        }
    });
  });
  $(document).on('click', '.nowStatus3', function(){
    var status = $(this).attr('data-status');
    var status_id = $(this).attr('data-status-id');
    var that = this;
    $.ajax({
        url: "/admin/change-notification-status",
        type: "post",
        data: { status: status, status_id: status_id},
        success: function(response) {
            if(response != 0){
                $(that).removeClass('nowStatus3');
                 $(that).addClass('nowStatus1');
                 $(that).attr('data-status', 3);
                 $(that).text('Unseen');
                 $('.count').text(response.total);
            }else{
                alert('Something went wrong.');
            }
        }
    });
  });




	var table = jQuery('#dataTable').DataTable({
    dom: 'Brftlip',
    buttons: [ 'csv', 'excel', 'pdf', 'print'],
		responsive: true,
		processing: true,
		serverSide: true,
		autoWidth: true,
		ajax: {
			url: "{{ route('admin.get.notification.list') }}",
			type: 'GET',
		},
		"order": [[0, 'desc']],
    "language": {"processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'},
		columns: [
         {data: 'DT_RowIndex',"className" : "text-center",orderable: false, searchable: false,},
         {data: 'user', name: 'user'},
         {data: 'user_type', name: 'user_type'},
         {data: 'title'},
         {data: 'description',name: 'description'},
         {data: 'created_at',name:'created_at'},
         {data: 'status', name: 'status'},
         {data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
		]
	});
</script>
@endpush