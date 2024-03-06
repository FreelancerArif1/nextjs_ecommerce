@extends('backend.layouts.master')
@section('title', 'Top Sold Product Report - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Top Sold Product Report</span>
                @if (Auth::user()->getRoleNames() != '["seller"]')
                    <div class="row">
                        <div class="col-md-12">

                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <select name="saller" id="seller_id" class="selectpicker form-control"
                                                data-show-subtext="true" data-live-search="true" required="">
                                                <option value="0">--Select Shop First--</option>
                                                @foreach (App\Models\ShopInfo::all() as $row)
                                                    <option value="{{ $row->seller_id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <select name="filter_option" id="filter_option" class="form-control">
                                                <option value="">--Select Option First--</option>
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

                                    <label class="col-sm-1"><button class="btn btn-dark" id="filter_data">Filter</button></label>

                                </div>

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="grid-margin ">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="designed_table">
                        <table id="dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Stock</th>
                                    <th class="text-center">No of Sale</th>
                                    <th class="text-center" data-priority = "1">Sale Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('footer')
        <script type="text/javascript">
            function fetchData(seller_id='', filter_by = '', start_date = '', end_date = '') {
                // alert(filter_by);
                var datatable = jQuery('#dataTable').DataTable({
                    dom: 'Brftlip',
                    buttons: ['csv', 'excel', 'pdf', 'print'],
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    ajax: {
                        url: "{{ url('/admin/reports/get-top-sold-product-report') }}",
                        type: 'GET',
                        data: {
                            'seller_id': seller_id,
                            'filter_by': filter_by,
                            'start_date': start_date,
                            'end_date': end_date,
                        }
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,

                    "order": [
                        [1, "desc"]
                    ],
                    "language": {
                        "processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            "className": "text-center",
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'image',
                            name: 'image',
                            "className": "text-left"
                        },
                        {
                            data: 'category_title'
                        },
                        {
                            data: 'brand_title'
                        },
                        {
                            data: 'qty'
                        },
                        {
                            data: 'no_of_sale',
                            name: 'no_of_sale',
                            orderable: true,
                        },
                        {
                            data: 'sale_amount',
                            name: 'sale_amount',
                            "className": "text-center"
                        },
                    ]
                });
            }

            // jQuery('#dataTable').DataTable().destroy();
            fetchData();


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

            // $("#seller_id,#filter_option,#end_date").change(function(e) {
            //     e.preventDefault();

            //     jQuery('#dataTable').DataTable().destroy();
            //     fetchData($('#seller_id').val(), $('#filter_option').val(), $('#start_date').val(), $('#end_date')
            //         .val());
            // });

            $('#filter_data').click(function(e) {
            e.preventDefault();

            jQuery('#dataTable').DataTable().destroy();
            fetchData($('#seller_id').val(), $('#filter_option').val(), $('#start_date').val(), $('#end_date').val());
            getStatusWiseSales($('#seller_id').val(), $('#filter_option').val(), $('#start_date').val(), $('#end_date').val());

        });

        </script>
    @endpush

@endsection
