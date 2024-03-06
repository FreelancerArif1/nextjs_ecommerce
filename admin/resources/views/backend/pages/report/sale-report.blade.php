@extends('backend.layouts.master')

@if (isset($title))
    @section('title', $title)
@else
    @section('title', 'Sales Report - ' . config('concave.cnf_appname'))
@endif

@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Sales Report</span>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST">
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
                                    <input type="hidden" name="saller" id="seller_id" value="{{Auth::user()->id}}">
                                @endif

                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <select name="filter_option" id="filter_option" class="form-control">
                                            <option value="-1">--Select Option First--</option>
                                            <option value="today">Today</option>
                                            <option value="7 day">7 Day</option>
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

                                <label class="col-sm-1"><button class="btn btn-dark" type="submit" id="filter_data">Filter</button></label>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Total Order Amount</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium mb-4" id="total_order_amount"> 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Total Orders</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium" id="total_orders"> 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Total Products Ordered</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium" id="total_product_ordered"> 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Total Order Customers</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium" id="total_ordered_customer"> 0</h3>
                </div>
            </div>
        </div>


        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Total Products Return</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium" id="total_product_returned"> 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Product Exchanged</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium" id="total_product_exchanged"> 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Product Refunded</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium" id="total_product_refunded"> 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Refunded Amount</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h3 class="font-weight-medium" id="refunded_amount"> 0</h3>
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
                            <th>Order Id</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Payment Id</th>
                            <th>Billing Name</th>
                            <th>Billing Phone</th>
                            <th>Payment Method</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Payment Status</th>
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
                    <p>Once you delete this item. You can not restore this item again!</p>
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

        function fetchData(seller_id='',filter_by = '', start_date = '', end_date = '', status_id = '') {
            
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ url('admin/reports/get-sale-report') }}",
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
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'payment_id',
                        name: 'payment_id'
                    },
                    {
                        data: 'shipping_name',
                        name: 'shipping_name'
                    },
                    {
                        data: 'billing_phone',
                        name: 'billing_phone'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
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
        fetchData($('#seller_id').val());

        function getSalesBasicReport(seller_id='',filter_by = '', start_date = '', end_date = '', status_id = ''){
            $.ajax({
                url: "{{  url('/admin/reports/filter/sale/report') }}",
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
                   jQuery('#total_product_returned').html(data.total_product_returned);
                   jQuery('#total_product_ordered').html(data.total_product_ordered);
                   jQuery('#total_ordered_customer').html(data.total_ordered_customer);
                   jQuery('#total_product_exchanged').html(data.total_product_exchanged);
                   jQuery('#total_product_refunded').html(data.total_product_refunded);
                   jQuery('#refunded_amount').html(data.refunded_amount);
                }
            })
        }

        getSalesBasicReport($('#seller_id').val());


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
            fetchData($('#seller_id').val(),$('#filter_option').val(), $('#start_date').val(), $('#end_date').val(), $('#status_id').val());

            getSalesBasicReport($('#seller_id').val(),$('#filter_option').val(), $('#start_date').val(), $('#end_date').val(), $('#status_id').val());

        });
        </script>
        
    @endpush

@endsection
