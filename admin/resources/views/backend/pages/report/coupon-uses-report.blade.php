@extends('backend.layouts.master')
@section('title', 'Coupon Uses Reports - ' . config('concave.cnf_appname'))
@section('content')
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <span class="card-title">Dashboard > Reports > Coupon Uses Reports</span>

            </div>
        </div>
    </div>
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="table-responsive designed_table" id="productTableLoader">
                <table id="dataTable" class="table">
                    <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Coupon Code</th>
                            <th>Coupon Amount</th>
                            <th>Order Amount</th>
                            <th data-priority = "1">Payment Status</th>
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
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,

                ajax: {
                    url: "{{ url('admin/reports/coupon/uses/reports/list') }}",
                    type: 'GET',
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
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        "className": "text-center",
                    },
                    {
                        data: 'user_id',
                        "className": "text-center",
                        name: 'user_id'
                    },

                    {
                        data: 'coupon_code',
                        "className": "text-center",
                        name: 'coupon_code'
                    },
                    {
                        data: 'coupon_amount',
                        "className": "text-center",
                        name: 'coupon_amount'
                    },

                    {
                        data: 'paid_amount',
                        "className": "text-center",
                        name: 'paid_amount'
                    },
                    {
                        data: 'status',
                        "className": "text-center",
                        name: 'status'
                    },
                ]
            });
        </script>
    @endpush

@endsection
