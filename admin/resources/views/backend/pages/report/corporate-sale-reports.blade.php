@extends('backend.layouts.master')

@if (isset($title))
    @section('title', $title)
@else
    @section('title', 'Corporate Sales Report - ' . config('concave.cnf_appname'))
@endif

@section('content')

    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Corporate Sales Report</span>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST">
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <select name="user_id" id="user_id" class="selectpicker form-control"
                                            data-show-subtext="true" data-live-search="true">
                                            <option value="">--Select Corporate--</option>
                                            @foreach (App\Models\User::where('user_type',2)->where('is_deleted',0)->get() as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <select name="filter_option" id="filter_option" class="form-control" required="">
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

                                <label class="col-sm-1"><button class="btn btn-dark" id="filterBtn">Filter</button></label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Total Sales</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h6 class="font-weight-medium mb-4" id="total_sale">0</h6>
                </div>
            </div>
        </div>
        <div class="col-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Product Qty</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h6 class="font-weight-medium" id="product_qty">0 </h6>
                </div>
            </div>
        </div>
        <div class="col-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Sale Amount</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h6 class="font-weight-medium" id="sale_amount">0  </h6>
                </div>
            </div>
        </div>
        <div class="col-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Sale Discounts</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h6 class="font-weight-medium" id="sale_discount">0 </h6>
                </div>
            </div>
        </div>
        <div class="col-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Paid Amount</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h6 class="font-weight-medium" id="paid_amount">0</h6>
                </div>
            </div>
        </div>


        <div class="col-md-2 grid-margin stretch-card">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Pending</h4>
                        <p class="font-weight-semibold mb-0"></p>
                    </div>
                    <h6 class="font-weight-medium" id="pending_amount">0</h6>
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
                    <p>Once you delete this item. You can not restore this item again!</p>
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
            jQuery(document).on('change', '#filter_option', function(e) {
                e.preventDefault();
                var val = $(this).val();
                // alert(val);
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

            function getInitialData(user_id = '', filter_option = '', start_date = '', end_date = ''){
            	$.ajax({
	                url: "{{  url('admin/reports/corporate/sales/reports/initialdata') }}",
	                type: "GET",
	                data: {
                        'user_id': user_id,
                        'filter_option': filter_option,
                        'start_date': start_date,
                        'end_date': end_date,
                    },
                    dataType: "json",
	                success: function (data) {
	                   	jQuery('#total_sale').html(data.total_sale);
	                   	jQuery('#product_qty').html(data.product_qty);
	                   	jQuery('#sale_amount').html('৳ '+data.sale_amount);
	                   	jQuery('#sale_discount').html('৳ '+data.sale_discount);
	                   	jQuery('#paid_amount').html('৳ '+data.paid_amount);
	                   	jQuery('#pending_amount').html('৳ '+data.pending_amount);
	                }
	            })
            }
            getInitialData();

            function fetchData(user_id = '', filter_option = '', start_date = '', end_date = '') {
	            var table = jQuery('#dataTable').DataTable({
	                dom: 'Brftlip',
	                buttons: ['csv', 'excel', 'pdf', 'print'],
	                responsive: true,
	                processing: true,
	                serverSide: true,
	                autoWidth: false,

	                ajax: {
	                    url: "{{ url('admin/reports/corporate/sales/reports/list') }}",
	                    type: 'GET',
	                    data: {
                            'user_id': user_id,
                            'filter_option': filter_option,
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
	                        data: 'payment_status',
	                        name: 'payment_status',
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

	        $("#filterBtn").click(function(e) {
                e.preventDefault();

                jQuery('#dataTable').DataTable().destroy();
                fetchData($('#user_id').val(), $('#filter_option').val(), $('#start_date').val(), $('#end_date')
                    .val());

                getInitialData($('#user_id').val(), $('#filter_option').val(), $('#start_date').val(), $('#end_date')
                    .val());
            });
        </script>
    @endpush

@endsection
