@extends('backend.layouts.master')

@section('title', 'Affiliates - ' . config('concave.cnf_appname'))

@section('content')

<div class="grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <span class="card-title">Dashboard > Affiliate > Withdrawal Request</span>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">

                        <div class="col-sm-2">
                            <div class="input-group">
                                <select name="saller" id="user_id" class="selectpicker form-control"
                                    data-show-subtext="true" data-live-search="true" id="user_id">
                                    <option value="">--Select User--</option>
                                    @foreach (App\Models\User::where('is_deleted', 0)->get() as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <select name="filter_option" id="filter_option" class="form-control">
                                    <option value="">--Select Option First--</option>
                                    <option value="today">Today</option>
                                    <option value="7 day">7 Day</option>
                                    <option value="this month">This Month</option>
                                    <option value="this year">This Year</option>
                                    <option value="date range">Date Range</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2 d-none" id="start_date_area">
                            <div class="input-group">
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2 d-none" id="end_date_area">
                            <div class="input-group">
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                        </div>

                        <label class="col-sm-1"><button class="btn btn-dark" id="filterBtn"
                                type="submit">Filter</button></label>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="grid-margin stretch-card">
    <div class="card">

        <div class="designed_table table-responsive" id="orderTable">
            <table id="dataTable" class="table dataTable">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Username</th>
                        <th>Amount</th>
                        <th>Channel</th>
                        <th>Account Details</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th class="text-center" data-priority="1">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
</div>

<!--Delete Modal -->
<div class="modal fade" id="changeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Do you want to make this request Settle?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <p>Once you delete this item, you can restore this from trash list!</p> --}}
                <textarea name="note" class="form-control"></textarea>

                {{-- <input type="text" class="form-control" name="reason" id="reason"
                        placeholder="Write reason, why you want to make this request Settle?"> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a type="button" href="#" class="btn btn-danger">Yes</a>
            </div>
        </div>
    </div>
</div>


@endsection
@push('footer')
<script type="text/javascript">
    jQuery(document).on('change', '#filter_option', function(e) {
        e.preventDefault();
        var val = $(this).val();
        if (val == 'date range') {
            $('#start_date_area').removeClass('d-none');
            $('#start_date_area').addClass('d-block');

            $('#end_date_area').removeClass('d-none');
            $('#end_date_area').addClass('d-block');
        } else {
            $('#start_date_area').removeClass('d-block');
            $('#start_date_area').addClass('d-none');

            $('#end_date_area').removeClass('d-block');
            $('#end_date_area').addClass('d-none');
        }
    });


    function fetchData(user_id, filter_option, start_date, end_date) {
        // alert(filter_by);
        var table = jQuery('#dataTable').DataTable({
            dom: 'Brftlip',
            buttons: ['csv', 'excel', 'pdf', 'print'],
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,

            ajax: {
                url: "{{ route('admin.get.affiliate.withdrawal') }}",
                type: 'GET',
                data: {
                    user_id: user_id,
                    filter_option: filter_option,
                    start_date: start_date,
                    end_date: end_date,
                },
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
                    data: 'username',
                    name: 'username',
                },
                {
                    data: 'amount',
                    name: 'amount',
                },
                {
                    data: 'channel',
                    name: 'channel',
                },
                {
                    data: 'account_details',
                    name: 'account_details',
                },
                {
                    data: 'note',
                    name: 'note',
                },
                {
                    data: 'status',
                    name: 'status',
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
    fetchData(0, 0, 0, 0);


    jQuery(document).on('click', '#filterBtn', function(e) {
        e.preventDefault();

        let user_id = jQuery('#user_id').val();
        let filter_option = jQuery('#filter_option').val();
        let start_date = jQuery('#start_date').val();
        let end_date = jQuery('#end_date').val();

        jQuery('#dataTable').DataTable().destroy();
        fetchData(user_id, filter_option, start_date, end_date);
    })

    jQuery(document).on('click', '.makeMaturedBtn', function(e) {
        e.preventDefault();

        let url = jQuery(this).attr('href');

        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            html:'Do you want to make this request Settle?<br><br> <textarea id="note" name="note" class="form-control" placeholder="Please enter the reason"></textarea>',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
            
        })
        .then((result) => {
            if (result.isConfirmed) {
                let note = jQuery("#note").val();
                jQuery.ajax({
                    method: 'GET',
                    url: url,
                   data: {note: note},
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: data,
                            showConfirmButton: false,
                            timer: 2000,
                        });

                        jQuery('#dataTable').DataTable().destroy();
                        fetchData(0, 0, 0, 0);

                    },
                    error: function(xhr) {
                        var errorMessage = '<div class="card bg-danger">\n' +
                            '                        <div class="card-body text-center p-5">\n' +
                            '                            <span class="text-white">';
                        jQuery.each(xhr.responseJSON.errors, function(key, value) {
                            errorMessage += ('' + value + '<br>');
                        });
                        errorMessage += '</span>\n' +
                            '                        </div>\n' +
                            '                    </div>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            footer: errorMessage
                        })
                    },
                    
                })
            }
        })
    })
</script>
@endpush
