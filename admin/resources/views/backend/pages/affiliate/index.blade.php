@extends('backend.layouts.master')

@section('title', 'Affiliates - ' . config('concave.cnf_appname'))

@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Affiliate > Affiliates</span>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">

                            <div class="col-sm-2">
                                <div class="input-group">
                                    <select name="saller" id="user_id" class="selectpicker form-control"
                                        data-show-subtext="true" data-live-search="true" id="user_id">
                                        <option value="0">--Select User--</option>
                                        @foreach (App\Models\User::where('is_deleted', 0)->get() as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="input-group">
                                    <select name="filter_option" id="filter_option" class="form-control">
                                        <option value="0">--Select Option First--</option>
                                        <option value="today">Today</option>
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

            <div class="designed_table">
                <table id="dataTable" class="table ">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Buyer</th>
                            <th>Products</th>
                            <th>Commission</th>
                            <th data-priority="1">Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

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

            function filter(user_id, filter_option, start_date, end_date) {

                var table = jQuery('#dataTable').DataTable({
                    dom: 'Brftlip',
                    buttons: ['csv', 'excel', 'pdf', 'print'],
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    ajax: {
                        url: "{{ route('admin.affiliate.list') }}",
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
                    "order": [
                        [0, 'desc']
                    ],
                    columns: [{
                            data: 'DT_RowIndex',
                            "className": "text-center",
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'user_id'
                        },
                        {
                            data: 'buyer_id'
                        },
                        {
                            data: 'product_ids',
                            name: 'product_ids'
                        },
                        {
                            data: 'commission_amount',
                            name: 'commission_amount'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                    ]
                });
            }

            jQuery('#dataTable').DataTable().destroy();
            filter(0, 0, 0, 0);


            jQuery(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();

                let user_id = jQuery('#user_id').val();
                let filter_option = jQuery('#filter_option').val();
                let start_date = jQuery('#start_date').val();
                let end_date = jQuery('#end_date').val();

                jQuery('#dataTable').DataTable().destroy();
                filter(user_id, filter_option, start_date, end_date);
            })


            jQuery(document).on('click', '.makeMaturedBtn', function(e) {
                e.preventDefault();

                let url = jQuery(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, do it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        jQuery.ajax({
                            method: 'GET',
                            url: url,
                            success: function (data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data,
                                    showConfirmButton: false,
                                    timer: 2000,
                                });

                                jQuery('#dataTable').DataTable().destroy();
                                filter(0, 0, 0, 0);

                            },
                            error: function (xhr) {
                                var errorMessage = '<div class="card bg-danger">\n' +
                                    '                        <div class="card-body text-center p-5">\n' +
                                    '                            <span class="text-white">';
                                jQuery.each(xhr.responseJSON.errors, function(key,value) {
                                    errorMessage +=(''+value+'<br>');
                                });
                                errorMessage +='</span>\n' +
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

@endsection
