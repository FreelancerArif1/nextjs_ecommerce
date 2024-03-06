@extends('backend.layouts.master')
@section('title', 'Sale Confirm Status Wise - ' . config('concave.cnf_appname'))
@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Sale Confirm Status Wise</span>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">

                            @if (Auth::user()->getRoleNames() != '["seller"]')
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <select name="saller" id="seller_id" class="selectpicker form-control"
                                            data-show-subtext="true" data-live-search="true">
                                            <option value="-1">--Select Shop First--</option>
                                            @foreach (App\Models\ShopInfo::all() as $row)
                                                <option value="{{ $row->seller_id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="saller" id="seller_id" value="{{ Auth::user()->id }}">
                            @endif

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

                            <div class="col-sm-2">
                                <div class="input-group">
                                    <select name="status_id" id='status_id' class="form-control">
                                        <option value="-1">--Select Option First--</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Accepted</option>
                                        <option value="3">On Hold</option>
                                        <option value="4">Failed</option>
                                        <option value="5">Canceled</option>
                                        <option value="6">Completed</option>
                                        <option value="7">Refund Requested
                                        </option>
                                        <option value="8">Refunded</option>
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
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="text-center">
                        <h4 class="card-title mb-0"><span class="filter_status"></span> Order Amount</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium text-center" id="total_order_amount"> 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="text-center">
                        <h4 class="card-title mb-0"><span class="filter_status"></span> Orders</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium text-center" id="total_orders"> 0</h3>
                </div>
            </div>
        </div>
    </div>


    <div class="grid-margin">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="designed_table table-responsive">

                        <table id="dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Payment Id</th>
                                    <th>Shipping Name</th>
                                    <th>Shipping Phone</th>
                                    <th>Payment Method</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Offers</th>
                                    <th>Payment Status</th>
                                    <th class="text-center" data-priority="1">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
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
        function fetchData(seller_id='', filter_by = '', start_date = '', end_date = '', status_id = '') {
            // alert(filter_by);
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ url('admin/reports/get-sale-confirm-status-wise') }}",
                    type: 'GET',
                    data: {
                        'seller_id' : seller_id,
                        'filter_by': filter_by,
                        'start_date': start_date,
                        'end_date': end_date,
                        'status_id': status_id,
                    }
                },
                aLengthMenu: [
                    [25, 50, 100, 500, 5000, -1],
                    [25, 50, 100, 500, 5000, "All"]
                ],
                iDisplayLength: 25,
                "order": [
                    [1, 'desc']
                ],
                "language": {
                    "processing": '<span style="color:#4eb9fa;"><i class=" mdi mdi-spin mdi-settings"></i> LOADING...</span>'
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'order_date',
                        name: 'order_date'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'payment_id'
                    },
                    {
                        data: 'shipping_name',
                        name: 'shipping_name'
                    },
                    {
                        data: 'shipping_phone',
                        name: 'shipping_phone'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'product_qty',
                        name: 'product_qty'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount'
                    },
                    {
                        data: 'promotion',
                        name: 'promotion'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
        fetchData($('#seller_id').val(),);

        function getStatusWiseSales(seller_id='', filter_by = '', start_date = '', end_date = '', status_id = ''){
            $.ajax({
                url: "{{  url('/admin/reports/get-sale-status-wise') }}",
                type: "GET",
                data: {
                    'seller_id' : seller_id,
                    'filter_by': filter_by,
                    'start_date': start_date,
                    'end_date': end_date,
                    'status_id': status_id,
                },
                dataType: "json",
                success: function (data) {
                   jQuery('#total_order_amount').html(data.total_order_amount);
                   jQuery('#total_orders').html(data.total_orders);
                   jQuery('.filter_status').html(data.status);
                }
            })
        }

        getStatusWiseSales($('#seller_id').val());

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


        $('#filter_data').click(function(e) {
            e.preventDefault();

            jQuery('#dataTable').DataTable().destroy();
            fetchData($('#seller_id').val(), $('#filter_option').val(), $('#start_date').val(), $('#end_date').val(), $('#status_id').val());
            getStatusWiseSales($('#seller_id').val(), $('#filter_option').val(), $('#start_date').val(), $('#end_date').val(), $('#status_id').val());

        });
    </script>
@endpush
