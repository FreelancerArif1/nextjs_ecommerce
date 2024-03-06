@extends('backend.layouts.master')
@section('title', 'Seller Product Wise Sale - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Seller Product Wise Sale</span>

            </div>
        </div>
    </div>
    <div class="grid-margin stretch-card">
        {{-- <form action="{{ route('admin.product.action') }}" method="POST" style="width: 100%;"> --}}
        <div class="card">
            <div class="toolbar">
                <div class="row">
                    <div class="col-md-12">
                        {{-- <form action="{{ route('admin.filter.report') }}" method="POST">
                                @csrf --}}
                        <div class="form-group row">
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
                        {{-- </form> --}}

                        @if (session()->has('report'))
                            <p> {!! session()->get('report') !!}</p>
                        @endif

                    </div>

                </div>
            </div>
            <div class="table-responsive designed_table" id="productTableLoader">
                <table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th>Seller ID</th>
                            <th>Seller Name</th>
                            <th>Shop Name</th>
                            <th>Product Stock Qty</th>
                            <th>No of Sale</th>
                            <th data-priority = "1">Sale Amount</th>

                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
        {{-- </form> --}}
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

    @push('footer')
        <script type="text/javascript">
            function fetchData(filter_by = '', start_date = '', end_date = '') {
                // alert(filter_by);
                var table = jQuery('#dataTable').DataTable({
                    dom: 'Brftlip',
                    buttons: ['csv', 'excel', 'pdf', 'print'],
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,

                    ajax: {
                        url: "{{ url('admin/reports/get-seller-product-wise-sale') }}",
                        type: 'GET',
                        data: {
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
                            data: 'seller_name',

                            name: 'seller_name'
                        },
                        {
                            data: 'shop_name',

                            name: 'shop_name'
                        },

                        {
                            data: 'product_stock_qty',

                            name: 'product_stock_qty'
                        },

                        {
                            data: 'no_of_sale',

                            name: 'no_of_sale'
                        },
                        {
                            data: 'sale_amount',

                            name: 'sale_amount'
                        },
                    ]
                });
            }
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



            $('#filter_data').click(function() {
                // e.preventDefault();
                jQuery('#dataTable').DataTable().destroy();
                fetchData($('#filter_option').val(), $('#start_date').val(), $('#end_date').val());
            });
        </script>
    @endpush

@endsection
