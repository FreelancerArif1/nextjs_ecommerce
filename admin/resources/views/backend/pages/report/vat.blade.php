@extends('backend.layouts.master')
@section('title', 'Vat - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Report > Vat</span>
                {{-- @if (Auth::user()->can('product.create'))
                    <a class="btn btn-success float-right" href="{{ route('admin.product.create') }}">Create New Product</a>
                @endif --}}
                {{-- @if (\Auth::user()->getRoleNames() == '["seller"]')
                    <input type="hidden" name="seller_id" value="{{Auth::id()}}">
                @endif --}}

                
                    <div class="row">
                        <div class="col-md-12">
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
                        </div>
                    </div>
               
            </div>
        </div>
    </div>

     @if (\Auth::user()->getRoleNames() == '["admin"]' || \Auth::user()->getRoleNames() == '["superadmin"]')
        <div class="grid-margin">
            <div class="row">

                <div class="col-md-3">
                    <div class="card bg-c-blue">
                        <div class="card-body pb-0">
                            <div class="text-center">
                                <p class="font-weight-semibold mb-2 "></p>
                                <h6 class="font-weight-medium mb-2 text-light" id="total_vat">0
                                   
                                </h6>
                                <h4 class="card-title mb-2">Total Vat</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="table-responsive designed_table" id="productTableLoader">
                <table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Order ID</th>
                            <th>Total Amount</th>
                            <th>Vat Amount</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
            function fetchData(filter_by = '', start_date = '', end_date = ''){
                var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ url('admin/reports/get-vat') }}",
                    type: 'GET',
                    data: {
                    'filter_by': filter_by,
                    'start_date': start_date,
                    'end_date': end_date,
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
                columns: [
                {
                        data: 'DT_RowIndex',
                            "className": "text-center",
                            orderable: false,
                            searchable: false,
                },
                {
                        data: 'id',
                        "className": "text-center",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'vat',
                        name: 'vat'
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

         function getInitialData(filter_by = '', start_date = '', end_date = ''){
            	$.ajax({
	                url: "{{  url('admin/reports/get-vat/initialdata') }}",
	                type: "GET",
	                data: {
                        'filter_by': filter_by,
                        'start_date': start_date,
                        'end_date': end_date,
                    },
                    dataType: "json",
	                success: function (data) {
	                   	jQuery('#total_vat').html('৳ '+data.total_vat);
	                   	
	                }
	            })
            }
            getInitialData(0);

         $('#filter_data').click(function(e) {
            e.preventDefault();

            jQuery('#dataTable').DataTable().destroy();
            fetchData($('#filter_option').val(), $('#start_date').val(), $('#end_date').val());
            getInitialData($('#filter_option').val(), $('#start_date').val(), $('#end_date').val());

        });

        </script>
    @endpush

@endsection
