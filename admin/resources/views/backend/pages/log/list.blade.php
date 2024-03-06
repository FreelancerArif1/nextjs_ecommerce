@extends('backend.layouts.master')
@section('title', 'Activity Log - ' . config('concave.cnf_appname'))
@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Activity Logs</span>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.activity.log.selected.delete')}}" method="POST" style="width: 100%;">
        <div class="row clearfix g-3">
            <div class="col-sm-12">
                <div class="card mb-3">
                    <div class="toolbar">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Bulk Action</label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <select name="action" id="bulkOption" class="form-control">
                                                <option value="">--Select Option First--</option>
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
                       </div>
                    </div>

                    <div class="designed_table table-responsive card-body">
                        <table id="dataTable" class="table " >
                            <thead>
                                <tr>
                                    <th>Log ID</th>
                                    <th>
                                        <div class="form-check form-check-flat">
                                            <label class="form-check-label">
                                                <input id="select_all" type="checkbox"  class="form-check-input"><i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </th>
                                    <th>Type</th>
                                    <th>Causer</th>
                                    <th>Date/Time</th>
                                    <th>Model Name</th>
                                    <th>Model Id</th>
                                    <th data-priority="1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>

                    </div>
                </div>

                {{-- <div class="mr-auto">{{ $activityLogs->links() }}</div> --}}

            </div>
        </div><!-- Row End -->
    </form>


    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                    <textarea name="reason" id="reason" placeholder="Write reason, why you want to delete this item."
                        class="form-control"></textarea>
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

        function fetchData(start_date, end_date) {
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,

                ajax: {
                    url: "{{ url('/admin/get-activity-log/') }}/"+start_date+"/"+end_date,
                    type: 'GET',
                },
                aLengthMenu: [
                    [25, 50, 100, 500, 5000, -1],
                    [25, 50, 100, 500, 5000, "All"]
                ],
                iDisplayLength: 25,
                "order": [[0, 'desc']],
                "language": {"processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'},
                columns: [{
                        data: 'id',
                        "className": "text-center",
                    },
                    {
                        data: 'checkbox',
                        name: 'checkbox',
                        "className": "text-center",
                        orderable : false,
                        searchable : false,
                    },
                    {
                        data: 'description',
                        name: 'description',
                        "className": "text-center",
                    },
                    {
                        data: 'causer_id',
                        name: 'causer.name',
                        "className": "text-center",
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        "className": "text-center",
                    },

                    {
                        data: 'subject_type',
                        name: 'subject_type',
                        "className": "text-center",
                    },
                    {
                        data: 'subject_id',
                        name: 'subject_id',
                        "className": "text-center",
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        "className": "text-center"
                    },

                ]
            });
        }

        jQuery('#dataTable').DataTable().destroy();
        fetchData(0,0);

        jQuery(document).on('click', '.logViewBtn', function(e) {
            e.preventDefault();
            var id = jQuery(this).attr('id');
            $.ajax({
                url: "{{ url('/admin/activity-log/view/') }}/" + id,
                type: "GET",
                dataType: "html",
                success: function(data) {
                    jQuery('#activityLogViewModalBody').html(data);
                    jQuery('#activityLogViewModal').modal('show');
                }
            })
        });

        jQuery(document).on('change', '#start_date, #end_date', function(e) {
            e.preventDefault();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            // alert(end_date);
            if (start_date != '' && end_date != '') {
                jQuery('#dataTable').DataTable().destroy();
                fetchData(start_date, end_date);
            }
       });
    </script>
@endpush
