@extends('backend.layouts.master')
@section('title', 'Career Request - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Career > Career Request</span>
               
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
                            <th>Position</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>File</th>
                            {{-- <th>Cover Letter</th> --}}
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
                    <textarea name="reason" class="reason" placeholder="Write reason, why you want to delete this item."
                        class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a type="button" href="#" class="btn btn-danger delete_trigger">Delete</a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="career_request_quick_view_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="">Career Request Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body career_request_form_element">

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
                    url: "{{ url('admin/career/get-career-request') }}",
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
                        data: 'job_id',
                        name: 'job_id',
                    },
                    {
                        data: 'first_name',
                        name: 'first_name',
                        "className": "text-center",
                    },
                    {
                        data: 'last_name',
                        name: 'last_name',
                        "className": "text-center",
                    },
                    {
                        data: 'email',
                        name: 'email',
                        "className": "text-center",
                    },

                    {
                        data: 'phone_number',
                        name: 'phone_number',
                        "className": "text-center",
                    },
                    {
                        data: 'file',
                        name: 'file',
                        "className": "text-center",
                    },
                    // {
                    //     data: 'cover_letter',
                    //     name: 'cover_letter',
                    //     "className": "text-center",
                    // },
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
        jQuery(document).on('click', '.career_request_quick_view_btn', function(e) {
            e.preventDefault();
            var reason = $('.reason').val();
            jQuery.ajax({
                url: "/admin/career/request/view/" + jQuery(this).attr('data-id'),
                type: "get",
                data: {
                    reason : reason
                },
                success: function(response) {
                    jQuery('.career_request_form_element').html(response);
                    jQuery('#career_request_quick_view_modal').modal('show');
                }
            });
        });


        // jQuery(document).on('change', '#filter_option', function(e) {
        //     e.preventDefault();
        //     var val = $(this).val();
        //     if (val == 'date range') {
        //         $('#start_date_area').removeClass('d-none');
        //         $('#start_date_area').addClass('d-block');

        //         $('#end_date_area').removeClass('d-none');
        //         $('#end_date_area').addClass('d-block');
        //     } else {
        //         $('#start_date_area').removeClass('d-block');
        //         $('#start_date_area').addClass('d-none');

        //         $('#end_date_area').removeClass('d-block');
        //         $('#end_date_area').addClass('d-none');
        //     }
        // });



        // $('#filter_data').click(function() {
        //     // e.preventDefault();
        //     jQuery('#dataTable').DataTable().destroy();
        //     fetchData($('#filter_option').val(), $('#start_date').val(), $('#end_date').val());
        // });
    </script>
@endpush
