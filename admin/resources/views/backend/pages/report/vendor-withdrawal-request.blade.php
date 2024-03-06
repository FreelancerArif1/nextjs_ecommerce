@extends('backend.layouts.master')
@section('title', 'Seller Withdrawal Request - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Seller Withdrawal Request</span>
                @if (Auth::user()->getRoleNames() != '["seller"]')
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <form action="{{ route('admin.vendor.withdrawal.request') }}" method="POST">
                                @csrf --}}
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <select name="saller" id="seller_id" class="selectpicker form-control"
                                                data-show-subtext="true" data-live-search="true">
                                                <option value="">--Select Shop First--</option>
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
                                    <div class="col-sm-2">
                                    <div class="input-group">
                                        <select name="status_id" id='status_id' class="form-control">
                                            <option value="-1">--Select Option First--</option>
                                            <option value="1">Pending</option>
                                            <option value="5">Canceled</option>
                                            <option value="6">Completed</option>
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
                                    <label class="col-sm-1"><button class="btn btn-dark"
                                            type="submit" id="filter_data">Filter</button></label>
                                </div>
                            {{-- </form> --}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if (Auth::user()->getRoleNames() == '["seller"]')
        <div class="row">
            <div class="col-md-3 col-12"></div>
            <div class="col-md-3 grid-margin stretch-card ">
                <div class="card bg-c-green">
                    <div class="card-body pb-0">
                        <div class="text-center">
                            <p class="font-weight-semibold mb-2 "></p>
                            <h3 class="font-weight-medium mb-2 text-light">
                                {{ Helper::getDefaultCurrency()->currency_symbol . ' ' . Helper::get_seller_balance(Auth::user()->id) }}
                            </h3>
                            <h4 class="card-title mb-2">Available Balance</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card bg-c-yellow">
                    @if (Helper::get_seller_balance(Auth::user()->id) < 500)
                        <a class="card-body pb-0" style="text-decoration: none;" data-toggle="modal"
                            data-target="#withdrawalValidationModal" href="#">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h3 class="font-weight-medium mb-2 text-light"><i class="menu-icon mdi mdi-cash"></i></h3>
                                <h4 class="card-title mb-2">Make a Withdrawal Request</h4>
                            </div>
                        </a>
                    @else
                        <a class="card-body pb-0" style="text-decoration: none;" data-toggle="modal"
                            data-target="#withdrawalModal" href="#">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h3 class="font-weight-medium mb-2 text-light"><i class="menu-icon mdi mdi-cash"></i></h3>
                                <h4 class="card-title mb-2">Make a Withdrawal Request</h4>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="designed_table table-responsive" id="orderTable">
                <table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Date</th>
                            <th>Seller</th>
                            <th>Payment Method</th>
                            <th>Requested Amount</th>
                            <th>Paid Amount</th>
                            <th>Status</th>
                            <th>Message</th>
                            <th>Accept/Reject Message</th>
                            <th class="text-center" data-priority="1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            {{-- <div class="pagination_center">{!! $data->links() !!}</div> --}}
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



    @if (Auth::user()->getRoleNames() == '["seller"]')
        {{-- WIDTHRAW REQUEST MODAL --}}
        <div class="modal fade" id="withdrawalModal" tabindex="-1" role="dialog" aria-labelledby="SaveModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="SaveModalLabel">Send Withdrawal Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-sample" method="post"
                            action="{{ route('admin.vendor.withdrawal.request.send') }}">
                            @csrf
                            <div class="row">
                                {{-- <div class="col-md-12">
                			<div class="form-group row">
                  				<label class="col-sm-3 col-form-label">Date <span style="color: #f00">*</span></label>
                  				<div class="col-sm-9">
			                    	<input type="date" name="date" class="form-control" value="{{ date('m-d-Y') }}" required="" />
			                  	</div>
                			</div>
              			</div> --}}
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Payment Method <span
                                                style="color: #f00">*</span></label>
                                        <div class="col-sm-9">
                                            <select name="payment_method" id="payment_method" class="form-control"
                                                required="">
                                                <option value="">-- Select Payment Method --</option>

                                                @if (Auth::user()->shopinfo->bank_name)
                                                    <option value="{{ Auth::user()->shopinfo->bank_name }}">Bank
                                                        ({{ Auth::user()->shopinfo->bank_name . ' - ' . Auth::user()->shopinfo->account_number }})
                                                    </option>
                                                @endif
                                                @if (Auth::user()->shopinfo->bkash)
                                                    <option value="{{ Auth::user()->shopinfo->bkash }}">Bkash
                                                        ({{ Auth::user()->shopinfo->bkash }})</option>
                                                @endif
                                                @if (Auth::user()->shopinfo->nagad)
                                                    <option value="{{ Auth::user()->shopinfo->nagad }}">Nagad
                                                        ({{ Auth::user()->shopinfo->nagad }})</option>
                                                @endif
                                                @if (Auth::user()->shopinfo->rocket)
                                                    <option value="{{ Auth::user()->shopinfo->rocket }}">Rocket
                                                        ({{ Auth::user()->shopinfo->rocket }})</option>
                                                @endif
                                                @if (Auth::user()->shopinfo->upay)
                                                    <option value="{{ Auth::user()->shopinfo->upay }}">Upay
                                                        ({{ Auth::user()->shopinfo->upay }})</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Requested Amount <span
                                                style="color: #f00">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="number" name="requested_amount" id="requested_amount"
                                                class="form-control" placeholder=""
                                                value="{{ Helper::get_seller_balance(Auth::user()->id) }}" min="100"
                                                max="{{ Helper::get_seller_balance(Auth::user()->id) }}"
                                                required="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Note</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="message"></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <p class="text-right">
                                            <button class="btn btn-primary" name="save" type="submit">Send
                                                Request</button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="withdrawalValidationModal" tabindex="-1" role="dialog"
            aria-labelledby="SaveModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="SaveModalLabel"> Withdrawal Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <b class="text-danger">You can not make a request when your balance is less than BDT 500!</b>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!--Delete Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure to approve this request? </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-sample" method="post"
                        action="{{ route('admin.vendor.withdrawal.request.approved') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Total amount to pay</label>
                                    <div class="col-sm-9">
                                       <input type="number" class="form-control" name="amount_to_pay">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Status <span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="status" id="status" class="form-control" required="">
                                            <option value="">Select</option>
                                            <option value="5">Canceled</option>
                                            <option value="6">Completed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Message <span
                                            style="color: #f00">*</span></label>
                                    <div class="col-sm-9">
                                       <textarea name="accept_reject_msg" id="" class="form-control" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-right">
                                        <input type="hidden" name="id" id="hidden_request_id">
                                        <button class="btn btn-primary" name="save" type="submit">Submit</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('footer')
        <script type="text/javascript">

 function fetchData(seller_id='', filter_by = '', start_date = '', end_date = '', status_id = '') {
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ url('admin/reports/get/vendor/withdrawal/request') }}",
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
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'seller_name',
                        name: 'seller_name'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'requested_amount',
                        name: 'requested_amount'
                    },
                    {
                        data: 'amount_to_pay',
                        name: 'amount_to_pay'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'message',
                        name: 'message'
                    },
                    
                    {
                        data: 'accept_reject_msg',
                        name: 'accept_reject_msg'
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

            jQuery(document).on("click", ".apprived_btn", function() {
                var id = jQuery(this).attr("id");
                $('#hidden_request_id').val(id);
                $('#approveModal').modal('show');
            });


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

        });
        </script>
    @endpush

@endsection
