@extends('backend.layouts.master')
@section('title', 'Corporate Request List - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Corporate > Corporate Request List</span>
            </div>
        </div>
    </div>


    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="designed_table">
                <table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Status</th>
                            <th data-priority="1" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Delete Modal -->
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
                    <textarea name="reason" id="reason"
                        class="form-control" placeholder="Write reason, why you want to delete this item."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a type="button" href="#" class="btn btn-danger delete_trigger">Delete</a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="career_quick_view_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="">Corporate Request Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body career_form_element">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

@push('footer')
    <script type="text/javascript">
        function fetchData() {
            // alert(filter_by);
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,

                ajax: {
                    url: "{{ url('admin/corporate/get-corporate-request-list') }}",
                    type: 'GET',
                },
                aLengthMenu: [
                    [25, 50, 100, 500, 5000, -1],
                    [25, 50, 100, 500, 5000, "All"]
                ],
                iDisplayLength: 25,
                "language": {
                    "processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'
                },
                "order": [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        "className": "text-center",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'user_id',
                        name: 'user_id',
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        "className": "text-center",
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        "className": "text-center",
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                        "className": "text-center",
                    },
                    {
                        data: 'deal_status',
                        name: 'deal_status',
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
        fetchData();

        // Quick View customer
        jQuery(document).on('click', '.corporate_request_quick_view_btn', function(e) {
            e.preventDefault();
            jQuery.ajax({
                url: "/admin/corporate/request/view/" + jQuery(this).attr('data-id'),
                type: "get",
                data: {},
                success: function(response) {
                    jQuery('.career_form_element').html(response);
                    jQuery('#career_quick_view_modal').modal('show');
                }
            });
        });

    </script>
@endpush
