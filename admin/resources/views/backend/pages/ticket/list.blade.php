@extends('backend.layouts.master')
@section('title','Ticket List - '.config('concave.cnf_appname'))

@section('content')
<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > Tickets</span>
      <a class="btn btn-success float-right" href="{{ route('admin.ticket.create')}}">Create New Ticket</a>
		  </div>
	</div>
</div>
<div class="grid-margin stretch-card">
  <form action="{{ route('admin.ticket.bulk.action')}}" method="POST" style="width: 100%;">
    <div class="card">
      @if(!Auth::user()->hasRole('seller'))
      <div class="toolbar">
          <div class="row">
             <div class="col-md-12">
                <div class="form-group row">
                   <div class="col-sm-3">
                      <label class="col-form-label">Bulk Action</label>
                      <div class="input-group">
                         <select name="action" id="bulkOption" class="form-control">
                            <option value="">--Select Option First--</option>
                            <option value="open">Change Status to Open</option>
                            <option value="closed">Change Status to Closed</option>
                            <option value="delete" onclick="confirm('Are you sure to delete ?')">Delete Selected Items</option>
                         </select>
                      </div>
                   </div>
                   <label class="col-sm-3 pt-2"><button class="btn btn-dark mt-4" type="submit">Apply</button></label>
                </div>
             </div>
          </div>
        </div>
      @endif

      <div class="card-body">
        <table id="dataTable"  class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>
                <div class="form-check form-check-flat">
                   <label class="form-check-label">
                      <input id="select_all" type="checkbox"  class="form-check-input"><i class="input-helper"></i>
                   </label>
                </div>
              </th>
              <th>Subject</th>
              <th>Priority</th>
              <th>User Type</th>
              <th>User Name</th>
			        <th>Department</th>
              <th>Created Time</th>
              <th>Status</th>
              <th data-priority="1">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </form>
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
        autoWidth: true,
        ajax: {
          url: "{{ route('admin.get.ticket.list') }}",
          type: 'GET',
        },
        "order": [[0, 'desc']],
        "language": {"processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'},
        columns: [
            {data: 'id'},
            {data: 'checkbox', name: 'checkbox',"className" : "text-center",orderable: false, searchable: false,},
            {data: 'subject'},
            {data: 'priority_id'},
            {data: 'user_type'},
            {data: 'user_id'},
            {data: 'department_id'},
            {data: 'created_at'},
            {data: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
        ]
      });

    </script>
@endpush