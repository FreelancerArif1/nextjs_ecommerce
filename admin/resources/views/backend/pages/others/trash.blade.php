@extends('backend.layouts.master')
@section('title','Trash System - '.config('concave.cnf_appname'))
@section('content')
<div class="grid-margin stretch-card">
   <div class="card">
      <div class="card-body">
         <span class="card-title">Dashboard > Trash System</span>
      </div>
   </div>
</div>

<form action="{{ route('admin.trash.bulk.action')}}" method="POST" style="width: 100%;">

<div class="grid-margin">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="toolbar">
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bulk Action</label>
                        <div class="col-sm-3">
                           <div class="input-group">
                              <select name="action" id="bulkOption" class="form-control">
                                 <option value="">--Select Option First--</option>
                                 <option value="undo">Undo Trash Items</option>
                                 <option value="delete" onClick="confirm('Are you sure to delete ?')">Delete Selected Items</option>
                              </select>
                           </div>
                        </div>
                        <label class="col-sm-2"><button class="btn btn-dark" type="submit">Apply</button></label>

                        <label class="col-sm-1 col-form-label">Filter Option</label>

                        <div class="col-sm-2 ">
                           <div class="input-group">
                               <input type="date" name="start_date" id="start_date" class="form-control">
                           </div>
                        </div>
                        <div class="col-sm-2" >
                           <div class="input-group">
                               <input type="date" name="end_date" id="end_date" class="form-control">
                           </div>
                        </div>

                     </div>
                  </div>
                  <div class="col-md-4 text-right"></div>
               </div>
            </div>


            <div class="designed_table table-responsive">
               <table id="dataTable" class="table" >
                  <thead>
                     <tr>
                        <th>SL</th>
                        <th>
                           <div class="form-check form-check-flat">
                              <label class="form-check-label">
                                 <input id="select_all" type="checkbox"  class="form-check-input"><i class="input-helper"></i>
                              </label>
                           </div>
                        </th>

                        <th>Type</th>
                        <th>Reason</th>
                        <th>Deleted By</th>
                        <th>Date/Time</th>
                        <th class="text-center">Action</th>
                     </tr>
                  </thead>
                  <tbody class="action_btn_review"> </tbody>
               </table>
            </div>
         </div>
      </div>
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
   function getData(start_date, end_date){
      var table = jQuery('#dataTable').DataTable({
         dom: 'Brftlip',
         buttons: [ 'csv', 'excel', 'pdf', 'print'],
      	responsive: true,
      	processing: true,
      	serverSide: true,
      	autoWidth: true,
      	ajax: {
      		url: "{{ url('admin/trash/get-trash-list/') }}/"+start_date+"/"+end_date,
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
              {data: 'DT_RowIndex',"className" : "text-center",orderable: false, searchable: false,},
              {data: 'checkbox', name: 'checkbox',"className" : "text-center",orderable: false, searchable: false,},
              {data: 'type',"className" : "text-uppercase"},
              {data: 'reason'},
              {data: 'deleted_by', 'name' : 'admin.name'},
              {data: 'created_at'},
              {data: 'action', name: 'action', orderable: false, searchable: false, "className" : "text-center"},
      	]
      });
   }

   jQuery('#dataTable').DataTable().destroy();
   getData(0, 0);

   jQuery(document).on('change', '#start_date, #end_date', function(e) {
      e.preventDefault();
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();
      
      if (start_date != '' && end_date != '') {
         jQuery('#dataTable').DataTable().destroy();
         getData(start_date, end_date);
      }
   });
</script>
@endpush
