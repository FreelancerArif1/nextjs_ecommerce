@extends('backend.layouts.master')
@section('title', 'Seller Account History - ' . config('concave.cnf_appname'))
@section('content')

 <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Seller Account History</span>
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
                                    <label class="col-sm-1"><button class="btn btn-dark" id="filter_data">Filter</button></label>
                                </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (\Auth::user()->getRoleNames() == '["admin"]' || \Auth::user()->getRoleNames() == '["superadmin"]')
        <div class="grid-margin">
            <div class="row">

                <div class="col-md-3">
                    <div class="card bg-c-yellow">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h6 class="font-weight-medium mb-2 text-light" id="seller_pending_maturation_balance">0
                                    {{-- {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . $seller_pending_maturation_balance }} --}}
                                </h6>
                                <h4 class="card-title mb-2">Pending Maturation</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card bg-c-blue">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h6 class="font-weight-medium mb-2 text-light" id="seller_revenue">0
                                    {{-- {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::get_seller_revenue() }} --}}
                                </h6>
                                <h4 class="card-title mb-2">Seller's Revenue</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-c-green">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h6 class="font-weight-medium mb-2 text-light" id="seller_withdrawal_amount">0
                                    {{-- {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::getSellerWithdrawalAmount() }} --}}
                                </h6>
                                <h4 class="card-title mb-2">Disbursed Amount</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card bg-c-pink">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h6 class="font-weight-medium mb-2 text-light" id="seller_balance">
                                    {{-- {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::get_seller_balance() }} --}}
                                </h6>
                                <h4 class="card-title mb-2">Amount to Pay</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-2">
                    <div class="card bg-c-leaf">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h6 class="font-weight-medium mb-2 text-light" id="company_revenue">0
                                    {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::get_company_revenue() }}
                                </h6>
                                <h4 class="card-title mb-2">Total Revenue</h4>
                            </div>
                        </div>
                    </div>
                </div> --}}


                {{-- <div class="col-md-2">
                    <div class="card bg-c-violate">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h6 class="font-weight-medium mb-2 text-light" id="company_profit">0
                                    {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::get_company_profit() }}
                                </h6>
                                <h4 class="card-title mb-2">Total Profit</h4>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    @endif

    {{-- <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Seller Account History</span>
                @if (Auth::user()->getRoleNames() != '["seller"]')
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="POST">
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
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div> --}}



    @if (\Auth::user()->getRoleNames() == '["seller"]')
        <div class="grid-margin">
            <div class="row">

                <div class="col-md-3">
                    <div class="card bg-c-yellow">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h3 class="font-weight-medium mb-2 text-light">
                                    {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::getSellerPendingMaturationBalance(Auth::id()) }}
                                </h3>
                                <h4 class="card-title mb-2">Pending Maturation</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card bg-c-blue">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h3 class="font-weight-medium mb-2 text-light">
                                    {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::get_seller_revenue(Auth::id()) }}
                                </h3>
                                <h4 class="card-title mb-2">Total Revenue</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-c-pink">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h3 class="font-weight-medium mb-2 text-light">
                                    {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::getSellerWithdrawalAmount(Auth::id()) }}
                                </h3>
                                <h4 class="card-title mb-2">Total Amount Withdrawn</h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card bg-c-green">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h3 class="font-weight-medium mb-2 text-light">
                                    {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::get_seller_balance(Auth::id()) }}
                                </h3>
                                <h4 class="card-title mb-2">Available Balance</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



    <div class="grid-margin">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="designed_table">
                        <table id="dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th width="30%">Product</th>
                                    <th>Order Id</th>
                                    <th>Total</th>
                                    <th>Seller Amount</th>
                                    <th>Commission</th>
                                    <th>Source Payment Id</th>
                                    <th>Source Payment Method</th>
                                    <th>Status</th>
                                    <th>Time</th>
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
            function fetchData(id) {
                var datatable = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    ajax: {
                        url: "{{ url('/admin/reports/get-seller-account-history') }}/" + id,
                        type: 'GET',
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [0, 'desc']
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
                            data: 'product',
                            name: 'product',
                            "className": "text-left"
                        },
                        {
                            data: 'order_id',
                            "className": "text-center"
                        },
                        {
                            data: 'price'
                        },
                        {
                            data: 'seller_amount',
                            name: 'seller_amount'
                        },
                        {
                            data: 'commission',
                            name: 'commission'
                        },
                        {
                            data: 'source_payment_id'
                        },
                        {
                            data: 'payment_method'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at'
                        },
                    ]
                });
            }

            // jQuery('#dataTable').DataTable().destroy();
            fetchData();

            

            // jQuery(document).on('change', '#seller_id', function(e) {
            //     e.preventDefault();
            //     var val = $(this).val();
            //     jQuery('#dataTable').DataTable().destroy();
            //     fetchData(val);
            // });

            function getInitialData(seller_id = ''){
            	$.ajax({
	                url: "{{  url('admin/reports/seller/account/history/initialdata') }}",
	                type: "GET",
	                data: {
                        'seller_id': seller_id,
                    },
                    dataType: "json",
	                success: function (data) {
	                   	jQuery('#seller_pending_maturation_balance').html('৳ '+data.seller_pending_maturation_balance);
	                   	jQuery('#seller_revenue').html('৳ '+data.seller_revenue);
	                   	jQuery('#seller_withdrawal_amount').html('৳ '+data.seller_withdrawal_amount);
	                   	jQuery('#seller_balance').html('৳ '+data.seller_balance);
	                }
	            })
            }
            getInitialData(0);


            $("#filter_data").click(function(e) {
                e.preventDefault();

                jQuery('#dataTable').DataTable().destroy();
                fetchData($('#seller_id').val());

                getInitialData($('#seller_id').val());
            });
        </script>
    @endpush

@endsection
